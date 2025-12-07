<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\UserProfile;

class Division extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_divisi',
        'deskripsi',
        'ketua_divisi_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ketua_divisi_id' => 'integer',
    ];

    public function ketuaDivisi()
    {
        return $this->belongsTo(User::class, 'ketua_divisi_id');
    }

     public function leader()
    {
        return $this->belongsTo(User::class, 'ketua_divisi_id');
    }

    public function members()
    {
        return $this->hasMany(UserProfile::class, 'divisi_id');
    }

}
