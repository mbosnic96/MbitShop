<x-app-layout>

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
        <!-- Most Sold Products Slider -->


        <!-- Najprodavaniji ovog mjeseca -->
        <div class="my-8">
            <h2 class="text-2xl font-bold mb-4 uppercase text-center">Najprodavaniji ovog mjeseca</h2>
            <div class="grid grid-cols-2 gap-8">
                <div class="grid grid-cols-3 gap-4" id="recommended-grid-1"></div>
                <div class="grid grid-cols-3 gap-4" id="recommended-grid-2"></div>
            </div>
        </div>

    </div>

    <!-- Kategorije -->
    <div
        class="py-12 px-4 sm:px-8 lg:px-16 bg-gradient-to-b from-gray-50 via-white to-gray-100 dark:from-gray-800 dark:via-gray-900 dark:to-gray-950">
        <h2 class="text-3xl font-bold text-center mb-10 uppercase tracking-wide text-gray-800 dark:text-gray-100">
            Kategorije
        </h2>

        <div id="categories-grid" class="dynamic-grid gap-6 auto-rows-fr"></div>

    </div>

    <div class="container mx-auto px-8">

        <!-- Akcija -->
        <div class="my-8">
            <h2 class="text-2xl font-bold mb-4 uppercase text-center">Akcija</h2>
            <div id="latest-products-grid"></div>
        </div>
    </div>

    <!-- Novi artikli -->
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

    <!-- JS Section -->
    <script>
        async function fetchCategories() {
            try {
                const response = await fetch('/api/categories');
                if (!response.ok) throw new Error('Network response was not ok');
                const result = await response.json();
                const categories = result.data || [];
                renderCategoriesGrid(categories);
            } catch (error) {
                console.error('Fetch error:', error);
            }
        }

        function renderCategoriesGrid(categories) {
    const grid = document.getElementById('categories-grid');
    grid.innerHTML = '';

    // Dynamically set number of columns based on category count
    const columnCount = Math.min(categories.length, 5); // max 5 cols
    grid.className = `grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-${columnCount} auto-rows-fr`;

    categories.forEach(category => {
        const div = document.createElement('div');
        div.className = `
            bg-white/70 dark:bg-gray-800/70 border border-gray-200 dark:border-gray-700
            backdrop-blur-sm rounded-2xl p-5 flex flex-col justify-between transition hover:shadow-lg hover:scale-[1.015]
        `;

        const imgSrc = category.image ?? '/storage/MbitShopLogo.png';

        div.innerHTML = `
            <a href="/categories/${category.slug}" class="flex flex-col items-center text-center">
                <div class="w-16 h-16 rounded-full overflow-hidden mb-4 border-2 border-blue-500/20 shadow-md bg-white dark:bg-gray-700">
                    <img src="${imgSrc}" alt="${category.name}" class="w-full h-full object-contain" />
                </div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">
                    ${category.name}
                </h3>
            </a>

            ${Array.isArray(category.children) && category.children.length > 0 ? `
                <div class="flex flex-wrap justify-center gap-1 mt-2">
                    ${category.children.map(child => `
                        <a href="/categories/${child.slug}" class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 text-xs px-2 py-1 rounded-full transition hover:bg-blue-200 dark:hover:bg-blue-800">
                            ${child.name}
                        </a>
                    `).join('')}
                </div>
            ` : ''}
        `;

        grid.appendChild(div);
    });
}


        document.addEventListener('DOMContentLoaded', fetchCategories);
    </script>

</x-app-layout>

<x-footer></x-footer>