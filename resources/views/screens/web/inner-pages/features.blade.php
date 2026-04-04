@extends('layouts.web.master')
@section('title', 'Features')
@section('content')
    <main>
        <!-- Inner Hero Section -->
        <section class="inner-hero">
            <div class="container relative z-10 text-center">
                <h1 class="inner-title">
                    Powerful <span class="cs-gradient">Features</span> for Modern Payroll
                </h1>
                <p class="inner-text">
                    Everything you need to manage your team, stay compliant, and ensure everyone gets paid accurately and on time.
                </p>
            </div>
        </section>

        <!-- Features Grid -->
        <section class="section-spacing">
            <div class="container">
                <div class="text-center mb-24">
                    <h2 class="section-heading">Everything You <span class="cs-gradient">Need</span></h2>
                    <p class="payroll-text mt-4 max-w-3xl mx-auto opacity-75">
                        Our platform is designed to handle the heavy lifting of payroll management, allowing you to focus on what matters most — growing your business.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10 lg:gap-14">
                    <!-- Feature 1 -->
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <img src="{{ asset('images/step-icon-01.png') }}" alt="Employee Management">
                        </div>
                        <h3 class="feature-card-heading">Employee Management</h3>
                        <p class="feature-card-text">
                            Maintain a centralized database of all your employees and contractors. Track roles, payment history, and essential documentation with a single click.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <img src="{{ asset('images/step-icon-02.png') }}" alt="Automated Calculations">
                        </div>
                        <h3 class="feature-card-heading">Automated Calculations</h3>
                        <p class="feature-card-text">
                            No more manual spreadsheets or human errors. Our engine automatically calculates taxes, withholdings, and net pay based on real-time regulations.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <img src="{{ asset('images/step-icon-03.png') }}" alt="Approval Workflows">
                        </div>
                        <h3 class="feature-card-heading">Approval Workflows</h3>
                        <p class="feature-card-text">
                            Set up customizable multi-level approval processes. Ensure that every payroll run is audited and reviewed by the right people before any funds are released.
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <img src="{{ asset('images/step-icon-04.png') }}" alt="Direct Deposit">
                        </div>
                        <h3 class="feature-card-heading">Direct Deposit (ACH)</h3>
                        <p class="feature-card-text">
                            Pay your team directly and securely into their bank accounts. Secure ACH transfers ensure fast, reliable, and completely paperless delivery of funds.
                        </p>
                    </div>

                    <!-- Feature 5 -->
                    <!-- <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <img src="{{ asset('images/check-icon.png') }}" alt="Compliance Reporting">
                        </div>
                        <h3 class="feature-card-heading">Compliance Reporting</h3>
                        <p class="feature-card-text">
                            Generate detailed, ready-to-file reports for tax obligations and internal audits. Stay compliant with local and federal regulations automatically.
                        </p>
                    </div> -->

                    <!-- Feature 6 -->
                    <!-- <div class="feature-card">
                        <div class="feature-icon-wrapper">
                             <img src="{{ asset('images/logo.png') }}" alt="Self-Service Portal">
                        </div>
                        <h3 class="feature-card-heading">Self-Service Portal</h3>
                        <p class="feature-card-text">
                            Empower your employees to access their own payslips, payment history, and tax forms through a secure and mobile-friendly personal dashboard.
                        </p>
                    </div> -->
                </div>
            </div>
        </section>

        <!-- Feature Preview Section -->
        <section class="pb-24 overflow-hidden">
            <div class="mx-4 md:mx-[20px]">
                <div class="security-box">
                    <div class="container">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                            <div>
                                <h2 class="text-[32px] md:text-[48px] font-bold leading-tight mb-8 text-white">
                                    Why Choose <span class="text-[#50C300]">PayVault</span>?
                                </h2>
                                <p class="security-text text-white/80">
                                    We combine ease of use with professional-grade depth. Our tools are built to scale with your business while keeping your sensitive data safe and secure.
                                </p>
                                <ul class="space-y-4 mb-12">
                                    <li class="security-list">
                                        <img src="{{ asset('images/check-icon.png') }}" alt="check" class="w-5 h-5 brightness-0 invert">
                                        <span class="span-text">Real-time data synchronization</span>
                                    </li>
                                    <li class="security-list">
                                        <img src="{{ asset('images/check-icon.png') }}" alt="check" class="w-5 h-5 brightness-0 invert">
                                        <span class="span-text">Advanced security protocols</span>
                                    </li>
                                    <li class="security-list">
                                        <img src="{{ asset('images/check-icon.png') }}" alt="check" class="w-5 h-5 brightness-0 invert">
                                        <span class="span-text">Seamless third-party integrations</span>
                                    </li>
                                </ul>
                                <a href="#" class="primary-btn">Learn More About Security</a>
                            </div>
                            <div class="relative">
                                
                                <img src="{{ asset('images/designed-payroll.png') }}" alt="Feature Preview" class="relative w-full h-auto ">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Ready to Simplify -->
        <section class="pb-24">
            <div class="mx-4 md:mx-[20px]">
                <div class="simple-payroll">
                    <div class="simple-payroll-content">
                        <!-- Left Image -->
                        <div class="flex justify-center order-2 lg:order-1">
                            <img src="{{ asset('images/ready-simplify.png') }}" alt="Ready to Simplify" class="w-full max-w-[500px] h-auto">
                        </div>

                        <!-- Right Content -->
                        <div class="order-1 lg:order-2">
                            <h2 class="section-heading mb-8">
                                Power Up Your <br class="hidden md:block"> Payroll Today
                            </h2>
                            <p class="simple-payroll-text text-lg mb-12">
                                All these powerful features and more are available in one unified, secure platform. See how we can transform your business.
                            </p>
                            <div class="flex flex-wrap gap-6">
                                <a href="#" class="primary-btn ">Explore Plans</a>
                                <a href="#" class="bg-black/5 text-[var(--text-secondary-color)] px-8 py-3 rounded-xl font-bold hover:bg-black/10 transition-all border border-black/5">Request a Demo</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

