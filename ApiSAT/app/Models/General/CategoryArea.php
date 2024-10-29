<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Auth\User;

class CategoryArea extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    // Indicar que la clave primaria es un UUID y no un entero autoincremental
    protected $primaryKey = 'category_area_id';
    public $incrementing = false;
    protected $keyType = 'uuid';

    // Especificar los campos que pueden ser asignados en masa
    protected $fillable = [
        'name',
        'description',
        'created_by_user',
        'updated_by_user',
        'deleted_by_user',
    ];

    // Relación con el modelo User para created_by_user
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user');
    }

    // Relación con el modelo User para updated_by_user
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_user');
    }

    // Relación con el modelo User para deleted_by_user
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by_user');
    }
}
