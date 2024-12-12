<x-app-layout>
    <div class="container mx-auto p-6 flex justify-center">
        <div class="card bg-white shadow-md rounded-lg w-full max-w-md overflow-hidden">
            <div class="card-body p-6">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4 text-center">Tambah Produk</h2>

                <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            class="input input-bordered w-full border-gray-300 rounded-md focus:ring-[#8B4513] focus:border-[#8B4513] text-sm" 
                            required 
                        >
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea 
                            name="description" 
                            id="description" 
                            class="textarea textarea-bordered w-full border-gray-300 rounded-md focus:ring-[#8B4513] focus:border-[#8B4513] text-sm" 
                            rows="3">
                        </textarea>
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                        <input 
                            type="number" 
                            name="price" 
                            id="price" 
                            class="input input-bordered w-full border-gray-300 rounded-md focus:ring-[#8B4513] focus:border-[#8B4513] text-sm" 
                            required 
                        >
                    </div>

                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                        <input 
                            type="number" 
                            name="stock" 
                            id="stock" 
                            class="input input-bordered w-full border-gray-300 rounded-md focus:ring-[#8B4513] focus:border-[#8B4513] text-sm" 
                            required 
                        >
                    </div>

                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Gambar</label>
                        <input 
                            type="file" 
                            name="image" 
                            id="image" 
                            class="input input-bordered w-full border-gray-300 rounded-md focus:ring-[#8B4513] focus:border-[#8B4513] text-sm" 
                            accept="image/*" 
                            required 
                        >
                    </div>

                    <div>
                        <button 
                            type="submit" 
                            class="w-full bg-[#8B4513] hover:bg-[#A0522D] text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8B4513]">
                            Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
