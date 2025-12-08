<?php

namespace App\Http\Controllers\Hrd;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\LeaveRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class HrdController extends Controller
{
    public function index()
    {
        $now        = Carbon::now();
        $startMonth = $now->copy()->startOfMonth();
        $endMonth   = $now->copy()->endOfMonth();

        $totalCutiBulanIni = LeaveRequest::whereBetween('tanggal_pengajuan', [
            $startMonth->toDateString(),
            $endMonth->toDateString(),
        ])->count();

        $pendingFinal = LeaveRequest::with([
                'user.profile',
                'leader',
            ])
            ->where(function ($q) {
                $q->where('status', 'Approved by Leader')
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'Pending')
                         ->whereHas('user', function ($u) {
                             $u->whereIn('role', ['Leader', 'Ketua Divisi']);
                         });
                  });
            })
            ->orderBy('tanggal_pengajuan', 'desc')
            ->get();

        $sedangCutiBulanIni = LeaveRequest::with([
                'user.profile',
            ])
            ->where('status', 'Approved')
            ->where(function ($q) use ($startMonth, $endMonth) {
                $q->where('tanggal_mulai', '<=', $endMonth->toDateString())
                  ->where('tanggal_selesai', '>=', $startMonth->toDateString());
            })
            ->orderBy('tanggal_mulai')
            ->get();

        $divisions = Division::with('ketuaDivisi')
            ->withCount('members')
            ->orderBy('nama_divisi')
            ->get();

        return view('hrd.dashboard', [
            'totalCutiBulanIni'  => $totalCutiBulanIni,
            'pendingFinal'       => $pendingFinal,
            'sedangCutiBulanIni' => $sedangCutiBulanIni,
            'divisions'          => $divisions,
        ]);
    }

    public function approvals(Request $request)
    {
        $statusFilter = $request->status;

        $query = LeaveRequest::with([
                'user.profile',
                'leader',
                'hrd',
            ])
            ->orderBy('tanggal_pengajuan', 'desc');

        if ($statusFilter === null || $statusFilter === '' || $statusFilter === 'pending') {
            $query->where(function ($q) {
                $q->where('status', 'Approved by Leader')
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'Pending')
                         ->whereHas('user', function ($u) {
                             $u->whereIn('role', ['Leader', 'Ketua Divisi']);
                         });
                  });
            });
        } elseif ($statusFilter === 'all') {
            // tampilkan semua, tanpa filter status
        } else {
            $query->where('status', $statusFilter);
        }

        $requests = $query->paginate(10)->withQueryString();

        return view('hrd.approvals.index', [
            'requests'     => $requests,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function process(Request $request, LeaveRequest $leaveRequest)
    {
        // kalau sudah final, jangan diproses lagi
        if (in_array($leaveRequest->status, ['Approved', 'Rejected', 'Cancelled'])) {
            return back()->with('error', 'Pengajuan ini sudah memiliki keputusan final.');
        }

        $action = $request->input('action');

        $rules = [
            'action' => 'required|in:approve,reject',
            'note'   => $action === 'reject'
                        ? 'required|string|min:10'
                        : 'nullable|string',
        ];

        $messages = [
            'note.required' => 'Catatan wajib diisi jika pengajuan ditolak.',
            'note.min'      => 'Catatan penolakan minimal 10 karakter.',
        ];

        $validated = $request->validate($rules, $messages);
        $hrd       = Auth::user();

        // === APPROVE ===
        if ($action === 'approve') {
            $leaveRequest->status = 'Approved';

            if (Schema::hasColumn('leave_requests', 'hrd_id')) {
                $leaveRequest->hrd_id = $hrd->id;
            }
            if (Schema::hasColumn('leave_requests', 'approved_hrd_at')) {
                $leaveRequest->approved_hrd_at = now();
            }

            $leaveRequest->save();

            return back()->with('success', 'Pengajuan cuti disetujui oleh HRD.');
        }

        // === REJECT ===
        // kembalikan kuota kalau jenis Tahunan
        $profile = optional($leaveRequest->user)->profile;
        if ($leaveRequest->jenis_cuti === 'Tahunan' && $profile) {
            $profile->kuota_cuti = ($profile->kuota_cuti ?? 0) + $leaveRequest->total_hari;
            $profile->save();
        }

        $leaveRequest->status = 'Rejected';

        if (Schema::hasColumn('leave_requests', 'hrd_id')) {
            $leaveRequest->hrd_id = $hrd->id;
        }
        if (Schema::hasColumn('leave_requests', 'approved_hrd_at')) {
            $leaveRequest->approved_hrd_at = now();
        }
        if (Schema::hasColumn('leave_requests', 'catatan_penolakan')) {
            $leaveRequest->catatan_penolakan = $validated['note'] ?? null;
        }

        $leaveRequest->save();

        return back()->with('success', 'Pengajuan cuti ditolak oleh HRD.');
    }

    public function bulkProcess(Request $request)
    {
        $action = $request->input('action');

        $rules = [
            'action'      => 'required|in:approve,reject',
            'selected'    => 'required|array|min:1',
            'selected.*'  => 'integer|exists:leave_requests,id',
            'note'        => $action === 'reject'
                                ? 'required|string|min:10'
                                : 'nullable|string',
        ];

        $messages = [
            'selected.required' => 'Pilih minimal satu pengajuan cuti.',
            'selected.min'      => 'Pilih minimal satu pengajuan cuti.',
            'note.required'     => 'Catatan wajib diisi jika pengajuan ditolak.',
            'note.min'          => 'Catatan penolakan minimal 10 karakter.',
        ];

        $validated = $request->validate($rules, $messages);

        $hrd    = Auth::user();
        $ids    = $validated['selected'];
        $note   = $validated['note'] ?? null;

        $leaves = LeaveRequest::with('user.profile')
            ->whereIn('id', $ids)
            ->get();

        $processedCount = 0;

        foreach ($leaves as $leaveRequest) {
            if (in_array($leaveRequest->status, ['Approved', 'Rejected', 'Cancelled'])) {
                continue;
            }

            if ($action === 'approve') {
                $leaveRequest->status = 'Approved';

                if (Schema::hasColumn('leave_requests', 'hrd_id')) {
                    $leaveRequest->hrd_id = $hrd->id;
                }
                if (Schema::hasColumn('leave_requests', 'approved_hrd_at')) {
                    $leaveRequest->approved_hrd_at = now();
                }
            } else {
                // REJECT (bulk)
                $profile = optional($leaveRequest->user)->profile;

                if ($leaveRequest->jenis_cuti === 'Tahunan' && $profile) {
                    $profile->kuota_cuti = ($profile->kuota_cuti ?? 0) + $leaveRequest->total_hari;
                    $profile->save();
                }

                $leaveRequest->status = 'Rejected';

                if (Schema::hasColumn('leave_requests', 'hrd_id')) {
                    $leaveRequest->hrd_id = $hrd->id;
                }
                if (Schema::hasColumn('leave_requests', 'approved_hrd_at')) {
                    $leaveRequest->approved_hrd_at = now();
                }
                if (Schema::hasColumn('leave_requests', 'catatan_penolakan')) {
                    $leaveRequest->catatan_penolakan = $note;
                }
            }

            $leaveRequest->save();
            $processedCount++;
        }

        $msg = $action === 'approve'
            ? "Berhasil menyetujui {$processedCount} pengajuan cuti."
            : "Berhasil menolak {$processedCount} pengajuan cuti.";

        return back()->with('success', $msg);
    }

    public function history(Request $request)
    {
        $status    = $request->status;
        $jenisCuti = $request->jenis_cuti;

        $query = LeaveRequest::with([
                'user.profile',
                'leader',
                'hrd',
            ])
            ->whereIn('status', ['Approved', 'Rejected', 'Cancelled'])
            ->orderBy('tanggal_pengajuan', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        if ($jenisCuti) {
            $query->where('jenis_cuti', $jenisCuti);
        }

        $leaves = $query->get();

        return view('hrd.history.index', [
            'leaves'    => $leaves,
            'status'    => $status,
            'jenisCuti' => $jenisCuti,
        ]);
    }

    public function reports(Request $request)
    {
        $status        = $request->status;
        $jenisCuti     = $request->jenis_cuti;
        $divisionId    = $request->division_id;
        $tanggalDari   = $request->tanggal_dari;
        $tanggalSampai = $request->tanggal_sampai;

        $query = LeaveRequest::with([
                'user.profile',
                'leader',
                'hrd',
            ])
            ->orderBy('tanggal_pengajuan', 'desc');

        if ($status) {
            $query->where('status', $status);
        }

        if ($jenisCuti) {
            $query->where('jenis_cuti', $jenisCuti);
        }

        if ($divisionId) {
            $query->whereHas('user.profile', function ($q) use ($divisionId) {
                $q->where('divisi_id', $divisionId);
            });
        }

        if ($tanggalDari && $tanggalSampai) {
            $query->whereBetween('tanggal_mulai', [$tanggalDari, $tanggalSampai]);
        }

        $leaves    = $query->get();
        $divisions = Division::orderBy('nama_divisi')->get();

        return view('hrd.reports.index', [
            'leaves'        => $leaves,
            'divisions'     => $divisions,
            'status'        => $status,
            'jenisCuti'     => $jenisCuti,
            'divisionId'    => $divisionId,
            'tanggalDari'   => $tanggalDari,
            'tanggalSampai' => $tanggalSampai,
        ]);
    }

    public function employees()
    {
        $employees = User::with('profile')
            ->orderBy('name')
            ->get();

        return view('hrd.employees.index', [
            'employees' => $employees,
        ]);
    }

    public function divisions()
    {
        $divisions = Division::with([
                'ketuaDivisi.profile',
                'members.user',
            ])
            ->withCount('members')
            ->orderBy('nama_divisi')
            ->get();

        return view('hrd.divisions.index', [
            'divisions' => $divisions,
        ]);
    }
}
