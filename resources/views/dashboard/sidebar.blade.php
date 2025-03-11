<div class="w-1/4 bg-gray-800 text-white p-4">
    <div class="flex items-center mb-6">
        <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile Photo" class="rounded-full w-12 h-12 mr-4">
        <div>
            <p class="text-lg">{{ Auth::user()->name }}</p>
            <p class="text-sm">{{ Auth::user()->email }}</p>
        </div>
    </div>
    <div class="h-screen px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800">
        <ul class="space-y-4">
            <li>
                <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" class="p-2 text-gray-800 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group tab-button cursor-pointer">
                    Dashboard
                </x-responsive-nav-link>
            </li>
            <li>
                <x-responsive-nav-link href="{{ route('dashboard.orders') }}" :active="request()->routeIs('dashboard.orders')" class="p-2 text-gray-800 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group tab-button cursor-pointer">
                    Moje narudžbe
                </x-responsive-nav-link>
            </li>
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
        </ul>
    </div>
</div>
