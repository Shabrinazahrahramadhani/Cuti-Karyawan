<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $profile = $user->profile;

        $kuotaTotal    = 12;
        $sisaKuota     = $profile->kuota_cuti ?? $kuotaTotal;
        $kuotaTerpakai = max(0, $kuotaTotal - $sisaKuota);

        $totalPengajuan = LeaveRequest::where('user_id', $user->id)->count();

        $jumlahCutiSakit = LeaveRequest::where('user_id', $user->id)
            ->where('jenis_cuti', 'sakit')
            ->count();

        $division = $profile->division ?? null;
        $leader   = $division->ketuaDivisi ?? null;

        return view('user.dashboard', compact(
            'user',
            'profile',
            'kuotaTotal',
            'sisaKuota',
            'kuotaTerpakai',
            'totalPengajuan',
            'jumlahCutiSakit',
            'division',
            'leader'
        ));
    }

}
