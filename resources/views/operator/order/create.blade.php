<x-layout-app :title="'Buat Transaksi Laundry'">

    <div class="mb-4">
        <a href="{{ route('operator.order.index') }}" class="text-decoration-none text-muted small">&larr; Kembali ke Transaksi Laundry</a>
        <h3 class="fw-bold text-dark mt-2">Buat Transaksi Baru</h3>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger rounded-3 mb-4">
            <ul class="m-0 pl-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('operator.order.store') }}">
        @csrf

        {{-- Card pilih customer --}}
        <div class="card shadow-sm border-0 rounded-4 p-4 mb-4 bg-white">
            <div class="mb-2">
                <label class="form-label fw-semibold text-dark">Customer</label>
                <select name="id_customer" required class="form-select rounded-3">
                    <option value="">-- Pilih Customer --</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->customer_name }} — {{ $customer->phone }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Card detail jasa --}}
        <div class="card shadow-sm border-0 rounded-4 p-4 mb-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark m-0">Detail Jasa</h5>
                <h5 class="m-0">Total: <span id="grand-total" class="font-monospace fw-bold text-success">Rp 0</span></h5>
            </div>

            <div id="service-rows" class="d-flex flex-column gap-3">
                {{-- Baris jasa pertama (template) --}}
                <div class="service-row row g-3 align-items-end bg-light rounded-3 p-3 border">
                    <div class="col-md-4 col-sm-12">
                        <label class="form-label text-muted small fw-semibold">Pilih Jasa</label>
                        <select name="services[]" required onchange="calculateRow(this)"
                            class="service-select form-select rounded-3">
                            <option value="">-- Pilih Jasa --</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                    {{ $service->service_name }} (Rp {{ number_format($service->price, 0, ',', '.') }}/kg)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <label class="form-label text-muted small fw-semibold">Qty (kg)</label>
                        <input type="number" name="qty[]" step="0.1" min="0.1" placeholder="Qty (kg)" required oninput="calculateRow(this)"
                            class="qty-input form-control rounded-3">
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label class="form-label text-muted small fw-semibold">Catatan (opsional)</label>
                        <input type="text" name="notes[]" placeholder="Catatan"
                            class="form-control rounded-3">
                    </div>
                    <div class="col-md-3 col-sm-12 d-flex justify-content-between align-items-center">
                        <div>
                            <label class="form-label text-muted small fw-semibold d-block">Subtotal</label>
                            <span class="row-subtotal fw-bold text-dark font-monospace">Rp 0</span>
                        </div>
                        <button type="button" onclick="removeRow(this)" class="btn btn-outline-danger btn-sm border-0 rounded-circle" style="width: 32px; height: 32px; padding: 0;" title="Hapus baris">✕</button>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="button" onclick="addServiceRow()" class="btn btn-outline-primary rounded-3 btn-sm">
                    + Tambah Jasa Lain
                </button>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-semibold">
                Simpan Transaksi
            </button>
            <a href="{{ route('operator.order.index') }}" class="text-decoration-none text-muted fw-semibold small">Batal</a>
        </div>
    </form>

    <script>
        function calculateRow(el) {
            const row = el.closest('.service-row');
            const select = row.querySelector('.service-select');
            const qtyInput = row.querySelector('.qty-input');
            const subtotalEl = row.querySelector('.row-subtotal');

            const price = parseFloat(select.selectedOptions[0]?.dataset.price || 0);
            const qty = parseFloat(qtyInput.value || 0);
            const subtotal = price * qty;

            subtotalEl.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');

            updateGrandTotal();
        }

        function updateGrandTotal() {
            let total = 0;
            document.querySelectorAll('.service-row').forEach(row => {
                const select = row.querySelector('.service-select');
                const qtyInput = row.querySelector('.qty-input');
                const price = parseFloat(select.selectedOptions[0]?.dataset.price || 0);
                const qty = parseFloat(qtyInput.value || 0);
                total += price * qty;
            });
            document.getElementById('grand-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        function addServiceRow() {
            const container = document.getElementById('service-rows');
            const newRow = container.children[0].cloneNode(true);

            newRow.querySelectorAll('select, input').forEach(el => el.value = '');
            newRow.querySelector('.row-subtotal').textContent = 'Rp 0';

            container.appendChild(newRow);
        }

        function removeRow(btn) {
            const container = document.getElementById('service-rows');
            if (container.children.length > 1) {
                btn.closest('.service-row').remove();
                updateGrandTotal();
            } else {
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Minimal harus ada 1 jasa dalam transaksi.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            }
        }
    </script>

</x-layout-app>