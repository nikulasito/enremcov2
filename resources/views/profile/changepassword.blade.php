<x-member-layout :title="'Security Settings'">
    @php
        $u = Auth::user();
        $initials = collect(explode(' ', trim($u->name ?? 'User')))
            ->filter()
            ->map(fn($p) => mb_substr($p, 0, 1))
            ->take(2)
            ->implode('');
        $memberId = $u->employees_id ?? ($u->member_id ?? '—');
        $lastLogin = $u->last_login_at ?? null; // optional if you don't have this column
    @endphp


    <div class="mx-auto">
        <div class="mb-10">
            <h1 class="text-3xl font-black text-[#111814]">Security Settings</h1>
            <p class="mt-2 text-[#638875]">Manage your account security and authentication preferences.</p>
        </div>

        {{-- Success message --}}
        @if (session('status') === 'password-updated')
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-bold text-green-700">
                Password updated successfully.
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-[#dce5e0] shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-[#dce5e0] bg-[#fcfdfc]">
                        <h2 class="text-lg font-black flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">key</span>
                            Change Password
                        </h2>
                    </div>

                    <div class="p-8">
                        <form class="flex flex-col gap-6" method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('PUT')

                            {{-- Current Password --}}
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-bold text-[#111814]" for="current_password">Current
                                    Password</label>
                                <div class="relative">
                                    <span
                                        class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#a0b0a8]">lock</span>
                                    <input id="current_password" name="current_password" type="password"
                                        class="h-12 w-full rounded-xl border-[#dce5e0] bg-[#f6f8f7] pl-12 pr-4 text-base focus:border-primary focus:ring-primary"
                                        placeholder="Enter current password" required />
                                </div>
                                @error('current_password')
                                    <p class="text-sm font-bold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="h-px bg-[#dce5e0] w-full"></div>

                            {{-- New Password --}}
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-bold text-[#111814]" for="password">New Password</label>
                                <div class="relative">
                                    <span
                                        class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#a0b0a8]">lock_reset</span>
                                    <input id="password" name="password" type="password"
                                        class="h-12 w-full rounded-xl border-[#dce5e0] bg-[#f6f8f7] pl-12 pr-4 text-base focus:border-primary focus:ring-primary"
                                        placeholder="Enter new password" required />
                                </div>
                                @error('password')
                                    <p class="text-sm font-bold text-red-600">{{ $message }}</p>
                                @enderror

                                {{-- (Optional UI only) password strength bar --}}
                                <div class="mt-2">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-[10px] font-bold uppercase text-[#a0b0a8]">Password
                                            Strength</span>
                                        <span id="pwStrengthLabel"
                                            class="text-[10px] font-bold uppercase text-[#a0b0a8]">—</span>
                                    </div>

                                    <div class="flex gap-1 h-1">
                                        <div class="pw-bar flex-1 bg-[#dce5e0] rounded-full"></div>
                                        <div class="pw-bar flex-1 bg-[#dce5e0] rounded-full"></div>
                                        <div class="pw-bar flex-1 bg-[#dce5e0] rounded-full"></div>
                                        <div class="pw-bar flex-1 bg-[#dce5e0] rounded-full"></div>
                                    </div>

                                    {{-- Optional: mini requirement indicators --}}
                                    <div class="mt-3 grid grid-cols-2 gap-2 text-[11px]">
                                        <div class="flex items-center gap-2">
                                            <span id="reqLenIcon"
                                                class="material-symbols-outlined text-base text-[#a0b0a8]">radio_button_unchecked</span>
                                            <span class="text-[#638875]">8+ characters</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span id="reqUpperIcon"
                                                class="material-symbols-outlined text-base text-[#a0b0a8]">radio_button_unchecked</span>
                                            <span class="text-[#638875]">Uppercase (A-Z)</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span id="reqNumIcon"
                                                class="material-symbols-outlined text-base text-[#a0b0a8]">radio_button_unchecked</span>
                                            <span class="text-[#638875]">Number (0-9)</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span id="reqSymIcon"
                                                class="material-symbols-outlined text-base text-[#a0b0a8]">radio_button_unchecked</span>
                                            <span class="text-[#638875]">Symbol (!@#…)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Confirm Password --}}
                            <div class="flex flex-col gap-2">
                                <label class="text-sm font-bold text-[#111814]" for="password_confirmation">Confirm New
                                    Password</label>
                                <div class="relative">
                                    <span
                                        class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#a0b0a8]">verified</span>
                                    <input id="password_confirmation" name="password_confirmation" type="password"
                                        class="h-12 w-full rounded-xl border-[#dce5e0] bg-[#f6f8f7] pl-12 pr-4 text-base focus:border-primary focus:ring-primary"
                                        placeholder="Confirm new password" required />
                                </div>
                                @error('password_confirmation')
                                    <p class="text-sm font-bold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <button type="submit"
                                    class="flex h-12 px-8 items-center justify-center rounded-xl bg-primary text-base font-black text-background-dark shadow-lg shadow-primary/20 transition-all hover:brightness-105 active:scale-[0.98]">
                                    Update Password
                                </button>

                                <a href="{{ route('profile') }}"
                                    class="text-sm font-bold text-[#638875] hover:text-background-dark transition-colors px-4">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <div class="bg-[#fcfdfc] rounded-2xl border border-[#dce5e0] p-6">
                    <h4 class="text-sm font-black text-[#111814] uppercase mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-lg">verified_user</span>
                        Password Rules
                    </h4>

                    <ul class="text-sm text-[#638875] space-y-3">
                        <li class="flex gap-2">
                            <span class="material-symbols-outlined text-primary text-base">check_circle</span>
                            <span>Minimum 8 characters long</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="material-symbols-outlined text-primary text-base">check_circle</span>
                            <span>One uppercase letter (A-Z)</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="material-symbols-outlined text-primary text-base">check_circle</span>
                            <span>One number and one symbol</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="material-symbols-outlined text-primary text-base">check_circle</span>
                            <span>Avoid personal information</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-sidebar-green rounded-2xl p-6 text-white relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 size-24 bg-white/5 rounded-full blur-2xl"></div>
                    <h4 class="text-sm font-bold text-primary uppercase mb-2">Pro Tip</h4>
                    <p class="text-sm text-[#dce5e0] leading-relaxed">
                        We recommend updating your password every 90 days to ensure the highest level of security for
                        your cooperative account.
                    </p>

                    <div class="mt-4 flex items-center gap-2 text-xs font-bold text-white/50">
                        <span class="material-symbols-outlined text-sm">schedule</span>
                        Last changed: —
                    </div>
                </div>

                {{-- Mini profile card (optional) --}}
                <div class="bg-white rounded-2xl border border-[#dce5e0] p-6 flex items-center gap-3">
                    <div
                        class="size-10 rounded-full bg-primary/20 flex items-center justify-center text-primary font-black">
                        {{ $initials ?: 'U' }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-black text-[#111814] truncate">{{ $u->name }}</p>
                        <p class="text-[10px] text-primary font-black uppercase truncate">Member ID: {{ $memberId }}</p>
                    </div>
                </div>
            </div>
        </div>

        <footer class="p-10 border-t border-[#dce5e0] mt-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm text-[#a0b0a8]">© {{ now()->format('Y') }} ENREMCO Cooperative. All rights reserved.
                </p>
                <div class="flex gap-6">
                    <a class="text-sm font-bold text-[#638875] hover:text-primary transition-colors" href="#">Support
                        Center</a>
                    <a class="text-sm font-bold text-[#638875] hover:text-primary transition-colors" href="#">Security
                        Policy</a>
                </div>
            </div>
        </footer>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const pw = document.getElementById('password');
                const label = document.getElementById('pwStrengthLabel');
                const hint = document.getElementById('pwStrengthHint');
                const bars = Array.from(document.querySelectorAll('.pw-bar'));

                if (!pw || !label || bars.length === 0) return;

                const colors = {
                    filled: '#19e680',   // primary
                    empty: '#dce5e0',
                    textWeak: '#a0b0a8',
                    textStrong: '#19e680',
                };

                function calcScore(value) {
                    let score = 0;
                    if ((value || '').length >= 8) score++;
                    if (/[a-z]/.test(value) && /[A-Z]/.test(value)) score++;
                    if (/\d/.test(value)) score++;
                    if (/[^A-Za-z0-9]/.test(value)) score++;
                    return score; // 0..4
                }

                function scoreToLabel(score, value) {
                    if (!value) return '—';
                    if (score <= 1) return 'Weak';
                    if (score === 2) return 'Fair';
                    if (score === 3) return 'Good';
                    return 'Strong';
                }

                function render() {
                    const value = pw.value || '';
                    const score = calcScore(value);
                    const text = scoreToLabel(score, value);

                    label.textContent = text;
                    label.style.color = (score >= 3) ? colors.textStrong : colors.textWeak;

                    bars.forEach((bar, idx) => {
                        bar.style.backgroundColor = (idx < score) ? colors.filled : colors.empty;
                    });

                    // Optional: show what’s missing
                    const missing = [];
                    if (value.length < 8) missing.push('8+ chars');
                    if (!(/[a-z]/.test(value) && /[A-Z]/.test(value))) missing.push('upper + lower');
                    if (!/\d/.test(value)) missing.push('number');
                    if (!/[^A-Za-z0-9]/.test(value)) missing.push('symbol');

                    hint.textContent = missing.length
                        ? `Missing: ${missing.join(', ')}`
                        : 'Looks good. Make sure it’s not easy to guess.';
                }

                pw.addEventListener('input', render);
                render();
            });
        </script>
    @endpush


</x-member-layout>