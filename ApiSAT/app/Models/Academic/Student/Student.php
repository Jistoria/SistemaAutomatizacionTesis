<?php

namespace App\Models\Academic\Student;

use App\Models\Academic\Degree;
use App\Models\Academic\Thesis\ThesisTitle as ThesisThesisTitle;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $primaryKey = 'student_id';
    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'student_id',
        'thesis_id',
        'degree_id',
        'dni',
        'enrollment_date',
    ];

    // Relación uno a uno con el usuario (user)
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'student_id');
    }

    // Relación con el modelo ThesisTitle
    public function thesis()
    {
        return $this->belongsTo(ThesisThesisTitle::class, 'thesis_id');
    }

    // Relación con el modelo Degree
    public function degree()
    {
        return $this->belongsTo(Degree::class, 'degree_id');
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

    //Relacion a Tutor


}
