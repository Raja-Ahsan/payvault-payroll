@include('layouts.web.partials.head')
<body class="min-h-screen font-sans antialiased" style="background-color: var(--bg-color);">

    @include('layouts.web.partials.header')

    <main>
        <section class="inner-page-section">
            <div class="form-card">
                <a href="{{ url('/') }}" class="flex justify-center mb-6"
                   data-aos="fade-down"
                   data-aos-duration="1500"
                   data-aos-easing="ease-out">
                    <img src="{{ asset('images/logo.png') }}" alt="Peak Peptides" class="h-10 md:h-12 w-auto object-contain">
                </a>
                {{ $slot }}
            </div>
        </section>
    </main>

    @include('layouts.web.partials.footer')

    <script src="{{ asset('assets/libs/js/animation/aos/aos.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof AOS !== 'undefined') {
                AOS.init({ once: true, duration: 800, easing: 'ease-out' });
            }
        });
    </script>
</body>
</html>
