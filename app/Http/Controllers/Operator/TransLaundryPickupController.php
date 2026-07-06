<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\TransLaundryPickup;
use App\Models\TransOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransLaundryPickupController extends Controller
{
    // Menampilkan daftar transaksi yang statusnya masih "baru" (belum diambil)
    // supaya Operator gampang mencari mana yang siap diproses pengambilannya
    public function index()
    {
        $orders = TransOrder::with('customer')
            ->where('order_status', 0) // hanya tampilkan yang belum diambil
            ->latest()
            ->paginate(10);

        return view('operator.pickup.index', compact('orders'));
    }

    // Menampilkan form konfirmasi pengambilan untuk 1 transaksi tertentu
    public function create(TransOrder $order)
    {
        // Jaga-jaga: kalau order ternyata sudah pernah diambil, tolak akses ke form ini
        if ($order->order_status == 1) {
            return redirect()->route('operator.pickup.index')->with('error', 'Transaksi ini sudah diambil sebelumnya.');
        }

        $order->load('customer', 'details.service');
        return view('operator.pickup.create', compact('order'));
    }

    // Memproses pengambilan: catat ke trans_laundry_pickup & update status trans_order jadi 1
    public function store(Request $request, TransOrder $order)
    {
        $request->validate([
            'notes' => 'nullable|string',
        ]);

        // Cegah double-process kalau ada yang submit form dua kali
        if ($order->order_status == 1) {
            return redirect()->route('operator.pickup.index')->with('error', 'Transaksi ini sudah diambil sebelumnya.');
        }

        // Gunakan transaction supaya insert pickup & update status order konsisten (atomic)
        DB::transaction(function () use ($request, $order) {

            // Catat riwayat pengambilan
            TransLaundryPickup::create([
                'id_order'    => $order->id,
                'id_customer' => $order->id_customer,
                'pickup_date' => now(), // tanggal & jam saat ini
                'notes'       => $request->notes,
            ]);

            // Update status order jadi 1 (sudah diambil) dan catat tanggal selesainya
            $order->update([
                'order_status'   => 1,
                'order_end_date' => now()->toDateString(),
            ]);
        });

        return redirect()->route('operator.pickup.index')->with('success', 'Pengambilan laundry berhasil dicatat.');
    }
}
