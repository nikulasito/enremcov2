<nav x-data="{ open: false, showNotifications: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('admin.dashboard') }}">
                        <img src="{{ asset('assets/images/enremco_logo2.png') }}" alt="ENREMCO Logo" class="img-fluid" style="max-width: 200px;">
                    </a>
                </div>
            </div>

            <!-- Notification and Settings -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Notification Icon -->
                <div class="relative me-4" @click.away="showNotifications = false">
                    <button @click="showNotifications = !showNotifications" class="relative inline-flex items-center focus:outline-none">
                        <i class="bi bi-bell text-lg"></i>
                        @if(isset($newMembersCount) && $newMembersCount > 0)
                            <span class="notif-number absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                                {{ $newMembersCount }}
                            </span>
                        @endif
                    </button>
                    <!-- Notification Dropdown -->
                    <div x-show="showNotifications" x-cloak class="absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg z-50">
                        <div class="p-4">
                            <h6 class="font-medium text-gray-800 flex justify-between">
                                Notifications
                            </h6>
                            <div class="mt-3 space-y-2">
                                @if(isset($newMembersCount) && $newMembersCount > 0)
                                    <div class="flex items-start space-x-3">
                                        <div class="w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center">
                                            <i class="bi bi-person-plus"></i>
                                        </div>
                                        <div>
                                        <a href="{{ route('admin.new-members') }}" class="text-sm text-blue-500 hover:underline">{{ $newMembersCount }} new member(s) awaiting approval.</a>
                                        </div>
                                    </div>
                                    <!-- <a href="{{ route('admin.new-members') }}" class="text-sm text-blue-500 hover:underline">View Details</a> -->
                                @else
                                    <p class="text-sm text-gray-500">No new notifications.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
                <div class="admin-logout">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link onclick="event.preventDefault(); this.closest('form').submit();">
                            <em>{{ __('Log Out') }}</em>
                        </x-dropdown-link>
                    </form>
                </div>
                <!-- User Settings -->
                <!-- <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown> -->
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
