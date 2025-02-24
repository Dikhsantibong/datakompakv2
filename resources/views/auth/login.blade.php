@extends('layouts.app')

@section('content')
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
                    <h5 class="card-title text-center mb-4">{{ __('Login Now') }}</h5>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Username -->
                        <div class="form-group custom-input-group">
                            <div class="input-wrapper">
                                <i class="fas fa-user"></i>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Enter Username" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            </div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-group custom-input-group mt-3">
                            <div class="input-wrapper">
                                <i class="fas fa-lock"></i>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter Password" required autocomplete="current-password">
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Unit Selection -->
                        <div class="form-group custom-input-group mt-3">
                            <label for="unit" class="text-primary">Pilih Unit:</label>
                            <div class="input-wrapper">
                                <i class="fas fa-building"></i>
                                <select name="unit" id="unit" required class="form-select">
                                    <option value="mysql" {{ $selectedUnit == 'mysql' ? 'selected' : '' }} class="text-success">
                                        <i class="fas fa-building"></i> UP Kendari
                                    </option>
                                    <option value="mysql_bau_bau" {{ $selectedUnit == 'mysql_bau_bau' ? 'selected' : '' }} class="text-primary">
                                        <i class="fas fa-building"></i> UNIT Bau-Bau
                                    </option>
                                    <option value="mysql_kolaka" {{ $selectedUnit == 'mysql_kolaka' ? 'selected' : '' }} class="text-primary">
                                        <i class="fas fa-building"></i> UNIT Kolaka
                                    </option>
                                    <option value="mysql_poasia" {{ $selectedUnit == 'mysql_poasia' ? 'selected' : '' }} class="text-primary">
                                        <i class="fas fa-building"></i> UNIT Poasia
                                    </option>
                                    <option value="mysql_wua_wua" {{ $selectedUnit == 'mysql_wua_wua' ? 'selected' : '' }} class="text-primary">
                                        <i class="fas fa-building"></i> UNIT Wua-Wua
                                    </option>
                                    <option value="mysql_moramo" {{ $selectedUnit == 'mysql_moramo' ? 'selected' : '' }} class="text-primary">
                                        <i class="fas fa-building"></i> UNIT Moramo
                                    </option>
                                    <option value="mysql_baruta" {{ $selectedUnit == 'mysql_baruta' ? 'selected' : '' }} class="text-primary">
                                        <i class="fas fa-building"></i> UNIT Baruta
                                    </option>
                                    <option value="mysql_pltmg_bau_bau" {{ $selectedUnit == 'mysql_pltmg_bau_bau' ? 'selected' : '' }} class="text-primary">
                                        <i class="fas fa-building"></i> UNIT PLTMG Bau-Bau
                                    </option>
                                    <option value="mysql_winning" {{ $selectedUnit == 'mysql_winning' ? 'selected' : '' }} class="text-primary">
                                        <i class="fas fa-building"></i> UNIT Winning
                                    </option>
                                    <option value="mysql_sabilambo" {{ $selectedUnit == 'mysql_sabilambo' ? 'selected' : '' }} class="text-primary">
                                        <i class="fas fa-building"></i> UNIT Sabilambo
                                    </option>
                                    <option value="mysql_rongi" {{ $selectedUnit == 'mysql_rongi' ? 'selected' : '' }} class="text-primary">
                                        <i class="fas fa-building"></i> UNIT Rongi
                                    </option>
                                    <option value="mysql_mikuasi" {{ $selectedUnit == 'mysql_mikuasi' ? 'selected' : '' }} class="text-primary">
                                        <i class="fas fa-building"></i> UNIT Mikuasi
                                    </option>
                                    <option value="mysql_ladumpi" {{ $selectedUnit == 'mysql_ladumpi' ? 'selected' : '' }} class="text-primary">
                                        <i class="fas fa-building"></i> UNIT Ladumpi
                                    </option>
                                    <option value="mysql_langara" {{ $selectedUnit == 'mysql_langara' ? 'selected' : '' }} class="text-primary">
                                        <i class="fas fa-building"></i> UNIT Langara
                                    </option>
                                    <option value="mysql_lanipa_nipa" {{ $selectedUnit == 'mysql_lanipa_nipa' ? 'selected' : '' }} class="text-primary">
                                        <i class="fas fa-building"></i> UNIT Lanipa Nipa
                                    </option>
                                    <option value="mysql_pasarwajo" {{ $selectedUnit == 'mysql_pasarwajo' ? 'selected' : '' }} class="text-primary">
                                        <i class="fas fa-building"></i> UNIT Pasarwajo
                                    </option>
                                    <option value="mysql_raha" {{ $selectedUnit == 'mysql_raha' ? 'selected' : '' }} class="text-primary">
                                        <i class="fas fa-building"></i> UNIT Raha
                                    </option>
                                    <option value="mysql_wangi_wangi" {{ $selectedUnit == 'mysql_wangi_wangi' ? 'selected' : '' }} class="text-primary">
                                        <i class="fas fa-building"></i> UNIT Wangi Wangi
                                    </option>
                                    <option value="mysql_ereke" {{ $selectedUnit == 'mysql_ereke' ? 'selected' : '' }} class="text-primary">
                                        <i class="fas fa-building"></i> UNIT Ereke
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Remember Me -->
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        <!-- Login Button -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary w-100 login-btn">
                                <span>{{ __('Login') }}</span>
                                <div class="energy-beam"></div>
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
        z-index: -1;
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
        width: 100%;
        max-width: 900px;
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(20px);
        border-radius: 25px;
        overflow: hidden;
        border: 1px solid rgba(30, 144, 255, 0.2);
        box-shadow: 
            0 0 40px rgba(30, 144, 255, 0.2),
            0 0 80px rgba(30, 144, 255, 0.1);
    }

    .card-inner {
        display: flex;
        background: rgba(0, 0, 0, 0.6);
    }

    .left-section {
        flex: 1;
        padding: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        background: rgba(0, 0, 0, 0.7);
        overflow: hidden;
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
        background: rgba(255, 255, 255, 0.97);
        padding: 40px;
        position: relative;
        border-radius: 25px;
        margin: 15px;
        backdrop-filter: blur(20px);
        box-shadow: 
            inset 0 0 100px rgba(30, 144, 255, 0.1),
            0 0 50px rgba(30, 144, 255, 0.2),
            0 10px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
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

    .input-wrapper {
        position: relative;
        border: 2px solid rgba(30, 144, 255, 0.3);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s ease;
        background: rgba(255, 255, 255, 0.95);
        margin-bottom: 20px;
        z-index: 2;
    }

    .input-wrapper i {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #1a237e;
        z-index: 3;
        font-size: 20px;
        transition: all 0.4s ease;
        pointer-events: none;
    }

    .form-control, 
    .form-select {
        position: relative;
        border: none;
        padding: 18px 50px;
        background: transparent;
        width: 100%;
        color: #1a237e;
        font-size: 16px;
        font-weight: 500;
        transition: all 0.4s ease;
        z-index: 2;
    }

    .form-control:focus,
    .form-select:focus {
        outline: none;
    }

    .input-wrapper:focus-within {
        border-color: #1e90ff;
        transform: translateY(-2px);
        box-shadow: 
            0 8px 25px rgba(30, 144, 255, 0.15),
            0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .input-wrapper:focus-within i {
        color: #1e90ff;
        transform: translateY(-50%) scale(1.1);
    }

    .form-check {
        position: relative;
        z-index: 2;
    }

    .form-check-input {
        cursor: pointer;
    }

    .form-check-label {
        cursor: pointer;
    }

    .login-btn {
        position: relative;
        background: linear-gradient(45deg, #1a237e, #1e90ff);
        border: none;
        padding: 18px;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s ease;
        font-size: 18px;
        font-weight: 700;
        letter-spacing: 2px;
        color: white;
        text-transform: uppercase;
        box-shadow: 
            0 5px 15px rgba(30, 144, 255, 0.3),
            0 3px 8px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        z-index: 2;
    }

    .login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 
            0 8px 25px rgba(30, 144, 255, 0.4),
            0 5px 12px rgba(0, 0, 0, 0.3);
    }

    .login-btn:active {
        transform: translateY(1px);
    }

    .card-title {
        position: relative;
        z-index: 2;
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
        
        .left-section, .right-section {
            width: 100%;
        }
        
        .left-section {
            padding: 20px;
        }
    }
</style>
@endsection

@section('scripts')
<script>
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
