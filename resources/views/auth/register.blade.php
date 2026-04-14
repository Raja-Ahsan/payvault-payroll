<x-guest-layout>
    <h1 class="form-title"
        data-aos="fade-down"
        data-aos-duration="1500"
        data-aos-delay="100"
        data-aos-easing="ease-out">Register</h1>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        @if (old('package', request('package')))
            <input type="hidden" name="package" value="{{ old('package', request('package')) }}" />
        @endif

        @if (! empty($package))
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="100" data-aos-easing="ease-out">
                You selected: <strong>{{ $package->title }}</strong> ({{ $package->currency }} {{ number_format((float) $package->price, 2) }}). After registering you can confirm your subscription.
            </p>
        @endif

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
            <div class="field-wrapper relative">
                <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
                <span class="eye-icon absolute right-[10px] top-1/2 -translate-y-1/2">
                    <i class="fa-regular fa-eye"></i>
                </span>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <!-- Confirm Password -->
        <div data-aos="fade-up" data-aos-duration="1500" data-aos-delay="450" data-aos-easing="ease-out">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="field-wrapper relative">
                <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
                <span class="eye-icon absolute right-[10px] top-1/2 -translate-y-1/2">
                    <i class="fa-regular fa-eye"></i>
                </span>
            </div>
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