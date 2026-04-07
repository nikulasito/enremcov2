@extends('layouts.auth')

@section('title', 'ENREMCO Reset Password')
@section('left_title', 'Set your new password')
@section('left_desc', 'Use a strong password so your account stays secure.')

@section('content')
    <div class="w-full">
        <h2 class="text-3xl font-black text-[#111814] sm:text-4xl">Reset Password</h2>
        <p class="mt-2 text-base text-[#638875]">
            Enter your email and new password to complete your password reset.
        </p>

        @if (session('status'))
            <div class="mt-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-bold text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <form class="mt-10 flex flex-col gap-6" method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="flex flex-col gap-2">
                <label class="text-sm font-bold text-[#111814]" for="email">Email Address</label>
                <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autofocus
                    autocomplete="username"
                    class="h-14 w-full rounded-xl border-[#dce5e0] bg-[#f6f8f7] px-4 text-base focus:border-primary focus:ring-primary" />
                @error('email')
                    <p class="text-sm font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-sm font-bold text-[#111814]" for="password">New Password</label>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                    class="h-14 w-full rounded-xl border-[#dce5e0] bg-[#f6f8f7] px-4 text-base focus:border-primary focus:ring-primary" />
                @error('password')
                    <p class="text-sm font-bold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-sm font-bold text-[#111814]" for="password_confirmation">Confirm New Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    autocomplete="new-password"
                    class="h-14 w-full rounded-xl border-[#dce5e0] bg-[#f6f8f7] px-4 text-base focus:border-primary focus:ring-primary" />
            </div>

            <button type="submit"
                class="mt-4 flex h-14 w-full items-center justify-center rounded-xl bg-primary text-lg font-black text-background-dark shadow-lg shadow-primary/20 transition-all hover:brightness-105 active:scale-[0.98]">
                Reset Password
            </button>
        </form>

        <div class="mt-8 text-center">
            <a class="inline-flex items-center gap-2 text-sm font-bold text-primary hover:underline"
                href="{{ route('login') }}">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                Back to Login
            </a>
        </div>
    </div>
@endsection
