<?php

namespace App\Models\Academic\Teacher;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $primaryKey = 'teacher_id';
    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'teacher_id',
        'created_by_user',
        'updated_by_user',
        'deleted_by_user',
    ];

    // Relación uno a uno con el usuario (user)
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'teacher_id');
    }

    // Relación con el modelo User (creador)
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user');
    }

    // Relación con el modelo User (actualizador)
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_user');
    }

    // Relación con el modelo User (eliminador)
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by_user');
    }
}
