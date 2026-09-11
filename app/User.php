<?php

namespace App;

use App\Models\Student;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'password',
        'id_user',
        'role',
        'kodeprodi'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isSadmin()
    {
        return $this->role == 1 || $this->hasRole('sadmin');
    }

    public function isDosen()
    {
        return $this->role == 2 || $this->hasRole('dosen');
    }

    public function isMhs()
    {
        return $this->role == 3 || $this->hasRole('mhs');
    }

    public function isNoMhs()
    {
        return $this->role == 4 || $this->hasRole('no_mhs');
    }

    public function isDosenluar()
    {
        return $this->role == 5 || $this->hasRole('dosen_luar');
    }

    public function isKaprodi()
    {
        return $this->role == 6 || $this->hasRole('kaprodi');
    }

    public function isWadir1()
    {
        return $this->role == 7 || $this->hasRole('wadir1');
    }

    public function isBauk()
    {
        return $this->role == 8 || $this->hasRole('bauk');
    }

    public function isAdminprodi()
    {
        return $this->role == 9 || $this->hasRole('admin_prodi');
    }

    public function isWadir3()
    {
        return $this->role == 10 || $this->hasRole('wadir3');
    }

    public function isPrausta()
    {
        return $this->role == 11 || $this->hasRole('prausta');
    }

    public function isGugusMutu()
    {
        return $this->role == 12 || $this->hasRole('gugus_mutu');
    }

    public function isYayasan()
    {
        return $this->hasRole('yayasan');
    }

    // Relasi ke Student
    public function student()
    {
        return $this->hasOne(Student::class, 'idstudent', 'id_user');
    }
}
