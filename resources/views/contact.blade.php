@extends('layouts.public')

@section('title', 'ENREMCO Contact & Support')

@push('head')
    <style>
        /* Tailwind @apply replacement for inputs/textarea */
        input,
        textarea {
            display: block;
            width: 100%;
            border-radius: .5rem;
            border: 1px solid #dce5e0;
            background: #fff;
            padding: .75rem 1rem;
            font-size: .875rem;
            outline: none;
        }

        input:focus,
        textarea:focus {
            border-color: #19e680;
            box-shadow: 0 0 0 3px rgba(25, 230, 128, .25);
        }

        html.dark input,
        html.dark textarea {
            border-color: #2a3a32;
            background: #1a2e24;
            color: #fff;
        }

        html.dark input:focus,
        html.dark textarea:focus {
            border-color: #19e680;
            box-shadow: 0 0 0 3px rgba(25, 230, 128, .25);
        }
    </style>
@endpush

@section('content')
    <main class="flex-1">

        <section class="bg-background-dark py-12 lg:py-16">
            <div class="mx-auto max-w-[1280px] px-6 lg:px-10">
                <div class="max-w-3xl">
                    <span class="text-primary font-bold text-sm uppercase tracking-widest">Support Center</span>
                    <h1 class="text-4xl font-black leading-tight tracking-tight text-white sm:text-5xl lg:text-6xl mt-4">
                        Contact &amp; Support
                    </h1>
                    <p class="mt-6 text-base font-normal leading-relaxed text-[#dce5e0] lg:text-lg">
                        Have questions about your membership or loan application? Our team is here to help you. Reach out
                        through the form below or visit us at our main office.
                    </p>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-[1280px] px-6 py-16 lg:px-10">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-2">

                <div
                    class="rounded-2xl bg-white p-8 shadow-sm dark:bg-[#1a2e24] border border-[#dce5e0] dark:border-[#2a3a32]">
                    <h2 class="mb-8 text-2xl font-black text-[#111814] dark:text-white">Send us a Message</h2>

                    @if(session('contact_success'))
                        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">
                            {{ session('contact_success') }}
                        </div>
                    @endif

                    @if($errors->has('contact'))
                        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                            {{ $errors->first('contact') }}
                        </div>
                    @endif

                    <form class="flex flex-col gap-6" method="POST" action="{{ route('contact.submit') }}">
                        @csrf
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-bold text-[#111814] dark:text-white" for="name">Full Name</label>
                                <input id="name" name="name" placeholder="Juan Dela Cruz" type="text" value="{{ old('name') }}" />
                                @error('name') <p class="text-xs font-semibold text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-bold text-[#111814] dark:text-white" for="email">Email
                                    Address</label>
                                <input id="email" name="email" placeholder="juan@example.com" type="email" value="{{ old('email') }}" />
                                @error('email') <p class="text-xs font-semibold text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-bold text-[#111814] dark:text-white" for="subject">Subject</label>
                            <input id="subject" name="subject" placeholder="How can we help you?" type="text" value="{{ old('subject') }}" />
                            @error('subject') <p class="text-xs font-semibold text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-bold text-[#111814] dark:text-white" for="message">Message</label>
                            <textarea id="message" name="message" placeholder="Type your message here..." rows="5">{{ old('message') }}</textarea>
                            @error('message') <p class="text-xs font-semibold text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit"
                            class="mt-2 flex w-full items-center justify-center rounded-lg bg-primary h-14 px-8 text-base font-black text-[#112119] shadow-lg shadow-primary/20 hover:brightness-110 transition-all">
                            Send Message
                        </button>
                    </form>
                </div>

                <div class="flex flex-col gap-10">
                    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">

                        <div class="flex flex-col gap-4">
                            <div class="flex size-12 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                <span class="material-symbols-outlined">location_on</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#111814] dark:text-white">Our Office</h3>
                                <p class="mt-1 text-sm text-[#638875] dark:text-[#a0b0a8] leading-relaxed">
                                    Department of Environment and Natural Resources 10<br />
                                    Puntod, Cagayan de Oro City, 9000
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col gap-4">
                            <div class="flex size-12 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                <span class="material-symbols-outlined">call</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#111814] dark:text-white">Contact Numbers</h3>
                                <p class="mt-1 text-sm text-[#638875] dark:text-[#a0b0a8]">
                                    +63 (02) 8689-5372<br />
                                    +63 (02) 8689-5300
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col gap-4">
                            <div class="flex size-12 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                <span class="material-symbols-outlined">mail</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#111814] dark:text-white">Email Address</h3>
                                <p class="mt-1 text-sm text-[#638875] dark:text-[#a0b0a8]">
                                    support@enremco.com
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col gap-4">
                            <div class="flex size-12 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                <span class="material-symbols-outlined">schedule</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#111814] dark:text-white">Office Hours</h3>
                                <p class="mt-1 text-sm text-[#638875] dark:text-[#a0b0a8]">
                                    Monday - Friday<br />
                                    8:00 AM - 5:00 PM
                                </p>
                            </div>
                        </div>

                    </div>

                    <div class="relative h-[340px] w-full overflow-hidden rounded-2xl border dark:border-[#2a3a32]">
                        <!-- <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-6">
                                                <span class="material-symbols-outlined text-4xl text-primary/40 mb-3">map</span>
                                                <p class="font-bold text-[#111814] dark:text-white">Location Map: Pasig City</p>
                                                <p class="text-xs text-[#638875] dark:text-[#a0b0a8] mt-2">
                                                    Energy Regulatory Commission Building, Ortigas Center
                                                </p>
                                            </div> -->
                        <div class="absolute inset-0 opacity-50 pointer-events-none">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2346.318514764379!2d124.65848961397298!3d8.497274794834828!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32fff2f26d193e5d%3A0x672b6c81b032591c!2sDepartment%20of%20Environment%20and%20Natural%20Resources%20-%20Region%20X!5e0!3m2!1sen!2sph!4v1771206705091!5m2!1sen!2sph"
                                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section class="bg-primary/5 py-16 border-y border-[#dce5e0] dark:border-[#2a3a32]">
            <div class="mx-auto max-w-[1280px] px-6 lg:px-10 text-center">
                <h2 class="text-3xl font-black text-[#111814] dark:text-white">Frequently Asked Questions</h2>
                <p class="mt-4 text-[#638875] dark:text-[#a0b0a8]">
                    Find quick answers to common questions about loans, membership, and dividends.
                </p>
                <button
                    class="mt-8 rounded-lg border-2 border-[#112119] px-8 py-3 font-bold text-[#112119] dark:border-white dark:text-white hover:bg-[#112119] hover:text-white dark:hover:bg-white dark:hover:text-[#112119] transition-all">
                    View FAQ
                </button>
            </div>
        </section>

    </main>
@endsection
