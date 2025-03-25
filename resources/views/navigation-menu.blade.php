<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-mark />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                        {{ __('Početna') }}
                    </x-nav-link>
                </div>

                @foreach($categories[null] ?? [] as $mainCategory)
                    @if(isset($categories[$mainCategory->id]) && count($categories[$mainCategory->id]) > 0)
                        <!-- Category Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ms-5 relative" x-data="{ open: false }">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <x-nav-link href="{{ route('categories.show', $mainCategory->slug) }}" @click.prevent="!open">
                                        {{ $mainCategory->name }} <i class="ms-2 fa fa-angle-down"></i>
                                    </x-nav-link>
                                </x-slot>

                                <x-slot name="content" x-show="open" @click.away="open = false">
                                    <x-dropdown-link href="{{ route('categories.show', $mainCategory->slug) }}">
                                        Prikaži sve
                                    </x-dropdown-link>

                                    @foreach($categories[$mainCategory->id] ?? [] as $subcategory)
                                        <x-dropdown-link href="{{ route('categories.show', $subcategory->slug) }}">
                                            {{ $subcategory->name }}
                                        </x-dropdown-link>
                                    @endforeach
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @else
                        <!-- Single Category Link (No Dropdown) -->
                        <div class="hidden space-x-8 sm:-my-px sm:ms-5 sm:flex">
                            <x-nav-link href="{{ route('categories.show', $mainCategory->slug) }}">
                                {{ $mainCategory->name }}
                            </x-nav-link>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Search and Account Management -->
            <div class="flex items-center ml-auto">
                <!-- Search Dropdown -->
                <div  class="relative me-2">
                    <div>
                        <x-dropdown align="right" width="64">
                            <x-slot name="trigger" @click="open = true">
                                <button class="p-2 bg-gray-100 hover:bg-gray-200 rounded-full relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
    </svg>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <!-- Search Component -->
                                <div style="width: 350px !important;">
                                    <x-search></x-search>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
                @if (Auth::check())
                <div class="relative me-2" x-data="cartData()" x-init="fetchCart">
        <!-- Cart Button -->
        <button @click="window.location.href='/dashboard/cart'" class="p-2 bg-gray-100 hover:bg-gray-200 rounded-full relative">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.293 2.293a1 1 0 00.293 1.414L7 17h10m-7 0a2 2 0 104 0m-4 0H7" />
            </svg>
            <!-- Cart Count Badge -->
            <span x-show="cartCount > 0" class="absolute top-0 right-0 bg-red-500 text-white rounded-full px-1.5 text-xs">
                <span x-text="cartCount"></span>
            </span>
        </button>
    </div>

              

                <div x-data="notificationData()" x-init="fetchNotifications()" class="relative">
    <!-- Notifications Button -->
    <button @click="open = !open" class="p-2 bg-gray-100 hover:bg-gray-200 rounded-full relative">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <!-- Notification Count Badge (Red Circle) -->
        <span x-show="unreadCount > 0" class="absolute top-0 right-0 bg-red-500 text-white rounded-full px-1.5 text-xs">
            <span x-text="unreadCount"></span>
        </span>
    </button>

    <!-- Notifications Dropdown -->
    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white shadow-lg rounded-lg overflow-hidden z-50">
        <!-- Dropdown Header -->
        <div class="p-4 border-b bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800">Notifications</h3>
            <p class="text-sm text-gray-500" x-text="`${unreadCount} unread`"></p>
        </div>

        <!-- Notifications List -->
        <ul :class="showAll ? 'max-h-96 overflow-y-auto' : 'max-h-60 overflow-y-auto'" class="divide-y divide-gray-100">
            <template x-for="notification in (showAll ? notifications : notifications.slice(0, 5))">
            <li @click="redirectToOrder(notification.data.order_number, notification.id)" class="p-4 hover:bg-gray-50 cursor-pointer transition-colors duration-200">
    <div class="flex items-start space-x-3">
        <!-- User Photo -->
        <img :src="notification.data.user_photo ? '../storage/' + notification.data.user_photo : '../storage/MbitShopLogo.png'"
     alt="User Photo"
     class="h-10 w-10 rounded-full object-cover">

        <!-- Notification Content -->
        <div class="flex-1">
            <p :class="notification.read_at ? 'text-gray-500' : 'text-gray-900'" class="text-sm font-medium" x-text="notification.data.message"></p>
            <p class="text-xs text-gray-500 mt-1" x-text="formatDate(notification.created_at)"></p>
        </div>
        <!-- Unread Indicator -->
        <div x-show="!notification.read_at" class="w-2 h-2 bg-blue-500 rounded-full"></div>
    </div>
</li>
            </template>
        </ul>

        <!-- Dropdown Footer -->
        <div class="p-4 border-t bg-gray-50">
            <button @click="showAll = !showAll" class="text-sm text-blue-600 hover:text-blue-500">
                <span x-text="showAll ? 'Show Less' : 'View All'"></span>
            </button>
        </div>
    </div>
</div>

@endif

                <!-- Account Management -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    @if (Auth::check())
                        <!-- Settings Dropdown -->
                        <div class="ms-3 relative">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                        <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                            <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                        </button>
                                    @else
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                {{ Auth::user()->name }}

                                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </span>
                                    @endif
                                </x-slot>

                                <x-slot name="content">
                                    <!-- Account Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Account') }}
                                    </div>

                                    <x-dropdown-link href="{{ route('profile.show') }}">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>

                                    @if (Auth::check() && Auth::user()->role === 'admin')
                                        <x-dropdown-link href="{{ route('dashboard') }}">
                                            {{ __('Dashboard') }}
                                        </x-dropdown-link>
                                    @endif

                                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                        <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                            {{ __('API Tokens') }}
                                        </x-dropdown-link>
                                    @endif

                                    <div class="border-t border-gray-200"></div>

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}" x-data>
                                        @csrf

                                        <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @else
                        <!-- Display these links for guest (not logged-in) users -->
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Prijavi se</a>
                        <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Registruj se</a>
                    @endif
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>
        @if (Auth::check())
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-4">
                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <div class="shrink-0 me-3">
                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        </div>
                    @endif

                    <div>
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <!-- Account Management -->
                    <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}" x-data>
                        @csrf

                        <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <!-- Display these links for guest (not logged-in) users -->
            <x-responsive-nav-link href="{{ route('login') }}">Prijavi se</x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('register') }}">Registruj se</x-responsive-nav-link>
        @endif
    </div>
</nav>

<script>
    function cartData() {
        return {
            cartCount: 0,
            fetchCart() {
                axios.get('/api/cart') 
                    .then(response => {
                        this.cartCount = response.data.cartCount;
                    })
                    .catch(error => {
                        console.error('Error fetching cart:', error);
                    });
            },
            init() {
            this.fetchCart(); 
        
        }
        };
    }
    function notificationData() {
    return {
        notifications: [], 
        unreadCount: 0, 
        open: false, 
        showAll: false, 

        
        fetchNotifications() {
            fetch('/api/notifications')
                .then(response => response.json())
                .then(data => {
                    this.notifications = data.unread.concat(data.read);
                    this.unreadCount = data.unread_count;
                })
                .catch(error => {
                    console.error('Error fetching notifications:', error);
                });
        },

        
        redirectToOrder(order_number, notificationId) {
            this.markAsRead(notificationId, () => {
                const baseUrl = "{{ url('/') }}";
window.location.href = `${baseUrl}/dashboard/orders?search=${order_number}`;
            });
        },

        
        markAsRead(id, callback) {
            fetch(`/api/notifications/read/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Add CSRF token
                },
            })
            .then(() => {
                this.fetchNotifications();
                if (callback) callback();
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
            });
        },

        
        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString(); 
        }
    };
}
</script>
