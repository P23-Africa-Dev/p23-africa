<x-guest-layout>

    {{-- ── Heading ── --}}
    <div class="form-heading">
        @if (!empty($organization))
            <h1>Welcome to {{ $organization->name }}</h1>
            <p>Sign in to your organization account</p>
        @else
            <h1>Welcome back</h1>
            <p>Sign in to your account to continue</p>
        @endif
    </div>

    {{-- ── Session Status ── --}}
    @if (session('status'))
        <div class="auth-alert success">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('status') }}
        </div>
    @endif

    {{-- ── Global validation error banner ── --}}
    @if ($errors->any())
        <div class="auth-alert error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ !empty($orgSlug) ? url('/' . $orgSlug . '/admin/login') : route('login_user') }}"
        id="loginForm" novalidate>
        @csrf

        {{-- Email ── --}}
        <div class="field-group">
            <label class="field-label" for="email">Email address</label>
            <div class="field-wrap">
                <i class="bi bi-envelope field-icon"></i>
                <input id="email" class="field-input" type="email" name="email" value="{{ old('email') }}"
                    placeholder="you@example.com" required autofocus autocomplete="username">
            </div>
            @error('email')
                <div class="field-error">
                    <i class="bi bi-x-circle-fill"></i> {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Password ── --}}
        <div class="field-group">
            <label class="field-label" for="password">Password</label>
            <div class="field-wrap">
                <i class="bi bi-lock field-icon"></i>
                <input id="password" class="field-input has-toggle" type="password" name="password"
                    placeholder="••••••••" required autocomplete="current-password">
                <button type="button" class="field-toggle" onclick="togglePwd()"
                    aria-label="Toggle password visibility" id="pwdToggleBtn">
                    <i class="bi bi-eye" id="pwdToggleIcon"></i>
                </button>
            </div>
            @error('password')
                <div class="field-error">
                    <i class="bi bi-x-circle-fill"></i> {{ $message }}
                </div>
            @enderror
        </div>

        {{-- Remember / Forgot ── --}}
        <div class="form-row">
            <label class="remember-label">
                <input type="checkbox" name="remember" id="remember_me">
                <span>Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a class="forgot-link" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        {{-- Submit ── --}}
        <button type="submit" class="btn-login" id="loginBtn">
            <span id="btnText">Sign In</span>
            <i class="bi bi-arrow-right" id="btnArrow"></i>
            <span class="btn-spinner" id="btnSpinner"></span>
        </button>

    </form>

    <script>
        /* Password visibility toggle */
        function togglePwd() {
            const input = document.getElementById('password');
            const icon = document.getElementById('pwdToggleIcon');
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
        }

        /* Loading state on submit */
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            const text = document.getElementById('btnText');
            const arrow = document.getElementById('btnArrow');
            const spinner = document.getElementById('btnSpinner');
            btn.disabled = true;
            text.textContent = 'Signing in…';
            arrow.style.display = 'none';
            spinner.style.display = 'block';
        });
    </script>

</x-guest-layout>
