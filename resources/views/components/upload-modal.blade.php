<div id="uploadModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 hidden overflow-y-auto h-full w-full z-50 backdrop-blur-sm transition-all duration-300">
    <div class="relative top-20 mx-auto p-8 border w-[500px] shadow-2xl rounded-xl bg-white transform transition-all">
        <div class="mt-2">
            <!-- Header -->
            <div class="flex justify-between items-center pb-5 border-b">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <i class="fas fa-cloud-upload-alt text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Upload Dokumen</h3>
                </div>
                <button onclick="closeUploadModal()" 
                        class="text-gray-400 hover:text-gray-500 hover:rotate-90 transition-all duration-300">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.library.upload') }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  class="mt-6"
                  id="uploadForm">
                @csrf
                <input type="hidden" name="category" id="documentCategory">
                
                <!-- File Input -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-3" for="document">
                        Pilih File
                    </label>
                    <div class="relative">
                        <!-- Drag & Drop Zone -->
                        <div id="dropZone" 
                             class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center
                                    transition-all duration-300 ease-in-out
                                    hover:border-blue-500 hover:bg-blue-50">
                            <input type="file" 
                                   name="document" 
                                   id="document" 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                   accept=".pdf,.doc,.docx,.xls,.xlsx"
                                   required>
                            
                            <div class="space-y-4">
                                <i class="fas fa-cloud-upload-alt text-4xl text-blue-500"></i>
                                <div class="text-sm">
                                    <p class="font-medium text-gray-700">
                                        Drag & drop file disini atau
                                        <span class="text-blue-500">pilih file</span>
                                    </p>
                                    <p class="text-gray-500 mt-1">
                                        Format yang didukung: PDF, DOC, DOCX, XLS, XLSX (Max. 10MB)
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- File Preview -->
                        <div id="filePreview" class="hidden mt-4 p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                                    <div>
                                        <p id="fileName" class="text-sm font-medium text-gray-700"></p>
                                        <p id="fileSize" class="text-xs text-gray-500"></p>
                                    </div>
                                </div>
                                <button type="button" 
                                        onclick="removeFile()" 
                                        class="text-gray-400 hover:text-red-500 transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Input -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-3" for="description">
                        Deskripsi Dokumen
                    </label>
                    <textarea name="description" 
                              id="description"
                              class="w-full px-4 py-2.5 text-sm text-gray-700
                                     border border-gray-200 rounded-lg
                                     focus:outline-none focus:border-blue-500
                                     transition-all duration-300
                                     resize-none"
                              rows="4"
                              placeholder="Tambahkan deskripsi dokumen..."></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" 
                            onclick="closeUploadModal()" 
                            class="px-4 py-2.5 text-sm font-medium text-gray-700 
                                   bg-white border border-gray-300 rounded-lg
                                   hover:bg-gray-50 hover:border-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-gray-200
                                   transition-all duration-300">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2.5 text-sm font-medium text-white
                                   bg-blue-600 rounded-lg
                                   hover:bg-blue-700
                                   focus:outline-none focus:ring-2 focus:ring-blue-500
                                   transition-all duration-300">
                        <i class="fas fa-cloud-upload-alt mr-2"></i>Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('document');
const filePreview = document.getElementById('filePreview');
const fileName = document.getElementById('fileName');
const fileSize = document.getElementById('fileSize');

// Prevent default drag behaviors
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
    document.body.addEventListener(eventName, preventDefaults, false);
});

// Highlight drop zone when dragging over it
['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, unhighlight, false);
});

// Handle dropped files
dropZone.addEventListener('drop', handleDrop, false);

// Handle file input change
fileInput.addEventListener('change', handleFiles);

function preventDefaults (e) {
    e.preventDefault();
    e.stopPropagation();
}

function highlight(e) {
    dropZone.classList.add('border-blue-500', 'bg-blue-50');
}

function unhighlight(e) {
    dropZone.classList.remove('border-blue-500', 'bg-blue-50');
}

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;
    handleFiles({ target: { files: files } });
}

function handleFiles(e) {
    const file = e.target.files[0];
    if (file) {
        // Check file type
        const allowedTypes = ['.pdf', '.doc', '.docx', '.xls', '.xlsx'];
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
        
        if (!allowedTypes.includes(fileExtension)) {
            alert('Format file tidak didukung. Silakan pilih file PDF, DOC, DOCX, XLS, atau XLSX.');
            return;
        }

        // Check file size (max 10MB)
        if (file.size > 10 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 10MB.');
            return;
        }

        // Update preview
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        dropZone.classList.add('hidden');
        filePreview.classList.remove('hidden');
    }
}

function removeFile() {
    fileInput.value = '';
    dropZone.classList.remove('hidden');
    filePreview.classList.add('hidden');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function showUploadModal(category) {
    const modal = document.getElementById('uploadModal');
    document.getElementById('documentCategory').value = category;
    modal.classList.remove('hidden');
    // Reset file input
    removeFile();
    // Add animation
    setTimeout(() => {
        modal.querySelector('.relative').classList.add('translate-y-0', 'opacity-100');
        modal.querySelector('.relative').classList.remove('translate-y-4', 'opacity-0');
    }, 100);
}

function closeUploadModal() {
    const modal = document.getElementById('uploadModal');
    const modalContent = modal.querySelector('.relative');
    
    // Add closing animation
    modalContent.classList.add('translate-y-4', 'opacity-0');
    modalContent.classList.remove('translate-y-0', 'opacity-100');
    
    // Hide modal after animation
    setTimeout(() => {
        modal.classList.add('hidden');
        document.getElementById('document').value = '';
        document.getElementById('description').value = '';
        removeFile();
    }, 300);
}

// Close modal when clicking outside
document.getElementById('uploadModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeUploadModal();
    }
});
</script>

<style>
    /* Animation classes */
    .relative {
        transition: all 0.3s ease-out;
    }
    
    #uploadModal:not(.hidden) .relative {
        animation: modalFadeIn 0.3s ease-out forwards;
    }
    
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Drag & Drop Styles */
    #dropZone.drag-over {
        border-color: #3B82F6;
        background-color: #EFF6FF;
    }
</style> 