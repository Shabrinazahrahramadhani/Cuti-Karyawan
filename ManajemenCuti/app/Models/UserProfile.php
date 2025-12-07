<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Division;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'kuota_cuti',
        'status_aktif',
        'foto',
        'alamat',
        'nomor_telepon',
        'divisi_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
   
    public function division()
    {
        return $this->belongsTo(Division::class, 'divisi_id');
    }
}
 