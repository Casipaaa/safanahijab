<x-app-layout>
    <div class="container mx-auto p-6 bg-white">
        <div class="card bg-white shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-3xl mb-4 text-[#8B4513] font-extrabold">Transaksi Admin</h2>

                <div class="flex justify-between items-center mb-6">
                    <form action="{{ route('admin.transaksi') }}" method="GET" class="flex items-center">
                        <select name="status"
                            class="form-select border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50 py-2 px-4"
                            onchange="this.form.submit()">
                            <option value="" disabled selected>Pilih Status</option>
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status
                            </option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>pending
                            </option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>completed
                            </option>
                            <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>canceled
                            </option>
                        </select>
                    </form>

                    <form action="{{ route('admin.transaksi') }}" method="GET" class="flex items-center">
                        <input type="text" name="search_id" placeholder="Cari oleh ID..."
                            value="{{ request('search_id') ?? '' }}"
                            class="border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50 w-full max-w-xs mr-4 py-2 px-4">
                        <button type="submit"
                            class="bg-gradient-to-r from-[#A0522D] to-[#8B4513] text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:shadow-xl hover:from-[#C08040] hover:to-[#A0522D] transition duration-300">
                            Cari
                        </button>
                    </form>
                </div>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <strong class="font-bold">Sukses!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @elseif (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif


                @if ($orders->isEmpty())
                    <p>Tidak ada hasil pencarian.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                        @foreach ($orders as $order)
                            <div
                                class="relative bg-white shadow-lg rounded-lg overflow-hidden border-t-4 border-[#A0522D] transition-transform transform hover:scale-105">
                                <div class="p-4">
                                    <h3 class="text-lg font-bold text-[#8B4513]">Order ID: {{ $order->id }}</h3>
                                    <p class="text-gray-500 mt-2">Total: <span class="font-semibold">Rp
                                            {{ number_format($order->total, 2) }}</span></p>
                                    <p class="text-gray-500">Tanggal:
                                        <span>{{ $order->created_at->format('d M Y') }}</span>
                                    </p>

                                    <div class="mt-4 flex justify-between items-center">
                                        <form action="{{ route('admin.update_status', $order->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status"
                                                class="form-select border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-opacity-50 py-2 px-4"
                                                {{ $order->status == 'completed' || $order->status == 'canceled' ? 'hidden' : '' }}
                                                onchange="this.form.submit()"
                                                {{ $order->status == 'completed' || $order->status == 'canceled' ? 'hidden' : '' }}>
                                                <option value="" hidden selected>Pilih Status</option>
                                                <option value="pending"
                                                    {{ $order->status == 'pending' ? 'selected' : '' }}
                                                    {{ $order->status == 'completed' || $order->status == 'canceled' ? 'hidden' : '' }}>
                                                    pending
                                                </option>
                                                <option value="completed"
                                                    {{ $order->status == 'completed' ? 'selected' : '' }}
                                                    {{ $order->status == 'completed' || $order->status == 'canceled' ? 'hidden' : '' }}>
                                                    completed
                                                </option>
                                                <option value="canceled"
                                                    {{ $order->status == 'canceled' ? 'selected' : '' }}
                                                    {{ $order->status == 'completed' || $order->status == 'canceled' ? 'hidden' : '' }}>
                                                    canceled
                                                </option>
                                            </select>
                                        </form>

                                        <!-- Tombol Detail -->
                                        <button onclick="openModal({{ $order->id }})"
                                            class="bg-[#8B4513] text-white py-2 px-4 rounded-lg hover:bg-[#A0522D] transition duration-300">
                                            Lihat Detail
                                        </button>
                                    </div>

                                    @if ($order->status == 'completed')
                                        ✅
                                    @elseif ($order->status == 'canceled')
                                        ❌
                                    @endif

                                    <span
                                        class="badge absolute top-4 right-4 
                                        {{ $order->status == 'pending' ? 'bg-yellow-400 text-gray-800' : '' }}
                                        {{ $order->status == 'completed' ? 'bg-green-400 text-gray-800' : '' }}
                                        {{ $order->status == 'canceled' ? 'bg-red-400 text-gray-800' : '' }}
                                        rounded-full px-3 py-1 text-sm font-semibold">
                                        {{ $order->status }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Detail Order -->
    <div id="orderDetailModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 w-full h-full">
        <div class="bg-white rounded-lg shadow-xl w-1/2 max-h-[80%] overflow-y-auto p-6 relative">
            <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <h2 class="text-xl font-bold mb-4 text-[#8B4513]">Detail Pesanan</h2>

            <div id="orderDetailContent" class="space-y-2">
                <!-- Konten detail pesanan akan diisi secara dinamis -->
            </div>

            <div class="mt-4 border-t pt-4">
                <p class="font-bold text-right">Total: <span id="modalTotalOrder"></span></p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openModal(orderId) {
                fetch(`/admin/transaksi/detail/${orderId}`)
                    .then(response => response.json())
                    .then(data => {
                        const content = document.getElementById('orderDetailContent');
                        const totalSpan = document.getElementById('modalTotalOrder');

                        content.innerHTML = '';

                        data.details.forEach(detail => {
                            const detailItem = document.createElement('div');
                            detailItem.classList.add('flex', 'justify-between', 'border-b', 'pb-2');
                            detailItem.innerHTML = `
                            <div>
                                <p class="font-semibold">${detail.product.name}</p>
                                <p class="text-sm text-gray-500">
                                    ${detail.quantity} x Rp ${detail.price.toLocaleString()}
                                </p>
                            </div>
                            <p class="font-semibold">
                                Rp ${(detail.quantity * detail.price).toLocaleString()}
                            </p>
                        `;
                            content.appendChild(detailItem);
                        });

                        totalSpan.textContent = `Rp ${data.total.toLocaleString()}`;

                        document.getElementById('orderDetailModal').classList.remove('hidden');
                        document.getElementById('orderDetailModal').classList.add('flex');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal memuat detail pesanan');
                    });
            }

            function closeModal() {
                document.getElementById('orderDetailModal').classList.remove('flex');
                document.getElementById('orderDetailModal').classList.add('hidden');
            }
        </script>
    @endpush
</x-app-layout>
