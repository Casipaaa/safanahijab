<x-app-layout>
    <div class="container mx-auto p-6 bg-white flex justify-center items-center ">
        <div class="card bg-white shadow-xl">
            <div class="card-body text-center">     
                <h2 class="card-title text-3xl mb-4 text-[#8B4513] font-extrabold text-center">Welcome to the Admin
                    Dashboard</h2>

                <p class="text-gray-600 text-lg mb-6">Manage your products and transactions easily with the links on the
                    sidebar.</p>

                <div class="flex justify-center space-x-4">     
                    <a href="{{ route('admin.create_product') }}"
                        class="bg-gradient-to-r from-[#A0522D] to-[#8B4513] text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:shadow-xl hover:from-[#C08040] hover:to-[#A0522D] transition duration-300">
                        Tambah Produk
                    </a>
                    <a href="{{ route('admin.transaksi') }}"
                        class="bg-gradient-to-r from-[#A0522D] to-[#8B4513] text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:shadow-xl hover:from-[#C08040] hover:to-[#A0522D] transition duration-300">
                        Transaksi
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
