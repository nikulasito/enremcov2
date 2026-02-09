<x-member-layout :title="'ENREMCO - My Profile'">

    @push('styles')
        <style type="text/tailwindcss">
            .info-label { @apply text-xs font-bold uppercase tracking-wider text-slate-400 mb-1; }
                                                    .info-value { @apply text-base font-semibold text-slate-900; }
                                                    .section-card { @apply bg-white rounded-2xl border border-slate-200 overflow-hidden; }
                                                    .section-header { @apply px-6 py-4 border-b border-slate-100 bg-white; }
                                                </style>
    @endpush

    @php
        $user = auth()->user();
        $fullName = $user->name ?? 'N/A';
        $memberId = $user->employee_ID ?? $user->employees_id ?? $user->employee_id ?? 'N/A';

        $dob = $user->date_of_birth ?? null;
        $tin = $user->tin ?? null;
        $sss = $user->sss_gsis ?? $user->gsis ?? $user->sss ?? null;
        $address = $user->address ?? null;
        $contact = $user->contact_no ?? $user->contact ?? null;
        $email = $user->email ?? null;
        $civilStatus = $user->civil_status ?? null;

        $office = $user->office ?? null;
        $position = $user->position ?? null;
        $salary = $user->salary ?? null;
        $yearJoined = $user->year_joined ?? null;
        $employmentStatus = $user->employment_status ?? null;
        $workLocation = $user->work_location ?? null;

        $status = $user->status ?? 'Active';
        $approvedDate = $user->approved_at ?? $user->email_verified_at ?? $user->created_at;

        $membershipType = $membershipType ?? ($user->membership_type ?? 'Regular');
        $shareCapital = $shareCapital ?? null;
        $nextRenewal = $nextRenewal ?? null;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- LEFT CONTENT --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="flex items-center gap-3">
                <button onclick="window.print()"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl border border-slate-200 font-bold text-slate-900 bg-white hover:bg-slate-50 transition-all text-sm">
                    <span class="material-symbols-outlined text-lg">print</span>
                    Print Record
                </button>

                <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl bg-primary text-background-dark font-black hover:brightness-105 transition-all shadow-md shadow-primary/10 text-sm">
                    <span class="material-symbols-outlined text-lg">edit</span>
                    Edit Details
                </a>
            </div>
            {{-- Personal Details (with actions moved here) --}}
            <section class="bg-white rounded-2xl border border-[#dce5e0] shadow-sm overflow-hidden">
                <div class="p-6 border-b border-[#dce5e0] bg-[#fcfdfc]">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">badge</span>
                            <div>
                                <h3 class="font-black text-lg uppercase tracking-tight text-slate-800">My Profile</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <h4 class="font-black text-sm uppercase tracking-wider text-slate-700 mb-4">Personal Details</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                        <div>
                            <p class="info-label">Full Name</p>
                            <p class="info-value">{{ $fullName }}</p>
                        </div>

                        <div>
                            <p class="info-label">Date of Birth</p>
                            <p class="info-value">{{ $dob ? \Carbon\Carbon::parse($dob)->format('F d, Y') : 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="info-label">Tax Identification Number (TIN)</p>
                            <p class="info-value">{{ $tin ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="info-label">GSIS / SSS Number</p>
                            <p class="info-value">{{ $sss ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="info-label">Residential Address</p>
                            <p class="info-value leading-relaxed">{{ $address ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="info-label">Contact Number</p>
                            <p class="info-value">{{ $contact ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="info-label">Personal Email</p>
                            <p class="info-value">{{ $email ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="info-label">Civil Status</p>
                            <p class="info-value">{{ $civilStatus ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Employment Details --}}
            <section class="bg-white rounded-2xl border border-[#dce5e0] shadow-sm overflow-hidden">
                <div class="p-6 border-b border-[#dce5e0] bg-[#fcfdfc]">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">work</span>
                            <div>
                                <h3 class="font-black text-lg uppercase tracking-tight text-slate-800">Employment
                                    Details</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                        <div>
                            <p class="info-label">Office / Department</p>
                            <p class="info-value">{{ $office ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="info-label">Current Position</p>
                            <p class="info-value">{{ $position ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="info-label">Basic Monthly Salary</p>
                            <p class="info-value">
                                {{ $salary !== null ? '₱ ' . number_format((float) $salary, 2) : 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <p class="info-label">Year Joined</p>
                            <p class="info-value">{{ $yearJoined ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="info-label">Employment Status</p>
                            <p class="info-value">{{ $employmentStatus ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <p class="info-label">Work Location</p>
                            <p class="info-value">{{ $workLocation ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </section>

        </div>

        {{-- RIGHT PANEL --}}
        <div class="lg:col-span-1 space-y-6">
            <section
                class="rounded-2xl bg-background-dark text-white overflow-hidden border border-white/10 card-shadow">
                <div class="p-6 border-b border-white/10 flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">verified_user</span>
                    <h3 class="font-black text-lg uppercase tracking-tight">Membership</h3>
                </div>

                <div class="p-6 space-y-6">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold uppercase tracking-widest text-white/60">Status</p>
                        <span
                            class="px-3 py-1 bg-primary/10 border border-primary text-primary text-[10px] font-black uppercase rounded-full">
                            {{ strtoupper($status) }}
                        </span>
                    </div>

                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-white/60 mb-2">Member ID</p>
                        <p class="text-2xl font-black">{{ $memberId }}</p>
                    </div>

                    <div class="pt-6 border-t border-white/10 space-y-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-white/60 mb-1">Approved Date</p>
                            <p class="font-bold">
                                {{ $approvedDate ? \Carbon\Carbon::parse($approvedDate)->format('F d, Y') : 'N/A' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-white/60 mb-1">Membership Type
                            </p>
                            <p class="font-bold">{{ $membershipType }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-white/60 mb-1">Share Capital</p>
                            <p class="text-primary text-xl font-black">
                                {{ $shareCapital !== null ? '₱ ' . number_format((float) $shareCapital, 2) : 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <div class="rounded-xl bg-white/5 p-4 border border-white/10 text-center">
                        <p class="text-[10px] font-bold text-white/60 uppercase mb-1">Next Renewal</p>
                        <p class="text-sm font-bold">{{ $nextRenewal ?? 'N/A' }}</p>
                    </div>
                </div>
            </section>

            <div class="rounded-2xl bg-primary/5 border border-primary/20 p-6">
                <h4 class="font-bold text-slate-900 mb-2">Notice anything wrong?</h4>
                <p class="text-sm text-slate-500 mb-4">If any information is incorrect or outdated, please contact the
                    Cooperative Secretary.</p>
                <a class="text-sm font-black text-primary hover:underline inline-flex items-center gap-1" href="#">
                    Contact Support
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
            </div>
        </div>

    </div>

</x-member-layout>