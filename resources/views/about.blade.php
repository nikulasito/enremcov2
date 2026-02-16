@extends('layouts.public')

@section('title', 'ENREMCO - About Us')

@section('content')
    <main class="flex-1">
        {{-- ✅ Keep all your sections exactly as-is (your original <main> content) --}}

            <section class="bg-background-dark py-12 lg:py-16">
                <div class="mx-auto max-w-[1280px] px-6 lg:px-10">
                    <div class="max-w-2xl">
                        <span class="text-primary font-bold text-sm uppercase tracking-[0.2em] mb-4 block">Our Story
                            &amp; Mission</span>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight mb-6">
                            Building a Shared Future <span class="text-primary">Together</span>
                            <p class="mt-6 text-base font-normal leading-relaxed text-[#dce5e0] lg:text-lg">
                                Dedicated to enhancing the quality of life of our members through sustainable financial
                                services and cooperative empowerment.
                            </p>
                    </div>
                </div>
            </section>

            <section class="bg-white dark:bg-[#0d1a14] py-16 lg:py-24">
                <div class="mx-auto max-w-[1280px] px-6 lg:px-10">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                        <div class="flex flex-col gap-6">
                            <span class="text-primary font-bold text-sm uppercase tracking-widest">About
                                ENREMCO</span>
                            <h2 class="text-3xl lg:text-4xl font-black text-[#111814] dark:text-white leading-tight">
                                ENVIRONMENT AND NATURAL RESOURCES MULTI-PURPOSE CREDIT COOPERATIVE</h2>
                            <p class="text-[#638875] dark:text-[#a0b0a8] text-lg leading-relaxed">
                                Founded on the principles of mutual aid and self-reliance, ENREMCO serves as a
                                cornerstone for the financial well-being of ERC employees. For nearly three
                                decades, we have evolved from a small collective into a robust financial
                                institution owned and managed by its members.
                            </p>
                            <p class="text-[#638875] dark:text-[#a0b0a8] text-lg leading-relaxed">
                                Our primary goal is to provide accessible credit, promote thrift and savings,
                                and ensure that every member has a voice in the growth of our shared cooperative
                                community.
                            </p>
                            <div class="grid grid-cols-2 gap-8 mt-4">
                                <div>
                                    <h4 class="text-3xl font-black text-primary">1995</h4>
                                    <p
                                        class="text-sm font-bold text-[#111814] dark:text-white uppercase tracking-wider mt-1">
                                        Established</p>
                                </div>
                                <div>
                                    <h4 class="text-3xl font-black text-primary">500+</h4>
                                    <p
                                        class="text-sm font-bold text-[#111814] dark:text-white uppercase tracking-wider mt-1">
                                        Active Members</p>
                                </div>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="aspect-square rounded-2xl overflow-hidden shadow-2xl">
                                <img alt="Cooperative Team" class="w-full h-full object-cover"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuBE2km1h-1TWE6qTz5hFtV11BhNvzXL5t57ifH0S_AlyjVYipGsHzYCyWgWlNFYlvhctUVVStPVnkIQkm0UIciUvcPWZe-er45C31LAjU90SzZb9zIkv60EGCP1LtdzeF4Fw1JxXIIFd7pBilUZ7LamsWZGF50D_zJhFUIofG6dM1gdojjR7TNO8yQAyOMfYTWB8XZdNW6CT1tzeuTwtRBVWbzBzX_eFa5vYv6l6pxePb2vuEAV1Y9H0Yn4GNcjizYgS_JrAGDxC1f8" />
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="bg-[#f6f8f7] dark:bg-background-dark py-20">
                <div class="mx-auto max-w-[1280px] px-6 lg:px-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div
                            class="bg-white dark:bg-[#1a2e24] p-10 rounded-3xl border border-[#dce5e0] dark:border-[#2a3a32] shadow-sm">
                            <div class="size-16 rounded-2xl bg-primary/10 flex items-center justify-center mb-8">
                                <span class="material-symbols-outlined text-primary text-3xl">visibility</span>
                            </div>
                            <h3 class="text-2xl font-black text-[#111814] dark:text-white mb-4">Our Vision</h3>
                            <p class="text-[#638875] dark:text-[#a0b0a8] text-lg leading-relaxed italic">
                                "To be the leading cooperative that empowers every ERC employee through
                                innovative financial solutions and sustainable wealth creation by 2030."
                            </p>
                        </div>
                        <div
                            class="bg-white dark:bg-[#1a2e24] p-10 rounded-3xl border border-[#dce5e0] dark:border-[#2a3a32] shadow-sm">
                            <div class="size-16 rounded-2xl bg-primary/10 flex items-center justify-center mb-8">
                                <span class="material-symbols-outlined text-primary text-3xl">flag</span>
                            </div>
                            <h3 class="text-2xl font-black text-[#111814] dark:text-white mb-4">Our Mission</h3>
                            <p class="text-[#638875] dark:text-[#a0b0a8] text-lg leading-relaxed italic">
                                "To provide professional and high-quality financial services, promote a culture
                                of savings, and foster cooperative values among our members for their holistic
                                development."
                            </p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="py-20 bg-white dark:bg-[#0d1a14]">
                <div class="mx-auto max-w-[1280px] px-6 lg:px-10">
                    <div class="text-center mb-16">
                        <span class="text-primary font-bold text-sm uppercase tracking-widest">The ENREMCO
                            Way</span>
                        <h2 class="text-3xl lg:text-4xl font-black text-[#111814] dark:text-white mt-2">Our Core
                            Values</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div
                            class="p-8 rounded-2xl bg-[#f6f8f7] dark:bg-background-dark border border-[#dce5e0] dark:border-[#2a3a32] text-center hover:border-primary transition-colors">
                            <h4 class="text-xl font-bold mb-3 text-primary">Integrity</h4>
                            <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">Upholding the highest ethical
                                standards in all our financial dealings and governance.</p>
                        </div>
                        <div
                            class="p-8 rounded-2xl bg-[#f6f8f7] dark:bg-background-dark border border-[#dce5e0] dark:border-[#2a3a32] text-center hover:border-primary transition-colors">
                            <h4 class="text-xl font-bold mb-3 text-primary">Excellence</h4>
                            <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">Striving for superior quality
                                in service delivery and operational efficiency.</p>
                        </div>
                        <div
                            class="p-8 rounded-2xl bg-[#f6f8f7] dark:bg-background-dark border border-[#dce5e0] dark:border-[#2a3a32] text-center hover:border-primary transition-colors">
                            <h4 class="text-xl font-bold mb-3 text-primary">Transparency</h4>
                            <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">Ensuring open communication
                                and accountability to our member-owners.</p>
                        </div>
                        <div
                            class="p-8 rounded-2xl bg-[#f6f8f7] dark:bg-background-dark border border-[#dce5e0] dark:border-[#2a3a32] text-center hover:border-primary transition-colors">
                            <h4 class="text-xl font-bold mb-3 text-primary">Service</h4>
                            <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">Prioritizing the needs and
                                well-being of our members above all else.</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="py-20 bg-[#f6f8f7] dark:bg-background-dark">
                <div class="mx-auto max-w-[1280px] px-6 lg:px-10">
                    <div class="mb-12">
                        <h2 class="text-3xl lg:text-4xl font-black text-[#111814] dark:text-white">Our Board of
                            Directors</h2>
                        <p class="text-[#638875] dark:text-[#a0b0a8] mt-2">The dedicated leaders guiding
                            ENREMCO's strategic direction.</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div class="group">
                            <div
                                class="aspect-[3/4] rounded-2xl bg-[#dce5e0] dark:bg-[#1a2e24] overflow-hidden relative mb-4">
                                <div class="absolute inset-0 flex items-center justify-center opacity-30">
                                    <span class="material-symbols-outlined text-6xl">person</span>
                                </div>
                            </div>
                            <h4 class="text-lg font-bold text-[#111814] dark:text-white">Atty. Ricardo M. Dela
                                Cruz</h4>
                            <p class="text-primary font-medium text-sm">Chairperson</p>
                        </div>
                        <div class="group">
                            <div
                                class="aspect-[3/4] rounded-2xl bg-[#dce5e0] dark:bg-[#1a2e24] overflow-hidden relative mb-4">
                                <div class="absolute inset-0 flex items-center justify-center opacity-30">
                                    <span class="material-symbols-outlined text-6xl">person</span>
                                </div>
                            </div>
                            <h4 class="text-lg font-bold text-[#111814] dark:text-white">Engr. Maria Santos</h4>
                            <p class="text-primary font-medium text-sm">Vice Chairperson</p>
                        </div>
                        <div class="group">
                            <div
                                class="aspect-[3/4] rounded-2xl bg-[#dce5e0] dark:bg-[#1a2e24] overflow-hidden relative mb-4">
                                <div class="absolute inset-0 flex items-center justify-center opacity-30">
                                    <span class="material-symbols-outlined text-6xl">person</span>
                                </div>
                            </div>
                            <h4 class="text-lg font-bold text-[#111814] dark:text-white">Ms. Elena Reyes</h4>
                            <p class="text-primary font-medium text-sm">Treasurer</p>
                        </div>
                        <div class="group">
                            <div
                                class="aspect-[3/4] rounded-2xl bg-[#dce5e0] dark:bg-[#1a2e24] overflow-hidden relative mb-4">
                                <div class="absolute inset-0 flex items-center justify-center opacity-30">
                                    <span class="material-symbols-outlined text-6xl">person</span>
                                </div>
                            </div>
                            <h4 class="text-lg font-bold text-[#111814] dark:text-white">Mr. Juanito Gonzales
                            </h4>
                            <p class="text-primary font-medium text-sm">Board Member</p>
                        </div>
                    </div>
                </div>
            </section>
            <!-- <section class="py-20 bg-white dark:bg-[#0d1a14]">
                            <div class="mx-auto max-w-[1280px] px-6 lg:px-10">
                                <div class="flex flex-col lg:flex-row gap-16">
                                    <div class="lg:w-1/3">
                                        <h2 class="text-3xl lg:text-4xl font-black text-[#111814] dark:text-white leading-tight">
                                            Guided by International Cooperative Principles</h2>
                                        <p class="mt-4 text-[#638875] dark:text-[#a0b0a8]">We strictly adhere to the seven
                                            universal principles that define the cooperative movement worldwide.</p>
                                        <div class="mt-8 size-24 bg-primary/10 rounded-full flex items-center justify-center">
                                            <img alt="COOP" class="size-16 rounded-full grayscale mix-blend-multiply opacity-50"
                                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuBUBOylhAcZvDjqzx9bdU2SH8Nykxec0Xzbngfjih1qr7Yo5WvH9NFJAl8PmcnbKOTNmX2LQzXKTkjWGJTRVbnQBWhZkjwUoEN9DrvjS5B3NYelyzhp2m1TxoN9jotQ5YE-HLvu5OiM3Z7njxAMJj4Whze3UELdIgHwSqwM_l4qjZngm415ju051sgDjwef01OEwg3DQbO9baQ-mxxIWLhghJ0_VquOgM9pWhlU11t-25cYsbqHKiPdxqKCKiAbVMtlxxLu3AB9wEO9" />
                                        </div>
                                    </div>
                                    <div class="lg:w-2/3 grid grid-cols-1 md:grid-cols-2 gap-y-10 gap-x-12">
                                        <div class="flex gap-4">
                                            <span class="text-primary font-black text-2xl">01</span>
                                            <div>
                                                <h5 class="font-bold text-[#111814] dark:text-white">Voluntary and Open
                                                    Membership</h5>
                                                <p class="text-sm text-[#638875] dark:text-[#a0b0a8] mt-1">Open to all
                                                    persons able to use services and willing to accept responsibilities.</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-4">
                                            <span class="text-primary font-black text-2xl">02</span>
                                            <div>
                                                <h5 class="font-bold text-[#111814] dark:text-white">Democratic Member
                                                    Control</h5>
                                                <p class="text-sm text-[#638875] dark:text-[#a0b0a8] mt-1">Controlled by
                                                    members who actively participate in setting policies and decisions.</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-4">
                                            <span class="text-primary font-black text-2xl">03</span>
                                            <div>
                                                <h5 class="font-bold text-[#111814] dark:text-white">Member Economic
                                                    Participation</h5>
                                                <p class="text-sm text-[#638875] dark:text-[#a0b0a8] mt-1">Members
                                                    contribute equitably to, and democratically control, the capital of
                                                    their coop.</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-4">
                                            <span class="text-primary font-black text-2xl">04</span>
                                            <div>
                                                <h5 class="font-bold text-[#111814] dark:text-white">Autonomy and
                                                    Independence</h5>
                                                <p class="text-sm text-[#638875] dark:text-[#a0b0a8] mt-1">Autonomous,
                                                    self-help organizations controlled by their members.</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-4">
                                            <span class="text-primary font-black text-2xl">05</span>
                                            <div>
                                                <h5 class="font-bold text-[#111814] dark:text-white">Education, Training and
                                                    Info</h5>
                                                <p class="text-sm text-[#638875] dark:text-[#a0b0a8] mt-1">Providing
                                                    education so members can contribute effectively to development.</p>
                                            </div>
                                        </div>
                                        <div class="flex gap-4">
                                            <span class="text-primary font-black text-2xl">06</span>
                                            <div>
                                                <h5 class="font-bold text-[#111814] dark:text-white">Concern for Community
                                                </h5>
                                                <p class="text-sm text-[#638875] dark:text-[#a0b0a8] mt-1">Working for the
                                                    sustainable development of their communities.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section> -->

        </main>
@endsection