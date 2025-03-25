<x-app-layout>
    <div class="flex h-screen" x-data="{ sidebarOpen: window.innerWidth >= 768 }" @resize.window="sidebarOpen = window.innerWidth >= 768">
        <!-- Mobile Sidebar Toggle Button -->
        <button @click="sidebarOpen = !sidebarOpen" 
                class="md:hidden fixed z-50 top-[80px] left-0 p-1.5 rounded-r-md bg-gray-800 text-white shadow-lg transition-all duration-300 hover:bg-gray-700"
                :class="{'left-64': sidebarOpen}">
            <span x-show="!sidebarOpen" class="text-xs font-bold">>></span>
            <span x-show="sidebarOpen" class="text-xs font-bold"><<</span>
        </button>

        <!-- Sidebar - Mobile Overlay -->
        <div x-show="sidebarOpen && window.innerWidth < 768" 
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden transition-opacity duration-300">
        </div>

        <!-- Sidebar Component -->
        <x-sidebar />

        <div class="flex-1 p-6 overflow-y-auto ml-0 transition-all duration-300">
            <div class="min-h-screen bg-gray-100" x-data="dashboard()" x-init="init()">
                <div class="container mx-auto px-4 py-8">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-8">
                        <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
                        <div class="text-sm text-gray-500" x-text="currentDateTime"></div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Total Revenue -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500">Orders</p>
                                    <p class="text-2xl font-bold" x-text="orderStats ? orderStats.current_month.count : 'Loading...'"></p>
                                </div>
                                <div class="p-3 rounded-full bg-green-100 text-green-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4">
                                <span class="text-sm font-semibold" 
                                      :class="{
                                          'text-green-500': orderStats && orderStats.percentage_changes.count >= 0,
                                          'text-red-500': orderStats && orderStats.percentage_changes.count < 0
                                      }"
                                      x-text="orderStats ? (orderStats.percentage_changes.count >= 0 ? '+' : '') + orderStats.percentage_changes.count + '%' : ''">
                                </span>
                                <span class="text-gray-500 text-sm ml-2" 
                                      x-text="orderStats ? orderStats.current_month.count + ' orders this month' : ''">
                                </span>
                            </div>
                        </div>

                        <!-- Revenue Card -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500">Revenue</p>
                                    <p class="text-2xl font-bold" x-text="orderStats ? formatCurrency(orderStats.current_month.revenue) : 'Loading...'"></p>
                                </div>
                                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4">
                                <span class="text-sm font-semibold" 
                                      :class="{
                                          'text-green-500': orderStats && orderStats.percentage_changes.revenue >= 0,
                                          'text-red-500': orderStats && orderStats.percentage_changes.revenue < 0
                                      }"
                                      x-text="orderStats ? (orderStats.percentage_changes.revenue >= 0 ? '+' : '') + orderStats.percentage_changes.revenue + '%' : ''">
                                </span>
                                <span class="text-gray-500 text-sm ml-2" 
                                      x-text="orderStats ? formatCurrency(orderStats.previous_month.revenue) + ' last month' : ''">
                                </span>
                            </div>
                        </div>
                        
                        <!-- Customers Card -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500">Kupci</p>
                                    <p class="text-2xl font-bold" x-text="userStats ? (userStats.current_month + userStats.previous_month) : 'Loading...'"></p>
                                </div>
                                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4">
                                <span class="text-sm font-semibold" 
                                      :class="{
                                          'text-green-500': userStats && userStats.percentage_change >= 0,
                                          'text-red-500': userStats && userStats.percentage_change < 0
                                      }"
                                      x-text="userStats ? (userStats.percentage_change >= 0 ? '+' : '') + userStats.percentage_change + '%' : 'Loading...'">
                                </span>
                                <span class="text-gray-500 text-sm ml-2" 
                                      x-text="userStats ? userStats.current_month + ' ovaj mjesec (' + userStats.previous_month + ' prošli mjesec)' : 'Loading...'">
                                </span>
                            </div>
                        </div>

                        <!-- Weather -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500">Vrijeme u Bihaću</p>
                                    <div class="flex items-center">
                                        <img :src="'https://cdn.weatherapi.com/weather/64x64/day/' + weather.icon + '.png'" 
                                             :alt="weather.condition" class="h-10 w-10">
                                        <p class="text-2xl font-bold ml-2" x-text="weather.temp + '°C'"></p>
                                    </div>
                                </div>
                                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-2">
                                <p class="text-gray-600 capitalize" x-text="weather.condition"></p>
                                <div class="flex flex-wrap gap-x-4 text-sm text-gray-500">
                                    <span x-text="'Vlažnost: ' + weather.humidity + '%'"></span>
                                    <span x-text="'Vjetar: ' + weather.wind_kph + ' km/h'"></span>
                                    <span x-text="'Osjećaj kao: ' + weather.feels_like + '°C'"></span>
                                </div>
                                <p class="text-xs text-gray-400 mt-2" x-text="'Osvježeno: ' + weather.last_updated"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Latest Orders -->
                        <div class="lg:col-span-2 bg-white rounded-lg shadow overflow-hidden flex flex-col" style="max-height: 50vh;">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-semibold text-gray-800">Narudžbe</h2>
                                
                                <a href="dashboard/orders" class="text-sm text-blue-600 hover:text-blue-800 whitespace-nowrap">Vidi sve</a>
                            </div>
                            <div class="divide-y divide-gray-200 overflow-y-auto flex-1">
                                <template x-for="order in latestOrders.data" :key="order.id">
                                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150">
                                        <div class="flex items-center justify-between">
                                            <div class="min-w-0">
                                                <p class="font-medium text-gray-800 truncate" x-text="'Order #' + order.order_number"></p>
                                                <p class="text-sm text-gray-500 truncate" x-text="order.shipping_address"></p>
                                                <div class="mt-1 space-y-1">
                                                    <template x-for="item in order.items" :key="item.id">
                                                        <div class="text-sm text-gray-600 truncate">
                                                            <span x-text="item.product.name"></span> ×
                                                            <span x-text="item.quantity"></span>
                                                            <span class="text-gray-400 ml-1" x-text="'$' + item.price"></span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                            <div class="text-right pl-4 flex-shrink-0">
                                                <p class="font-medium" x-text="'$' + order.total_price"></p>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1" 
                                                    :class="{
                                                        'bg-green-100 text-green-800': order.status === 'poslano',
                                                        'bg-yellow-100 text-yellow-800': order.status === 'u obradi',
                                                        'bg-blue-100 text-blue-800': order.status === 'na čekanju',
                                                        'bg-red-100 text-red-500': order.status === 'cancelled'
                                                    }"
                                                    x-text="order.status.charAt(0).toUpperCase() + order.status.slice(1)">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-2 flex justify-between items-center">
                                            <p class="text-sm text-gray-500" x-text="new Date(order.created_at).toLocaleDateString()"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between items-center">
                                <div>
                                    <span class="text-sm text-gray-500">
                                        Prikaz <span x-text="latestOrders.data.length"></span> od <span x-text="latestOrders.total"></span> narudžbi
                                    </span>
                                </div>
                                <div class="flex space-x-2">
                                    <template x-if="latestOrders.current_page > 1">
                                        <button @click="fetchOrders(latestOrders.current_page - 1)" 
                                                class="px-3 py-1 bg-gray-200 rounded text-sm hover:bg-gray-300 transition">
                                            Prethodna
                                        </button>
                                    </template>
                                    <template x-if="latestOrders.current_page < latestOrders.last_page">
                                        <button @click="fetchOrders(latestOrders.current_page + 1)" 
                                                class="px-3 py-1 bg-gray-200 rounded text-sm hover:bg-gray-300 transition">
                                            Sljedeća
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="bg-white rounded-lg shadow overflow-hidden flex flex-col" style="max-height: 50vh;">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-semibold text-gray-800">Notifikacije</h2>
                            </div>
                            <div class="divide-y divide-gray-200 overflow-y-auto flex-1">
                                <template x-for="notification in recentActivities.unread" :key="notification.id">
                                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150 bg-blue-50">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <img x-show="notification.data.user_photo" 
                                                     :src="'/storage/' + notification.data.user_photo" 
                                                     class="h-10 w-10 rounded-full object-cover">
                                                <span x-show="!notification.data.user_photo" 
                                                      class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900" x-text="notification.data.message"></p>
                                                <div class="flex justify-between items-center mt-1">
                                                    <p class="text-xs text-gray-500" x-text="new Date(notification.created_at).toLocaleString()"></p>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                        Novo
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                
                                <!-- Read Notifications -->
                                <template x-for="notification in recentActivities.read" :key="notification.id">
                                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <img x-show="notification.data.user_photo" 
                                                     :src="'/storage/' + notification.data.user_photo" 
                                                     class="h-10 w-10 rounded-full object-cover">
                                                <span x-show="!notification.data.user_photo" 
                                                      class="h-10 w-10 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="ml-3 flex-1">
                                                <p class="text-sm font-medium text-gray-900" x-text="notification.data.message"></p>
                                                <p class="text-xs text-gray-500 mt-1" x-text="new Date(notification.created_at).toLocaleString()"></p>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
                                <span class="text-sm text-gray-500">
                                    <span x-text="recentActivities.unread_count"></span> nepročitanih notifikacija
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dashboard', () => ({
            currentDateTime: '',
            userStats: null,
            orderStats: null,
            stats: {
                total_revenue: 0,
                revenue_change: 0,
                new_orders: 0,
                order_change: 0,
            },
            weather: {
                temp: 0,
                condition: '',
                icon: '',
                humidity: 0,
                wind_kph: 0,
                feels_like: 0,
                last_updated: ''
            },
            latestOrders: {
                data: [],
                current_page: 1,
                last_page: 1,
                total: 0
            },
            recentActivities: [],

            init() {
                this.updateDateTime();
                setInterval(() => this.updateDateTime(), 60000);
                
                this.fetchOrderStats();
                this.fetchUserStats();
                this.fetchWeather();
                this.fetchOrders();
                this.fetchRecentActivities();
            },

            updateDateTime() {
                const now = new Date();
                this.currentDateTime = now.toLocaleString('en-US', {
                    weekday: 'long',
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            },

            async fetchOrderStats() {
                try {
                    const response = await fetch('/api/order-stats');
                    this.orderStats = await response.json();
                } catch (error) {
                    console.error('Error fetching order stats:', error);
                    this.orderStats = {
                        current_month: { count: 0, revenue: 0 },
                        previous_month: { count: 0, revenue: 0 },
                        percentage_changes: { count: 0, revenue: 0 }
                    };
                }
            },
            
            formatCurrency(amount) {
                return new Intl.NumberFormat('bs-BA', {
                    style: 'currency',
                    currency: 'BAM'
                }).format(amount);
            },  
            
            async fetchUserStats() {
                try {
                    const response = await fetch('/api/user-stats');
                    this.userStats = await response.json();
                } catch (error) {
                    console.error('Error fetching user stats:', error);
                    this.userStats = {
                        current_month: 0,
                        previous_month: 0,
                        percentage_change: 0
                    };
                }
            },
            
            async fetchWeather() {
                try {
                    const response = await fetch('/api/dashboard/weather');
                    const data = await response.json();
                    this.weather = {
                        temp: Math.round(data.temp),
                        condition: data.condition,
                        icon: data.icon,
                        humidity: data.humidity,
                        wind_kph: data.wind_kph,
                        feels_like: data.feels_like,
                        last_updated: data.last_updated
                    };
                } catch (error) {
                    console.error('Error fetching weather:', error);
                    this.weather = {
                        temp: 12,
                        condition: 'Patchy rain nearby',
                        icon: '176',
                        humidity: 83,
                        wind_kph: 6.5,
                        feels_like: 12,
                        last_updated: new Date().toISOString()
                    };
                }
            },

            async fetchOrders(page = 1) {
                try {
                    const response = await fetch(`/api/dashboard/orders/?page=${page}`);
                    const data = await response.json();
                    this.latestOrders = {
                        data: data.data,
                        current_page: data.current_page,
                        last_page: data.last_page,
                        total: data.total
                    };
                } catch (error) {
                    console.error('Error fetching orders:', error);
                    this.latestOrders = {
                        data: [],
                        current_page: 1,
                        last_page: 1,
                        total: 0
                    };
                }
            },

            async fetchRecentActivities() {
                try {
                    const response = await fetch('/api/notifications');
                    const data = await response.json();
                    this.recentActivities = data;
                } catch (error) {
                    console.error('Error fetching recent activities:', error);
                    this.recentActivities = [];
                }
            }
        }));
    });
    </script>
</x-app-layout>