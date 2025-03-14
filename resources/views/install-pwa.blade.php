<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install RDM App</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#009BB9">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        .install-button {
            display: none;
        }
        
        @media all and (display-mode: browser) {
            .install-button {
                display: block;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="text-center mb-8">
                <img src="/images/logo.png" alt="DATAKOMPAK Logo" class="w-24 h-24 mx-auto mb-4">
                <h1 class="text-2xl font-bold text-gray-800">DATAKOMPAK</h1>
                <p class="text-sm text-gray-600 mt-1">Data Komunitas Operasi Mantap Unit Pembangkit Kendari</p>
                <p class="text-gray-600 mt-2">Install aplikasi untuk pengalaman yang lebih baik</p>
            </div>

            <div class="space-y-4">
                <div class="flex items-center text-gray-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Akses lebih cepat</span>
                </div>
                <div class="flex items-center text-gray-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Bekerja secara offline</span>
                </div>
                <div class="flex items-center text-gray-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Update otomatis</span>
                </div>
            </div>

            <div class="mt-8 space-y-4">
                <button id="installButton" class="install-button w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    Install Aplikasi
                </button>
                
                <a href="{{ route('admin.dashboard') }}" class="block text-center w-full bg-gray-200 text-gray-800 py-3 px-4 rounded-lg font-medium hover:bg-gray-300 transition-colors">
                    Kembali ke Aplikasi
                </a>
            </div>

            <div id="installInstructions" class="mt-6 text-sm text-gray-600 hidden">
                <p class="font-medium mb-2">Cara Install di iOS:</p>
                <ol class="list-decimal pl-5 space-y-1">
                    <li>Buka Safari</li>
                    <li>Ketuk tombol Share</li>
                    <li>Pilih 'Add to Home Screen'</li>
                    <li>Ketuk 'Add'</li>
                </ol>
            </div>
        </div>
    </div>

    <script>
        let deferredPrompt;
        const installButton = document.getElementById('installButton');
        const installInstructions = document.getElementById('installInstructions');

        // Deteksi apakah bisa install PWA
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            installButton.style.display = 'block';
        });

        // Handle tombol install
        installButton.addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                console.log(`User response to install prompt: ${outcome}`);
                deferredPrompt = null;
            }
        });

        // Deteksi platform
        if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
            installButton.style.display = 'none';
            installInstructions.style.display = 'block';
        }

        // Deteksi jika sudah terinstall
        window.addEventListener('appinstalled', () => {
            installButton.style.display = 'none';
            console.log('PWA was installed');
        });
    </script>
</body>
</html> 