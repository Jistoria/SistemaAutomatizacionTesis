<?php

namespace App\Models\Auth;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\General\Menu;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens, HasUuids;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $id = Auth::id();
            $model->created_by_user = $id;
            $model->updated_by_user = $id;
        });

        static::updating(function ($model) {
            $id = Auth::id();
            $model->updated_by_user = $id;
        });
    }


    public $incrementing = false;  // Para deshabilitar auto-increment
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_login_at',
        'created_by_user',
        'updated_by_user',
        'deleted_by_user',
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function setSessionDetails()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->getRoleNames(),
        ];
    }


    public function menu()
    {
        $this->roles()->with('menus');
    }

}
