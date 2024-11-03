<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;

class Menu extends Model
{

    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'menus';

    protected $primaryKey = 'menu_id';

    protected $fillable = [
        'name',
        'url',
        'icon',
        'order',
    ];

    // public function subMenus()
    // {
    //     return $this->hasMany(SubMen::class, 'menu_id', 'menu_id');
    // }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'menu_role', 'menu_id', 'role_id', 'menu_id', 'uuid');
    }
}
