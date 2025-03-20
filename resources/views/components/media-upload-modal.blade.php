<div id="mediaUploadModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 hidden overflow-y-auto h-full w-full z-50 backdrop-blur-sm transition-all duration-300">
    <div class="relative top-20 mx-auto p-8 border w-[500px] shadow-2xl rounded-xl bg-white transform transition-all">
        <div class="mt-2">
            <!-- Header -->
            <div class="flex justify-between items-center pb-5 border-b">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-lg">
                        <i class="fas fa-cloud-upload-alt text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">Upload Media</h3>
                </div>
                <button onclick="closeMediaModal()" 
                        class="text-gray-400 hover:text-gray-500 hover:rotate-90 transition-all duration-300">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Form -->
            <form action="#" method="POST" enctype="multipart/form-data" class="mt-6" id="mediaUploadForm">
                @csrf
                <input type="hidden" name="row_id" id="mediaRowId">
                
                <!-- File Type Selection -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-3">
                        Tipe File
                    </label>
                    <select id="mediaType" name="media_type" class="w-full px-4 py-2.5 text-sm text-gray-700 
                           border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 
                           transition-all duration-300" onchange="updateAcceptedTypes()">
                        <option value="">Pilih Tipe File</option>
                        <option value="image">Foto</option>
                        <option value="video">Video</option>
                    </select>
                </div>

                <!-- File Input -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-3">
                        Pilih File
                    </label>
                    <div class="relative">
                        <!-- Drag & Drop Zone -->
                        <div id="mediaDropZone" 
                             class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center
                                    transition-all duration-300 ease-in-out
                                    hover:border-blue-500 hover:bg-blue-50">
                            <input type="file" 
                                   name="media_file" 
                                   id="mediaFile" 
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                   required>
                            
                            <div class="space-y-4">
                                <i class="fas fa-cloud-upload-alt text-4xl text-blue-500"></i>
                                <div class="text-sm">
                                    <p class="font-medium text-gray-700">
                                        Drag & drop file disini atau
                                        <span class="text-blue-500">pilih file</span>
                                    </p>
                                    <p class="text-gray-500 mt-1" id="acceptedFormats">
                                        Pilih tipe file terlebih dahulu
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Container -->
                        <div id="mediaPreview" class="hidden mt-4">
                            <!-- Image Preview -->
                            <img id="imagePreview" class="hidden max-h-48 rounded-lg mx-auto" alt="Preview">
                            <!-- Video Preview -->
                            <video id="videoPreview" class="hidden max-h-48 rounded-lg mx-auto" controls></video>
                            
                            <div class="mt-3 p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <i id="fileIcon" class="fas text-xl"></i>
                                        <div>
                                            <p id="mediaFileName" class="text-sm font-medium text-gray-700"></p>
                                            <p id="mediaFileSize" class="text-xs text-gray-500"></p>
                                        </div>
                                    </div>
                                    <button type="button" 
                                            onclick="removeMediaFile()" 
                                            class="text-gray-400 hover:text-red-500 transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" 
                            onclick="closeMediaModal()" 
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
function updateAcceptedTypes() {
    const mediaType = document.getElementById('mediaType').value;
    const mediaFile = document.getElementById('mediaFile');
    const acceptedFormats = document.getElementById('acceptedFormats');
    
    if (mediaType === 'image') {
        mediaFile.accept = "image/*";
        acceptedFormats.textContent = "Format yang didukung: JPG, PNG, GIF (Max. 5MB)";
    } else if (mediaType === 'video') {
        mediaFile.accept = "video/*";
        acceptedFormats.textContent = "Format yang didukung: MP4, MOV, AVI (Max. 50MB)";
    } else {
        mediaFile.accept = "";
        acceptedFormats.textContent = "Pilih tipe file terlebih dahulu";
    }
}

function handleMediaFiles(e) {
    const file = e.target.files[0];
    if (!file) return;

    const mediaType = document.getElementById('mediaType').value;
    if (!mediaType) {
        alert('Pilih tipe file terlebih dahulu');
        return;
    }

    // Validate file type
    if (mediaType === 'image' && !file.type.startsWith('image/')) {
        alert('File harus berupa gambar');
        return;
    }
    if (mediaType === 'video' && !file.type.startsWith('video/')) {
        alert('File harus berupa video');
        return;
    }

    // Validate file size
    const maxSize = mediaType === 'image' ? 5 : 50; // 5MB for images, 50MB for videos
    if (file.size > maxSize * 1024 * 1024) {
        alert(`Ukuran file terlalu besar. Maksimal ${maxSize}MB`);
        return;
    }

    // Show preview
    const mediaPreview = document.getElementById('mediaPreview');
    const imagePreview = document.getElementById('imagePreview');
    const videoPreview = document.getElementById('videoPreview');
    const mediaFileName = document.getElementById('mediaFileName');
    const mediaFileSize = document.getElementById('mediaFileSize');
    const fileIcon = document.getElementById('fileIcon');
    const dropZone = document.getElementById('mediaDropZone');

    mediaFileName.textContent = file.name;
    mediaFileSize.textContent = formatFileSize(file.size);
    
    if (mediaType === 'image') {
        imagePreview.src = URL.createObjectURL(file);
        imagePreview.classList.remove('hidden');
        videoPreview.classList.add('hidden');
        fileIcon.className = 'fas fa-image text-blue-500 text-xl';
    } else {
        videoPreview.src = URL.createObjectURL(file);
        videoPreview.classList.remove('hidden');
        imagePreview.classList.add('hidden');
        fileIcon.className = 'fas fa-video text-blue-500 text-xl';
    }

    dropZone.classList.add('hidden');
    mediaPreview.classList.remove('hidden');
}

function removeMediaFile() {
    const mediaFile = document.getElementById('mediaFile');
    const mediaPreview = document.getElementById('mediaPreview');
    const dropZone = document.getElementById('mediaDropZone');
    const imagePreview = document.getElementById('imagePreview');
    const videoPreview = document.getElementById('videoPreview');

    mediaFile.value = '';
    imagePreview.src = '';
    videoPreview.src = '';
    dropZone.classList.remove('hidden');
    mediaPreview.classList.add('hidden');
}

function showMediaModal(rowId) {
    const modal = document.getElementById('mediaUploadModal');
    document.getElementById('mediaRowId').value = rowId;
    modal.classList.remove('hidden');
    removeMediaFile();
}

function closeMediaModal() {
    const modal = document.getElementById('mediaUploadModal');
    modal.classList.add('hidden');
    document.getElementById('mediaType').value = '';
    removeMediaFile();
}

// Initialize event listeners
document.getElementById('mediaFile').addEventListener('change', handleMediaFiles);

// Format file size helper
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}
</script> 