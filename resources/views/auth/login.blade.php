<x-guest-layout>
    <h1 class="form-title"
        data-aos="fade-down"
        data-aos-duration="1500"
        data-aos-delay="100"
        data-aos-easing="ease-out">Log in</h1>

    <x-auth-session-status class="mb-4" :status="session('status')"
                           data-aos="fade-up"
                           data-aos-duration="1500"
                           data-aos-delay="150"
                           data-aos-easing="ease-out" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div data-aos="fade-up" data-aos-duration="1500" data-aos-delay="200" data-aos-easing="ease-out">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div data-aos="fade-up" data-aos-duration="1500" data-aos-delay="300" data-aos-easing="ease-out">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Remember Me -->
        <div class="block" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="400" data-aos-easing="ease-out">
            <label for="remember_me" class="inline-flex items-center form-label">
                <input id="remember_me" type="checkbox" class="auth-checkbox" name="remember">
                <span class="ms-2 text-sm text-[var(--text-secondary-color)] style-inter">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2"
             data-aos="fade-up"
             data-aos-duration="1500"
             data-aos-delay="500"
             data-aos-easing="ease-out">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="form-link">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
