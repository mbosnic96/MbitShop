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

            
        document.addEventListener('DOMContentLoaded', () => {
                // Function to fetch data from an API
        async function fetchData(api) {
            try {
                const response = await fetch(api);
                if (!response.ok) throw new Error('Network response was not ok');
                return await response.json();
            } catch (error) {
                console.error('Fetch error:', error);
                return [];
            }
        }

        // Function to render a product slide
        function renderProductSlide(item) {
            const images = item?.image ? JSON.parse(item.image) : [];
            const imageUrl = images.length > 0 ? `storage/${images[0]}` : null;
        
            return `
              <a href="/product/${item.slug}" class="w-full h-full">
               <li class="splide__slide flex items-center justify-center text-white text-4xl px-3 relative">
    ${imageUrl ? `
        <div class="relative w-full h-full">
            <img src="${imageUrl}" alt="${item.name}" class="w-full h-full object-cover" style="filter: brightness(50%);"> <!-- Direct brightness filter -->
        </div>
    ` : `
        <div class="w-full flex items-center justify-center">
            <span class="text-gray-500">No Image Available</span>
        </div>
    `}
    <div class="absolute bottom-4 left-4 p-4">
        <h1 class="text-xl">${item?.name}</h1>
        ${item.discount > 0 ? `
            <p class="text-white line-through text-sm me-2">$${item.price}</p>
        ` : ''}

        <!-- New Price (green if discount > 0) -->
       <p class="${item.discount > 0 ? 'text-green-500 font-semibold' : 'text-white font-semibold'}">
    $${item.discount > 0 
        ? (item.price - (item.price * item.discount / 100)).toFixed(2) 
        : item.price}
    ${item.discount > 0 ? `
        <span class="inline-flex items-center bg-green-500 text-white text-xs font-medium px-4 py-1.5 rounded-full dark:bg-green-900 dark:text-green-300 ml-2">
            ${item.discount}%
        </span>
    ` : ''}
</p>

${item?.description && item?.promo ? `<p class="text-sm">${item.description.split(' ').slice(0, 15).join(' ')}...</p>` : ''}

        
      
  </a>
    </div>

</li>

            `;
        }

        // Function to render a product card
        function renderProductCard(item) {
            const product = item.product ?? item;
            const images = product?.image ? JSON.parse(product.image) : [];
            const imageUrl = images.length > 0 ? `storage/${images[0]}` : null;
        
            return `
            <a href="/product/${product.slug}">
                <div class="bg-transparent h-full rounded shadow hover:shadow-lg transition-shadow border border-solid border-gray-300">
                    ${imageUrl ? `
                        <img src="${imageUrl}" alt="${product.name}" class="w-full h-48 object-cover rounded">
                    ` : `
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center rounded">
                            <span class="text-gray-500">No Image Available</span>
                        </div>
                    `}
                   
        
                    <div class="flex p-4 flex-col">
                    <div> <h3 class="text-lg font-semibold mt-2">${product.name}</h3></div>
                        <!-- Old Price (crossed out) only if discount > 0 -->
                        ${product.discount > 0 ? `
                            <p class="text-gray-600 line-through text-sm me-2">$${product.price}</p>
                        ` : ''}
        
                        <!-- New Price (green if discount > 0) -->
                       <p class="${product.discount > 0 ? 'text-green-500 font-semibold' : 'text-gray-700 font-semibold'}">
    $${product.discount > 0 
        ? (product.price - (product.price * product.discount / 100)).toFixed(2) 
        : product.price}
    ${product.discount > 0 ? `
        <span class="inline-flex items-center bg-green-500 text-white text-xs font-medium px-4 py-1.5 rounded-full dark:bg-green-900 dark:text-green-300 ml-2">
            ${product.discount}%
        </span>
    ` : ''}
</p>
<div>${item.total_sold !== undefined ? `<p class="text-sm text-gray-500">Ukupno prodano: ${item.total_sold}</p>` : ''}</div>
                    </div>
        
                    
        
                    <!-- View Button (link to the product's slug) -->
                    
       
                </div>
                </a>
            `;
        }

        function renderProductCardBig(item) {
            const product = item.product ?? item;
            const images = product?.image ? JSON.parse(product.image) : [];
            const imageUrl = images.length > 0 ? `storage/${images[0]}` : null;
        
            return `
              <a href="/product/${product.slug}">
                <div class="bg-transparent h-full rounded shadow hover:shadow-lg transition-shadow border border-solid border-gray-300">
                    ${imageUrl ? `
                        <img src="${imageUrl}" alt="${product.name}" class="h-48 w-full object-cover rounded">
                    ` : `
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center rounded">
                            <span class="text-gray-500">No Image Available</span>
                        </div>
                    `}
                   <div class="flex p-4 flex-col">
                    <div> <h3 class="text-lg font-semibold mt-2">${product.name}</h3></div>
                        <!-- Old Price (crossed out) only if discount > 0 -->
                        ${product.discount > 0 ? `
                            <p class="text-gray-600 line-through text-sm me-2">$${product.price}</p>
                        ` : ''}
        
                        <!-- New Price (green if discount > 0) -->
                       <p class="${product.discount > 0 ? 'text-green-500 font-semibold' : 'text-gray-700 font-semibold'}">
    $${product.discount > 0 
        ? (product.price - (product.price * product.discount / 100)).toFixed(2) 
        : product.price}
    ${product.discount > 0 ? `
        <span class="inline-flex items-center bg-green-500 text-white text-xs font-medium px-4 py-1.5 rounded-full dark:bg-green-900 dark:text-green-300 ml-2">
            ${product.discount}%
        </span>
    ` : ''}
</p>
<div>${item.total_sold !== undefined ? `<p class="text-sm text-gray-500">Ukupno prodano: ${item.total_sold}</p>` : ''}</div>
                    </div>
                    
      
                </div>
                </a>
            `;
        }
        
        
        
        

        // Initialize Most Sold Products Slider
        async function initMostSoldSlider() {
            const data = await fetchData('/api/products/promo');
            const sliderList = document.getElementById('most-sold-slider-list');
            sliderList.innerHTML = data.map(renderProductSlide).join('');

            const splide = new Splide('#most-sold-slider .splide', {
                type: 'loop',
                autoplay: true,
                interval: 3000,
                pauseOnHover: true,
                arrows: true,
                padding: '5rem',
                pagination: false,
            });

            const bar = document.querySelector('#most-sold-slider .my-slider-progress-bar');
            splide.on('mounted move', () => {
                const end = splide.Components.Controller.getEnd() + 1;
                const rate = Math.min((splide.index + 1) / end, 1);
                bar.style.width = `${100 * rate}%`;
            });

            splide.mount();
        }

        // Initialize Top Selling Products Slider
        async function initTopSellingSlider() {
            const data = await fetchData('/api/products/get-latest-products');
            const sliderList = document.getElementById('top-selling-slider-list');
            sliderList.innerHTML = data.map(renderProductSlide).join('');

            const splide = new Splide('#top-selling-slider .splide', {
                type: 'loop',
                autoplay: true,
                interval: 5000,
                pauseOnHover: true,
                perPage: 3,
                perMove: 1,
                arrows: true,
                padding: '5rem',
                height   : '10rem',
                pagination: false,
                focus  : 'center',
                breakpoints: {
                    640: {
                        perPage: 1,
                    },
                    1024: {
                        perPage: 2,
                    },
                },
            });


            splide.mount();
        }

        async function initLatestProductsGrid() {
            const response = await fetchData('/api/products/on-discount'); // paginated response
            const data = response.data || []; // access actual products
        
            const gridContainer = document.getElementById('latest-products-grid');
        
            // Determine the number of products to display (up to 7)
            const productsToDisplay = data.slice(0, 7); // Take up to 7 products
        
            // Generate the grid layout based on the number of products
            gridContainer.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                    <div class="flex gap-4">
                        ${productsToDisplay.length > 0 ? `
                            <div class="w-1/4 full-img">
                                ${renderProductCardBig(productsToDisplay[0])}
                            </div>
                        ` : ''}
        
                        <div class="w-3/4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            ${productsToDisplay.slice(1, 7).map(renderProductCardBig).join('')}
                        </div>
                    </div>
        
                </div>
            `;
        }
        
        
        

        // Initialize Recommended Products Grid
        async function initRecommendedProductsGrid() {
            const response = await fetchData('/api/products/top-selling-products');
            const data = response.data || [];
            const grid1 = document.getElementById('recommended-grid-1');
            const grid2 = document.getElementById('recommended-grid-2');
            

            const firstThree = data.slice(0, 3);
            const nextThree = data.slice(3, 6);

            grid1.innerHTML = firstThree.map(renderProductCard).join('');
            grid2.innerHTML = nextThree.map(renderProductCard).join('');
        }

        // Initialize all components
            initMostSoldSlider();
            initTopSellingSlider();
            initLatestProductsGrid();
            initRecommendedProductsGrid();
        });
    </script>

</x-app-layout>

<x-footer></x-footer>