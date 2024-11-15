<?php

namespace App\Models\Academic\Teacher;

use App\Models\Academic\Thesis\ThesisProcess;
use App\Models\Auth\User;
use App\Models\General\CategoryArea;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, HasUuids;


    protected $table = 'teachers';
    protected $primaryKey = 'teacher_id';
    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'teacher_id',
    ];

    // Relación uno a uno con el usuario (user)
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'teacher_id');
    }

    public function categoryAreas()
    {
        return $this->belongsToMany(CategoryArea::class, 'category_teacher', 'teacher_id', 'category_area_id');
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

    public function students_process()
    {
        return $this->hasMany(ThesisProcess::class, 'teacher_id', 'teacher_id');
    }

    public function isTutorOf($studentId)
    {
        return $this->students_process()->where('student_id', $studentId)->exists();
    }

}
