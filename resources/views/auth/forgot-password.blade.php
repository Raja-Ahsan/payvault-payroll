<x-guest-layout>
    <h1 class="form-title"
        data-aos="fade-down"
        data-aos-duration="1500"
        data-aos-delay="100"
        data-aos-easing="ease-out">Forgot Password</h1>

    <div class="mb-4 text-sm text-[var(--text-secondary-color)] leading-relaxed style-inter"
         data-aos="fade-up"
         data-aos-duration="1500"
         data-aos-delay="150"
         data-aos-easing="ease-out">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')"
                           data-aos="fade-up"
                           data-aos-duration="1500"
                           data-aos-delay="200"
                           data-aos-easing="ease-out" />

    <form method="POST" action="{{ route('password.email') }}">
        <div data-aos="fade-up" data-aos-duration="1500" data-aos-delay="250" data-aos-easing="ease-out">
            @csrf
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="flex justify-end mt-4"
             data-aos="fade-up"
             data-aos-duration="1500"
             data-aos-delay="350"
             data-aos-easing="ease-out">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
