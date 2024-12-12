<x-app-layout>
    <div class="container mx-auto p-6 bg-white">
        <div class="card bg-white shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-3xl mb-4 text-[#8B4513] font-extrabold">Product Management</h2>

                <div class="flex justify-between items-center mb-6">
                    <a href="{{ route('admin.create_product') }}"
                        class="bg-gradient-to-r from-[#A0522D] to-[#8B4513] text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:shadow-xl hover:from-[#C08040] hover:to-[#A0522D] transition duration-300">
                        Tambah Produk
                    </a>

                    <form action="{{ route('admin.product') }}" method="GET" class="flex items-center">
                        <input type="text" name="search" placeholder="Cari produk..." value="{{ $search ?? '' }}"
                            class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#A0522D] focus:border-transparent w-full max-w-xs mr-4">
                        <button type="submit"
                            class="bg-gradient-to-r from-[#A0522D] to-[#8B4513] text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:shadow-xl hover:from-[#C08040] hover:to-[#A0522D] transition duration-300">
                            Cari
                        </button>
                    </form>
                </div>

                @if ($products->isEmpty())
                    <div class="alert bg-[#FFF8E7] border border-[#FFD700] text-[#A0522D] rounded-lg p-4 shadow-md">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 h-6 w-6 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>
                                {{ $search ? 'Tidak ada produk yang cocok dengan pencarian "' . $search . '"' : 'Tidak ada produk ditemukan' }}
                            </span>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
                    @foreach ($products as $product)
                        <div
                            class="relative bg-white shadow-lg rounded-lg overflow-hidden border-t-4 border-[#A0522D] transition-transform transform hover:scale-105">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h3 class="text-lg font-bold text-[#8B4513]">{{ $product->name }}</h3>
                                <p class="text-gray-500 mt-2">{{ Str::limit($product->description, 50) }}</p>
                                <p class="text-xl font-semibold text-[#8B4513] mt-4">Rp
                                    {{ number_format($product->price, 2) }}</p>
                                <p class="text-sm text-gray-600">Stock: {{ $product->stock }}</p>

                                <div class="flex space-x-4 mt-4">
                                    <a href="{{ route('admin.edit_product', $product->id) }}"
                                        class="bg-gradient-to-r from-[#C08040] to-[#A0522D] text-white font-semibold py-2 px-4 rounded-lg shadow hover:from-[#D19050] hover:to-[#C08040] transition duration-300">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.delete_product', $product->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-gradient-to-r from-[#C72C41] to-[#A52A2A] text-white font-semibold py-2 px-4 rounded-lg shadow hover:from-[#E03B50] hover:to-[#C72C41] transition duration-300">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($products->isEmpty())
                    <div
                        class="alert bg-[#FFF8E7] border border-[#FFD700] text-[#A0522D] rounded-lg p-4 shadow-md mt-6">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 h-6 w-6 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>No products found</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
