<x-guest-layout>
    <h1 class="form-title"
        data-aos="fade-down"
        data-aos-duration="1500"
        data-aos-delay="100"
        data-aos-easing="ease-out">Verify Email</h1>

    <div class="mb-4 text-sm text-[var(--text-secondary-color)] leading-relaxed style-inter"
         data-aos="fade-up"
         data-aos-duration="1500"
         data-aos-delay="150"
         data-aos-easing="ease-out">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 auth-flash-success style-inter"
             data-aos="fade-up"
             data-aos-duration="1500"
             data-aos-delay="200"
             data-aos-easing="ease-out">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4"
         data-aos="fade-up"
         data-aos-duration="1500"
         data-aos-delay="250"
         data-aos-easing="ease-out">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                {{ __('Resend Verification Email') }}
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="form-link">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
