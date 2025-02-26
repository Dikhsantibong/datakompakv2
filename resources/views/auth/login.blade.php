@extends('layouts.app')

@section('content')
<!-- Add canvas element at the top of the container -->
<canvas id="matrix" class="matrix-bg"></canvas>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <!-- Power grid background -->
    <div class="power-grid">
        <!-- Electric current animations -->
        <div class="electric-current current-1"></div>
        <div class="electric-current current-2"></div>
        <div class="electric-current current-3"></div>
        <div class="electric-current current-4"></div>
        
        <div class="power-line horizontal"></div>
        <div class="power-line vertical"></div>
        <div class="power-line diagonal-1"></div>
        <div class="power-line diagonal-2"></div>
        
        <!-- Electric nodes -->
        <div class="electric-node node-1">
            <div class="node-spark"></div>
        </div>
        <div class="electric-node node-2">
            <div class="node-spark"></div>
        </div>
        <div class="electric-node node-3">
            <div class="node-spark"></div>
        </div>
        <div class="electric-node node-4">
            <div class="node-spark"></div>
        </div>
    </div>

    <div class="card login-card">
        <div class="card-inner">
            <!-- Kolom kiri -->
            <div class="left-section">
                <div class="logo-wrapper">
                    <img src="{{ asset('logo/pjb-logo.png') }}" alt="Logo" class="logo">
                    <div class="electric-circle"></div>
                    <!-- Electric sparks -->
                    <div class="spark spark-1"></div>
                    <div class="spark spark-2"></div>
                    <div class="spark spark-3"></div>
                    <div class="spark spark-4"></div>
                </div>
            </div>
            <!-- Kolom kanan -->
            <div class="right-section">
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">Login Now</h5>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Username -->
                        <div class="form-group mb-3">
                            <div class="modern-input-group">
                                <i class="fas fa-user"></i>
                                <input id="email" type="email" class="modern-form-control @error('email') is-invalid @enderror" name="email" placeholder="Enter Username" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            </div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-group mb-3">
                            <div class="modern-input-group">
                                <i class="fas fa-lock"></i>
                                <input id="password" type="password" class="modern-form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter Password" required autocomplete="current-password">
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Unit Selection -->
                        <div class="form-group mb-3">
                            <label class="modern-label">Pilih Unit:</label>
                            <div class="modern-input-group">
                                <i class="fas fa-building"></i>
                                <select name="unit" id="unit" class="modern-form-control" required>
                                    <option value="mysql" {{ $selectedUnit == 'mysql' ? 'selected' : '' }}>
                                        UP Kendari
                                    </option>
                                    <option value="mysql_bau_bau" {{ $selectedUnit == 'mysql_bau_bau' ? 'selected' : '' }}>
                                        UNIT Bau-Bau
                                    </option>
                                    <option value="mysql_kolaka" {{ $selectedUnit == 'mysql_kolaka' ? 'selected' : '' }}>
                                        UNIT Kolaka
                                    </option>
                                    <option value="mysql_poasia" {{ $selectedUnit == 'mysql_poasia' ? 'selected' : '' }}>
                                        UNIT Poasia
                                    </option>
                                    <option value="mysql_wua_wua" {{ $selectedUnit == 'mysql_wua_wua' ? 'selected' : '' }}>
                                        UNIT Wua-Wua
                                    </option>
                                    <option value="mysql_moramo" {{ $selectedUnit == 'mysql_moramo' ? 'selected' : '' }}>
                                        UNIT Moramo
                                    </option>
                                    <option value="mysql_baruta" {{ $selectedUnit == 'mysql_baruta' ? 'selected' : '' }}>
                                        UNIT Baruta
                                    </option>
                                    <option value="mysql_pltmg_bau_bau" {{ $selectedUnit == 'mysql_pltmg_bau_bau' ? 'selected' : '' }}>
                                        UNIT PLTMG Bau-Bau
                                    </option>
                                    <option value="mysql_winning" {{ $selectedUnit == 'mysql_winning' ? 'selected' : '' }}>
                                        UNIT Winning
                                    </option>
                                    <option value="mysql_sabilambo" {{ $selectedUnit == 'mysql_sabilambo' ? 'selected' : '' }}>
                                        UNIT Sabilambo
                                    </option>
                                    <option value="mysql_rongi" {{ $selectedUnit == 'mysql_rongi' ? 'selected' : '' }}>
                                        UNIT Rongi
                                    </option>
                                    <option value="mysql_mikuasi" {{ $selectedUnit == 'mysql_mikuasi' ? 'selected' : '' }}>
                                        UNIT Mikuasi
                                    </option>
                                    <option value="mysql_ladumpi" {{ $selectedUnit == 'mysql_ladumpi' ? 'selected' : '' }}>
                                        UNIT Ladumpi
                                    </option>
                                    <option value="mysql_langara" {{ $selectedUnit == 'mysql_langara' ? 'selected' : '' }}>
                                        UNIT Langara
                                    </option>
                                    <option value="mysql_lanipa_nipa" {{ $selectedUnit == 'mysql_lanipa_nipa' ? 'selected' : '' }}>
                                        UNIT Lanipa Nipa
                                    </option>
                                    <option value="mysql_pasarwajo" {{ $selectedUnit == 'mysql_pasarwajo' ? 'selected' : '' }}>
                                        UNIT Pasarwajo
                                    </option>
                                    <option value="mysql_raha" {{ $selectedUnit == 'mysql_raha' ? 'selected' : '' }}>
                                        UNIT Raha
                                    </option>
                                    <option value="mysql_wangi_wangi" {{ $selectedUnit == 'mysql_wangi_wangi' ? 'selected' : '' }}>
                                        UNIT Wangi Wangi
                                    </option>
                                    <option value="mysql_ereke" {{ $selectedUnit == 'mysql_ereke' ? 'selected' : '' }}>
                                        UNIT Ereke
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>

                        <!-- Login Button -->
                        <div class="d-grid">
                            <button type="submit" class="modern-button">
                                LOGIN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    body {
        margin: 0;
        opacity: 0;
        animation: fadeIn ease 1s forwards;
        background: #090c1f;
        overflow: hidden;
    }

    .power-grid {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, #090c1f, #1a237e);
        z-index: 1;
        overflow: hidden;
    }

    .electric-current {
        position: absolute;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, transparent, #1e90ff, transparent);
        opacity: 0;
        filter: blur(1px);
    }

    .current-1 {
        top: 20%;
        animation: currentFlow 3s infinite;
    }

    .current-2 {
        top: 40%;
        animation: currentFlow 3s infinite 1s;
    }

    .current-3 {
        top: 60%;
        animation: currentFlow 3s infinite 1.5s;
    }

    .current-4 {
        top: 80%;
        animation: currentFlow 3s infinite 2s;
    }

    @keyframes currentFlow {
        0% {
            transform: translateX(-100%);
            opacity: 0;
        }
        10%, 90% {
            opacity: 0.5;
        }
        100% {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    .node-spark {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: #1e90ff;
        animation: sparkPulse 1s infinite;
    }

    @keyframes sparkPulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.5);
            opacity: 0.5;
        }
    }

    .power-line {
        position: absolute;
        background: rgba(30, 144, 255, 0.1);
        box-shadow: 0 0 15px rgba(30, 144, 255, 0.2);
    }

    .horizontal {
        width: 100%;
        height: 2px;
        top: 50%;
        animation: powerPulse 3s infinite;
    }

    .vertical {
        width: 2px;
        height: 100%;
        left: 50%;
        animation: powerPulse 3s infinite 1s;
    }

    .diagonal-1 {
        width: 2px;
        height: 150%;
        top: -25%;
        left: 25%;
        transform: rotate(45deg);
        animation: powerPulse 3s infinite 1.5s;
    }

    .diagonal-2 {
        width: 2px;
        height: 150%;
        top: -25%;
        right: 25%;
        transform: rotate(-45deg);
        animation: powerPulse 3s infinite 2s;
    }

    .electric-node {
        position: absolute;
        width: 20px;
        height: 20px;
        background: #1e90ff;
        border-radius: 50%;
        box-shadow: 
            0 0 20px #1e90ff,
            0 0 40px #1e90ff,
            0 0 60px #1e90ff;
        animation: nodeGlow 2s infinite;
    }

    .node-1 { top: 20%; left: 20%; }
    .node-2 { top: 20%; right: 20%; }
    .node-3 { bottom: 20%; left: 20%; }
    .node-4 { bottom: 20%; right: 20%; }

    @keyframes powerPulse {
        0%, 100% {
            opacity: 0.1;
            box-shadow: 0 0 15px rgba(30, 144, 255, 0.2);
        }
        50% {
            opacity: 0.5;
            box-shadow: 
                0 0 15px rgba(30, 144, 255, 0.5),
                0 0 30px rgba(30, 144, 255, 0.3);
        }
    }

    @keyframes nodeGlow {
        0%, 100% {
            transform: scale(1);
            opacity: 0.8;
        }
        50% {
            transform: scale(1.2);
            opacity: 1;
        }
    }

    .login-card {
        position: relative;
        z-index: 2;
        width: 100%;
        max-width: 900px;
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .card-inner {
        display: flex;
        background: #fff;
    }

    .left-section {
        flex: 1;
        padding: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #1e3c72, #2a5298);
    }

    .logo-wrapper {
        position: relative;
        z-index: 2;
    }

    .logo {
        width: 300px;
        height: auto;
        position: relative;
        z-index: 2;
    }

    .electric-circle {
        position: absolute;
        width: 300px;
        height: 300px;
        border: 2px solid rgba(30, 144, 255, 0.3);
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        animation: rotateCircle 10s linear infinite;
    }

    .electric-circle::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border: 2px solid rgba(30, 144, 255, 0.2);
        border-radius: 50%;
        animation: pulseCircle 2s ease-out infinite;
    }

    .spark {
        position: absolute;
        width: 2px;
        height: 20px;
        background: #1e90ff;
        border-radius: 50%;
        opacity: 0;
    }

    .spark-1 { top: 20%; left: 50%; animation: sparkFlash 2s infinite; }
    .spark-2 { top: 50%; right: 20%; animation: sparkFlash 2s infinite 0.5s; }
    .spark-3 { bottom: 20%; left: 50%; animation: sparkFlash 2s infinite 1s; }
    .spark-4 { top: 50%; left: 20%; animation: sparkFlash 2s infinite 1.5s; }

    @keyframes rotateCircle {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }

    @keyframes pulseCircle {
        0% {
            transform: scale(0.8);
            opacity: 0.8;
        }
        100% {
            transform: scale(1.2);
            opacity: 0;
        }
    }

    @keyframes sparkFlash {
        0%, 100% {
            opacity: 0;
            height: 20px;
        }
        50% {
            opacity: 1;
            height: 40px;
            box-shadow: 
                0 0 10px #1e90ff,
                0 0 20px #1e90ff,
                0 0 30px #1e90ff;
        }
    }

    .right-section {
        flex: 1;
        padding: 40px;
        background: #fff;
    }

    .power-lines {
        position: absolute;
        inset: 0;
        pointer-events: none;
        z-index: 0;
    }

    .card-body {
        position: relative;
        z-index: 2;
    }

    .card-title {
        color: #333;
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 30px;
    }

    .modern-input-group {
        position: relative;
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 12px 15px;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
    }

    .modern-input-group:focus-within {
        border-color: #2a5298;
        box-shadow: 0 0 0 3px rgba(42,82,152,0.1);
    }

    .modern-input-group i {
        color: #2a5298;
        margin-right: 10px;
        font-size: 18px;
    }

    .modern-form-control {
        border: none;
        background: transparent;
        width: 100%;
        padding: 5px;
        color: #333;
    }

    .modern-form-control:focus {
        outline: none;
    }

    .modern-form-control::placeholder {
        color: #999;
    }

    .modern-label {
        color: #2a5298;
        margin-bottom: 8px;
        display: block;
    }

    .modern-button {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 15px;
        font-weight: 600;
        letter-spacing: 1px;
        width: 100%;
        transition: all 0.3s ease;
    }

    .modern-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(42,82,152,0.3);
    }

    .form-check-label {
        color: #666;
    }

    .form-check-input:checked {
        background-color: #2a5298;
        border-color: #2a5298;
    }

    /* Fixing the overlay effects */
    .right-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(45deg, 
            transparent, 
            rgba(30, 144, 255, 0.05), 
            transparent);
        pointer-events: none;
        z-index: 1;
    }

    .power-line-h,
    .power-line-v {
        pointer-events: none;
        z-index: 1;
    }

    /* Ensuring form elements are clickable */
    form {
        position: relative;
        z-index: 2;
    }

    /* Making select options visible and clickable */
    .form-select option {
        background: white;
        color: #1a237e;
        padding: 10px;
    }

    /* Improving focus visibility */
    .form-control:focus,
    .form-select:focus {
        box-shadow: none;
        background: rgba(255, 255, 255, 0.98);
    }

    /* Custom select styling */
    .form-select {
        cursor: pointer;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    .input-wrapper select {
        padding-right: 30px;
    }

    .input-wrapper::after {
        content: '▼';
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #1a237e;
        pointer-events: none;
        font-size: 12px;
        z-index: 3;
    }

    /* Ensuring error messages are visible */
    .invalid-feedback {
        position: relative;
        z-index: 2;
        color: #dc3545;
        font-size: 14px;
        margin-top: 5px;
    }

    /* Additional hover effects */
    .input-wrapper:hover {
        border-color: rgba(30, 144, 255, 0.5);
    }

    .form-check-input:hover {
        border-color: #1e90ff;
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-inner {
            flex-direction: column;
        }
        
        .left-section,
        .right-section {
            padding: 30px;
        }
    }

    /* Add new Matrix canvas styles */
    .matrix-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
    }
</style>
@endsection

@section('scripts')
<script>
    // Add Matrix Rain Animation
    const canvas = document.getElementById('matrix');
    const ctx = canvas.getContext('2d');

    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%^&*';
    const charSize = 14;
    const columns = canvas.width / charSize;
    const drops = [];

    for (let i = 0; i < columns; i++) {
        drops[i] = 1;
    }

    function draw() {
        ctx.fillStyle = 'rgba(10, 25, 47, 0.05)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        ctx.fillStyle = '#64ffda';
        ctx.font = `${charSize}px monospace`;

        for (let i = 0; i < drops.length; i++) {
            const text = chars[Math.floor(Math.random() * chars.length)];
            ctx.fillText(text, i * charSize, drops[i] * charSize);

            if (drops[i] * charSize > canvas.height && Math.random() > 0.975) {
                drops[i] = 0;
            }
            drops[i]++;
        }
    }

    setInterval(draw, 35);

    window.addEventListener('resize', () => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    });

    // Existing scripts
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: '{{ session("error") }}',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Coba Lagi',
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session("success") }}',
                confirmButtonColor: '#28a745',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: 'Email atau password yang Anda masukkan salah!',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Coba Lagi',
                timer: 3000,
                timerProgressBar: true
            });
        @endif
    });
</script>
@endsection
