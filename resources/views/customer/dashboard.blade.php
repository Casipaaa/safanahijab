<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Customer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-4">
                            <form action="{{ route('customer.dashboard') }}" method="GET" class="flex">
                                <input type="text" name="search" placeholder="Cari produk..."
                                    value="{{ $search ?? '' }}" class="input input-bordered w-full max-w-xs mr-2">
                                <button type="submit"
                                    class="bg-[#8B4513] hover:bg-[#A0522D] text-white font-bold py-2 px-4 rounded">
                                    Cari
                                </button>
                            </form>
                        </div>

                        @php
                            $availableProducts = $products->where('stock', '>', 0);
                            $outOfStockProducts = $products->where('stock', 0);
                        @endphp

                        @if ($availableProducts->isNotEmpty())
                            <h3 class="text-2xl font-bold mb-4">Produk Tersedia</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
                                @foreach ($availableProducts as $product)
                                    <div class="card bg-base-200 shadow-md p-4">
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            class="w-full h-48 object-cover mb-4">

                                        <h3 class="text-lg font-bold">{{ $product->name }}</h3>
                                        <p class="text-gray-600">{{ Str::limit($product->description, 50) }}</p>
                                        <p class="text-xl font-semibold">Rp {{ number_format($product->price, 2) }}</p>
                                        <p class="text-sm">Stok Tersedia: {{ $product->stock }}</p>

                                        <div class="flex space-x-2 mt-4 items-center">
                                            <button onclick="addToCart({{ $product->id }})"
                                                class="bg-[#D2B48C] hover:bg-[#C2B280] text-white font-bold py-1 px-3 rounded">
                                                Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if ($outOfStockProducts->isNotEmpty())
                            <h3 class="text-2xl font-bold mb-4 text-red-600">Produk Habis</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach ($outOfStockProducts as $product)
                                    <div class="card bg-base-200 shadow-md p-4 relative opacity-50">
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                            alt="{{ $product->name }}"
                                            class="w-full h-48 object-cover mb-4 opacity-50">

                                        <h3 class="text-lg font-bold">{{ $product->name }}</h3>
                                        <p class="text-gray-600">{{ Str::limit($product->description, 50) }}</p>
                                        <p class="text-xl font-semibold">Rp {{ number_format($product->price, 2) }}</p>
                                        <p class="text-sm text-red-600">Stok: Habis</p>

                                        <div class="flex space-x-2 mt-4 items-center">
                                            <button
                                                class="bg-red-400 text-white font-bold py-1 px-3 rounded cursor-not-allowed"
                                                disabled>
                                                Stok Habis
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if ($products->isEmpty())
                            <div class="alert alert-warning shadow-lg">
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <span>
                                        {{ $search ? 'Tidak ada produk yang cocok dengan pencarian "' . $search . '"' : 'Tidak ada produk ditemukan' }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function addToCart(productId) {
        const quantity = 1;

        fetch(`/cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Produk berhasil ditambahkan ke keranjang!');
                } else {
                    alert('Gagal menambahkan produk ke keranjang.');
                }
            })
            .catch(error => console.error('Error:', error));
    }
</script>
