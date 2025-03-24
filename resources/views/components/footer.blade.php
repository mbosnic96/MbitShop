<footer class="bg-gray-900 text-gray-300 mt-16">
    <div class="container mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Logo & Description -->
        <div>
            <a href="/" class="text-white text-2xl font-bold">MbitShop</a>
            <p class="mt-2 text-sm text-gray-400">
                Vaša IT destinacija za računare, periferiju i elektroniku.
            </p>
        </div>

        <!-- Navigation -->
        <div>
            <h4 class="text-white font-semibold mb-3">Navigacija</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="/" class="hover:text-white">Početna</a></li>
                <li><a href="/login" class="hover:text-white">Prijavi se</a></li>
                <li><a href="/register" class="hover:text-white">Registruj se</a></li>
            </ul>
        </div>

        <!-- Kontakt -->
        <div>
            <h4 class="text-white font-semibold mb-3">Kontakt</h4>
            <p class="text-sm"><i class="fa fa-envelope me-2"></i>  <a href="mailto:{{ config('mail.from.address') }}" class="hover:text-white">
    {{ config('mail.from.address') }}
  </a></p>
            <p class="text-sm mt-1"><i class="fa fa-phone me-2"></i> <a href="tel:+387603004395" class="hover:text-white">+387 60 300 4395</a></p>
            <p class="text-sm mt-1"><i class="fa fa-map-marker me-2"></i> Bihać, BiH</p>
        </div>

      
    </div>

    <div class="border-t border-gray-800 mt-8">
        <div class="container mx-auto px-6 py-4 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} MbitShop. Sva prava zadržana.</p>
           
        </div>
    </div>
</footer>
