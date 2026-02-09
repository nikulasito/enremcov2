@extends('layouts.auth')

@section('title', 'ENREMCO Forgot Password Recovery')
@section('left_title', 'Recover your account access')
@section('left_desc', 'Follow the instructions to safely reset your password and regain access to your Cooperative dashboard.')

@section('content')
    <div class="w-full">
        <h2 class="text-3xl font-black text-[#111814] sm:text-4xl">Forgot Password?</h2>
        <p class="mt-2 text-base text-[#638875]">
            Enter your registered email address and we will send you a link to reset your password.
        </p>

        {{-- Session status --}}
        @if (session('status'))
            <div class="mt-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-bold text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <form class="mt-10 flex flex-col gap-6" method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="flex flex-col gap-2">
                <label class="text-sm font-bold text-[#111814]" for="email">Email Address</label>
                <div class="relative">
                    <span
                        class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#a0b0a8]">mail</span>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                        placeholder="name@example.com"
                        class="h-14 w-full rounded-xl border-[#dce5e0] bg-[#f6f8f7] pl-12 pr-4 text-base focus:border-primary focus:ring-primary" />
                </div>

                @if ($errors->has('email'))
                    <p class="text-sm font-bold text-red-600">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <button type="submit"
                class="mt-4 flex h-14 w-full items-center justify-center rounded-xl bg-primary text-lg font-black text-background-dark shadow-lg shadow-primary/20 transition-all hover:brightness-105 active:scale-[0.98]">
                Send Reset Link
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