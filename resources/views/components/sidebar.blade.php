<div class="w-80 bg-gray-800 text-white p-4 fixed md:relative z-50 h-full transition-all duration-300 ease-in-out transform"
     x-show="sidebarOpen" 
     :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
     @click.away="sidebarOpen = window.innerWidth >= 768 ? true : false">
    <!-- Rest of your sidebar content remains the same -->
    <div class="flex items-center mb-6">
        <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile Photo" class="rounded-full w-12 h-12 mr-4">
        <div>
            <p class="text-lg">{{ Auth::user()->name }}</p>
            <p class="text-sm">{{ Auth::user()->email }}</p>
        </div>
    </div>
    <div class="h-[calc(100vh-8rem)] px-3 py-4 overflow-y-auto">
        <ul class="space-y-4">
            @if (Auth::check() && Auth::user()->role === 'admin')
            <li>
                <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="p-2 text-gray-800 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group tab-button cursor-pointer">
                    Dashboard
                </x-responsive-nav-link>
            </li>
            @endif
            <li>
                <x-responsive-nav-link href="{{ route('cart.index') }}" :active="request()->routeIs('cart.index')" class="p-2 text-gray-800 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group tab-button cursor-pointer">
                    Košarica
                </x-responsive-nav-link>
            </li>
            <li>
                <x-responsive-nav-link href="{{ route('orders.index') }}" :active="request()->routeIs('orders.index')" class="p-2 text-gray-800 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group tab-button cursor-pointer">
                    Narudžbe
                </x-responsive-nav-link>
            </li>
            @if (Auth::check() && Auth::user()->role === 'admin')
            <li>
                <x-responsive-nav-link href="{{ route('products.index') }}"  :active="request()->routeIs('products.index')" class="p-2 text-gray-800 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group tab-button cursor-pointer">
                    Artikli
                </x-responsive-nav-link>
            </li>
            <li>
                <x-responsive-nav-link href="{{ route('brands.index') }}"  :active="request()->routeIs('brands.index')" class="p-2 text-gray-800 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group tab-button cursor-pointer">
                    Brendovi
                </x-responsive-nav-link>
            </li>
            <li>
                <x-responsive-nav-link href="{{ route('categories.index') }}"  :active="request()->routeIs('categories.index')" class="p-2 text-gray-800 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group tab-button cursor-pointer">
                    Kategorije
                </x-nav-link>
            </li>
            <li>
                <x-responsive-nav-link href="{{ route('users.index') }}" :active="request()->routeIs('users.index')"  class="p-2 text-gray-800 rounded-lg dark:text-white active:bg-gray-100 dark:hover:bg-gray-700 group tab-button cursor-pointer">
                    Korisnici
                </x-responsive-nav-links>
            </li>
            @endif
        </ul>
    </div>
</div>