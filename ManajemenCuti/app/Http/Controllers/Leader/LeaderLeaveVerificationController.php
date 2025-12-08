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

        // cari divisi yang dipimpin leader ini
        $division = Division::where('ketua_divisi_id', $leader->id)->first();

        if (!$division) {
            return view('leader.verifications.index', [
                'division' => null,
                'requests' => collect(),
            ]);
        }

        // ambil anggota divisi, TAPI exclude leader sendiri
        $anggotaIds = UserProfile::where('divisi_id', $division->id)
            ->where('user_id', '!=', $leader->id)
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

        // CEK: leader tidak boleh memproses pengajuan dirinya sendiri
        if ($leaveRequest->user_id === $leader->id) {
            return back()->with('error', 'Leader tidak dapat menyetujui pengajuan cutinya sendiri. Pengajuan ini akan diproses oleh HRD.');
        }

        if (!$this->bolehMemproses($leader->id, $leaveRequest)) {
            abort(403, 'Anda tidak berwenang memproses pengajuan ini.');
        }

        // siapkan data update
        $updateData = [
            'status'    => 'Approved by Leader',
            'leader_id' => $leader->id,
        ];

        if (Schema::hasColumn('leave_requests', 'approved_leader_at')) {
            $updateData['approved_leader_at'] = now();
        }

        $leaveRequest->update($updateData);

        return back()->with('success', 'Pengajuan cuti disetujui oleh Leader.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $leader = Auth::user();

        $request->validate([
            'note' => 'required|string|min:5',
        ]);

        // CEK: leader tidak boleh memproses pengajuan dirinya sendiri
        if ($leaveRequest->user_id === $leader->id) {
            return back()->with('error', 'Leader tidak dapat menolak pengajuan cutinya sendiri. Pengajuan ini akan diproses oleh HRD.');
        }

        if (!$this->bolehMemproses($leader->id, $leaveRequest)) {
            abort(403, 'Anda tidak berwenang memproses pengajuan ini.');
        }

        $updateData = [
            'status'    => 'Rejected',
            'leader_id' => $leader->id,
        ];

        if (Schema::hasColumn('leave_requests', 'approved_leader_at')) {
            $updateData['approved_leader_at'] = now();
        }

        if (Schema::hasColumn('leave_requests', 'catatan_penolakan')) {
            $updateData['catatan_penolakan'] = $request->note;
        }

        // kalau karyawan (bukan leader) ditolak dan jenis cuti tahunan → kuota dikembalikan
        $profile = optional($leaveRequest->user)->profile;
        if ($leaveRequest->jenis_cuti === 'Tahunan' && $profile) {
            $profile->kuota_cuti = ($profile->kuota_cuti ?? 0) + $leaveRequest->total_hari;
            $profile->save();
        }

        $leaveRequest->update($updateData);

        return back()->with('success', 'Pengajuan cuti ditolak dengan catatan.');
    }

    protected function bolehMemproses(int $leaderId, LeaveRequest $leaveRequest): bool
    {
        // 1) leader tidak boleh memproses cutinya sendiri
        if ($leaveRequest->user_id === $leaderId) {
            return false;
        }

        $divisionLeader = Division::where('ketua_divisi_id', $leaderId)->first();

        if (!$divisionLeader) {
            return false;
        }

        $user        = $leaveRequest->user;
        $profileUser = $user?->profile;

        if ($profileUser && $profileUser->divisi_id) {
            // hanya boleh memproses anggota di divisinya sendiri
            return $profileUser->divisi_id === $divisionLeader->id;
        }

        // kalau user tidak punya divisi yang jelas, amankan saja: jangan boleh
        return false;
    }
}
