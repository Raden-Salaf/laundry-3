<x-layout-app :title="'Detail Transaksi'">

    <div class="mb-6">
        <a href="{{ route('pimpinan.laporan.index') }}" class="text-sm text-ink/50 hover:text-suds transition">&larr;
            Kembali ke Laporan</a>
        <div class="mt-4 rounded-3xl bg-white border border-ink/10 p-6 shadow-sm">
            <div class="d-flex flex-column gap-4 sm:d-flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-display text-3xl font-bold text-ink">{{ $order->order_code }}</h2>
                    <p class="mt-2 text-sm text-ink/60">Detail transaksi laundry, status pengambilan, dan status
                        pembayaran.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @if ($order->order_status == 0)
                        <span class="badge bg-warning text-dark px-3 py-1 rounded-pill text-xs fw-semibold">Baru</span>
                    @else
                        <span class="badge bg-info text-white px-3 py-1 rounded-pill text-xs fw-semibold">Sudah
                            Diambil</span>
                    @endif
                    @if ($order->order_pay > 0)
                        <span class="badge bg-success text-white px-3 py-1 rounded-pill text-xs fw-semibold">Lunas</span>
                    @else
                        <span class="badge bg-warning text-dark px-3 py-1 rounded-pill text-xs fw-semibold">Belum
                            Dibayar</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.8fr_1fr]">
        <div class="space-y-6">
            <section class="bg-white rounded-2xl border border-ink/10 shadow-sm overflow-hidden">
                <div class="p-6 border-bottom border-ink/10">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <div>
                            <h3 class="font-display text-xl font-semibold text-ink mb-1">Info Customer</h3>
                            <p class="text-sm text-ink/60">Data pelanggan dan tanggal transaksi.</p>
                        </div>
                        <span class="badge bg-cloud text-ink px-3 py-2 rounded-pill text-xs fw-semibold">Dibuat pada
                            {{ \Carbon\Carbon::parse($order->order_date)->translatedFormat('d F Y') }}</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="rounded-3xl border border-ink/10 p-4 h-100">
                                <p class="text-ink/50 text-xs mb-2">Nama</p>
                                <p class="mb-0 fw-semibold text-ink">
                                    {{ $order->customer->customer_name ?? 'Pelanggan Terhapus' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="rounded-3xl border border-ink/10 p-4 h-100">
                                <p class="text-ink/50 text-xs mb-2">Telepon</p>
                                <p class="mb-0 fw-semibold text-ink">{{ $order->customer->phone ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="rounded-3xl border border-ink/10 p-4 h-100">
                                <p class="text-ink/50 text-xs mb-2">Alamat</p>
                                <p class="mb-0 fw-semibold text-ink">{{ $order->customer->address ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="rounded-3xl border border-ink/10 p-4 h-100">
                                <p class="text-ink/50 text-xs mb-2">Tanggal Masuk</p>
                                <p class="mb-0 fw-semibold text-ink">
                                    {{ \Carbon\Carbon::parse($order->order_date)->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="rounded-3xl border border-ink/10 p-4 h-100">
                                <p class="text-ink/50 text-xs mb-2">Tanggal Selesai</p>
                                <p class="mb-0 fw-semibold text-ink">
                                    {{ $order->order_end_date ? \Carbon\Carbon::parse($order->order_end_date)->translatedFormat('d F Y') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-2xl border border-ink/10 shadow-sm overflow-hidden">
                <div class="p-6 border-bottom border-ink/10">
                    <h3 class="font-display text-xl font-semibold text-ink mb-1">Rincian Jasa</h3>
                    <p class="text-sm text-ink/60 mb-0">Detail item laundry setiap layanan.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-borderless mb-0">
                        <thead class="bg-cloud text-ink/70">
                            <tr>
                                <th class="py-3 px-4 text-start">Jasa</th>
                                <th class="py-3 px-4 text-end">Qty</th>
                                <th class="py-3 px-4 text-end">Harga/kg</th>
                                <th class="py-3 px-4 text-end">Subtotal</th>
                                <th class="py-3 px-4 text-start">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->details as $detail)
                                <tr class="border-bottom border-ink/10 align-middle">
                                    <td class="py-3 px-4 text-ink">{{ $detail->service->service_name }}</td>
                                    <td class="py-3 px-4 text-end text-ink/70">{{ $detail->qty }}</td>
                                    <td class="py-3 px-4 text-end font-mono text-ink/70">Rp
                                        {{ number_format($detail->service->price, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-end font-mono fw-semibold text-teal">Rp
                                        {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-start text-ink/60">{{ $detail->notes ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <aside class="space-y-6">
            <section class="bg-white rounded-2xl border border-ink/10 shadow-sm p-6">
                <div class="d-flex align-items-center justify-content-between mb-5 gap-3">
                    <div>
                        <p class="text-sm text-ink/60 text-uppercase mb-2">Ringkasan Pembayaran</p>
                        <h3 class="mb-0 text-3xl fw-bold text-ink">Rp {{ number_format($order->total, 0, ',', '.') }}
                        </h3>
                    </div>
                    <span
                        class="badge {{ $order->order_pay > 0 ? 'bg-success text-white' : 'bg-warning text-dark' }} px-3 py-2 rounded-pill text-xs fw-semibold">{{ $order->order_pay > 0 ? 'Lunas' : 'Belum Bayar' }}</span>
                </div>
                <div class="list-group list-group-flush">
                    <div
                        class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center border-bottom border-ink/10">
                        <span class="text-ink/60">Total Bayar</span>
                        <span class="fw-semibold">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                    <div
                        class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center border-bottom border-ink/10">
                        <span class="text-ink/60">Dibayar</span>
                        <span
                            class="fw-semibold">{{ $order->order_pay > 0 ? 'Rp ' . number_format($order->order_pay, 0, ',', '.') : '-' }}</span>
                    </div>
                    <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                        <span class="text-ink/60">Kembalian</span>
                        <span
                            class="fw-semibold">{{ $order->order_change > 0 ? 'Rp ' . number_format($order->order_change, 0, ',', '.') : '-' }}</span>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-2xl border border-ink/10 shadow-sm p-6">
                <h3 class="font-display text-xl font-semibold text-ink mb-3">Catatan</h3>
                <p class="text-sm text-ink/70 leading-6">Halaman ini menampilkan semua informasi penting transaksi.
                    Periksa status pembayaran dan catatan layanan sebelum menyetujui laporan.</p>
            </section>
        </aside>
    </div>

</x-layout-app>