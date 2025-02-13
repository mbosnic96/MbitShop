<div class="w-1/4 bg-gray-800 text-white p-4">
        <div class="flex items-center mb-6">
            <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile Photo" class="rounded-full w-12 h-12 mr-4">
            <div>
                <p class="text-lg">{{ Auth::user()->name }}</p>
                <p class="text-sm">{{ Auth::user()->email }}</p>
            </div>
        </div>
        <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800">
        <ul class="space-y-4">
            <li><a class="flex items-center p-2 text-gray-800 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group tab-button cursor-pointer">Dashboard</a></li>
            <li><a class="flex items-center p-2 text-gray-800 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group tab-button cursor-pointer">Products</a></li>
            <li><a class="flex items-center p-2 text-gray-800 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group tab-button cursor-pointer">Brands</a></li>
            <li><a class="flex items-center p-2 text-gray-800 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group tab-button cursor-pointer">Categories</a></li>
            <li><a class="flex items-center p-2 text-gray-800 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group tab-button cursor-pointer">Users</a></li>
        </ul>
        </div>
</div>