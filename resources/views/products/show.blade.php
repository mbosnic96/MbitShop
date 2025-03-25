<x-app-layout>
  <div class="max-w-7xl mx-auto px-6 py-12 lg:px-8" x-data="productPage()" x-init="init()">
    <!-- Loading State -->
    <div x-show="loading" class="text-center py-20">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500 mx-auto"></div>
      <p class="mt-4 text-gray-600">Učitavanje sadržaja...</p>
    </div>

    <!-- Error State -->
    <div x-show="error" class="text-center py-20">
      <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </div>
      <h3 class="mt-3 text-lg font-medium text-gray-900">Product not found</h3>
      <p x-text="errorMessage" class="mt-2 text-sm text-gray-500"></p>
      <div class="mt-6">
        <a href="/products" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
          Browse Products
        </a>
      </div>
    </div>

    <!-- Product Content -->
    <div x-show="!loading && !error && product" class="grid grid-cols-1 lg:grid-cols-2 gap-12">
      <!-- Product Gallery Section -->
      <div>
        <div class="splide" id="main-slider">
          <div class="splide__track">
            <ul class="splide__list" x-ref="galleryImages">
              <template x-for="(image, index) in productImages" :key="index">
                <li class="splide__slide">
                  <img :src="'/storage/' + image" :alt="product.name || ''" class="w-full h-full object-contain rounded-lg">
                </li>
              </template>
            </ul>
          </div>
        </div>

        <!-- Thumbnail Navigation Section -->
        <div class="splide mt-4" id="thumbnail-slider">
          <div class="splide__track">
            <ul class="splide__list flex gap-4" x-ref="thumbnailSlider">
              <template x-for="(image, index) in productImages" :key="index">
                <li class="splide__slide">
                  <img :src="'/storage/' + image" :alt="product.name || ''" class="w-24 h-24 object-contain rounded-lg cursor-pointer">
                </li>
              </template>
            </ul>
          </div>
        </div>
      </div>

      <!-- Product Details Section -->
      <div class="flex flex-col space-y-6">
        <!-- Product Name -->
        <h1 class="text-3xl font-extrabold text-gray-900" x-text="product?.name || 'Product'"></h1>

        <!-- Product Description -->
        <p class="text-xl text-gray-500" x-text="product?.description || 'No description available'"></p>

        <!-- Product Price Section -->
        <div class="flex items-center space-x-4">
          <p class="text-2xl font-semibold text-gray-900" x-text="formattedPrice"></p>
          <p x-show="product?.discount > 0" class="text-sm text-gray-500 line-through" x-text="'$' + (product ? parseFloat(product.price).toFixed(2) : '')"></p>
          <span x-show="product?.discount > 0" class="bg-green-500 text-white text-xs font-bold py-1 px-3 rounded-full" x-text="product?.discount + '% OFF'"></span>
        </div>

        <!-- Product Brand and Category -->
        <div class="flex items-center space-x-4">
          <div x-show="product?.brand" class="flex items-center space-x-2">
            <span class="text-gray-500">Brand:</span>
            <span class="font-semibold" x-text="product?.brand?.name || ''"></span>
          </div>
          <div x-show="product?.category" class="flex items-center space-x-2">
            <span class="text-gray-500">Category:</span>
            <span class="font-semibold" x-text="product?.category?.name || ''"></span>
          </div>
        </div>

        <!-- Product Specifications -->
        <div class="space-y-4">
          <template x-for="spec in specifications" :key="spec.label">
            <div class="flex items-center space-x-2">
              <i class="fa" :class="spec.icon + ' text-gray-500'"></i>
              <span x-text="spec.label + ': ' + spec.value + (spec.suffix || '')"></span>
            </div>
          </template>
        </div>

        <!-- Add to Cart Button -->
        <div class="flex items-center space-x-4">
          <button @click="addToCart(product?.id)" 
                  :disabled="!product"
                  class="flex items-center bg-blue-500 text-white px-4 py-2 rounded-full hover:bg-blue-600 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed">
            <i class="fa fa-shopping-cart mr-2"></i>
            Kupi
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    
document.addEventListener('alpine:init', () => {
  Alpine.data('productPage', () => ({
    loading: true,
    error: false,
    errorMessage: '',
    product: null,
    productImages: [],
    specifications: [],
    formattedPrice: '',

    async init() {
      const slug = window.location.pathname.split('/').filter(Boolean).pop();
      
      if (!slug) {
        this.showError('Invalid product URL');
        return;
      }

      try {
        this.product = await this.fetchProduct(slug);
        
        if (this.product) {
          this.prepareProductData();
          this.$nextTick(() => {
            this.initializeSplideSlider();
          });
        } else {
          this.showError('Product not found');
        }
      } catch (error) {
        console.error('Error:', error);
        this.showError(error.message || 'Failed to load product details');
      } finally {
        this.loading = false;
      }
    },

    async fetchProduct(slug) {
      const response = await fetch(`/api/product/${slug}`);
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      return await response.json();
    },

    prepareProductData() {
      if (!this.product) return;
      
      // Format price
      const price = parseFloat(this.product.price);
      const discount = parseFloat(this.product.discount || 0);
      
      this.formattedPrice = discount > 0
        ? `$${(price * (1 - discount / 100)).toFixed(2)}`
        : `$${price.toFixed(2)}`;

      // Prepare specifications
      this.specifications = [
        { icon: 'fa-microchip', label: 'Processor', value: this.product.processor || 'N/A' },
        { icon: 'fa-hdd-o', label: 'RAM', value: this.product.ram_size || 'N/A', suffix: ' GB' },
        { icon: 'fa-hdd-o', label: 'Storage', value: this.product.storage || 'N/A', suffix: ' GB' },
        { icon: 'fa-cogs', label: 'OS', value: this.product.operating_system || 'N/A' },
        { icon: 'fa-desktop', label: 'Screen', value: this.product.screen_size || 'N/A', suffix: ' inches' }
      ].filter(spec => spec.value && spec.value !== 'N/A');

      // Parse images
      if (this.product.image) {
        try {
          this.productImages = JSON.parse(this.product.image);
        } catch (e) {
          console.error('Error parsing product images:', e);
          this.productImages = [];
        }
      }
    },

    initializeSplideSlider() {
      if (this.productImages.length === 0) return;
      
      const main = new Splide('#main-slider', {
        type: 'fade',
        heightRatio: 0.5,
        pagination: false,
        arrows: false,
        cover: true,
      });

      const thumbnails = new Splide('#thumbnail-slider', {
        rewind: true,
        fixedWidth: 104,
        fixedHeight: 58,
        isNavigation: true,
        gap: 10,
        focus: 'center',
        pagination: false,
        cover: true,
        dragMinThreshold: {
          mouse: 4,
          touch: 10,
        },
        breakpoints: {
          640: {
            fixedWidth: 66,
            fixedHeight: 38,
          },
        },
      });

      main.sync(thumbnails);
      main.mount();
      thumbnails.mount();
    },

    showError(message) {
      this.errorMessage = message;
      this.error = true;
    },

    async addToCart(productId) {
      if (!productId) return;
      
      try {
        const response = await fetch('/api/cart/add', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ product_id: productId })
        });

        const responseData = await response.json();

        if (response.ok) {
          toastr.success(responseData.message, 'Dodano u košaricu');
        } else {
          toastr.error(responseData?.message || 'Nepoznata greška', 'Error');
        }
      } catch (error) {
        toastr.error('Error adding product to cart', 'Error');
      }
    }
  }));
});
  </script>


</x-app-layout>
