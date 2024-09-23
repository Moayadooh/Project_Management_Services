<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function roles() {
        return $this->belongsToMany(Role::class);
    }

    public function permissions() {
        return $this->belongsToMany(Permission::class);
    }

    public function hasRole($role) {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasPermission($permission) {
        return $this->permissions()->where('name', $permission)->exists();
    }

    public function hasPermissionTo($permission)
    {
        // Check if user has permission directly
        /*if ($this->hasPermission($permission)) {
            return true;
        }*/

        // Check if user has permission through roles
        foreach ($this->roles as $role) {
            if ($role->permissions()->where('name', $permission)->exists()) {
                return true;
            }
        }

        return false;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mobile_no',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'mobile_no_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
