<x-layout-app :title="'Detail Transaksi'">

    <div class="mb-6">
        <a href="{{ route('pimpinan.laporan.index') }}" class="text-sm text-ink/50 hover:text-suds transition">&larr; Kembali ke Laporan</a>
        <div class="flex items-center gap-3 mt-2">
            <h2 class="font-display text-2xl font-bold text-ink">{{ $order->order_code }}</h2>
            @if ($order->order_status == 0)
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-sunbeam/20 text-[#8A6300]">Baru</span>
            @else
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-teal/10 text-teal">Sudah Diambil</span>
            @endif
        </div>
    </div>

    {{-- Info customer --}}
    <div class="bg-white rounded-2xl shadow-sm border border-ink/5 p-6 mb-6">
        <h3 class="font-display font-semibold text-ink mb-3">Info Customer</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-4">
            <div>
                <p class="text-ink/50">Nama</p>
                <p class="font-medium text-ink">{{ $order->customer->customer_name ?? 'Pelanggan Terhapus' }}</p>
            </div>
            <div>
                <p class="text-ink/50">Telepon</p>
                <p class="font-medium text-ink">{{ $order->customer->phone ?? '-' }}</p>
            </div>
            <div>
                <p class="text-ink/50">Alamat</p>
                <p class="font-medium text-ink">{{ $order->customer->address ?? '-' }}</p>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm pt-4 border-t border-dashed border-ink/10">
            <div>
                <p class="text-ink/50">Tanggal Masuk</p>
                <p class="font-medium text-ink">{{ \Carbon\Carbon::parse($order->order_date)->translatedFormat('d F Y') }}</p>
            </div>
            <div>
                <p class="text-ink/50">Tanggal Selesai</p>
                <p class="font-medium text-ink">{{ $order->order_end_date ? \Carbon\Carbon::parse($order->order_end_date)->translatedFormat('d F Y') : '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Rincian jasa --}}
    <div class="bg-white rounded-2xl shadow-sm border border-ink/5 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-cloud text-ink/60 text-left">
                    <th class="px-6 py-4 font-semibold">Jasa</th>
                    <th class="px-6 py-4 font-semibold">Qty (kg)</th>
                    <th class="px-6 py-4 font-semibold">Harga/kg</th>
                    <th class="px-6 py-4 font-semibold">Subtotal</th>
                    <th class="px-6 py-4 font-semibold">Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink/5">
                @foreach ($order->details as $detail)
                    <tr>
                        <td class="px-6 py-4 font-medium text-ink">{{ $detail->service->service_name }}</td>
                        <td class="px-6 py-4 text-ink/70">{{ $detail->qty }}</td>
                        <td class="px-6 py-4 text-ink/70 font-mono">Rp {{ number_format($detail->service->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-mono font-semibold text-teal">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-ink/50">{{ $detail->notes ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Kartu total, konsisten dengan halaman detail transaksi Operator --}}
    <div class="ticket-edge bg-gradient-to-r from-suds to-teal rounded-2xl p-6 pb-8 mt-6 text-white flex items-center justify-between max-w-sm ml-auto shadow-lg shadow-suds/20">
        <span class="font-medium">Total Bayar</span>
        <span class="font-mono text-2xl font-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
    </div>

</x-layout-app>