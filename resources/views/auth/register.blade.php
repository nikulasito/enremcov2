<x-register-layout>
    <main class="flex-1 py-12 lg:py-20 px-6">
        <div class="mx-auto max-w-[1200px]">
            <div class="mb-10 text-center lg:text-left">
                <h1 class="text-3xl lg:text-4xl font-black text-[#111814] dark:text-white">Join the Cooperative</h1>
                <p class="mt-2 text-[#638875] dark:text-[#a0b0a8]">Apply for ENREMCO membership in three easy steps.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
                {{-- Left side --}}
                <aside class="lg:col-span-4 flex flex-col gap-6">
                    <div class="bg-background-dark text-white rounded-2xl p-8 relative overflow-hidden">
                        <div class="absolute top-0 right-0 size-32 bg-primary/10 rounded-full blur-3xl -mr-16 -mt-16">
                        </div>

                        <h3 class="text-xl font-black mb-6 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">military_tech</span>
                            Why Join ENREMCO?
                        </h3>

                        <div class="space-y-6">
                            <div class="flex gap-4">
                                <div
                                    class="size-10 shrink-0 rounded-lg bg-white/5 flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined">trending_up</span>
                                </div>
                                <div>
                                    <p class="font-bold text-sm">High Dividend Rates</p>
                                    <p class="text-xs text-[#a0b0a8] mt-1">Receive annual dividends based on your share
                                        capital contribution.</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div
                                    class="size-10 shrink-0 rounded-lg bg-white/5 flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined">percent</span>
                                </div>
                                <div>
                                    <p class="font-bold text-sm">Low-Interest Loans</p>
                                    <p class="text-xs text-[#a0b0a8] mt-1">Access to multi-purpose and emergency loans
                                        at the lowest rates.</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div
                                    class="size-10 shrink-0 rounded-lg bg-white/5 flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined">volunteer_activism</span>
                                </div>
                                <div>
                                    <p class="font-bold text-sm">Member Assistance</p>
                                    <p class="text-xs text-[#a0b0a8] mt-1">Exclusive access to retirement benefits and
                                        medical aid programs.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-[#1a2e24] rounded-2xl p-8 border border-[#dce5e0] dark:border-[#2a3a32]">
                        <h4 class="text-sm font-black uppercase tracking-widest text-primary mb-4">Requirements</h4>
                        <ul class="space-y-3 text-sm text-[#638875] dark:text-[#a0b0a8]">
                            <li class="flex items-center gap-2"><span
                                    class="material-symbols-outlined text-xs text-primary">check_circle</span> Must be a
                                regular ERC employee</li>
                            <li class="flex items-center gap-2"><span
                                    class="material-symbols-outlined text-xs text-primary">check_circle</span>
                                Attendance to PMES (Orientation)</li>
                            <li class="flex items-center gap-2"><span
                                    class="material-symbols-outlined text-xs text-primary">check_circle</span>
                                Accomplished application form</li>
                        </ul>
                    </div>
                </aside>

                {{-- Right side --}}
                <div class="lg:col-span-8">
                    <form id="registerForm" method="POST" action="{{ route('register') }}" enctype="multipart/form-data"
                        class="bg-white dark:bg-[#1a2e24] rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] overflow-hidden shadow-sm">
                        @csrf

                        <div class="p-8 lg:p-10 space-y-8">
                            {{-- Personal --}}
                            <div>
                                <h2 class="text-xl font-bold flex items-center gap-2 mb-6">
                                    <span class="material-symbols-outlined text-primary">person</span>
                                    Personal Information
                                </h2>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="md:col-span-2">
                                        <label for="name">Full Name (Last, First, Middle)</label>
                                        <input id="name" name="name" value="{{ old('name') }}" required type="text"
                                            placeholder="Dela Cruz, Juan Antonio" />
                                    </div>

                                    <div>
                                        <label for="sex">Gender</label>
                                        <select id="sex" name="sex" required>
                                            <option value="" disabled {{ old('sex') ? '' : 'selected' }}>Select Gender
                                            </option>
                                            <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male
                                            </option>
                                            <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female
                                            </option>
                                            <option value="Other" {{ old('sex') == 'Other' ? 'selected' : '' }}>Other
                                            </option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="marital_status">Civil Status</label>
                                        <select id="marital_status" name="marital_status" required>
                                            <option value="" disabled {{ old('marital_status') ? '' : 'selected' }}>
                                                Select Status</option>
                                            <option value="Single" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>
                                                Single</option>
                                            <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                            <option value="Divorced" {{ old('marital_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                            <option value="Widowed" {{ old('marital_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                        </select>
                                    </div>

                                    {{-- Date UI like design; still submit month/day/year like your backend --}}
                                    <div>
                                        <label for="dob_ui">Birthdate</label>
                                        <input id="dob_ui" type="date" required />
                                        <input type="hidden" id="birth_month" name="birth_month"
                                            value="{{ old('birth_month') }}">
                                        <input type="hidden" id="birth_day" name="birth_day"
                                            value="{{ old('birth_day') }}">
                                        <input type="hidden" id="birth_year" name="birth_year"
                                            value="{{ old('birth_year') }}">
                                    </div>

                                    <div>
                                        <label for="religion">Religion</label>
                                        <input id="religion" name="religion" value="{{ old('religion') }}" required
                                            type="text" />
                                    </div>

                                    <div>
                                        <label for="contact_no">Contact No.</label>
                                        <input id="contact_no" name="contact_no" value="{{ old('contact_no') }}"
                                            required type="text" placeholder="09XXXXXXXXX" />
                                        <p class="mt-1.5 text-[10px] text-[#638875] dark:text-[#a0b0a8]">Enter 11 digits
                                            phone number with no space</p>
                                    </div>

                                    <div>
                                        <label for="email">Email</label>
                                        <input id="email" name="email" value="{{ old('email') }}" required
                                            type="email" />
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="address">Address</label>
                                        <input id="address" name="address" value="{{ old('address') }}" required
                                            type="text" />
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="beneficiaries">Name of Beneficiary/ies</label>
                                        <input id="beneficiaries" name="beneficiaries"
                                            value="{{ old('beneficiaries') }}" required type="text" />
                                    </div>

                                    <div class="md:col-span-2">
                                        <label for="education">Educational Attainment</label>
                                        <input id="education" name="education" value="{{ old('education') }}" required
                                            type="text" />
                                    </div>
                                </div>
                            </div>

                            {{-- Employment --}}
                            <div class="pt-8 border-t border-[#dce5e0] dark:border-[#2a3a32]">
                                <h2 class="text-xl font-bold flex items-center gap-2 mb-6">
                                    <span class="material-symbols-outlined text-primary">work</span>
                                    Employment Details
                                </h2>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="office">Office</label>
                                        <select id="office" name="office" required>
                                            <option value="" disabled {{ old('office') ? '' : 'selected' }}>Select
                                                Office</option>
                                            <option value="Department of Environment and Natural Resources" {{ old('office') == 'Department of Environment and Natural Resources' ? 'selected' : '' }}>Department of Environment and Natural Resources
                                            </option>
                                            <option value="Environmental Management Bureau" {{ old('office') == 'Environmental Management Bureau' ? 'selected' : '' }}>
                                                Environmental Management Bureau</option>
                                            <option value="Mines and Geosciences Bureau" {{ old('office') == 'Mines and Geosciences Bureau' ? 'selected' : '' }}>Mines and Geosciences Bureau
                                            </option>
                                            <option value="PENRO Bukidnon" {{ old('office') == 'PENRO Bukidnon' ? 'selected' : '' }}>PENRO Bukidnon</option>
                                            <option value="PENRO Camiguin" {{ old('office') == 'PENRO Camiguin' ? 'selected' : '' }}>PENRO Camiguin</option>
                                            <option value="PENRO Lanao Del Norte" {{ old('office') == 'PENRO Lanao Del Norte' ? 'selected' : '' }}>PENRO Lanao Del Norte</option>
                                            <option value="PENRO Misamis Occidental" {{ old('office') == 'PENRO Misamis Occidental' ? 'selected' : '' }}>PENRO Misamis Occidental</option>
                                            <option value="PENRO Misamis Oriental" {{ old('office') == 'PENRO Misamis Oriental' ? 'selected' : '' }}>PENRO Misamis Oriental</option>
                                            <option value="CENRO Don Carlos" {{ old('office') == 'CENRO Don Carlos' ? 'selected' : '' }}>CENRO Don Carlos</option>
                                            <option value="CENRO Manolo Fortich" {{ old('office') == 'CENRO Manolo Fortich' ? 'selected' : '' }}>CENRO Manolo Fortich</option>
                                            <option value="CENRO Valencia" {{ old('office') == 'CENRO Valencia' ? 'selected' : '' }}>CENRO Valencia</option>
                                            <option value="CENRO Talakag" {{ old('office') == 'CENRO Talakag' ? 'selected' : '' }}>CENRO Talakag</option>
                                            <option value="CENRO Iligan" {{ old('office') == 'CENRO Iligan' ? 'selected' : '' }}>CENRO Iligan</option>
                                            <option value="CENRO Kolambugan" {{ old('office') == 'CENRO Kolambugan' ? 'selected' : '' }}>CENRO Kolambugan</option>
                                            <option value="CENRO Oroquieta" {{ old('office') == 'CENRO Oroquieta' ? 'selected' : '' }}>CENRO Oroquieta</option>
                                            <option value="CENRO Ozamis" {{ old('office') == 'CENRO Ozamis' ? 'selected' : '' }}>CENRO Ozamis</option>
                                            <option value="CENRO Gingoog" {{ old('office') == 'CENRO Gingoog' ? 'selected' : '' }}>CENRO Gingoog</option>
                                            <option value="CENRO Initao" {{ old('office') == 'CENRO Initao' ? 'selected' : '' }}>CENRO Initao</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label for="position">Position</label>
                                        <input id="position" name="position" value="{{ old('position') }}" required
                                            type="text" />
                                    </div>

                                    <div>
                                        <label for="annual_income">Annual Income</label>
                                        <input id="annual_income" name="annual_income"
                                            value="{{ old('annual_income') }}" required type="number"
                                            placeholder="No comma" />
                                    </div>

                                    <div>
                                        <label for="employment_status">Employment Status</label>
                                        <select id="employment_status" name="employment_status" required>
                                            <option value="" disabled {{ old('employment_status') ? '' : 'selected' }}>
                                                Select Employment Status</option>
                                            <option value="Regular" {{ old('employment_status') == 'Regular' ? 'selected' : '' }}>Regular</option>
                                            <option value="Casual" {{ old('employment_status') == 'Casual' ? 'selected' : '' }}>Casual</option>
                                            <option value="Contract of Service" {{ old('employment_status') == 'Contract of Service' ? 'selected' : '' }}>Contract of Service</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Commitment --}}
                            <div class="pt-8 border-t border-[#dce5e0] dark:border-[#2a3a32]">
                                <h2 class="text-xl font-bold flex items-center gap-2 mb-6">
                                    <span class="material-symbols-outlined text-primary">savings</span>
                                    Monthly Contribution
                                </h2>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="shares">Shares</label>
                                        <input id="shares" name="shares" value="{{ old('shares', 250) }}" required
                                            type="number" />
                                    </div>

                                    <div>
                                        <label for="savings">Savings</label>
                                        <input id="savings" name="savings" value="{{ old('savings', 250) }}" required
                                            type="number" />
                                    </div>
                                </div>
                            </div>

                            {{-- Account --}}
                            <div class="pt-8 border-t border-[#dce5e0] dark:border-[#2a3a32]">
                                <h2 class="text-xl font-bold flex items-center gap-2 mb-6">
                                    <span class="material-symbols-outlined text-primary">lock</span>
                                    Account
                                </h2>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="username">Username</label>
                                        <input id="username" name="username" value="{{ old('username') }}" required
                                            type="text" />
                                    </div>

                                    <div>
                                        <label for="photo">Photo</label>
                                        <input id="photo" name="photo" accept="image/png,image/gif,image/jpeg" required
                                            type="file" />
                                        <p class="mt-1.5 text-[10px] text-[#638875] dark:text-[#a0b0a8]">Photo size: 2MB
                                            Max</p>
                                    </div>

                                    <div>
                                        <label for="password">Password</label>
                                        <input id="password" name="password" required type="password"
                                            autocomplete="new-password" />
                                    </div>

                                    <div>
                                        <label for="password_confirmation">Confirm Password</label>
                                        <input id="password_confirmation" name="password_confirmation" required
                                            type="password" autocomplete="new-password" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-[#f6f8f7] dark:bg-[#112119] p-8 flex flex-col sm:flex-row items-center justify-between gap-6 border-t border-[#dce5e0] dark:border-[#2a3a32]">
                            <div class="flex items-start gap-3">
                                <div class="pt-1">
                                    <input class="rounded border-[#dce5e0] text-primary focus:ring-primary" id="terms"
                                        type="checkbox" required />
                                </div>
                                <label class="normal-case text-xs leading-relaxed tracking-normal mb-0 font-medium"
                                    for="terms">
                                    I hereby certify that the information provided is true and correct. I agree to abide
                                    by the ENREMCO Bylaws and Articles of Cooperation.
                                </label>
                            </div>

                            <button id="registerBtn"
                                class="w-full sm:w-auto min-w-[240px] flex items-center justify-center gap-2 bg-primary text-[#112119] h-14 rounded-xl font-black text-lg hover:brightness-110 transition-all shadow-lg shadow-primary/20"
                                type="submit">
                                Submit Application
                                <span class="material-symbols-outlined">send</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    {{-- Keep your SAME bootstrap modals (verifyEmailModal + errorModal) here --}}
    {{-- (Paste your existing modal markup exactly as-is) --}}

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const registerForm = document.getElementById("registerForm");
            const registerBtn = document.getElementById("registerBtn");

            const dobUi = document.getElementById("dob_ui");
            const birthMonth = document.getElementById("birth_month");
            const birthDay = document.getElementById("birth_day");
            const birthYear = document.getElementById("birth_year");

            const errorModalEl = document.getElementById("errorModal");
            const errorMessageEl = document.getElementById("errorMessage");
            const verifyModalEl = document.getElementById("verifyEmailModal");
            const verifyEmailFrame = document.getElementById("verifyEmailFrame");

            const syncDob = () => {
                if (!dobUi.value) return;
                const [yy, mm, dd] = dobUi.value.split("-");
                birthYear.value = yy;
                birthMonth.value = parseInt(mm, 10);
                birthDay.value = parseInt(dd, 10);
            };

            dobUi.addEventListener("change", syncDob);

            registerForm.addEventListener("submit", function (e) {
                e.preventDefault();
                syncDob();

                // simple required check
                let hasErrors = false;
                registerForm.querySelectorAll("[required]").forEach((el) => {
                    if (el.type === "checkbox" && !el.checked) hasErrors = true;
                    else if (el.type === "file" && (!el.files || el.files.length === 0)) hasErrors = true;
                    else if (!el.value) hasErrors = true;
                });

                if (hasErrors) {
                    errorMessageEl.innerHTML = `<div class="text-danger">Please fill in all required fields.</div>`;
                    new bootstrap.Modal(errorModalEl).show();
                    return;
                }

                const formData = new FormData(registerForm);

                registerBtn.disabled = true;
                registerBtn.innerHTML = `<span class="inline-flex items-center gap-2">
                    <span class="h-4 w-4 rounded-full border-2 border-[#112119] border-t-transparent animate-spin"></span>
                    Processing...
                </span>`;

                fetch("{{ route('register') }}", {
                    method: "POST",
                    headers: {
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                    .then(async (response) => {
                        const data = await response.json().catch(() => ({}));
                        if (!response.ok || !data.success) throw data;

                        const pdfUrl = data.user_id ? `/user/pdf/${data.user_id}` : "#";
                        verifyEmailFrame.srcdoc = `
                        <div style="padding:2rem; font-family:Arial; text-align:center;">
                            <h2>Thank you for registering!</h2>
                            <p>Please check your email for additional instructions.</p>
                            <p><a href="${pdfUrl}" target="_blank">Download Registration PDF</a></p>
                        </div>
                    `;

                        new bootstrap.Modal(verifyModalEl).show();
                        verifyModalEl.addEventListener("hidden.bs.modal", () => registerForm.reset());
                    })
                    .catch((err) => {
                        let html = "Something went wrong.";
                        if (err?.errors) {
                            html = "";
                            for (const field in err.errors) {
                                html += `<div class="text-danger mb-1">• ${err.errors[field][0]}</div>`;
                            }
                        }
                        errorMessageEl.innerHTML = html;
                        new bootstrap.Modal(errorModalEl).show();
                    })
                    .finally(() => {
                        registerBtn.disabled = false;
                        registerBtn.innerHTML = `Submit Application <span class="material-symbols-outlined">send</span>`;
                    });
            });
        });
    </script>
</x-register-layout>