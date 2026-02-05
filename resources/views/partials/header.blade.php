<header class=" relative top-0 z-50 ">
    <nav class="container mx-auto px-4 md:px-6 py-4 flex items-center justify-between">
        <!-- Logo -->
        <a href="/" class="flex items-center">
            <img src="{{ asset('images/logo.png') }}" alt="DIY Payroll Solutions Logo" class="h-12 w-auto">
        </a>

        <!-- Desktop Navigation -->
        <div class="hidden lg:flex items-center space-x-8">
            <a href="/" class="destop-nav-links">Home</a>
            <a href="{{ route('how-it-work') }}" class="destop-nav-links">How It Works</a>
            <a href="{{ route('features') }}" class="destop-nav-links">Features</a>
            <a href="{{ route('security') }}" class="destop-nav-links">Security</a>
            <a href="{{ route('about') }}" class="destop-nav-links">About</a>
            <a href="{{ route('contact') }}" class="destop-nav-links">Contact</a>
        </div>

        <!-- Auth Buttons -->
        <div class="hidden lg:flex items-center space-x-6">
            <a href="/login" class="text-[var(--text-secondary-color)] font-regular text-[18px] underline decoration-1 underline-offset-4 hover:text-[#348C31] transition-all">Login</a>
            <a href="/register" class="primary-btn">
                Register
            </a>
        </div>

        <!-- Mobile Menu Button -->
        <button id="mobile-menu-button" class="lg:hidden text-gray-700 focus:outline-none p-2 relative w-10 h-10">
            <!-- Hamburger Icon -->
            <svg id="hamburger-icon" class="w-8 h-8 absolute inset-0 m-auto transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <!-- Cross Icon (X) -->
            <svg id="close-icon" class="w-8 h-8 absolute inset-0 m-auto transition-all duration-300 opacity-0 scale-90 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-gray-100 absolute w-full left-0 shadow-xl animate-in slide-in-from-top duration-300">
        <div class="px-6 py-6 flex flex-col space-y-4">
            <a href="/" class="mobile-nav-links">Home</a>
            <a href="{{ route('how-it-work') }}" class="mobile-nav-links">How It Works</a>
            <a href="{{ route('features') }}" class="mobile-nav-links">Features</a>
            <a href="{{ route('security') }}" class="mobile-nav-links">Security</a>
            <a href="{{ route('about') }}" class="mobile-nav-links">About</a>
            <a href="{{ route('contact') }}" class="mobile-nav-links">Contact</a>
            <hr class="border-gray-100">
            <div class="flex flex-col space-y-4 pt-2">
                <a href="/" class="text-[var(--text-secondary-color)] font-semibold text-center py-2 border border-gray-200 rounded-lg">Login</a>
                <a href="/" class="bg-linear-to-r from-[#1D5C24] to-[#348C31] text-white text-center py-3 rounded-lg font-bold shadow-md">
                    Register
                </a>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const hamburgerIcon = document.getElementById('hamburger-icon');
        const closeIcon = document.getElementById('close-icon');

        function toggleMenu() {
            const isHidden = mobileMenu.classList.toggle('hidden');
            
            if (isHidden) {
                // Show Hamburger, Hide X
                hamburgerIcon.classList.remove('opacity-0', 'scale-90', 'rotate-90');
                closeIcon.classList.add('opacity-0', 'scale-90');
                closeIcon.classList.remove('rotate-0');
            } else {
                // Hide Hamburger, Show X
                hamburgerIcon.classList.add('opacity-0', 'scale-90', 'rotate-90');
                closeIcon.classList.remove('opacity-0', 'scale-90');
                closeIcon.classList.add('rotate-0');
            }
        }

        menuButton.addEventListener('click', toggleMenu);

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!menuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                if (!mobileMenu.classList.contains('hidden')) {
                    toggleMenu();
                }
            }
        });
    });
</script>