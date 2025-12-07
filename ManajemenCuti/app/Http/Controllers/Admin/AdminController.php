<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Division;
use App\Models\LeaveRequest;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $totalKaryawan = User::whereIn('role', ['User', 'Leader'])->count();

        $aktifKaryawan    = UserProfile::where('status_aktif', true)->count();
        $nonAktifKaryawan = UserProfile::where('status_aktif', false)->count();

        $totalDivisi = Division::count();

        $now = Carbon::now();
        $cutiBulanIni = LeaveRequest::whereMonth('tanggal_mulai', $now->month)
            ->whereYear('tanggal_mulai', $now->year)
            ->count();

        $pendingBulanIni = LeaveRequest::whereMonth('tanggal_mulai', $now->month)
            ->whereYear('tanggal_mulai', $now->year)
            ->where('status', 'Pending')
            ->count();

        $masaKerjaKurangSetahun = User::whereIn('role', ['User', 'Leader'])
            ->whereDate('created_at', '>=', $now->copy()->subYear())
            ->count();

        $karyawanTerbaru = User::with(['profile.division'])
            ->whereIn('role', ['User', 'Leader', 'Leader'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'totalKaryawan'          => $totalKaryawan,
            'aktifKaryawan'          => $aktifKaryawan,
            'nonAktifKaryawan'       => $nonAktifKaryawan,
            'totalDivisi'            => $totalDivisi,
            'cutiBulanIni'           => $cutiBulanIni,
            'pendingBulanIni'        => $pendingBulanIni,
            'masaKerjaKurangSetahun' => $masaKerjaKurangSetahun,
            'karyawanTerbaru'        => $karyawanTerbaru,
        ]);
    }
}
