<footer class="bg-[#0B270B] pt-16 pb-4 rounded-[20px]">
    <div class="container mx-auto px-4 md:px-6">
        <!-- Footer Logo -->
        <div class="flex justify-center mb-16">
            <a href="/">
                <img src="{{ asset('images/footer-logo.png') }}" alt="DIY Payroll Solutions Logo" class="h-16 w-auto">
            </a>
        </div>

        <!-- Footer Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
            <!-- Product -->
            <div>
                <span class="footer-title">Product</span>
                <ul class="space-y-2">
                    <li><a href="/how-it-work" class="footer-link">How It Works</a></li>
                    <li><a href="/features" class="footer-link">Features</a></li>
                    <li><a href="/security" class="footer-link">Security & Compliance</a></li>
                    <li><a href="#" class="footer-link">Employee Portal</a></li>
                </ul>
            </div>

            <!-- Company -->
            <div>
                <span class="footer-title">Company</span>
                <ul class="space-y-2">
                    <li><a href="/about" class="footer-link">About Us</a></li>
                    <li><a href="/contact" class="footer-link">Contact</a></li>
                    <li><a href="#" class="footer-link">Request a Demo</a></li>
                    <li><a href="/login" class="footer-link">Login</a></li>
                </ul>
            </div>

            <!-- Legal -->
            <div>
                <span class="footer-title">Legal</span>
                <ul class="space-y-2">
                    <li><a href="#" class="footer-link">Privacy Policy</a></li>
                    <li><a href="#" class="footer-link">Terms of Service</a></li>
                    <li><a href="#" class="footer-link">Data Protection & Security</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <span class="footer-title">Contact</span>
                <div class="flex items-center space-x-3">
                    <div class="">
                        <img src="{{ asset('images/footer-mail-icon.png') }}" alt="Mail" class="w-10 h-10">
                    </div>
                    <a href="mailto:info@yourcompany.com" class="text-white font-medium hover:text-[#348C31] transition-colors">
                        info@yourcompany.com
                    </a>
                </div>
            </div>
        </div>
          <!-- Bottom Bar -->
    
    </div>
    <div class="bg-linear-to-b from-[#42771E] to-[#035003] py-4 w-full">
   
            <p class="text-[var(--text-color)] text-[20PX] font-normal text-center">
                &copy; {{ date('Y') }} DIY Payroll Solutions . All rights reserved.
            </p>
    
    </div>

  
</footer>