<div id="advanced-search-section">
    <!-- Search Bar -->
    <input type="text" id="search-input" class="w-full border rounded-md focus:ring focus:ring-blue-300"
        placeholder="Pretraži artikle...">
    <div id="search-results" class="hidden bg-white border rounded-md mt-1 max-h-60 overflow-y-auto"></div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("search-input");
        const searchResults = document.getElementById("search-results");

        searchInput.addEventListener("input", function () {
            const query = searchInput.value.trim();
            if (query.length > 2) { // Only search if the query is longer than 2 characters
                fetchFilteredProducts(query);
            } else {
                searchResults.innerHTML = '';
                searchResults.classList.add('hidden');
            }
        });

        function fetchFilteredProducts(query) {
            fetch('/api/search', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ search: query })
            })
                .then(response => response.json())
                .then(products => {
                    updateSearchResults(products);
                })
                .catch(error => console.error('Fetch Error:', error));
        }

        function updateSearchResults(products) {
            searchResults.innerHTML = ''; // Clear existing results

            if (products.length === 0) {
                searchResults.innerHTML = '<p class="p-2 text-gray-600">Nema rezultata.</p>';
                searchResults.classList.remove('hidden');
                return;
            }

            products.forEach(product => {
                let images = JSON.parse(product.image || '[]');
                let firstImage = images.length ? `/storage/${images[0]}` : '/storage/MbitShopLogo.png';
                let productHTML = `
                    <a href="/product/${product.slug}" class="flex align-items-center p-2 hover:bg-gray-100 cursor-pointer">
                        <div>
                            <img src="${firstImage}" alt="${product.name}" class="h-[80px] w-[80px] object-cover">
                        </div>
                        <div class="p-2">
                            <p class="text-gray-800">${product.name}</p>
                            <p class="text-gray-600">${product.price || ''} KM</p>
                        </div>
                    </a>
                `;
                searchResults.insertAdjacentHTML('beforeend', productHTML);
            });

            searchResults.classList.remove('hidden');
        }
    });
</script>