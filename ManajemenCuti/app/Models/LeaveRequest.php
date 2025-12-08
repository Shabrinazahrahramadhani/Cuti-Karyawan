<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LeaveRequest extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'jenis_cuti',          // 'Tahunan' / 'Sakit'
        'tanggal_pengajuan',
        'tanggal_mulai',
        'tanggal_selesai',
        'total_hari',
        'alasan',
        'surat_dokter',        // <— sesuai migration
        'alamat_selama_cuti',         // <— sesuai migration
        'nomor_darurat',
        'status',              // 'Pending', 'Approved by Leader', ...
        'alasan_pembatalan',
        'catatan_penolakan',
        'approved_leader_at',
        'approved_hrd_at',
        'leader_id',
        'hrd_id',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_mulai'     => 'date',
        'tanggal_selesai'   => 'date',
        'approved_leader_at'=> 'datetime',
        'approved_hrd_at'   => 'datetime',
    ];

    protected static function booted()
    {
        static::saving(function (LeaveRequest $leave) {
        if (
                    $leave->status === 'Approved by Leader'
                    && $leave->user
                    && $leave->user->role === 'Leader'
                ) {
                    $leave->status = 'Pending';
                }
            });
    }



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvals()
    {
        return $this->hasMany(LeaveApproval::class);
    }

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function hrd()
    {
        return $this->belongsTo(User::class, 'hrd_id');
    }

    public function history()
    {
        $user = Auth::user();

          $leaveRequests = LeaveRequest::where('user_id', $user->id)
        ->latest('created_at')
        ->get();

        return view('leave.history', compact('leaveRequests'));
    }

}
