<?php

namespace App\Http\Controllers\Leader;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\LeaveRequest;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaderDashboardController extends Controller
{
    public function index()
    {
        $leader = Auth::user();

        // Divisi yang dipimpin leader
        $division = Division::where('ketua_divisi_id', $leader->id)->first();

        if (!$division) {
            return view('leader.dashboard', [
                'division'          => null,
                'pengajuanMasuk'    => 0,
                'pendingVerifikasi' => 0,
                'sedangCuti'        => 0,
                'sedangCutiList'    => collect(),
                'anggota'           => collect(),
            ]);
        }

        // Ambil anggota divisi berdasarkan UserProfile.divisi_id
        $anggotaProfiles = UserProfile::where('divisi_id', $division->id)
            ->with('user')
            ->get();

        $anggotaIds = $anggotaProfiles->pluck('user_id');

        // Bulan ini
        $startMonth = Carbon::now()->startOfMonth();
        $endMonth   = Carbon::now()->endOfMonth();

        // 1. Pengajuan Masuk Bulan Ini
        $pengajuanMasuk = LeaveRequest::whereIn('user_id', $anggotaIds)
            ->whereBetween('tanggal_pengajuan', [$startMonth, $endMonth])
            ->count();

        // 2. Pending Verifikasi oleh Leader
        $pendingVerifikasi = LeaveRequest::whereIn('user_id', $anggotaIds)
            ->where('status', 'Pending')
            ->count();

        // 3. Sedang Cuti Minggu Ini (count)
        $startWeek = Carbon::now()->startOfWeek(); // default: Senin
        $endWeek   = Carbon::now()->endOfWeek();   // Minggu

        $statusSedangCuti = ['Approved by Leader', 'Approved'];

        $sedangCutiQuery = LeaveRequest::whereIn('user_id', $anggotaIds)
            ->whereIn('status', $statusSedangCuti)
            ->where(function ($q) use ($startWeek, $endWeek) {
                $q->whereBetween('tanggal_mulai', [$startWeek, $endWeek])
                    ->orWhereBetween('tanggal_selesai', [$startWeek, $endWeek])
                    ->orWhere(function ($q2) use ($startWeek, $endWeek) {
                        $q2->where('tanggal_mulai', '<=', $startWeek)
                           ->where('tanggal_selesai', '>=', $endWeek);
                    });
            });

        $sedangCuti     = (clone $sedangCutiQuery)->count();
        $sedangCutiList = (clone $sedangCutiQuery)
            ->with('user')
            ->orderBy('tanggal_mulai')
            ->get();

        return view('leader.dashboard', [
            'division'          => $division,
            'anggota'           => $anggotaProfiles,
            'pengajuanMasuk'    => $pengajuanMasuk,
            'pendingVerifikasi' => $pendingVerifikasi,
            'sedangCuti'        => $sedangCuti,
            'sedangCutiList'    => $sedangCutiList,
        ]);
    }
}
