<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reading_histories', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['user_id']);

            // Drop existing unique constraint
            $table->dropUnique(['user_id', 'manga_id']);

            // Make user_id nullable to support guest users
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // Add session_id for guest tracking
            $table->string('session_id', 255)->nullable()->after('user_id');

            // Add foreign key back with nullable support
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Add index on session_id for better query performance
            $table->index('session_id');

            // Note: We cannot add unique constraints with nullable columns in MySQL
            // Instead, we'll handle uniqueness in application logic
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reading_histories', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['user_id']);

            // Drop indexes
            $table->dropIndex(['session_id']);

            // Remove session_id column
            $table->dropColumn('session_id');

            // Make user_id required again
            $table->unsignedBigInteger('user_id')->nullable(false)->change();

            // Add foreign key back
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Restore original unique constraint
            $table->unique(['user_id', 'manga_id']);
        });
    }
};
