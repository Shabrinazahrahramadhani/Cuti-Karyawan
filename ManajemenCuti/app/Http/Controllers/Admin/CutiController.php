<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class CutiController extends Controller
{
    public function index(Request $request)
    {
        $query = LeaveRequest::with(['user.profile'])
            ->orderByDesc('created_at');

        if ($request->filled('jenis_cuti')) {
            $query->where('jenis_cuti', $request->jenis_cuti);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_pengajuan_from')) {
            $query->whereDate('created_at', '>=', $request->tanggal_pengajuan_from);
        }

        if ($request->filled('tanggal_pengajuan_to')) {
            $query->whereDate('created_at', '<=', $request->tanggal_pengajuan_to);
        }

        if ($request->filled('periode_from')) {
            $query->whereDate('tanggal_mulai', '>=', $request->periode_from);
        }

        if ($request->filled('periode_to')) {
            $query->whereDate('tanggal_selesai', '<=', $request->periode_to);
        }

        $leaveRequests = $query->paginate(15)->withQueryString();

        $summaryBase = clone $query;

        $summary = [
            'total'           => (clone $summaryBase)->count(),
            'pending'         => (clone $summaryBase)->where('status', 'pending')->count(),
            'approved_leader' => (clone $summaryBase)->where('status', 'approved_leader')->count(),
            'approved'        => (clone $summaryBase)->where('status', 'approved')->count(),
            'rejected'        => (clone $summaryBase)->where('status', 'rejected')->count(),
            'cancelled'       => (clone $summaryBase)->where('status', 'cancelled')->count(),
        ];

        return view('admin.cuti.index', compact('leaveRequests', 'summary'));
    }

    public function show(LeaveRequest $cuti)
    {
        $cuti->load(['user.profile.division', 'leader', 'hrd']);

        return view('admin.cuti.show', [
            'leave' => $cuti,
        ]);
    }
}
