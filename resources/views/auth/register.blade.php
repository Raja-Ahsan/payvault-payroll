<x-guest-layout>
    <h1 class="form-title"
        data-aos="fade-down"
        data-aos-duration="1500"
        data-aos-delay="100"
        data-aos-easing="ease-out">Register</h1>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div data-aos="fade-up" data-aos-duration="1500" data-aos-delay="150" data-aos-easing="ease-out">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <!-- Email Address -->
        <div data-aos="fade-up" data-aos-duration="1500" data-aos-delay="250" data-aos-easing="ease-out">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div data-aos="fade-up" data-aos-duration="1500" data-aos-delay="350" data-aos-easing="ease-out">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Confirm Password -->
        <div data-aos="fade-up" data-aos-duration="1500" data-aos-delay="450" data-aos-easing="ease-out">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2"
             data-aos="fade-up"
             data-aos-duration="1500"
             data-aos-delay="550"
             data-aos-easing="ease-out">
            <a href="{{ route('login') }}" class="form-link">
                {{ __('Already registered?') }}
            </a>
            <x-primary-button>
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
