<section class="changepass">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Change Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <x-input-label for="current_password" :value="__('Current Password')" />
            <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full"
                autocomplete="current-password" required />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('New Password')" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full"
                autocomplete="new-password" required />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                class="mt-1 block w-full" autocomplete="new-password" required />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }" x-show="show"
                    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
                    <div class="bg-white rounded-lg p-6 shadow-lg w-full max-w-sm" style="width: 600px;text-align: center;">
                        <h2 class="text-lg font-bold mb-2 text-green-600">Success!</h2>
                        <p class="text-sm text-gray-700">Password updated successfully.</p>
                        <button @click="show = false"
                            class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700" background: #000000;>
                            Close
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </form>



</section>