<?php

namespace App\Livewire;


use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;

class ProductFilter extends Component
{
    use WithPagination;

    public $category;
    public $selectedBrands = [];
    public $selectedProcessors = [];
    public $selectedRam = [];
    public $selectedStorage = [];
    public $selectedScreenSizes = [];
    public $selectedGraphics = [];
    public $maxPrice = 5000;

    // Filter options
    public $brands;
    public $processors;
    public $ramSizes;
    public $storages;
    public $screenSizes;
    public $graphicsCards;
    public $cart = [];

    public function mount($slug)
    {
        $this->category = Category::where('slug', $slug)
            ->with(['children'])
            ->firstOrFail();
        
        $this->cart = session()->get('cart', []);

        $this->initializeFilterOptions();
    }

    private function initializeFilterOptions()
    {
        $baseQuery = Product::whereIn('category_id', $this->getCategoryIds());

        $this->brands = Brand::whereHas('products', function($query) {
            $query->whereIn('category_id', $this->getCategoryIds());
        })->get();

        $this->processors = $baseQuery->pluck('processor')->unique()->filter();
        $this->ramSizes = $baseQuery->pluck('ram_size')->unique()->sort();
        $this->storages = $baseQuery->pluck('storage')->unique()->sort();
        $this->screenSizes = $baseQuery->pluck('screen_size')->unique()->sort();
        $this->graphicsCards = $baseQuery->pluck('graphics_card')->unique()->filter();
    }

    private function getCategoryIds()
    {
        return $this->category->children->pluck('id')->push($this->category->id);
    }

    public function render()
    {
        $products = $this->getFilteredProducts();
        
        return view('livewire.product-filter', [
            'products' => $products,
        ])->layout('layouts.app');
    }

    private function getFilteredProducts()
    {
        return Product::whereIn('category_id', $this->getCategoryIds())
            ->when($this->selectedBrands, fn($q) => $q->whereIn('brand_id', $this->selectedBrands))
            ->when($this->selectedProcessors, fn($q) => $q->whereIn('processor', $this->selectedProcessors))
            ->when($this->selectedRam, fn($q) => $q->whereIn('ram_size', $this->selectedRam))
            ->when($this->selectedStorage, fn($q) => $q->whereIn('storage', $this->selectedStorage))
            ->when($this->selectedScreenSizes, fn($q) => $q->whereIn('screen_size', $this->selectedScreenSizes))
            ->when($this->selectedGraphics, fn($q) => $q->whereIn('graphics_card', $this->selectedGraphics))
            ->where('price', '<=', $this->maxPrice)
            ->with('brand')
            ->paginate(1);
    }

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);

        $cart = session()->get('cart', []);

        // If product exists in cart, increment the quantity
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            // Otherwise, add product to cart with initial quantity
            $cart[$productId] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
            ];
        }

        // Save the updated cart in session
        session()->put('cart', $cart);
        $this->cart = $cart;

        // Emit event to notify that the cart is updated (useful for other components)
       // $this->emit('cartUpdated');
       // $this->dispatchBrowserEvent('cart-updated', ['message' => 'Product added to cart']);
    }

    public function updated()
    {
        $this->resetPage();
    }
}