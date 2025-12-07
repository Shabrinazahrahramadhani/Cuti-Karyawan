<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LeaveRequestController extends Controller
{
    public function create()
    {
        $user    = Auth::user();
        $profile = UserProfile::where('user_id', $user->id)->first();

        $sisaKuota = $profile->kuota_cuti ?? 0;

        return view('leave.create', [
            'user'      => $user,
            'profile'   => $profile,
            'sisaKuota' => $sisaKuota,
        ]);
    }

    public function store(Request $request)
    {
        $user    = Auth::user();
        $profile = UserProfile::where('user_id', $user->id)->first();

        $rules = [
            'jenis_cuti'      => ['required', Rule::in(['Tahunan', 'Sakit'])],
            'tanggal_mulai'   => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'alasan'          => ['required', 'string', 'max:500'],
            'alamat_cuti'     => ['nullable', 'string', 'max:255'],
            'nomor_darurat'   => ['nullable', 'string', 'max:50'],
        ];

        if ($request->jenis_cuti === 'Sakit') {
            $rules['surat_dokter'] = ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'];
        } else {
            $rules['surat_dokter'] = ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'];
        }

        $validated = $request->validate($rules);

        $tanggalPengajuan = Carbon::today();
        $mulai   = Carbon::parse($validated['tanggal_mulai'])->startOfDay();
        $selesai = Carbon::parse($validated['tanggal_selesai'])->startOfDay();

        if ($validated['jenis_cuti'] === 'Tahunan') {
            $minimalMulai = (clone $tanggalPengajuan)->addDays(3);

            if ($mulai->lt($minimalMulai)) {
                return back()
                    ->withErrors([
                        'tanggal_mulai' => 'Cuti tahunan harus diajukan minimal H-3 dari tanggal mulai cuti.',
                    ])
                    ->withInput();
            }
        }

        if ($validated['jenis_cuti'] === 'Sakit') {
            $maksPengajuan = (clone $mulai)->addDays(3);

            if ($tanggalPengajuan->lt($mulai) || $tanggalPengajuan->gt($maksPengajuan)) {
                return back()
                    ->withErrors([
                        'tanggal_mulai' => 'Untuk cuti sakit, pengajuan hanya boleh di H-0 sampai maksimal 3 hari setelah tanggal mulai sakit.',
                    ])
                    ->withInput();
            }
        }

        $totalHariKerja = $this->hitungHariKerja($mulai, $selesai);

        if ($totalHariKerja <= 0) {
            return back()
                ->withErrors([
                    'tanggal_selesai' => 'Range tanggal cuti tidak valid (tidak ada hari kerja di dalamnya).',
                ])
                ->withInput();
        }

        if ($validated['jenis_cuti'] === 'Tahunan') {
            $sisaKuota = $profile->kuota_cuti ?? 0;

            if ($totalHariKerja > $sisaKuota) {
                return back()
                    ->withErrors([
                        'tanggal_selesai' => 'Sisa kuota cuti tahunan tidak mencukupi. Sisa kuota: ' . $sisaKuota . ' hari.',
                    ])
                    ->withInput();
            }
        }

        $overlap = LeaveRequest::where('user_id', $user->id)
            ->whereIn('status', ['Pending', 'Approved by Leader', 'Approved'])
            ->where(function ($q) use ($mulai, $selesai) {
                $q->whereBetween('tanggal_mulai', [$mulai, $selesai])
                    ->orWhereBetween('tanggal_selesai', [$mulai, $selesai])
                    ->orWhere(function ($sub) use ($mulai, $selesai) {
                        $sub->where('tanggal_mulai', '<=', $mulai)
                            ->where('tanggal_selesai', '>=', $selesai);
                    });
            })
            ->exists();

        if ($overlap) {
            return back()
                ->withErrors([
                    'tanggal_mulai' => 'Periode cuti bertabrakan dengan pengajuan cuti lain yang sudah dibuat.',
                ])
                ->withInput();
        }

        $suratPath = null;
        if ($request->hasFile('surat_dokter')) {
            $suratPath = $request->file('surat_dokter')->store('surat_dokter', 'public');
        }

        LeaveRequest::create([
            'user_id'           => $user->id,
            'jenis_cuti'        => $validated['jenis_cuti'],
            'tanggal_pengajuan' => $tanggalPengajuan,
            'tanggal_mulai'     => $mulai,
            'tanggal_selesai'   => $selesai,
            'total_hari'        => $totalHariKerja,
            'alasan'            => $validated['alasan'],
            'alamat_selama_cuti'=> $validated['alamat_cuti'] ?? null,
            'nomor_darurat'     => $validated['nomor_darurat'] ?? null,
            'surat_dokter'      => $suratPath,
            'status'            => 'Pending',
        ]);

        if ($user->role === 'Leader') {
            return redirect()
                ->route('leader.dashboard')
                ->with('success', 'Pengajuan cuti sebagai Leader berhasil dibuat. Menunggu final approval HRD.');
        }

        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Pengajuan cuti berhasil dibuat. Menunggu verifikasi Ketua Divisi.');
    }

    public function cancel(Request $request, LeaveRequest $leave)
    {
        $user = Auth::user();

        if ($leave->user_id !== $user->id) {
            abort(403, 'Anda tidak berhak membatalkan pengajuan ini.');
        }

        if ($leave->status !== 'Pending') {
            return back()->with('error', 'Pengajuan hanya dapat dibatalkan jika masih berstatus Pending.');
        }

        $request->validate([
            'alasan_pembatalan' => 'required|string|min:5|max:255',
        ]);

        $leave->update([
            'status'             => 'Cancelled',
            'alasan_pembatalan'  => $request->alasan_pembatalan,
        ]);

        if ($user->role === 'Leader') {
            return redirect()
                ->route('leader.dashboard')
                ->with('success', 'Pengajuan cuti berhasil dibatalkan.');
        }

        return redirect()
            ->route('leave.history')
            ->with('success', 'Pengajuan cuti berhasil dibatalkan.');
    }

    protected function hitungHariKerja(Carbon $mulai, Carbon $selesai): int
    {
        $counter = 0;
        $current = (clone $mulai);

        while ($current->lte($selesai)) {
            if ($current->isWeekday()) {
                $counter++;
            }
            $current->addDay();
        }

        return $counter;
    }
}
