<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class LeaveRequestController extends Controller
{
    public function create()
    {
        $user    = Auth::user();
        $profile = $user->profile;

        $sisaKuota = $profile->kuota_cuti ?? 0;

        return view('leave.create', compact('user', 'profile', 'sisaKuota'));
    }

    public function store(Request $request)
    {
        $user    = Auth::user();
        $profile = $user->profile;

        $rules = [
            'jenis_cuti'         => 'required|in:Tahunan,Sakit',
            'tanggal_mulai'      => 'required|date',
            'alasan'             => 'required|string|min:5',
            'alamat_selama_cuti' => 'nullable|string|max:255',
            'nomor_darurat'      => 'nullable|string|max:30',
        ];

        if ($request->jenis_cuti === 'Tahunan') {
            $rules['tanggal_selesai'] = 'required|date|after_or_equal:tanggal_mulai';
            $rules['surat_dokter']    = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048';
        } else {
            $rules['tanggal_selesai'] = 'nullable|date|after_or_equal:tanggal_mulai';
            $rules['surat_dokter']    = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
        }

        $validated = $request->validate($rules);

        $jenisCuti        = ucfirst(strtolower($validated['jenis_cuti']));
        $tanggalPengajuan = Carbon::today();
        $mulai            = Carbon::parse($validated['tanggal_mulai']);

        if (!empty($validated['tanggal_selesai'])) {
            $selesai = Carbon::parse($validated['tanggal_selesai']);
        } else {
            $selesai = $mulai->copy();
        }

        $totalHariKerja = $this->hitungHariKerja($mulai, $selesai);

        if ($totalHariKerja <= 0) {
            return back()->withInput()->withErrors([
                'tanggal_mulai' => 'Periode cuti harus mengandung minimal 1 hari kerja (Senin–Jumat).',
            ]);
        }

        // Validasi khusus Cuti Tahunan
        if ($jenisCuti === 'Tahunan') {
            if (!$profile) {
                return back()->withInput()->withErrors([
                    'jenis_cuti' => 'Data profil karyawan tidak ditemukan. Hubungi admin/HRD.',
                ]);
            }

            $sisaKuota = $profile->kuota_cuti ?? 0;

            if ($totalHariKerja > $sisaKuota) {
                return back()->withInput()->withErrors([
                    'jenis_cuti' => 'Sisa kuota cuti tahunan tidak mencukupi. Sisa kuota: ' . $sisaKuota . ' hari.',
                ]);
            }

            $minimalMulai = $tanggalPengajuan->copy()->addDays(3);
            if ($mulai->lt($minimalMulai)) {
                return back()->withInput()->withErrors([
                    'tanggal_mulai' => 'Cuti tahunan harus diajukan minimal H+3 dari hari ini (' . $minimalMulai->format('d M Y') . ').',
                ]);
            }

            if ($request->user()->profile->status_aktif == false) {
                return redirect()
                    ->route('dashboard')
                    ->with('error', 'Akun non-aktif, tidak bisa mengajukan cuti.');
            }
        }

        // Cek tabrakan dengan cuti lain yang sudah disetujui
        $adaTabrakan = LeaveRequest::where('user_id', $user->id)
            ->whereIn('status', ['Approved', 'Approved by Leader'])
            ->where(function ($q) use ($mulai, $selesai) {
                $q->whereBetween('tanggal_mulai', [$mulai, $selesai])
                    ->orWhereBetween('tanggal_selesai', [$mulai, $selesai])
                    ->orWhere(function ($q2) use ($mulai, $selesai) {
                        $q2->where('tanggal_mulai', '<=', $mulai)
                            ->where('tanggal_selesai', '>=', $selesai);
                    });
            })
            ->exists();

        if ($adaTabrakan) {
            return back()->withInput()->withErrors([
                'tanggal_mulai' => 'Periode cuti bertabrakan dengan cuti lain yang sudah disetujui.',
            ]);
        }

        // Upload surat dokter jika ada
        $suratDokterPath = null;
        if ($request->hasFile('surat_dokter')) {
            $suratDokterPath = $request->file('surat_dokter')->store('surat_dokter', 'public');
        }

        // =========================
        // STATUS AWAL & LEADER INFO
        // =========================

        // Anggap siapapun yang role-nya BUKAN 'user' (case-insensitive) adalah atasan (leader)
        $isLeader = (strtolower($user->role) !== 'user');

        $statusAwal       = $isLeader ? 'Approved by Leader' : 'Pending';
        $leaderId         = $isLeader ? $user->id : null;
        $approvedLeaderAt = $isLeader ? now() : null;
        $catatanLeader    = $isLeader ? 'Pengajuan cuti pribadi atasan (auto-approve).' : null;

        $data = [
            'user_id'            => $user->id,
            'jenis_cuti'         => $jenisCuti,
            'tanggal_pengajuan'  => $tanggalPengajuan,
            'tanggal_mulai'      => $mulai,
            'tanggal_selesai'    => $selesai,
            'total_hari'         => $totalHariKerja,
            'alasan'             => $validated['alasan'],
            'alamat_selama_cuti' => $validated['alamat_selama_cuti'] ?? null,
            'nomor_darurat'      => $validated['nomor_darurat'] ?? null,
            'status'             => $statusAwal,
        ];

        if ($suratDokterPath) {
            $data['surat_dokter'] = $suratDokterPath;
        }

        // isi kolom leader_* kalau ada
        if ($leaderId && Schema::hasColumn('leave_requests', 'leader_id')) {
            $data['leader_id'] = $leaderId;
        }
        if ($approvedLeaderAt && Schema::hasColumn('leave_requests', 'approved_leader_at')) {
            $data['approved_leader_at'] = $approvedLeaderAt;
        }
        if ($catatanLeader && Schema::hasColumn('leave_requests', 'catatan_leader')) {
            $data['catatan_leader'] = $catatanLeader;
        }

        LeaveRequest::create($data);

        // Potong kuota kalau tahunan
        if ($jenisCuti === 'Tahunan' && $profile) {
            $profile->kuota_cuti = max(0, ($profile->kuota_cuti ?? 0) - $totalHariKerja);
            $profile->save();
        }

        return redirect()
            ->route('user.leave.history')
            ->with('success', 'Pengajuan cuti berhasil dikirim dan menunggu persetujuan.');
    }

    public function history()
    {
        $user = Auth::user();

        $leaves = LeaveRequest::where('user_id', $user->id)
            ->latest('tanggal_mulai')
            ->paginate(10);

        $profile   = $user->profile;
        $sisaKuota = $profile->kuota_cuti ?? 0;

        return view('leave.history', compact('leaves', 'sisaKuota'));
    }

    public function cancel(Request $request, LeaveRequest $leaveRequest)
    {
        $user = Auth::user();

        if ($leaveRequest->user_id !== $user->id) {
            abort(403, 'Anda tidak berhak membatalkan pengajuan ini.');
        }

        if (strcasecmp($leaveRequest->status, 'Pending') !== 0) {
            return back()->with('error', 'Pengajuan hanya dapat dibatalkan jika status masih Pending.');
        }

        $request->validate([
            'alasan_pembatalan' => 'required|string|min:5',
        ]);

        $alasanPembatalan = $request->alasan_pembatalan;

        if (strcasecmp($leaveRequest->jenis_cuti, 'Tahunan') === 0 && $leaveRequest->total_hari) {
            $profile = $user->profile;

            if ($profile) {
                $profile->kuota_cuti = ($profile->kuota_cuti ?? 0) + $leaveRequest->total_hari;
                $profile->save();
            }
        }

        $leaveRequest->status = 'Cancelled';

        if (Schema::hasColumn('leave_requests', 'alasan_pembatalan')) {
            $leaveRequest->alasan_pembatalan = $alasanPembatalan;
        }

        $leaveRequest->save();

        return redirect()
            ->route('user.leave.history')
            ->with('success', 'Pengajuan cuti berhasil dibatalkan. Alasan: ' . $alasanPembatalan);
    }

    protected function hitungHariKerja(Carbon $mulai, Carbon $selesai): int
    {
        $hari    = 0;
        $tanggal = $mulai->copy();

        while ($tanggal->lte($selesai)) {
            if ($tanggal->isWeekday()) {
                $hari++;
            }

            $tanggal->addDay();
        }

        return $hari;
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load([
            'user.profile.division',
            'leader',
            'hrd',
        ]);

        return view('leave.show', [
            'leave' => $leaveRequest,
        ]);
    }
}
