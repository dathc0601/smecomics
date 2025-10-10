<?php

namespace App\Console\Commands;

use App\Models\Manga;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MarkCompletedMangas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manga:mark-completed
                            {--dry-run : Preview changes without updating database}
                            {--list : Show detailed list of affected mangas}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan all mangas and mark as completed if any chapter title contains "end" or "tập cuối"';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting manga completion scan...');
        $this->newLine();

        $isDryRun = $this->option('dry-run');
        $isVerbose = $this->option('list');

        try {
            // Start transaction for safety
            DB::beginTransaction();

            // OPTIMIZATION: Single query to get manga IDs where END chapter is the latest chapter
            // Uses JOIN to validate that the chapter with "END" is the highest chapter_number
            $this->info('📊 Scanning chapters for completion indicators...');

            $mangaIds = DB::table('chapters as c1')
                ->join(DB::raw('(SELECT manga_id, MAX(chapter_number) as max_chapter FROM chapters GROUP BY manga_id) as c2'), function($join) {
                    $join->on('c1.manga_id', '=', 'c2.manga_id')
                         ->on('c1.chapter_number', '=', 'c2.max_chapter');
                })
                ->where(function($query) {
                    $query->whereRaw('LOWER(c1.title) LIKE ?', ['%end%'])
                          ->orWhereRaw('LOWER(c1.title) LIKE ?', ['%tập cuối%']);
                })
                // Exclude season endings (END SS1, END SS2, etc.)
                ->whereRaw('LOWER(c1.title) NOT LIKE ?', ['%end ss%'])
                ->distinct()
                ->pluck('c1.manga_id')
                ->toArray();

            $totalFound = count($mangaIds);

            if ($totalFound === 0) {
                $this->info('✅ No mangas found with completion indicators in chapter titles.');
                DB::rollBack();
                return self::SUCCESS;
            }

            $this->info("🔍 Found {$totalFound} manga(s) with completion indicators");
            $this->newLine();

            // Get mangas that are not already completed
            $mangasToUpdate = Manga::whereIn('id', $mangaIds)
                ->where('status', '!=', 'completed')
                ->get();

            $updateCount = $mangasToUpdate->count();
            $alreadyCompleted = $totalFound - $updateCount;

            if ($alreadyCompleted > 0) {
                $this->comment("ℹ️  {$alreadyCompleted} manga(s) already marked as completed (skipping)");
            }

            if ($updateCount === 0) {
                $this->info('✅ All mangas with completion indicators are already marked as completed.');
                DB::rollBack();
                return self::SUCCESS;
            }

            // Show verbose details if requested
            if ($isVerbose) {
                $this->newLine();
                $this->info('📝 Mangas to be marked as completed:');
                $this->table(
                    ['ID', 'Title', 'Status', 'Total Ch.', 'Latest Ch.', 'Ending Chapters'],
                    $mangasToUpdate->map(function($manga) {
                        // Get total chapter count
                        $totalChapters = $manga->chapters()->count();

                        // Get latest chapter number
                        $latestChapter = $manga->chapters()->max('chapter_number');

                        // Get chapters with completion indicators
                        $indicatorChapters = $manga->chapters()
                            ->where(function($query) {
                                $query->whereRaw('LOWER(title) LIKE ?', ['%end%'])
                                      ->orWhereRaw('LOWER(title) LIKE ?', ['%tập cuối%']);
                            })
                            // Exclude season endings
                            ->whereRaw('LOWER(title) NOT LIKE ?', ['%end ss%'])
                            ->pluck('title')
                            ->take(3)
                            ->join(', ');

                        return [
                            $manga->id,
                            strlen($manga->title) > 35 ? substr($manga->title, 0, 32) . '...' : $manga->title,
                            $manga->status,
                            $totalChapters,
                            $latestChapter,
                            strlen($indicatorChapters) > 40 ? substr($indicatorChapters, 0, 37) . '...' : $indicatorChapters,
                        ];
                    })->toArray()
                );
                $this->newLine();
            }

            if ($isDryRun) {
                $this->warn('🔄 DRY RUN MODE - No changes will be made to the database');
                $this->info("Would update {$updateCount} manga(s) to 'completed' status");
                DB::rollBack();
                return self::SUCCESS;
            }

            // Confirm before proceeding
            if (!$this->confirm("Update {$updateCount} manga(s) to 'completed' status?", true)) {
                $this->comment('Operation cancelled.');
                DB::rollBack();
                return self::SUCCESS;
            }

            // OPTIMIZATION: Bulk update in single query
            $updatedCount = Manga::whereIn('id', $mangasToUpdate->pluck('id'))
                ->where('status', '!=', 'completed')
                ->update(['status' => 'completed']);

            DB::commit();

            $this->newLine();
            $this->info("✅ Successfully updated {$updatedCount} manga(s) to 'completed' status!");
            $this->newLine();

            // Summary
            $this->info('📊 Summary:');
            $this->line("  • Total mangas found: {$totalFound}");
            $this->line("  • Already completed: {$alreadyCompleted}");
            $this->line("  • Newly updated: {$updatedCount}");

            return self::SUCCESS;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return self::FAILURE;
        }
    }
}
