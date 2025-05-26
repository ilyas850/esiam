<?php

namespace App;

use App\Models\Student;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
        if ($this->role == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function isDosen()
    {
        if ($this->role == 2) {
            return true;
        } else {
            return false;
        }
    }

    public function isMhs()
    {
        if ($this->role == 3) {
            return true;
        } else {
            return false;
        }
    }

    public function isNoMhs()
    {
        if ($this->role == 4) {
            return true;
        } else {
            return false;
        }
    }

    public function isDosenluar()
    {
        if ($this->role == 5) {
            return true;
        } else {
            return false;
        }
    }

    public function isKaprodi()
    {
        if ($this->role == 6) {
            return true;
        } else {
            return false;
        }
    }

    public function isWadir1()
    {
        if ($this->role == 7) {
            return true;
        } else {
            return false;
        }
    }

    public function isBauk()
    {
        if ($this->role == 8) {
            return true;
        } else {
            return false;
        }
    }

    public function isAdminprodi()
    {
        if ($this->role == 9) {
            return true;
        } else {
            return false;
        }
    }

    public function isWadir3()
    {
        if ($this->role == 10) {
            return true;
        } else {
            return false;
        }
    }

    public function isPrausta()
    {
        if ($this->role == 11) {
            return true;
        } else {
            return false;
        }
    }

    public function isGugusMutu()
    {
        if ($this->role == 12) {
            return true;
        } else {
            return false;
        }
    }

    // Relasi ke Student
    public function student()
    {
        return $this->hasOne(Student::class, 'idstudent', 'id_user');
    }
}
