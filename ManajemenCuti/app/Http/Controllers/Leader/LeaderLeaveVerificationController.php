<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\LeaveRequest;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class LeaderLeaveVerificationController extends Controller
{
    public function index()
    {
        $leader = Auth::user();

        $division = Division::where('ketua_divisi_id', $leader->id)->first();

        if (!$division) {
            return view('leader.verifications.index', [
                'division' => null,
                'requests' => collect(),
            ]);
        }

        $anggotaIds = UserProfile::where('divisi_id', $division->id)
            ->pluck('user_id');

        $requests = LeaveRequest::with('user.profile')
            ->whereIn('user_id', $anggotaIds)
            ->where('status', 'Pending')
            ->orderBy('tanggal_pengajuan', 'desc')
            ->paginate(10);

        return view('leader.verifications.index', [
            'division' => $division,
            'requests' => $requests,
        ]);
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $leader = Auth::user();

        return view('leader.verifications.show', [
            'leader'       => $leader,
            'leaveRequest' => $leaveRequest,
        ]);
    }

    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $leader = Auth::user();

        $request->validate([
            'note' => 'nullable|string|max:500',
        ]);

        if (!$this->bolehMemproses($leader->id, $leaveRequest)) {
            abort(403, 'Anda tidak berwenang memproses pengajuan ini.');
        }

        $leaveRequest->update([
            'status'    => 'Approved by Leader',
            'leader_id' => $leader->id,
        ]);

        return back()->with('success', 'Pengajuan cuti disetujui oleh Leader.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $leader = Auth::user();

        $request->validate([
            'note' => 'required|string|min:5',
        ]);

        if (!$this->bolehMemproses($leader->id, $leaveRequest)) {
            abort(403, 'Anda tidak berwenang memproses pengajuan ini.');
        }

        $updateData = [
            'status'    => 'Rejected by Leader',
            'leader_id' => $leader->id,
        ];

        if (Schema::hasColumn('leave_requests', 'catatan_penolakan')) {
            $updateData['catatan_penolakan'] = $request->note;
        }

        $leaveRequest->update($updateData);

        return back()->with('success', 'Pengajuan cuti ditolak dengan catatan.');
    }

    protected function bolehMemproses(int $leaderId, LeaveRequest $leaveRequest): bool
    {
        $divisionLeader = Division::where('ketua_divisi_id', $leaderId)->first();

        if (!$divisionLeader) {
            return false;
        }

        $user = $leaveRequest->user;
        $profileUser = $user?->profile;

        if ($profileUser && $profileUser->divisi_id) {
            return $profileUser->divisi_id === $divisionLeader->id;
        }

        return true;
    }
}
