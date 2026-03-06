@extends('layouts.app')
@section('title', 'Home | DIY Payroll')
@section('content')
    <main>
        <!-- hero section -->
        <section class="hero-section" style="background-image: url('{{ asset('images/hero-bg-image.png') }}');">
            <div class="container">
                <h2 class="text-[var(--text-color)] text-[30px] md:text-[48px] font-bold leading-tight mb-4">
                    Do It Yourself Payroll
                </h2>
                <p class="text-[var(--text-color)] text-lg md:text-xl font-normal mb-10 max-w-lg">
                    Accurate payroll processing with secure employee access and approvals.
                </p>
                <div class="flex">
                    <a href="#" class="primary-btn">
                        View Pricing
                    </a>
                </div>
            </div>

        </section>
        <!-- Designed for Every Role in Payroll -->
        <section class="py-10 md:py-20">
            <div class="container">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                    <!-- Left Content -->
                    <div>
                        <h2
                            class="text-[var(--text-secondary-color)] text-[32px] md:text-[48px] font-bold leading-tight mb-12 max-w-md">
                            <span class="cs-gradient mr-2"> Designed</span>for Every Role in Payroll
                        </h2>

                        <div class="space-y-6">
                            <!-- For Administrators -->
                            <div class="bg-[#2D5A27] p-6 rounded-[20px]">
                                <h3 class="text-white text-[20px]  md:text-[28px] font-bold mb-3">For Administrators</h3>
                                <p class="text-[var(--text-color)] text-[18px] font-normal leading-relaxed">
                                    Oversee multiple companies, manage payroll workflows, ensure compliance, and maintain
                                    complete audit trails — all from one dashboard.
                                </p>
                            </div>

                            <!-- For Business Owners -->
                            <div class="p-4">
                                <h3 class="payroll-heading">For Business Owners</h3>
                                <p class="payroll-text">
                                    Run payroll with confidence. Review, approve, and finalize payroll quickly without
                                    complexity or manual calculations.
                                </p>
                            </div>

                            <!-- For Employees -->
                            <div class="p-4">
                                <h3 class="payroll-heading">For Employees</h3>
                                <p class="payroll-text">
                                    Securely access payslips, payroll history, and payment details anytime through a simple,
                                    user-friendly portal.
                                </p>
                            </div>
                        </div>

                        <div class="flex">
                            <a href="#" class="primary-btn">
                                View Pricing
                            </a>
                        </div>
                    </div>

                    <!-- Right Image -->
                    <div class="relative">
                        <img src="{{ asset('images/designed-payroll.png') }}" alt="Payroll Management Dashboard"
                            class="w-full h-auto mt-10">
                    </div>

                </div>
            </div>
        </section>

        <!-- step section -->
        <section class="py-20 md:py-24">
            <div class="container">
                <div class="text-center mb-16">
                    <h2 class="text-[var(--text-secondary-color)] text-[32px] md:text-[48px] font-bold leading-tight">
                        Payroll in <span class="cs-gradient">Four</span> Simple Steps
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 relative">
                    <!-- Step 01 -->
                    <div class="text-center flex flex-col items-center">
                        <div class="mb-6">
                            <img src="{{ asset('images/step-icon-01.png') }}" alt="Step 1"
                                class="w-28 h-28 md:w-30 md:h-30 object-contain">
                        </div>
                        <h3 class="payroll-heading text-center mb-3">Add and Manage Employees</h3>
                        <p class="step-text ">
                            Quickly onboard employees, manage roles, and keep payroll information organized in one place.
                        </p>
                    </div>

                    <!-- Step 02 -->
                    <div class="text-center flex flex-col items-center">
                        <div class="mb-6">
                            <img src="{{ asset('images/step-icon-02.png') }}" alt="Step 2"
                                class="w-28 h-28 md:w-30 md:h-30 object-contain">
                        </div>
                        <h3 class="payroll-heading text-center mb-3">Run Payroll for the Pay Period</h3>
                        <p class="step-text">
                            Select the pay period and let the system automatically calculate payroll with accuracy.
                        </p>
                    </div>

                    <!-- Step 03 -->
                    <div class="text-center flex flex-col items-center">
                        <div class="mb-6">
                            <img src="{{ asset('images/step-icon-03.png') }}" alt="Step 3"
                                class="w-28 h-28 md:w-30 md:h-30 object-contain">
                        </div>
                        <h3 class="payroll-heading text-center mb-3">Review and Approve Payroll</h3>
                        <p class="step-text ">
                            Review payroll details, make adjustments if needed, and approve with confidence.
                        </p>
                    </div>

                    <!-- Step 04 -->
                    <div class="text-center flex flex-col items-center">
                        <div class="mb-6">
                            <img src="{{ asset('images/step-icon-04.png') }}" alt="Step 4"
                                class="w-28 h-28 md:w-30 md:h-30 object-contain">
                        </div>
                        <h3 class="payroll-heading text-center mb-3">Get Paid and Generate Payslips</h3>
                        <p class="step-text">
                            Funds are securely deposited via ACH, and employees receive their payslips instantly.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Security and Trust Box -->
        <section class="pb-20">
            <div class="security-box">
                <!-- Built with Security and Compliance -->
                <div class="container">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center mb-32">
                        <!-- Left Image -->
                        <div class="order-2 lg:order-1">
                            <img src="{{ asset('images/first-left-image.png') }}" alt="Security Illustration"
                                class="w-full max-w-xl mx-auto h-auto">
                        </div>

                        <!-- Right Content -->
                        <div class="order-1 lg:order-2">
                            <h2 class="text-[32px] md:text-[48px] font-bold leading-tight mb-6">
                                Built with <span class="text-[#50C300]">Security</span> and Compliance at Its Core
                            </h2>
                            <p class="security-text">
                                Payroll data is highly sensitive — and we treat it that way. Our platform is built using
                                industry best practices to safeguard employee and financial information, ensuring secure
                                access, complete transparency, and full accountability across every payroll action.
                            </p>

                            <ul class="space-y-4 mb-12">
                                <li class="security-list">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="Step 1" class="w-5 h-5">
                                    <span class="span-text">Role-based access control</span>
                                </li>
                                <li class="security-list">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="Step 1" class="w-5 h-5">
                                    <span class="span-text">Encrypted data in transit and at rest</span>
                                </li>
                                <li class="security-list">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="Step 1" class="w-5 h-5">
                                    <span class="span-text">Secure payroll and financial records</span>
                                </li>
                                <li class="security-list">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="Step 1" class="w-5 h-5">
                                    <span class="span-text">Detailed audit logs for all payroll activities</span>
                                </li>
                            </ul>

                            <div class="flex flex-wrap gap-4">
                                <a href="#" class="primary-btn">Learn About Our Security</a>
                                <a href="#"
                                    class="bg-[var(--bg-color)] text-[var(--text-secondary-color)] px-8 py-3 rounded-xl font-semibold hover:bg-gray-100 transition-all">Request
                                    a Demo</a>
                            </div>
                        </div>
                    </div>

                    <!-- Payroll Software You Can Trust -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                        <!-- Left Content -->
                        <div>
                            <h2 class="text-[32px] md:text-[48px] font-bold leading-tight mb-8 max-w-[500px]">
                                <span class="text-[#50C300]">Payroll</span> Software You Can Trust
                            </h2>

                            <ul class="space-y-4 mb-12">
                                <li class="security-list">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="Step 1" class="w-5 h-5">
                                    <span class="span-text">Multi-company management from a single login</span>
                                </li>
                                <li class="security-list">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="Step 1" class="w-5 h-5">
                                    <span class="span-text">Clean, modern, and intuitive interface</span>
                                </li>
                                <li class="security-list">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="Step 1" class="w-5 h-5">
                                    <span class="span-text">Employee self-service access</span>
                                </li>
                                <li class="security-list">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="Step 1" class="w-5 h-5">
                                    <span class="span-text">Designed for accuracy, transparency, and scalability</span>
                                </li>
                            </ul>

                            <div class="flex">
                                <a href="#" class="primary-btn">Request a Demo</a>
                            </div>
                        </div>

                        <!-- Right Image -->
                        <div>
                            <img src="{{ asset('images/secomd-right-image.png') }}" alt="Payroll Dashboard Mockup"
                                class="w-full h-auto">
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- Pricing cards -->
        <section class="py-20 md:py-32">
            <div class="container">
                <!-- Section Header -->
                <div class="text-center mb-20 text-[var(--text-secondary-color)]">
                    <h2 class="text-[32px] md:text-[48px] font-bold leading-tight mb-4">
                        Simple, <br class="hidden md:block"> <span class="cs-gradient">Transparent</span> Pricing
                    </h2>
                    <p class="text-[18px] md:text-[20px] font-normal opacity-70 max-w-2xl mx-auto leading-relaxed">
                        Choose the plan that fits your business. No hidden fees. <br class="hidden md:block"> No long-term
                        contracts.
                    </p>
                </div>

                <!-- Pricing Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                    <!-- DIY Card -->
                    <div class="pricing-card group">
                        <div class="pricing-card-top">
                            <h3 class="pricing-title">Simple Payroll, Direct Deposit</h3>
                            <p class="pricing-subtitle">Best for small businesses</p>
                            <div class="mb-10">
                                <span class="pricing-price-val">$350</span>
                                <span class="pricing-price-unit">/Flat yearly Fee</span>
                            </div>
                            <a href="{{ route('web.login') }}" class="pricing-card-btn text-white">Start Today</a>
                        </div>

                        <div class="pricing-divider"></div>

                        <div class="pricing-card-bottom">
                            <p class="font-bold text-[16px] mb-2 feature-text">Includes:</p>
                            <ul class="space-y-4">
                                <li class="flex items-start space-x-3">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="check" class="check-icon">
                                    <span class="feature-text">No per-employee fees</span>
                                </li>
                                <li class="flex items-start space-x-3">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="check" class="check-icon">
                                    <span class="feature-text">Unlimited runs. Perfect for up to 15 employees</span>
                                </li>
                                <li class="flex items-start space-x-3">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="check" class="check-icon">
                                    <span class="feature-text">Secure ACH direct deposits every payday</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Business Card -->
                    <div class="pricing-card group">
                        <div class="pricing-card-top">
                            <h3 class="pricing-title">Business Payroll</h3>
                            <p class="pricing-subtitle">Best for growing companies</p>
                            <div class="mb-10">
                                <span class="pricing-price-val">$149</span>
                                <span class="pricing-price-unit">/Month</span>
                            </div>
                            <a href="#" class="pricing-card-btn text-white">Request a Demo</a>
                        </div>

                        <div class="pricing-divider"></div>

                        <div class="pricing-card-bottom">
                            <p class="font-bold text-[16px] mb-2 feature-text">Includes everything in DIY Payroll, plus:
                            </p>
                            <ul class="space-y-4">
                                <li class="pricing-feature">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="check" class="check-icon">
                                    <span class="feature-text">Multi-company management</span>
                                </li>
                                <li class="pricing-feature">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="check" class="check-icon">
                                    <span class="feature-text">Advanced payroll approvals</span>
                                </li>
                                <li class="pricing-feature">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="check" class="check-icon">
                                    <span class="feature-text">Payroll reports & exports</span>
                                </li>
                                <li class="pricing-feature">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="check" class="check-icon">
                                    <span class="feature-text">Audit logs for payroll actions</span>
                                </li>
                                <li class="pricing-feature">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="check" class="check-icon">
                                    <span class="feature-text">Priority support</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Enterprise Card -->
                    <div class="pricing-card group">
                        <div class="pricing-card-top">
                            <h3 class="pricing-title">Enterprise / Custom</h3>
                            <p class="pricing-subtitle">For larger organizations</p>
                            <div class="mb-15">
                                <span class="pricing-price-val">Custom</span>
                            </div>
                            <a href="#" class="pricing-card-btn text-white">Contact Sales</a>
                        </div>

                        <div class="pricing-divider"></div>

                        <div class="pricing-card-bottom">
                            <p class="font-bold text-[16px] mb-2 feature-text">Includes:</p>
                            <ul class="space-y-4">
                                <li class="pricing-feature">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="check" class="check-icon">
                                    <span class="feature-text">Customized payroll workflows</span>
                                </li>
                                <li class="pricing-feature">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="check" class="check-icon">
                                    <span class="feature-text">Advanced security & compliance support</span>
                                </li>
                                <li class="pricing-feature">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="check" class="check-icon">
                                    <span class="feature-text">Dedicated account manager</span>
                                </li>
                                <li class="pricing-feature">
                                    <img src="{{ asset('images/check-icon.png') }}" alt="check" class="check-icon">
                                    <span class="feature-text">Custom reporting & integrations</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!--  Ready to Simplify Payroll Management?-->
        <section class="pb-20">
            <div class="mx-[10px]">
                <div class="simple-payroll">
                    <div class="simple-payroll-content">
                        <!-- Left Image -->
                        <div class="flex justify-center order-2 lg:order-1">
                            <img src="{{ asset('images/ready-simplify.png') }}" alt="Ready to Simplify"
                                class="w-full max-w-[500px] h-auto">
                        </div>

                        <!-- Right Content -->
                        <div class="order-1 lg:order-2">
                            <h2
                                class="text-[32px] md:text-[48px] font-bold text-[var(--text-secondary-color)] leading-tight mb-6">
                                Ready to Simplify <br class="hidden md:block"> Payroll Management?
                            </h2>
                            <p class="simple-payroll-text">
                                See how our platform can streamline payroll operations while maintaining security and
                                compliance.
                            </p>
                            <div class="flex flex-wrap gap-4">
                                <a href="#" class="primary-btn">Request a Demo</a>
                                <a href="#"
                                    class="bg-[#D6E6CB] text-[var(--text-secondary-color)] px-8 py-3 rounded-xl font-semibold hover:bg-black/10 transition-all">Contact
                                    Our Team</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
