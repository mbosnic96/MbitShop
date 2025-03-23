<x-app-layout>

    <!-- Most Sold Slider -->
    <div id="most-sold-slider" class="overflow-hidden">
        <div class="splide h-[80vh] mt-[50px] relative">
            <div class="splide__track h-full">
                <ul class="splide__list h-full" id="most-sold-slider-list"></ul>
            </div>

            <!-- Progress Bar -->
            <div class="my-slider-progress h-1 bg-gray-300 w-full absolute bottom-0">
                <div class="my-slider-progress-bar h-full bg-blue-600 w-0 transition-all duration-300"></div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-8">

        <!-- Most Sold Products Section -->
        <div class="my-8">
            <h2 class="text-2xl font-bold mb-4 uppercase text-center">Najprodavaniji ovog mjeseca</h2>
            <div class="grid grid-cols-2 gap-8">
                <div class="grid grid-cols-3 gap-4" id="recommended-grid-1"></div>
                <div class="grid grid-cols-3 gap-4" id="recommended-grid-2"></div>
            </div>
        </div>

    </div>

    <!-- Categories Section -->
    <section class="py-12 px-4 sm:px-8 lg:px-16 bg-gradient-to-b from-gray-50 via-white to-gray-100 dark:from-gray-800 dark:via-gray-900 dark:to-gray-950">
        <h2 class="text-3xl font-bold text-center mb-10 uppercase tracking-wide text-gray-800 dark:text-gray-100">
            Kategorije
        </h2>

        <div id="categories-grid" class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 auto-rows-fr">
            <!-- Categories will be dynamically injected here -->
        </div>
    </section>

    <div class="container mx-auto px-8">

        <!-- Akcija Section -->
        <div class="my-8">
            <h2 class="text-2xl font-bold mb-4 uppercase text-center">Akcija</h2>
            <div id="latest-products-grid"></div>
        </div>
    </div>

    <!-- Novi Artikli Section -->
    <div class="py-12 px-4 sm:px-8 lg:px-16 bg-gradient-to-b from-gray-50 via-white to-gray-100 dark:from-gray-800 dark:via-gray-900 dark:to-gray-950">
        <h2 class="text-3xl font-bold text-center mb-10 uppercase tracking-wide text-gray-800 dark:text-gray-100">Novi artikli</h2>
        <div id="top-selling-slider" class="overflow-hidden">
            <div class="splide relative">
                <div class="splide__track h-full">
                    <ul class="splide__list h-full" id="top-selling-slider-list"></ul>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Section for Dynamic Categories -->
   
</x-app-layout>

<x-footer></x-footer>