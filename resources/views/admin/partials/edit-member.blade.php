<form method="POST" id="updateMemberForm" action="{{ route('admin.update-member', $member->id) }}" class="min-h-full">
    @csrf
    @method('PATCH')

    <div class="p-8 space-y-8">
        {{-- Top identity --}}
        <div class="flex items-start gap-5">
            <div class="shrink-0">
                @php
                    $photo = $member->photo ? asset('storage/' . $member->photo) : null;
                @endphp

                @if($photo)
                    <img src="{{ $photo }}"
                        class="h-20 w-20 rounded-full object-cover border border-[#dce5e0] dark:border-[#2a3a32]" />
                @else
                    <div class="h-20 w-20 rounded-full bg-[#f6f8f7] dark:bg-black/20 border border-[#dce5e0] dark:border-[#2a3a32]
                                    flex items-center justify-center text-[#638875] dark:text-[#a0b0a8] font-black">
                        {{ strtoupper(substr($member->name ?? 'U', 0, 1)) }}
                    </div>
                @endif
            </div>

            <div class="flex-1">
                <p class="text-sm font-black text-[#638875] dark:text-[#a0b0a8]">Member</p>
                <h4 class="text-lg font-black">{{ $member->name ?? '—' }}</h4>
                <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">
                    {{ $member->employee_ID ?? '—' }} • {{ $member->office ?? '—' }}
                </p>
            </div>
        </div>

        {{-- READ-ONLY DETAILS (NO INPUTS) --}}
        <div class="space-y-4">
            <h5 class="text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                Member Details (Read-only)
            </h5>

            <div class="grid grid-cols-1 gap-x-10 gap-y-6 md:grid-cols-2">
                {{-- Left --}}
                <div class="space-y-5">
                    @php
                        $box = "mt-1 rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] bg-[#f6f8f7] dark:bg-black/20 px-4 py-2.5 text-sm";
                        $lbl = "text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]";
                    @endphp

                    <div>
                        <div class="{{ $lbl }}">Employee ID</div>
                        <div class="{{ $box }}">{{ $member->employee_ID ?? '—' }}</div>
                    </div>

                    <div>
                        <div class="{{ $lbl }}">Email</div>
                        <div class="{{ $box }}">{{ $member->email ?? '—' }}</div>
                    </div>

                    <div>
                        <div class="{{ $lbl }}">Position</div>
                        <div class="{{ $box }}">{{ $member->position ?? '—' }}</div>
                    </div>

                    <div>
                        <div class="{{ $lbl }}">Address</div>
                        <div class="{{ $box }}">{{ $member->address ?? '—' }}</div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="{{ $lbl }}">Sex</div>
                            <div class="{{ $box }}">{{ $member->sex ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="{{ $lbl }}">Marital Status</div>
                            <div class="{{ $box }}">{{ $member->marital_status ?? '—' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Right --}}
                <div class="space-y-5">
                    <div>
                        <div class="{{ $lbl }}">Office</div>
                        <div class="{{ $box }}">{{ $member->office ?? '—' }}</div>
                    </div>

                    <div>
                        <div class="{{ $lbl }}">Contact No.</div>
                        <div class="{{ $box }}">{{ $member->contact_no ?? '—' }}</div>
                    </div>

                    <div>
                        <div class="{{ $lbl }}">Annual Income</div>
                        <div class="{{ $box }}">{{ $member->annual_income ?? '—' }}</div>
                    </div>

                    <div>
                        <div class="{{ $lbl }}">Beneficiary/ies</div>
                        <div class="{{ $box }}">{{ $member->beneficiaries ?? '—' }}</div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="{{ $lbl }}">Birthdate</div>
                            <div class="{{ $box }}">
                                {{ $member->birthdate ? \Carbon\Carbon::parse($member->birthdate)->format('M d, Y') : '—' }}
                            </div>
                        </div>
                        <div>
                            <div class="{{ $lbl }}">Education</div>
                            <div class="{{ $box }}">{{ $member->education ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- EDITABLE FIELDS ONLY --}}
        <div class="space-y-4 pt-6 border-t border-[#dce5e0] dark:border-[#2a3a32]">
            <h5 class="text-xs font-black uppercase tracking-wider text-[#638875] dark:text-[#a0b0a8]">
                Editable Fields
            </h5>

            <div class="grid grid-cols-1 gap-x-10 gap-y-6 md:grid-cols-2">
                <div>
                    <label class="{{ $lbl }}">Shares</label>
                    <input type="number" name="shares" step="any" min="0"
                        class="w-full rounded-lg border-[#dce5e0] bg-white py-2.5 px-4 text-sm focus:border-primary focus:ring-primary dark:border-[#2a3a32] dark:bg-[#112119] dark:text-white"
                        value="{{ old('shares', $member->shares) }}">
                </div>

                <div>
                    <label class="{{ $lbl }}">Savings</label>
                    <input type="number" name="savings" step="any" min="0"
                        class="w-full rounded-lg border-[#dce5e0] bg-white py-2.5 px-4 text-sm focus:border-primary focus:ring-primary dark:border-[#2a3a32] dark:bg-[#112119] dark:text-white"
                        value="{{ old('savings', $member->savings) }}">
                </div>

                <div>
                    <label class="{{ $lbl }}">Date of Membership</label>
                    <input type="date" name="membership_date"
                        class="w-full rounded-lg border-[#dce5e0] bg-white py-2.5 px-4 text-sm focus:border-primary focus:ring-primary dark:border-[#2a3a32] dark:bg-[#112119] dark:text-white"
                        value="{{ old('membership_date', $member->membership_date ? \Carbon\Carbon::parse($member->membership_date)->format('Y-m-d') : '') }}">
                </div>

                <div>
                    <label class="{{ $lbl }}">Status</label>
                    <select name="status"
                        class="w-full rounded-lg border-[#dce5e0] bg-white py-2.5 px-4 text-sm focus:border-primary focus:ring-primary dark:border-[#2a3a32] dark:bg-[#112119] dark:text-white">
                        <option value="Active" {{ old('status', $member->status) === 'Active' ? 'selected' : '' }}>Active
                        </option>
                        <option value="Inactive" {{ old('status', $member->status) === 'Inactive' ? 'selected' : '' }}>
                            Inactive</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- Sticky footer (always visible) --}}
    <div class="sticky bottom-0 flex items-center justify-end gap-4 border-t border-[#dce5e0] bg-[#f6f8f7] px-8 py-5
                dark:border-[#2a3a32] dark:bg-sidebar-dark/30">
        <button type="button"
            class="rounded-lg px-6 py-2.5 text-sm font-black text-gray-600 transition-colors hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-white/5"
            data-modal-close="updateMemberModal">
            Cancel
        </button>

        <button type="submit"
            class="rounded-lg bg-primary px-8 py-2.5 text-sm font-black text-[#112119] transition-all hover:brightness-110">
            Save Changes
        </button>
    </div>
</form>