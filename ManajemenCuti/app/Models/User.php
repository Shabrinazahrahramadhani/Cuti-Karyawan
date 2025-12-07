<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\UserProfile;
use App\Models\Division;
use App\Models\LeaveRequest;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
     protected $fillable = [
        'name',
        'role',
        'email',
        'password',
        'foto',
        'alamat',
        'nomor_telepon',
        'divisi_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');

    }

        public function divisions()
    {
        return $this->hasMany(Division::class, 'ketua_divisi_id');
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function divisionLed()
    {
    return $this->hasOne(\App\Models\Division::class, 'ketua_divisi_id');
    }

    public function divisiPimpinan()
    {
    return $this->hasOne(Division::class, 'ketua_divisi_id');
    }


}

    



