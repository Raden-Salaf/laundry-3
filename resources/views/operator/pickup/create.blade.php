<x-layout-app :title="'Proses Pengambilan'">

    <div class="mb-4">
        <a href="{{ route('operator.pickup.index') }}" class="text-decoration-none text-muted small">&larr; Kembali ke Pengambilan Laundry</a>
        <h3 class="fw-bold text-dark mt-2">Proses Pengambilan — {{ $order->order_code }}</h3>
    </div>

    {{-- Info customer --}}
    <div class="card shadow-sm border-0 rounded-4 p-4 mb-4 bg-white">
        <h5 class="fw-bold text-dark mb-3">Info Customer</h5>
        <div class="row g-3 text-sm">
            <div class="col-md-6">
                <span class="text-muted small d-block">Nama</span>
                <span class="fw-bold text-dark">{{ $order->customer->customer_name ?? 'Pelanggan Terhapus' }}</span>
            </div>
            <div class="col-md-6">
                <span class="text-muted small d-block">Telepon</span>
                <span class="fw-bold text-dark font-monospace">{{ $order->customer->phone ?? '-' }}</span>
            </div>
        </div>
    </div>

    {{-- Rincian jasa yang dilaundry --}}
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted">
                    <tr>
                        <th class="px-4 py-3 fw-semibold">Jasa</th>
                        <th class="px-4 py-3 fw-semibold">Qty (kg)</th>
                        <th class="px-4 py-3 fw-semibold">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->details as $detail)
                        <tr>
                            <td class="px-4 py-3 fw-bold text-dark">{{ $detail->service->service_name }}</td>
                            <td class="px-4 py-3 text-dark">{{ $detail->qty }}</td>
                            <td class="px-4 py-3 font-monospace fw-bold text-success">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Kartu total bayar --}}
    <div class="card shadow-sm border-0 rounded-4 bg-primary bg-gradient text-white p-4 mb-4" style="max-width: 320px;">
        <div class="d-flex justify-content-between align-items-center">
            <span class="fw-semibold text-white-50">Total Bayar</span>
            <span class="font-monospace fs-4 fw-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Form konfirmasi pengambilan --}}
    <div class="card shadow-sm border-0 rounded-4 p-4 bg-white" style="max-width: 600px;">
        <form method="POST" action="{{ route('operator.pickup.store', $order->id) }}" class="form-confirm-pickup">
            @csrf

            <div class="mb-4">
                <label class="form-label fw-semibold text-dark">Catatan Pengambilan (opsional)</label>
                <textarea name="notes" rows="3"
                    class="form-control rounded-3"
                    placeholder="Contoh: diambil oleh keluarga customer"></textarea>
            </div>

            <button type="submit" class="btn btn-success px-4 py-2.5 rounded-3 fw-semibold d-inline-flex align-items-center gap-2">
                ✓ Konfirmasi Pakaian Sudah Diambil
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.form-confirm-pickup');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (form.dataset.confirmed) {
                        return;
                    }
                    e.preventDefault();
                    Swal.fire({
                        title: 'Konfirmasi Pengambilan',
                        text: "Apakah pakaian benar-benar sudah diambil oleh customer?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Sudah Diambil!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.dataset.confirmed = true;
                            form.submit();
                        }
                    });
                });
            }
        });
    </script>

</x-layout-app>