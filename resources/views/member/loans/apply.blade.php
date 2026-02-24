<x-member-layout title="ENREMCO - Loan Application">
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900">Loan Application Form</h1>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-slate-400 uppercase">Application ID</p>
                    <p class="text-sm font-bold text-slate-700">LA-{{ now()->format('YmdHis') }}</p>
                </div>
                <button
                    class="flex size-10 items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                    <span class="material-symbols-outlined">help_outline</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="w-full">
        @php
            $resolvedLoanType = old('loan_type', $selectedLoanType ?? '');
        @endphp
        <div class="mb-6 flex items-center justify-between">
            <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">description</span>
                Application Details
            </h3>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 card-shadow overflow-hidden">

            <form class="p-8 space-y-10" method="POST" action="{{ route('member.loans.store') }}">
                @csrf

                {{-- Personal Information --}}
                <section class="space-y-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        Personal Information
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700" for="full-name">Full Name</label>
                            <input
                                class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-400 px-4 py-3 font-medium cursor-not-allowed"
                                id="full-name" name="full_name" type="text"
                                value="{{ old('full_name', auth()->user()->name) }}" readonly />

                            @error('full_name') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700" for="member-id">Member ID</label>
                            <input
                                class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-400 px-4 py-3 font-medium cursor-not-allowed"
                                disabled id="member-id" type="text"
                                value="{{ auth()->user()->employee_ID ?? auth()->user()->employees_id ?? auth()->user()->employee_id ?? 'N/A' }}" />
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="text-sm font-bold text-slate-700" for="address">Residential Address</label>
                            <input
                                class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-400 px-4 py-3 font-medium cursor-not-allowed"
                                id="address" name="address" type="text"
                                value="{{ old('address', auth()->user()->address ?? '') }}" readonly />

                            @error('address') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                {{-- Loan Specifications --}}
                <section class="space-y-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        Loan Specifications
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700" for="loan-type">Loan Type</label>
                            <select
                                class="w-full rounded-xl border-slate-200 bg-white text-slate-900 px-4 py-3 font-medium"
                                id="loan-type" name="loan_type">
                                <option value="regular" @selected($resolvedLoanType === 'regular')>Regular Loan</option>
                                <option value="educational" @selected($resolvedLoanType === 'educational')>Educational Loan
                                </option>
                                <option value="appliance" @selected($resolvedLoanType === 'appliance')>Appliance Loan
                                </option>
                                <option value="grocery" @selected($resolvedLoanType === 'grocery')>Grocery Loan</option>
                            </select>
                            @error('loan_type') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700" for="loan-amount">Desired Loan Amount
                                (₱)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">₱</span>
                                <input
                                    class="w-full rounded-xl border-slate-200 bg-white text-slate-900 pl-8 pr-4 py-3 font-black placeholder:text-slate-300"
                                    id="loan-amount" name="loan_amount" placeholder="0.00" type="number" step="0.01"
                                    value="{{ old('loan_amount') }}" />
                            </div>
                            <p id="loan-amount-hint" class="hidden text-xs font-semibold text-slate-500">
                                For Appliance Loan, this amount is auto-computed from your item list total.
                            </p>
                            @error('loan_amount') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>

                {{-- Loan Type Specific Details --}}
                <section class="space-y-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        Loan Type Details
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </h3>

                    <div data-loan-type-group="regular" class="js-loan-type-group hidden rounded-2xl border border-slate-200 bg-slate-50 p-6">
                        <h4 class="text-sm font-black text-slate-800 mb-4">Regular Loan Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-sm font-bold text-slate-700" for="loan-purpose">Loan Purpose</label>
                                <textarea
                                    class="w-full rounded-xl border-slate-200 bg-white text-slate-900 px-4 py-3 font-medium"
                                    id="loan-purpose" name="loan_purpose" rows="3" data-required="1"
                                    placeholder="State the purpose of your regular loan...">{{ old('loan_purpose') }}</textarea>
                                @error('loan_purpose') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div data-loan-type-group="educational" class="js-loan-type-group hidden rounded-2xl border border-slate-200 bg-slate-50 p-6">
                        <h4 class="text-sm font-black text-slate-800 mb-4">Educational Loan Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700" for="beneficiary_name">Beneficiary Name</label>
                                <input
                                    class="w-full rounded-xl border-slate-200 bg-white text-slate-900 px-4 py-3 font-medium"
                                    id="beneficiary_name" name="beneficiary_name" type="text" data-required="1"
                                    value="{{ old('beneficiary_name') }}">
                                @error('beneficiary_name') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700" for="school_name">School Name</label>
                                <input
                                    class="w-full rounded-xl border-slate-200 bg-white text-slate-900 px-4 py-3 font-medium"
                                    id="school_name" name="school_name" type="text" data-required="1"
                                    value="{{ old('school_name') }}">
                                @error('school_name') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700" for="school_program">Program/Course</label>
                                <input
                                    class="w-full rounded-xl border-slate-200 bg-white text-slate-900 px-4 py-3 font-medium"
                                    id="school_program" name="school_program" type="text" data-required="1"
                                    value="{{ old('school_program') }}">
                                @error('school_program') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700" for="school_year">School Year</label>
                                <input
                                    class="w-full rounded-xl border-slate-200 bg-white text-slate-900 px-4 py-3 font-medium"
                                    id="school_year" name="school_year" type="text" data-required="1"
                                    placeholder="e.g. 2026-2027" value="{{ old('school_year') }}">
                                @error('school_year') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700" for="semester">Semester</label>
                                <select
                                    class="w-full rounded-xl border-slate-200 bg-white text-slate-900 px-4 py-3 font-medium"
                                    id="semester" name="semester" data-required="1">
                                    <option value="">Select semester</option>
                                    <option value="1st Semester" @selected(old('semester') === '1st Semester')>1st Semester</option>
                                    <option value="2nd Semester" @selected(old('semester') === '2nd Semester')>2nd Semester</option>
                                    <option value="Summer" @selected(old('semester') === 'Summer')>Summer</option>
                                </select>
                                @error('semester') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div data-loan-type-group="appliance" class="js-loan-type-group hidden rounded-2xl border border-slate-200 bg-slate-50 p-6">
                        <h4 class="text-sm font-black text-slate-800 mb-4">Appliance Loan Details</h4>
                        @php
                            $applianceItemsOld = old('appliance_items');
                            if (!is_array($applianceItemsOld) || count($applianceItemsOld) === 0) {
                                $applianceItemsOld = [['item_name' => '', 'quantity' => 1, 'unit_price' => '']];
                            }
                        @endphp

                        <div class="space-y-3">
                            <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
                                <table class="w-full text-left text-sm">
                                    <thead class="bg-slate-50 border-b border-slate-200">
                                        <tr>
                                            <th class="px-4 py-3 font-black text-slate-600">Item</th>
                                            <th class="px-4 py-3 font-black text-slate-600 w-28">Qty</th>
                                            <th class="px-4 py-3 font-black text-slate-600 w-36">Unit Price</th>
                                            <th class="px-4 py-3 font-black text-slate-600 w-36 text-right">Amount</th>
                                            <th class="px-4 py-3 font-black text-slate-600 w-24 text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="appliance-items-container" class="divide-y divide-slate-100">
                                        @foreach($applianceItemsOld as $idx => $row)
                                            <tr data-item-row>
                                                <td class="px-4 py-3">
                                                    <input
                                                        class="w-full rounded-lg border-slate-200 bg-white text-slate-900 px-3 py-2 text-sm font-medium"
                                                        type="text"
                                                        name="appliance_items[{{ $idx }}][item_name]"
                                                        value="{{ $row['item_name'] ?? '' }}"
                                                        data-required="1"
                                                        placeholder="e.g. Refrigerator">
                                                </td>
                                                <td class="px-4 py-3">
                                                    <input
                                                        class="w-full rounded-lg border-slate-200 bg-white text-slate-900 px-3 py-2 text-sm font-medium"
                                                        type="number"
                                                        name="appliance_items[{{ $idx }}][quantity]"
                                                        min="1"
                                                        step="1"
                                                        value="{{ $row['quantity'] ?? 1 }}"
                                                        data-required="1">
                                                </td>
                                                <td class="px-4 py-3">
                                                    <input
                                                        class="w-full rounded-lg border-slate-200 bg-white text-slate-900 px-3 py-2 text-sm font-medium"
                                                        type="number"
                                                        name="appliance_items[{{ $idx }}][unit_price]"
                                                        min="0"
                                                        step="0.01"
                                                        value="{{ $row['unit_price'] ?? '' }}"
                                                        data-required="1">
                                                </td>
                                                <td class="px-4 py-3 text-right font-black text-slate-800">
                                                    <span data-item-amount>0.00</span>
                                                </td>
                                                <td class="px-4 py-3 text-right">
                                                    <button type="button"
                                                        class="js-remove-appliance-item px-2.5 py-1.5 text-xs font-black rounded-lg border border-red-200 text-red-600 hover:bg-red-50">
                                                        Remove
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="flex items-center justify-between gap-3">
                                <button type="button" id="add-appliance-item"
                                    class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 text-xs font-black hover:bg-slate-100 transition-colors">
                                    + Add Another Item
                                </button>
                                <div class="text-sm font-black text-slate-700">
                                    Total Amount:
                                    <span class="text-primary">&#8369;<span id="appliance-items-total-display">0.00</span></span>
                                </div>
                            </div>

                            <input type="hidden" id="appliance_total_amount" name="appliance_total_amount" value="{{ old('appliance_total_amount') }}">

                            @error('appliance_items') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                            @error('appliance_items.0.item_name') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                            @error('appliance_items.0.quantity') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                            @error('appliance_items.0.unit_price') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <template id="appliance-item-row-template">
                            <tr data-item-row>
                                <td class="px-4 py-3">
                                    <input
                                        class="w-full rounded-lg border-slate-200 bg-white text-slate-900 px-3 py-2 text-sm font-medium"
                                        type="text"
                                        name="appliance_items[__INDEX__][item_name]"
                                        data-required="1"
                                        placeholder="e.g. Refrigerator">
                                </td>
                                <td class="px-4 py-3">
                                    <input
                                        class="w-full rounded-lg border-slate-200 bg-white text-slate-900 px-3 py-2 text-sm font-medium"
                                        type="number"
                                        name="appliance_items[__INDEX__][quantity]"
                                        min="1"
                                        step="1"
                                        value="1"
                                        data-required="1">
                                </td>
                                <td class="px-4 py-3">
                                    <input
                                        class="w-full rounded-lg border-slate-200 bg-white text-slate-900 px-3 py-2 text-sm font-medium"
                                        type="number"
                                        name="appliance_items[__INDEX__][unit_price]"
                                        min="0"
                                        step="0.01"
                                        value=""
                                        data-required="1">
                                </td>
                                <td class="px-4 py-3 text-right font-black text-slate-800">
                                    <span data-item-amount>0.00</span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <button type="button"
                                        class="js-remove-appliance-item px-2.5 py-1.5 text-xs font-black rounded-lg border border-red-200 text-red-600 hover:bg-red-50">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        </template>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700" for="appliance_store">Store / Supplier</label>
                                <input
                                    class="w-full rounded-xl border-slate-200 bg-white text-slate-900 px-4 py-3 font-medium"
                                    id="appliance_store" name="appliance_store" type="text" data-required="1"
                                    value="{{ old('appliance_store') }}">
                                @error('appliance_store') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700" for="appliance_downpayment">Downpayment (₱)</label>
                                <input
                                    class="w-full rounded-xl border-slate-200 bg-white text-slate-900 px-4 py-3 font-medium"
                                    id="appliance_downpayment" name="appliance_downpayment" type="number" step="0.01" min="0"
                                    value="{{ old('appliance_downpayment') }}">
                                @error('appliance_downpayment') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700" for="appliance_warranty_months">Warranty (months)</label>
                                <input
                                    class="w-full rounded-xl border-slate-200 bg-white text-slate-900 px-4 py-3 font-medium"
                                    id="appliance_warranty_months" name="appliance_warranty_months" type="number" min="0"
                                    value="{{ old('appliance_warranty_months') }}">
                                @error('appliance_warranty_months') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div data-loan-type-group="grocery" class="js-loan-type-group hidden rounded-2xl border border-slate-200 bg-slate-50 p-6">
                        <h4 class="text-sm font-black text-slate-800 mb-4">Grocery Loan Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700" for="grocery_partner_store">Preferred Store / Partner</label>
                                <input
                                    class="w-full rounded-xl border-slate-200 bg-white text-slate-900 px-4 py-3 font-medium"
                                    id="grocery_partner_store" name="grocery_partner_store" type="text" data-required="1"
                                    value="{{ old('grocery_partner_store') }}">
                                @error('grocery_partner_store') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700" for="household_size">Household Size</label>
                                <input
                                    class="w-full rounded-xl border-slate-200 bg-white text-slate-900 px-4 py-3 font-medium"
                                    id="household_size" name="household_size" type="number" min="1" data-required="1"
                                    value="{{ old('household_size') }}">
                                @error('household_size') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700" for="grocery_period_from">Coverage Start Date</label>
                                <input
                                    class="w-full rounded-xl border-slate-200 bg-white text-slate-900 px-4 py-3 font-medium"
                                    id="grocery_period_from" name="grocery_period_from" type="date" data-required="1"
                                    value="{{ old('grocery_period_from') }}">
                                @error('grocery_period_from') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700" for="grocery_period_to">Coverage End Date</label>
                                <input
                                    class="w-full rounded-xl border-slate-200 bg-white text-slate-900 px-4 py-3 font-medium"
                                    id="grocery_period_to" name="grocery_period_to" type="date" data-required="1"
                                    value="{{ old('grocery_period_to') }}">
                                @error('grocery_period_to') <p class="text-xs text-red-600 font-semibold">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Co-makers --}}
                <section class="space-y-6">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        Co-maker Requirements
                        <div class="flex-1 h-px bg-slate-100"></div>
                    </h3>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                        {{-- Co-maker 1 --}}
                        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 space-y-4">
                            <div class="flex items-center gap-3 mb-2">
                                <div
                                    class="size-8 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-xs">
                                    01</div>
                                <h4 class="font-bold text-slate-800">Co-maker 1</h4>
                            </div>

                            <div class="space-y-2 relative">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider"
                                    for="cm1-name">Full Name</label>

                                <input
                                    class="w-full rounded-lg border-slate-200 bg-white text-slate-900 px-3 py-2 text-sm font-medium"
                                    id="cm1-name" name="comaker1_name" type="text" autocomplete="off"
                                    value="{{ old('comaker1_name') }}" />

                                {{-- hidden selected co-maker user id --}}
                                <input type="hidden" id="cm1-user-id" name="comaker1_user_id"
                                    value="{{ old('comaker1_user_id') }}">

                                {{-- suggestion dropdown --}}
                                <div id="cm1-suggestions"
                                    class="hidden absolute z-30 mt-1 w-full rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
                                </div>
                                <p id="cm1-limit-msg" class="mt-1 text-xs font-semibold text-red-600 hidden"></p>
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider"
                                    for="cm1-position">Position</label>
                                <input
                                    class="w-full rounded-lg border-slate-200 bg-white text-slate-900 px-3 py-2 text-sm font-medium"
                                    id="cm1-position" name="comaker1_position" type="text"
                                    value="{{ old('comaker1_position') }}" readonly />
                            </div>
                        </div>

                        {{-- Co-maker 2 --}}
                        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 space-y-4">
                            <div class="flex items-center gap-3 mb-2">
                                <div
                                    class="size-8 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-xs">
                                    02</div>
                                <h4 class="font-bold text-slate-800">Co-maker 2</h4>
                            </div>

                            <div class="space-y-2 relative">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider"
                                    for="cm2-name">Full Name</label>

                                <input
                                    class="w-full rounded-lg border-slate-200 bg-white text-slate-900 px-3 py-2 text-sm font-medium"
                                    id="cm2-name" name="comaker2_name" type="text" autocomplete="off"
                                    value="{{ old('comaker2_name') }}" />

                                <input type="hidden" id="cm2-user-id" name="comaker2_user_id"
                                    value="{{ old('comaker2_user_id') }}">

                                <div id="cm2-suggestions"
                                    class="hidden absolute z-30 mt-1 w-full rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
                                </div>
                                <p id="cm2-limit-msg" class="mt-1 text-xs font-semibold text-red-600 hidden"></p>
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider"
                                    for="cm2-position">Position</label>
                                <input
                                    class="w-full rounded-lg border-slate-200 bg-white text-slate-900 px-3 py-2 text-sm font-medium"
                                    id="cm2-position" name="comaker2_position" type="text"
                                    value="{{ old('comaker2_position') }}" readonly />
                            </div>
                        </div>

                    </div>

                </section>

                <div class="pt-8 flex flex-col items-center gap-6 border-t border-slate-100">
                    <button
                        class="w-full max-w-md py-4 rounded-xl bg-primary text-background-dark font-black text-base transition-all hover:brightness-105 active:scale-[0.98] shadow-lg shadow-primary/20 flex items-center justify-center gap-2"
                        type="submit">
                        <span class="material-symbols-outlined">send</span>
                        Submit Loan Application
                    </button>

                    <a class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors underline underline-offset-4 decoration-2"
                        href="{{ route('dashboard') }}">
                        Cancel and return to dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if(session('loan_submitted'))
    @php($m = session('loan_submitted'))
    <div id="successModal" class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 px-4">
        <div class="w-full max-w-lg rounded-2xl bg-white shadow-xl overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-full bg-green-100 text-green-700 flex items-center justify-center">
                        <span class="material-symbols-outlined">check_circle</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-900">Application Submitted!</h3>
                        <p class="text-sm text-slate-500">Your loan application has been sent to Admin for review.</p>
                    </div>
                </div>
                <button type="button" id="closeSuccessModal" class="text-slate-400 hover:text-slate-700">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Application Reference</p>
                    <p class="text-lg font-black text-slate-900">{{ $m['application_no'] }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Loan Type</p>
                        <p class="text-sm font-bold text-slate-900">{{ ucwords($m['loan_type']) }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 p-4">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Amount</p>
                        <p class="text-sm font-black text-slate-900">₱{{ number_format($m['loan_amount'], 2) }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('dashboard') }}"
                        class="px-5 py-3 rounded-xl bg-primary text-background-dark font-black text-sm hover:brightness-105 transition-all">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    @push('scripts')
        <script>
            (function () {
                const loanTypeEl = document.getElementById('loan-type');
                const typeGroups = document.querySelectorAll('.js-loan-type-group');
                const loanAmountEl = document.getElementById('loan-amount');
                const loanAmountHint = document.getElementById('loan-amount-hint');
                if (!loanTypeEl || !typeGroups.length) return;

                function syncTypeGroups() {
                    const selectedType = (loanTypeEl.value || 'regular').toLowerCase();

                    typeGroups.forEach((group) => {
                        const isActive = (group.dataset.loanTypeGroup || '').toLowerCase() === selectedType;
                        group.classList.toggle('hidden', !isActive);

                        group.querySelectorAll('input, select, textarea').forEach((field) => {
                            field.disabled = !isActive;
                            const mustRequire = isActive && field.dataset.required === '1';
                            if (mustRequire) {
                                field.setAttribute('required', 'required');
                            } else {
                                field.removeAttribute('required');
                            }
                        });
                    });

                    // Appliance total drives loan amount
                    const applianceMode = selectedType === 'appliance';
                    if (loanAmountEl) {
                        loanAmountEl.readOnly = applianceMode;
                        loanAmountEl.classList.toggle('bg-slate-50', applianceMode);
                        loanAmountEl.classList.toggle('cursor-not-allowed', applianceMode);
                    }
                    if (loanAmountHint) {
                        loanAmountHint.classList.toggle('hidden', !applianceMode);
                    }

                    document.dispatchEvent(new CustomEvent('loan-type-changed', {
                        detail: { type: selectedType }
                    }));
                }

                loanTypeEl.addEventListener('change', syncTypeGroups);
                syncTypeGroups();
            })();
        </script>

        <script>
            (function () {
                const loanTypeEl = document.getElementById('loan-type');
                const loanAmountEl = document.getElementById('loan-amount');
                const container = document.getElementById('appliance-items-container');
                const addBtn = document.getElementById('add-appliance-item');
                const tpl = document.getElementById('appliance-item-row-template');
                const totalEl = document.getElementById('appliance-items-total-display');
                const totalHidden = document.getElementById('appliance_total_amount');

                if (!loanTypeEl || !loanAmountEl || !container || !addBtn || !tpl || !totalEl || !totalHidden) {
                    return;
                }

                const parseNumber = (v) => {
                    const n = parseFloat(v);
                    return Number.isFinite(n) ? n : 0;
                };
                const fmt = (v) => parseNumber(v).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                function rowAmount(row) {
                    const qty = parseNumber(row.querySelector('input[name*=\"[quantity]\"]')?.value);
                    const unit = parseNumber(row.querySelector('input[name*=\"[unit_price]\"]')?.value);
                    return Math.max(0, qty) * Math.max(0, unit);
                }

                function reindexRows() {
                    [...container.querySelectorAll('[data-item-row]')].forEach((row, idx) => {
                        row.querySelectorAll('input[name^=\"appliance_items[\"]').forEach((input) => {
                            input.name = input.name.replace(/appliance_items\\[\\d+\\]/, `appliance_items[${idx}]`);
                        });
                    });
                }

                function recalcApplianceTotal() {
                    let total = 0;
                    [...container.querySelectorAll('[data-item-row]')].forEach((row) => {
                        const amt = rowAmount(row);
                        total += amt;
                        const amountEl = row.querySelector('[data-item-amount]');
                        if (amountEl) amountEl.textContent = fmt(amt);
                    });

                    totalEl.textContent = fmt(total);
                    totalHidden.value = total.toFixed(2);

                    if ((loanTypeEl.value || '').toLowerCase() === 'appliance') {
                        loanAmountEl.value = total > 0 ? total.toFixed(2) : '';
                    }
                }

                function addRow() {
                    const index = container.querySelectorAll('[data-item-row]').length;
                    const html = tpl.innerHTML.replaceAll('__INDEX__', String(index));
                    container.insertAdjacentHTML('beforeend', html);
                    reindexRows();
                    recalcApplianceTotal();
                }

                addBtn.addEventListener('click', addRow);

                container.addEventListener('click', (e) => {
                    const btn = e.target.closest('.js-remove-appliance-item');
                    if (!btn) return;

                    const rows = container.querySelectorAll('[data-item-row]');
                    if (rows.length <= 1) return;
                    btn.closest('[data-item-row]')?.remove();
                    reindexRows();
                    recalcApplianceTotal();
                });

                container.addEventListener('input', (e) => {
                    if (!e.target.matches('input')) return;
                    recalcApplianceTotal();
                });

                document.addEventListener('loan-type-changed', () => {
                    recalcApplianceTotal();
                });

                reindexRows();
                recalcApplianceTotal();
            })();
        </script>

        <script>
            (function () {
                const endpoint = "{{ route('member.loans.comakers.search') }}";

                function debounce(fn, delay = 250) {
                    let t;
                    return (...args) => {
                        clearTimeout(t);
                        t = setTimeout(() => fn(...args), delay);
                    };
                }

                function renderSuggestions(box, items, onPick, onBlocked) {
                    if (!items.length) {
                        box.innerHTML = `<div class="px-3 py-2 text-xs text-slate-500">No matches found</div>`;
                        box.classList.remove('hidden');
                        return;
                    }

                    box.innerHTML = items.map((item) => {
                        const locked = !!item.limit_reached;
                        const badge = locked
                            ? `<span class="text-[10px] font-black px-2 py-0.5 rounded-full bg-red-50 text-red-600 border border-red-100">
                                    Limit reached
                               </span>`
                            : `<span class="text-xs font-bold text-slate-400">Select</span>`;

                        return `
                            <button type="button"
                                data-locked="${locked ? '1' : '0'}"
                                class="w-full text-left px-3 py-2 hover:bg-slate-50 flex items-center justify-between gap-3 ${locked ? 'opacity-60' : ''}">
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-slate-800 truncate">${escapeHtml(item.name)}</div>
                                    <div class="text-xs text-slate-500 truncate">
                                        ${escapeHtml(item.position || '—')}
                                        ${locked ? ` • ${Number(item.co_maker_count || 0)}/3 loans` : ''}
                                    </div>
                                </div>
                                ${badge}
                            </button>
                        `;
                    }).join('');

                    [...box.querySelectorAll('button')].forEach((btn, idx) => {
                        btn.addEventListener('click', () => {
                            const picked = items[idx];
                            if (picked.limit_reached) {
                                onBlocked?.(picked);
                                return;
                            }
                            onPick(picked);
                        });
                    });

                    box.classList.remove('hidden');
                }


                function hideSuggestions(box) {
                    box.classList.add('hidden');
                    box.innerHTML = '';
                }

                function escapeHtml(str) {
                    return String(str ?? '')
                        .replaceAll('&', '&amp;')
                        .replaceAll('<', '&lt;')
                        .replaceAll('>', '&gt;')
                        .replaceAll('"', '&quot;')
                        .replaceAll("'", '&#039;');
                }

                function wireCoMaker({ nameInputId, posInputId, userIdInputId, boxId, limitMsgId }) {
                    const nameEl = document.getElementById(nameInputId);
                    const posEl = document.getElementById(posInputId);
                    const userIdEl = document.getElementById(userIdInputId);
                    const boxEl = document.getElementById(boxId);
                    const msgEl = document.getElementById(limitMsgId);

                    if (!nameEl || !posEl || !userIdEl || !boxEl) return;

                    // inline warning text under the input
                    let warnEl = nameEl.parentElement.querySelector('.cm-warn');
                    if (!warnEl) {
                        warnEl = document.createElement('p');
                        warnEl.className = 'cm-warn mt-2 text-xs font-semibold text-red-600 hidden';
                        nameEl.parentElement.appendChild(warnEl);
                    }

                    function showWarn(msg) {
                        warnEl.textContent = msg;
                        warnEl.classList.remove('hidden');
                    }

                    function hideWarn() {
                        warnEl.textContent = '';
                        warnEl.classList.add('hidden');
                    }


                    const clearLimit = () => {
                        if (!msgEl) return;
                        msgEl.textContent = '';
                        msgEl.classList.add('hidden');
                    };

                    const showLimit = (text) => {
                        if (!msgEl) return;
                        msgEl.textContent = text;
                        msgEl.classList.remove('hidden');
                    };

                    let abortCtrl = null;

                    const doSearch = debounce(async () => {
                        clearLimit();

                        const q = nameEl.value.trim();

                        userIdEl.value = '';
                        posEl.value = '';

                        if (q.length < 2) {
                            hideSuggestions(boxEl);
                            return;
                        }

                        if (abortCtrl) abortCtrl.abort();
                        abortCtrl = new AbortController();

                        hideWarn();

                        try {
                            const res = await fetch(`${endpoint}?q=${encodeURIComponent(q)}`, {
                                headers: { 'Accept': 'application/json' },
                                signal: abortCtrl.signal
                            });

                            if (!res.ok) throw new Error('Search failed');

                            const items = await res.json();
                            const qLower = q.toLowerCase();
                            const exactLocked = Array.isArray(items)
                                ? items.find((it) => String(it.name || '').toLowerCase() === qLower && it.limit_reached)
                                : null;
                            if (exactLocked) {
                                showWarn('This co-maker already reached the 3-loan limit. Please select another co-maker.');
                            } else {
                                hideWarn();
                            }

                            renderSuggestions(
                                boxEl,
                                items,
                                (picked) => {
                                    nameEl.value = picked.name;
                                    posEl.value = picked.position || '';
                                    userIdEl.value = picked.id;
                                    hideWarn();
                                    hideSuggestions(boxEl);
                                },
                                (picked) => {
                                    // ✅ blocked selection message
                                    showWarn('Limit reached, please select another co-maker.');
                                }
                            );

                        } catch (e) {
                            if (e.name !== 'AbortError') hideSuggestions(boxEl);
                        }
                    }, 250);

                    nameEl.addEventListener('input', doSearch);

                    document.addEventListener('click', (e) => {
                        if (!boxEl.contains(e.target) && e.target !== nameEl) hideSuggestions(boxEl);
                    });

                    nameEl.addEventListener('focus', () => {
                        if (nameEl.value.trim().length >= 2) doSearch();
                    });
                }

                wireCoMaker({
                    nameInputId: 'cm1-name',
                    posInputId: 'cm1-position',
                    userIdInputId: 'cm1-user-id',
                    boxId: 'cm1-suggestions',
                    limitMsgId: 'cm1-limit-msg'
                });

                wireCoMaker({
                    nameInputId: 'cm2-name',
                    posInputId: 'cm2-position',
                    userIdInputId: 'cm2-user-id',
                    boxId: 'cm2-suggestions',
                    limitMsgId: 'cm2-limit-msg'
                });
            })();
        </script>


        <script>
            // ✅ Modal close script (safe even if modal doesn't exist)
            (function () {
                const modal = document.getElementById('successModal');
                const btn = document.getElementById('closeSuccessModal');
                if (!modal) return;

                function close() { modal.remove(); }

                btn?.addEventListener('click', close);

                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') close();
                });
            })();
        </script>
    @endpush

</x-member-layout>

