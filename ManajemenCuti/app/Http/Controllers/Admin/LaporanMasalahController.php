<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanMasalahController extends Controller
{
    public function index(Request $request)
    {
        $baseQuery = LeaveRequest::with(['user.profile'])
            ->where(function ($q) {
                $q->whereIn('status', ['rejected', 'cancelled'])
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'pending')
                         ->whereDate('created_at', '<=', Carbon::now()->subDays(7));
                  });
            });

        if ($request->filled('jenis_cuti')) {
            $baseQuery->where('jenis_cuti', $request->jenis_cuti);
        }

        if ($request->filled('status')) {
            $baseQuery->where('status', $request->status);
        }

        if ($request->filled('tanggal_pengajuan_from')) {
            $baseQuery->whereDate('created_at', '>=', $request->tanggal_pengajuan_from);
        }

        if ($request->filled('tanggal_pengajuan_to')) {
            $baseQuery->whereDate('created_at', '<=', $request->tanggal_pengajuan_to);
        }

        if ($request->filled('periode_from')) {
            $baseQuery->whereDate('tanggal_mulai', '>=', $request->periode_from);
        }

        if ($request->filled('periode_to')) {
            $baseQuery->whereDate('tanggal_selesai', '<=', $request->periode_to);
        }

        $laporan = $baseQuery->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.laporan_masalah.index', compact('laporan'));
    }
}
