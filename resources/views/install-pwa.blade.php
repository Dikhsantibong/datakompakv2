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
                <img src="{{ asset('logo/navlogo.png') }}" alt="DATAKOMPAK Logo" class="w-auto h-24 mx-auto mb-4">
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

        // Cek apakah PWA sudah terinstall
        const isPWAInstalled = () => {
            return window.matchMedia('(display-mode: standalone)').matches ||
                   window.navigator.standalone === true;
        };

        // Fungsi untuk mengecek kriteria installable yang lebih detail
        const checkInstallable = async () => {
            if (isPWAInstalled()) {
                console.log('PWA sudah terinstall');
                installButton.style.display = 'none';
                return false;
            }

            // Cek HTTPS
            if (window.location.protocol !== 'https:') {
                console.error('PWA membutuhkan HTTPS');
                return false;
            }

            // Cek Service Worker
            if (!('serviceWorker' in navigator)) {
                console.error('Service Worker tidak didukung di browser ini');
                return false;
            }

            try {
                const registration = await navigator.serviceWorker.register('/sw.js');
                console.log('Service Worker berhasil didaftarkan:', registration.scope);
                
                // Cek manifest
                const manifestLink = document.querySelector('link[rel="manifest"]');
                if (!manifestLink) {
                    console.error('Web manifest tidak ditemukan');
                    return false;
                }

                return true;
            } catch (error) {
                console.error('Gagal mendaftarkan Service Worker:', error);
                return false;
            }
        };

        // Inisialisasi dengan pengecekan yang lebih detail
        window.addEventListener('load', async () => {
            const isInstallable = await checkInstallable();
            if (!isInstallable) {
                console.log('PWA tidak dapat diinstall karena kriteria tidak terpenuhi');
                // Tampilkan pesan error jika dibutuhkan
            }
        });

        // Tangkap event beforeinstallprompt
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            console.log('Install prompt siap');
            
            // Tampilkan tombol install hanya jika belum terinstall
            if (!isPWAInstalled()) {
                installButton.style.display = 'block';
            }
        });

        // Handle klik tombol install dengan error handling yang lebih baik
        installButton.addEventListener('click', async () => {
            if (!deferredPrompt) {
                console.log('Prompt instalasi tidak tersedia');
                
                // Cek apakah running di supported browser
                const isChrome = /Chrome/.test(navigator.userAgent);
                const isSafari = /Safari/.test(navigator.userAgent);
                
                if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
                    installInstructions.style.display = 'block';
                    installButton.style.display = 'none';
                } else if (!isChrome && !isSafari) {
                    console.log('Browser mungkin tidak mendukung instalasi PWA');
                }
                return;
            }

            try {
                // Tampilkan prompt instalasi
                const result = await deferredPrompt.prompt();
                console.log('Prompt instalasi ditampilkan:', result);
                
                // Tunggu user response
                const choiceResult = await deferredPrompt.userChoice;
                console.log('User choice:', choiceResult.outcome);
                
                if (choiceResult.outcome === 'accepted') {
                    console.log('User menerima instalasi');
                } else {
                    console.log('User menolak instalasi');
                }
                
                // Reset prompt
                deferredPrompt = null;
                installButton.style.display = 'none';
                
            } catch (error) {
                console.error('Error saat instalasi:', error);
            }
        });

        // Deteksi successful installation
        window.addEventListener('appinstalled', (evt) => {
            console.log('DATAKOMPAK berhasil diinstall!');
            installButton.style.display = 'none';
        });

        // Khusus untuk iOS
        if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
            installButton.style.display = 'none';
            if (!isPWAInstalled()) {
                installInstructions.style.display = 'block';
            }
        }
    </script>
</body>
</html> 