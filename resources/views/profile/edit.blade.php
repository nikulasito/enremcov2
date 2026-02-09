@extends('layouts.member-dashboard')

@section('title', 'ENREMCO - Edit Profile')

@push('styles')
    <style type="text/tailwindcss">
        .section-card { @apply bg-white rounded-2xl border border-slate-200 overflow-hidden card-shadow; }
          .section-header { @apply px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-white; }
          .section-title { @apply font-black text-lg uppercase tracking-tight text-slate-800 flex items-center gap-2; }

          .field-label { @apply text-xs font-bold uppercase tracking-wider text-slate-400; }
          .field-help { @apply text-xs text-slate-400 mt-1; }

          .input-base {
            @apply mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3
            text-sm font-semibold text-slate-900 placeholder-slate-400
            focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary;
          }
          .select-base {
            @apply mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3
            text-sm font-semibold text-slate-900
            focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary;
          }
          .error-text { @apply mt-2 text-xs font-bold text-red-600; }

          .btn-primary {
            @apply inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-background-dark
            font-black hover:brightness-105 transition-all shadow-md shadow-primary/10;
          }
          .btn-secondary {
            @apply inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 bg-white
            font-bold text-slate-900 hover:bg-slate-50 transition-all;
          }
        </style>
@endpush

@php
    $user = $user ?? auth()->user();

    $memberId = $user->employee_ID ?? $user->employees_id ?? $user->employee_id ?? 'N/A';

    // Keep your existing select handling
    $officeCurrent = session()->hasOldInput() ? old('office') : old('office', $user->office);
    $sexCurrent = session()->hasOldInput() ? old('sex') : old('sex', $user->sex);
    $msCurrent = session()->hasOldInput() ? old('marital_status') : old('marital_status', $user->marital_status);

    $norm = fn($v) => trim(mb_strtolower((string) $v));

    $birthVal = session()->hasOldInput()
        ? old('birthdate')
        : ($user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->format('Y-m-d') : '');
@endphp

@section('header')
    <header class="bg-white border-b border-slate-200 px-10 py-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900">Edit Profile</h1>
                <div class="mt-2 flex items-center gap-2 text-slate-500">
                    <span class="text-sm font-medium uppercase tracking-wider">Member ID:</span>
                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-900 font-bold text-sm">{{ $memberId }}</span>
                </div>
                <p class="mt-3 text-sm text-slate-500">Update your account’s profile information.</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('member.profile') }}" class="btn-secondary">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                    Back to Profile
                </a>

                {{-- Submit button placed in header but submits the form below --}}
                <button type="submit" form="profileUpdateForm" class="btn-primary">
                    <span class="material-symbols-outlined text-lg">save</span>
                    Save Changes
                </button>
            </div>
        </div>
    </header>
@endsection

@section('content')
    {{-- Verification resend form (kept from your current page) --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- Breadcrumb --}}
    <!-- <div class="mb-6">
            <nav aria-label="Breadcrumb" class="flex">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a class="text-sm font-medium text-slate-500 hover:text-primary transition-colors"
                            href="{{ route('dashboard') }}">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-base text-slate-300">chevron_right</span>
                            <a class="ml-1 text-sm font-medium text-slate-500 hover:text-primary transition-colors"
                                href="{{ route('member.profile') }}">
                                My Profile
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-base text-slate-300">chevron_right</span>
                            <span class="ml-1 text-sm font-bold text-slate-900">Edit</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div> -->

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- LEFT: FORM --}}
        <div class="lg:col-span-2 space-y-8">

            @if (session('status') === 'profile-updated')
                <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-sm font-bold text-green-700">
                    Saved successfully.
                </div>
            @endif

            {{-- ACCOUNT DETAILS --}}
            <section class="section-card">
                <div class="section-header">
                    <div class="section-title">
                        <span class="material-symbols-outlined text-primary">account_circle</span>
                        Account Details
                    </div>
                </div>

                <div class="p-6">
                    {{-- optional email verification notice --}}
                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4">
                            <p class="text-sm font-bold text-amber-800">
                                Your email address is unverified.
                            </p>
                            <button type="submit" form="send-verification"
                                class="mt-2 text-sm font-black text-amber-800 hover:underline">
                                Click here to re-send the verification email.
                            </button>
                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 text-xs font-bold text-green-700">
                                    A new verification link has been sent to your email address.
                                </p>
                            @endif
                        </div>
                    @endif

                    <form id="profileUpdateForm" method="post" action="{{ route('profile.update') }}"
                        enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Name --}}
                            <div>
                                <label class="field-label" for="name">Name</label>
                                <input id="name" name="name" type="text" class="input-base"
                                    value="{{ old('name', $user->name) }}" autocomplete="name" />
                                @foreach($errors->get('name') as $msg)
                                    <p class="error-text">{{ $msg }}</p>
                                @endforeach
                            </div>

                            {{-- Email --}}
                            <div>
                                <label class="field-label" for="email">Email</label>
                                <input id="email" name="email" type="email" class="input-base"
                                    value="{{ old('email', $user->email) }}" autocomplete="username" />
                                @foreach($errors->get('email') as $msg)
                                    <p class="error-text">{{ $msg }}</p>
                                @endforeach
                            </div>

                            {{-- Profile Picture --}}
                            <div class="md:col-span-2">
                                <label class="field-label" for="photo">Profile Picture</label>

                                <div class="mt-3 flex items-center gap-4">
                                    <div
                                        class="size-16 rounded-full overflow-hidden border border-slate-200 bg-slate-100 flex items-center justify-center">
                                        @if ($user->photo)
                                            <img src="{{ asset('storage/' . $user->photo) }}" alt="Profile Photo"
                                                class="w-full h-full object-cover">
                                        @else
                                            <span class="material-symbols-outlined text-slate-400">person</span>
                                        @endif
                                    </div>

                                    <div class="flex-1">
                                        <input id="photo" name="photo" type="file" accept="image/png, image/gif, image/jpeg"
                                            class="block w-full text-sm file:mr-4 file:rounded-xl file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:font-bold file:text-slate-700 hover:file:bg-slate-200" />
                                        <p class="field-help">PNG, JPG, or GIF. Recommended square image.</p>

                                        @foreach($errors->get('photo') as $msg)
                                            <p class="error-text">{{ $msg }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- PERSONAL DETAILS --}}
                        <section class="section-card">
                            <div class="section-header">
                                <div class="section-title">
                                    <span class="material-symbols-outlined text-primary">badge</span>
                                    Personal Details
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                    {{-- Address --}}
                                    <div>
                                        <label class="field-label" for="address">Address</label>
                                        <input id="address" name="address" type="text" class="input-base"
                                            value="{{ old('address', $user->address) }}" autocomplete="address" />
                                        @foreach($errors->get('address') as $msg)
                                            <p class="error-text">{{ $msg }}</p>
                                        @endforeach
                                    </div>

                                    {{-- Religion --}}
                                    <div>
                                        <label class="field-label" for="religion">Religion</label>
                                        <input id="religion" name="religion" type="text" class="input-base"
                                            value="{{ old('religion', $user->religion) }}" autocomplete="religion" />
                                        @foreach($errors->get('religion') as $msg)
                                            <p class="error-text">{{ $msg }}</p>
                                        @endforeach
                                    </div>

                                    {{-- Sex --}}
                                    <div>
                                        <label class="field-label" for="sex">Sex</label>
                                        <select id="sex" name="sex" class="select-base" autocomplete="off">
                                            <option value="" disabled {{ $sexCurrent ? '' : 'selected' }}>Select Sex
                                            </option>
                                            @foreach($sexes as $sexOption)
                                                @php $selected = $norm($sexCurrent) === $norm($sexOption); @endphp
                                                <option value="{{ $sexOption }}" {{ $selected ? 'selected' : '' }}>
                                                    {{ $sexOption }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @foreach($errors->get('sex') as $msg)
                                            <p class="error-text">{{ $msg }}</p>
                                        @endforeach
                                    </div>

                                    {{-- Marital Status --}}
                                    <div>
                                        <label class="field-label" for="marital_status">Marital Status</label>
                                        <select id="marital_status" name="marital_status" class="select-base"
                                            autocomplete="off">
                                            <option value="" disabled {{ $msCurrent ? '' : 'selected' }}>Select Marital
                                                Status</option>
                                            @foreach($maritalStatuses as $ms)
                                                @php $selected = $norm($msCurrent) === $norm($ms); @endphp
                                                <option value="{{ $ms }}" {{ $selected ? 'selected' : '' }}>{{ $ms }}</option>
                                            @endforeach
                                        </select>
                                        @foreach($errors->get('marital_status') as $msg)
                                            <p class="error-text">{{ $msg }}</p>
                                        @endforeach
                                    </div>

                                    {{-- Birthdate --}}
                                    <div>
                                        <label class="field-label" for="birthdate">Birthdate</label>
                                        <input id="birthdate" name="birthdate" type="date" class="input-base"
                                            value="{{ $birthVal }}" autocomplete="off" />
                                        @foreach($errors->get('birthdate') as $msg)
                                            <p class="error-text">{{ $msg }}</p>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        </section>

                        {{-- EMPLOYMENT DETAILS --}}
                        <section class="section-card">
                            <div class="section-header">
                                <div class="section-title">
                                    <span class="material-symbols-outlined text-primary">work</span>
                                    Employment Details
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                    {{-- Position --}}
                                    <div>
                                        <label class="field-label" for="position">Position</label>
                                        <input id="position" name="position" type="text" class="input-base"
                                            value="{{ old('position', $user->position) }}" autocomplete="position" />
                                        @foreach($errors->get('position') as $msg)
                                            <p class="error-text">{{ $msg }}</p>
                                        @endforeach
                                    </div>

                                    {{-- Office --}}
                                    <div>
                                        <label class="field-label" for="office">Office</label>
                                        <select id="office" name="office" class="select-base" required autocomplete="off">
                                            <option value="" disabled {{ $officeCurrent ? '' : 'selected' }}>Select Office
                                            </option>
                                            @foreach($offices as $officeOption)
                                                @php $selected = $norm($officeCurrent) === $norm($officeOption); @endphp
                                                <option value="{{ $officeOption }}" {{ $selected ? 'selected' : '' }}>
                                                    {{ $officeOption }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @foreach($errors->get('office') as $msg)
                                            <p class="error-text">{{ $msg }}</p>
                                        @endforeach
                                    </div>

                                    {{-- Annual Income --}}
                                    <div>
                                        <label class="field-label" for="annual_income">Annual Income</label>
                                        <input id="annual_income" name="annual_income" type="text" class="input-base"
                                            value="{{ old('annual_income', $user->annual_income) }}"
                                            autocomplete="annual_income" />
                                        @foreach($errors->get('annual_income') as $msg)
                                            <p class="error-text">{{ $msg }}</p>
                                        @endforeach
                                    </div>

                                    {{-- Beneficiaries --}}
                                    <div>
                                        <label class="field-label" for="beneficiaries">Beneficiary/ies</label>
                                        <input id="beneficiaries" name="beneficiaries" type="text" class="input-base"
                                            value="{{ old('beneficiaries', $user->beneficiaries) }}"
                                            autocomplete="beneficiaries" />
                                        @foreach($errors->get('beneficiaries') as $msg)
                                            <p class="error-text">{{ $msg }}</p>
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        </section>

                        {{-- Bottom actions --}}
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                            <a href="{{ route('member.profile') }}" class="btn-secondary w-full sm:w-auto justify-center">
                                <span class="material-symbols-outlined text-lg">close</span>
                                Cancel
                            </a>

                            <button type="submit" class="btn-primary w-full sm:w-auto justify-center">
                                <span class="material-symbols-outlined text-lg">save</span>
                                Save Changes
                            </button>
                        </div>

                    </form>
                </div>
            </section>
        </div>

        {{-- RIGHT: HELP PANEL (same vibe as profile page support box) --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="rounded-2xl bg-primary/5 border border-primary/20 p-6">
                <h4 class="font-bold text-slate-900 mb-2">Need help?</h4>
                <p class="text-sm text-slate-500 mb-4">
                    If you’re unsure about a field (office, position, income, etc.), please contact the Cooperative
                    Secretary.
                </p>
                <a class="text-sm font-black text-primary hover:underline inline-flex items-center gap-1" href="#">
                    Contact Support
                    <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 card-shadow">
                <h4 class="text-sm font-black uppercase tracking-widest text-slate-400">Tip</h4>
                <p class="mt-2 text-sm font-semibold text-slate-700">
                    Use a clear profile photo. It helps admin verification and records consistency.
                </p>
            </div>
        </div>
    </div>
@endsection