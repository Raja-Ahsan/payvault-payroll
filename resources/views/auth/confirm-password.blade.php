<x-guest-layout>
    <h1 class="form-title"
        data-aos="fade-down"
        data-aos-duration="1500"
        data-aos-delay="100"
        data-aos-easing="ease-out">Confirm Password</h1>

    <div class="mb-4 text-sm text-[var(--text-secondary-color)] leading-relaxed style-inter"
         data-aos="fade-up"
         data-aos-duration="1500"
         data-aos-delay="150"
         data-aos-easing="ease-out">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div data-aos="fade-up" data-aos-duration="1500" data-aos-delay="200" data-aos-easing="ease-out">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="flex justify-end mt-4"
             data-aos="fade-up"
             data-aos-duration="1500"
             data-aos-delay="300"
             data-aos-easing="ease-out">
            <x-primary-button>
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
