<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanHarian;
use App\Models\AuditLog;

class SupervisorController extends Controller
{
    public function approveLaporan($id)
    {
        $laporan = LaporanHarian::findOrFail($id);
        
        if ($laporan->selisih != 0) {
            return back()->with('error', 'Peringatan Supervisor: Terdapat selisih kas fisik sebesar Rp ' . number_format($laporan->selisih) . '. Laporan memerlukan penanganan selisih sebelum disetujui.');
        }

        $laporan->status_approval = 'approved';
        $laporan->supervisor_id = auth()->id();
        $laporan->save();

        AuditLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Approval Laporan Harian Teller',
            'detail' => 'Menyetujui laporan harian Teller ID ' . $laporan->user_id . ' tanggal ' . $laporan->tanggal,
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Laporan harian teller telah berhasil di-approve dan kas dipindahkan ke brankas utama.');
    }

    public function rejectLaporan(Request $request, $id)
    {
        $laporan = LaporanHarian::findOrFail($id);
        $laporan->status_approval = 'rejected';
        $laporan->supervisor_id = auth()->id();
        $laporan->save();

        AuditLog::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Penolakan Laporan Harian Teller',
            'detail' => 'Menolak laporan harian Teller ID ' . $laporan->user_id . ' tanggal ' . $laporan->tanggal,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Laporan harian ditolak. Teller diminta melakukan rekonsiliasi kas ulang.');
    }
}
