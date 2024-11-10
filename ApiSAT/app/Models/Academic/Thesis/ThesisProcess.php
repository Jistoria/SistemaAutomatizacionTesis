<?php

namespace App\Models\Academic\Thesis;

use App\Models\Academic\PeriodAcademic;
use App\Models\Academic\Student\Student;
use App\Models\Academic\Teacher\Teacher;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThesisProcess extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'thesis_process';

    protected $primaryKey = 'thesis_process_id';

    public $incrementing = false;

    protected $keyType = 'uuid';


    protected $fillable = [
        'teacher_id',
        'student_id',
        'thesis_id',
        'period_academic_id',
        'state_now',
        'date_start',
        'date_end',
        'created_by_user',
        'updated_by_user',
        'deleted_by_user',
        'deleted_at',
    ];

    // Relaciones

    // Relación con el estudiante
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    // Relación con el profesor (teacher)
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    // Relación con la tesis
    public function thesis()
    {
        return $this->belongsTo(ThesisTitle::class, 'thesis_id');
    }

    // Relación con el periodo académico
    public function periodAcademic()
    {
        return $this->belongsTo(PeriodAcademic::class, 'period_academic_id');
    }

    // Timestamps para created_by_user y updated_by_user
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_user');
    }

    public function phasesStudent()
    {
        return $this->hasMany(ThesisProcessPhases::class, 'thesis_process_id');
    }
}
