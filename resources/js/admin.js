import tinymce from 'tinymce';
import Choices from 'choices.js';
import Sortable from 'sortablejs';
import axios from 'axios';

// Import TinyMCE theme and plugins
import 'tinymce/themes/silver';
import 'tinymce/icons/default';
import 'tinymce/models/dom';

// Import TinyMCE plugins
import 'tinymce/plugins/anchor';
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/charmap';
import 'tinymce/plugins/code';
import 'tinymce/plugins/emoticons';
import 'tinymce/plugins/emoticons/js/emojis';
import 'tinymce/plugins/image';
import 'tinymce/plugins/link';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/media';
import 'tinymce/plugins/searchreplace';
import 'tinymce/plugins/table';
import 'tinymce/plugins/visualblocks';
import 'tinymce/plugins/wordcount';

// Import TinyMCE skins
import 'tinymce/skins/ui/oxide/skin.css';

// TinyMCE Configuration
document.addEventListener('DOMContentLoaded', function() {
    // Initialize TinyMCE for textareas with class 'tinymce'
    if (document.querySelector('.tinymce')) {
        tinymce.init({
            selector: '.tinymce',
            license_key: 'gpl',
            skin: false,
            content_css: false,
            plugins: 'anchor autolink charmap code emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | code | removeformat',
            height: 400,
            menubar: false,
            branding: false,
            promotion: false,
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 14px; line-height: 1.6; padding: 1rem; }',
        });
    }

    // Initialize Choices.js for multi-select (genres, tags)
    const multiSelects = document.querySelectorAll('.choices-multiple');
    multiSelects.forEach(select => {
        new Choices(select, {
            removeItemButton: true,
            searchEnabled: true,
            searchPlaceholderValue: 'Search...',
            itemSelectText: 'Click to select',
        });
    });

    // Initialize Choices.js for single select (author, status, type)
    const singleSelects = document.querySelectorAll('.choices-single');
    singleSelects.forEach(select => {
        new Choices(select, {
            searchEnabled: true,
            searchPlaceholderValue: 'Search...',
            itemSelectText: 'Click to select',
        });
    });

    // Image preview functionality
    const imageInputs = document.querySelectorAll('input[type="file"][accept="image/*"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                const preview = this.dataset.preview ? document.getElementById(this.dataset.preview) : null;

                reader.onload = function(e) {
                    if (preview) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                    }
                };

                reader.readAsDataURL(file);
            }
        });
    });

    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');

    if (titleInput && slugInput) {
        titleInput.addEventListener('input', function() {
            if (!slugInput.dataset.manual) {
                const slug = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                slugInput.value = slug;
            }
        });

        slugInput.addEventListener('input', function() {
            this.dataset.manual = 'true';
        });
    }

    // ===== Chapter Image Management =====
    if (document.getElementById('images-grid')) {
        const imagesGrid = document.getElementById('images-grid');
        const dropZone = document.getElementById('drop-zone');
        const imageUpload = document.getElementById('image-upload');
        const previewGrid = document.getElementById('preview-grid');
        const uploadBtn = document.getElementById('upload-btn');
        const toast = document.getElementById('toast');
        const loadingOverlay = document.getElementById('loading-overlay');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let selectedFiles = [];

        // Configure Axios defaults
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

        // Initialize Sortable for drag-and-drop reordering
        const sortable = new Sortable(imagesGrid, {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                updateImageOrder();
            }
        });

        // Update image order via AJAX
        function updateImageOrder() {
            const imageItems = imagesGrid.querySelectorAll('.image-item');
            const order = Array.from(imageItems).map(item => item.dataset.id);

            showLoading();

            axios.post(`/admin/chapters/${window.chapterId}/images/reorder`, { order })
                .then(response => {
                    updateOrderLabels();
                    showToast('Images reordered successfully');
                })
                .catch(error => {
                    console.error('Error reordering images:', error);
                    showToast('Error reordering images', 'error');
                })
                .finally(() => hideLoading());
        }

        // Update order number labels
        function updateOrderLabels() {
            const imageItems = imagesGrid.querySelectorAll('.image-item');
            imageItems.forEach((item, index) => {
                const orderLabel = item.querySelector('.image-order');
                if (orderLabel) {
                    orderLabel.textContent = `Page ${index + 1}`;
                }
            });
        }

        // Delete image
        imagesGrid.addEventListener('click', function(e) {
            if (e.target.closest('.delete-image')) {
                const btn = e.target.closest('.delete-image');
                const imageId = btn.dataset.id;
                const imageItem = btn.closest('.image-item');

                if (confirm('Are you sure you want to delete this image?')) {
                    showLoading();

                    axios.delete(`/admin/chapters/${window.chapterId}/images/${imageId}`)
                        .then(response => {
                            imageItem.style.transform = 'scale(0)';
                            imageItem.style.opacity = '0';
                            setTimeout(() => {
                                imageItem.remove();
                                updateOrderLabels();
                                showToast('Image deleted successfully');
                            }, 200);
                        })
                        .catch(error => {
                            console.error('Error deleting image:', error);
                            showToast('Error deleting image', 'error');
                        })
                        .finally(() => hideLoading());
                }
            }
        });

        // Drag and drop file upload
        dropZone.addEventListener('click', () => imageUpload.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-orange-500', 'bg-orange-50');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-orange-500', 'bg-orange-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-orange-500', 'bg-orange-50');
            const files = Array.from(e.dataTransfer.files);
            handleFiles(files);
        });

        imageUpload.addEventListener('change', (e) => {
            const files = Array.from(e.target.files);
            handleFiles(files);
        });

        // Handle selected files
        function handleFiles(files) {
            selectedFiles = files.filter(file => file.type.startsWith('image/'));

            if (selectedFiles.length === 0) {
                showToast('Please select valid image files', 'error');
                return;
            }

            // Show preview
            previewGrid.innerHTML = '';
            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const preview = document.createElement('div');
                    preview.className = 'relative group';
                    preview.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg shadow-md">
                        <button type="button" class="remove-preview absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity" data-index="${index}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    `;
                    previewGrid.appendChild(preview);
                };
                reader.readAsDataURL(file);
            });

            previewGrid.classList.remove('hidden');
            uploadBtn.classList.remove('hidden');
        }

        // Remove file from preview
        previewGrid.addEventListener('click', (e) => {
            if (e.target.closest('.remove-preview')) {
                const index = parseInt(e.target.closest('.remove-preview').dataset.index);
                selectedFiles.splice(index, 1);

                if (selectedFiles.length === 0) {
                    previewGrid.classList.add('hidden');
                    uploadBtn.classList.add('hidden');
                    imageUpload.value = '';
                } else {
                    handleFiles(selectedFiles);
                }
            }
        });

        // Upload images via AJAX
        uploadBtn.addEventListener('click', () => {
            if (selectedFiles.length === 0) return;

            const formData = new FormData();
            selectedFiles.forEach(file => {
                formData.append('images[]', file);
            });

            showLoading();

            axios.post(`/admin/chapters/${window.chapterId}/images/upload`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })
            .then(response => {
                if (response.data.success) {
                    // Add new images to grid
                    response.data.images.forEach(image => {
                        const imageHtml = `
                            <div class="image-item group relative bg-white rounded-lg shadow-md overflow-hidden transition-all duration-200 hover:shadow-xl" data-id="${image.id}">
                                <div class="drag-handle absolute top-2 left-2 cursor-move z-10 bg-gray-800 bg-opacity-75 text-white p-1.5 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"></path>
                                    </svg>
                                </div>
                                <button type="button" class="delete-image absolute top-2 right-2 z-10 bg-red-500 hover:bg-red-600 text-white p-1.5 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-200 transform hover:scale-110" data-id="${image.id}">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <img src="${image.url}" alt="Page ${image.order}" class="w-full h-40 object-cover">
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-3">
                                    <span class="image-order text-white text-sm font-semibold">Page ${image.order}</span>
                                </div>
                            </div>
                        `;
                        imagesGrid.insertAdjacentHTML('beforeend', imageHtml);
                    });

                    // Reset form
                    selectedFiles = [];
                    previewGrid.classList.add('hidden');
                    uploadBtn.classList.add('hidden');
                    previewGrid.innerHTML = '';
                    imageUpload.value = '';

                    showToast(response.data.message);
                }
            })
            .catch(error => {
                console.error('Error uploading images:', error);
                showToast('Error uploading images', 'error');
            })
            .finally(() => hideLoading());
        });

        // Toast notification
        function showToast(message, type = 'success') {
            const toastMessage = document.getElementById('toast-message');
            toastMessage.textContent = message;

            if (type === 'error') {
                toast.querySelector('.border-green-500').classList.remove('border-green-500');
                toast.querySelector('.border-l-4').classList.add('border-red-500');
                toast.querySelector('.text-green-500').classList.remove('text-green-500');
                toast.querySelector('.text-green-500').classList.add('text-red-500');
            }

            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }

        // Loading overlay
        function showLoading() {
            loadingOverlay.classList.remove('hidden');
            loadingOverlay.classList.add('flex');
        }

        function hideLoading() {
            loadingOverlay.classList.add('hidden');
            loadingOverlay.classList.remove('flex');
        }
    }

    // ===== Chapter Create Page - Image Upload with Preview =====
    if (document.getElementById('drop-zone-create')) {
        const dropZone = document.getElementById('drop-zone-create');
        const imageInput = document.getElementById('images');
        const previewGrid = document.getElementById('preview-grid-create');
        const imagesInfo = document.getElementById('images-info');
        const imageCount = document.getElementById('image-count');
        let selectedFiles = [];
        let dataTransfer = new DataTransfer();

        // Initialize Sortable for preview grid
        let sortable = null;

        // Drag and drop events
        dropZone.addEventListener('click', () => imageInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-orange-500', 'bg-orange-50');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-orange-500', 'bg-orange-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-orange-500', 'bg-orange-50');
            const files = Array.from(e.dataTransfer.files);
            handleFiles(files);
        });

        imageInput.addEventListener('change', (e) => {
            const files = Array.from(e.target.files);
            handleFiles(files);
        });

        // Handle selected files
        function handleFiles(files) {
            const imageFiles = files.filter(file => file.type.startsWith('image/'));

            if (imageFiles.length === 0) {
                alert('Please select valid image files');
                return;
            }

            // Add new files to existing selection
            selectedFiles = [...selectedFiles, ...imageFiles];
            updatePreview();
        }

        // Update preview grid
        function updatePreview() {
            previewGrid.innerHTML = '';

            if (selectedFiles.length === 0) {
                previewGrid.classList.add('hidden');
                imagesInfo.classList.add('hidden');
                return;
            }

            previewGrid.classList.remove('hidden');
            imagesInfo.classList.remove('hidden');
            imageCount.textContent = selectedFiles.length;

            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const preview = document.createElement('div');
                    preview.className = 'preview-item group relative bg-white rounded-lg shadow-md overflow-hidden transition-all duration-200 hover:shadow-xl';
                    preview.dataset.index = index;
                    preview.innerHTML = `
                        <div class="drag-handle-preview absolute top-2 left-2 cursor-move z-10 bg-gray-800 bg-opacity-75 text-white p-1.5 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"></path>
                            </svg>
                        </div>
                        <button type="button" class="remove-preview absolute top-2 right-2 z-10 bg-red-500 hover:bg-red-600 text-white p-1.5 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-200 transform hover:scale-110" data-index="${index}">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-40 object-cover">
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-3">
                            <span class="text-white text-sm font-semibold">Page ${index + 1}</span>
                        </div>
                    `;
                    previewGrid.appendChild(preview);

                    // Initialize or update Sortable after all images are loaded
                    if (index === selectedFiles.length - 1) {
                        initSortable();
                    }
                };
                reader.readAsDataURL(file);
            });

            // Update the file input with the current files
            updateFileInput();
        }

        // Initialize Sortable
        function initSortable() {
            if (sortable) {
                sortable.destroy();
            }

            sortable = new Sortable(previewGrid, {
                animation: 150,
                handle: '.drag-handle-preview',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onEnd: function(evt) {
                    // Reorder the files array
                    const movedFile = selectedFiles.splice(evt.oldIndex, 1)[0];
                    selectedFiles.splice(evt.newIndex, 0, movedFile);
                    updatePreview();
                }
            });
        }

        // Remove image from preview
        previewGrid.addEventListener('click', (e) => {
            if (e.target.closest('.remove-preview')) {
                const index = parseInt(e.target.closest('.remove-preview').dataset.index);
                selectedFiles.splice(index, 1);
                updatePreview();
            }
        });

        // Update the file input with current files
        function updateFileInput() {
            dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            imageInput.files = dataTransfer.files;
        }
    }
});
