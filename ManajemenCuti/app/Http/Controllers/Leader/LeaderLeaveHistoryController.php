<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\LeaveRequest;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaderLeaveHistoryController extends Controller
{
    public function index(Request $request)
    {
        $leader = Auth::user();

        $division = Division::where('ketua_divisi_id', $leader->id)->first();

        if (!$division) {
            return view('leader.history', [
                'division' => null,
                'requests' => collect(),
                'statusFilter' => null,
                'jenisFilter' => null,
            ]);
        }

        $anggotaIds = UserProfile::where('divisi_id', $division->id)
            ->pluck('user_id');

        $statusFilter = $request->query('status');
        $jenisFilter  = $request->query('jenis_cuti');

        $query = LeaveRequest::with('user')
            ->whereIn('user_id', $anggotaIds)
            ->orderBy('tanggal_pengajuan', 'desc');

        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($jenisFilter && $jenisFilter !== 'all') {
            $query->where('jenis_cuti', $jenisFilter);
        }

        $requests = $query->paginate(10)->withQueryString();

        return view('leader.history', [
            'division'      => $division,
            'requests'      => $requests,
            'statusFilter'  => $statusFilter,
            'jenisFilter'   => $jenisFilter,
        ]);
    }
}
