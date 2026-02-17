@extends('layouts.app')
@section('title', 'Contact Us')
@section('content')
    <main>
        <!-- Inner Hero Section -->
        <section class="inner-hero">
            <div class="container relative z-10 text-center">
                <h1 class="inner-title">
                    Get in <span class="cs-gradient">Touch</span>
                </h1>
                <p class="inner-text">
                    Have questions about PayVault? Our team is here to help you find the perfect payroll solution for your business.
                </p>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="section-spacing">
            <div class="container">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
                    <!-- Contact Info -->
                    <div class="lg:col-span-5 space-y-8">
                        <div>
                            <h2 class="section-heading mb-6">Let's <span class="cs-gradient">Talk</span></h2>
                            <p class="payroll-text mb-10">
                                Whether you're a small startup or a large enterprise, we're ready to show you how PayVault can transform your payroll experience.
                            </p>
                        </div>

                        <div class="contact-info-card">
                            <div class="contact-icon-box">
                                <svg class="w-6 h-6 text-[#1D5C24]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="contact-label">Email us at</span>
                                <a href="mailto:hello@payvault.com" class="contact-value hover:text-green-700 transition-colors">hello@payvault.com</a>
                            </div>
                        </div>

                        <div class="contact-info-card">
                            <div class="contact-icon-box">
                                <svg class="w-6 h-6 text-[#1D5C24]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="contact-label">Call us at</span>
                                <a href="tel:+1234567890" class="contact-value hover:text-green-700 transition-colors">+1 (234) 567-890</a>
                            </div>
                        </div>

                        <div class="contact-info-card">
                            <div class="contact-icon-box">
                                <svg class="w-6 h-6 text-[#1D5C24]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <span class="contact-label">Visit us</span>
                                <span class="contact-value">123 Tech Avenue, <br> Silicon Valley, CA 94025</span>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="lg:col-span-7">
                        <div class="contact-form-box">
                            <form action="#" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="form-group">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-input" placeholder="Enter your name" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Work Email</label>
                                        <input type="email" class="form-input" placeholder="Enter your email" required>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="form-group">
                                        <label class="form-label">Company Name</label>
                                        <input type="text" class="form-input" placeholder="Enter your company name">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Phone Number</label>
                                        <input type="tel" class="form-input" placeholder="Enter your phone number">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Subject</label>
                                    <select class="form-input appearance-none bg-[url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22currentColor%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E')] bg-[position:right_1.25rem_center] bg-[length:1.25rem_1.25rem] bg-no-repeat">
                                        <option>Select an option</option>
                                        <option>General Inquiry</option>
                                        <option>Sales & Pricing</option>
                                        <option>Technical Support</option>
                                        <option>Partnership</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Message</label>
                                    <textarea class="form-textarea" placeholder="Enter your message" required></textarea>
                                </div>
                                <button type="submit" class="primary-btn w-full py-4 text-lg">Send Message</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="pb-24">
            <div class="container">
                <div class="security-box text-center">
                    <h2 class="text-[32px] md:text-[48px] font-bold leading-tight mb-8 text-white">
                        Ready to <span class="text-[#50C300]">Simplify</span> Your Payroll?
                    </h2>
                    <p class="security-text mx-auto text-white/80 mb-12">
                        Get started with PayVault today and see why companies trust us with their payroll management.
                    </p>
                    <div class="flex flex-wrap justify-center gap-6">
                        <a href="#" class="primary-btn">Create Account</a>
                        <a href="#" class="bg-white/10 text-white px-8 py-3 rounded-xl font-bold hover:bg-white/20 transition-all border border-white/20">Learn More</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
