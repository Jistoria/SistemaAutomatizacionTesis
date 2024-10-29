<?php

namespace App\Models\Academic\Teacher;

use App\Models\Academic\PeriodAcademic;
use App\Models\Academic\Student\Student;
use App\Models\Academic\Thesis\ThesisProcess;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThesisCommittee extends Model
{
    use HasFactory, SoftDeletes,HasUuids;

    protected $table = 'thesis_committee';

    protected $fillable = [
        'thesis_process_id',
        'student_id',
        'thesis_id',
        'period_academic_id',
        'teacher_id',
        'created_by_user',
        'updated_by_user',
        'deleted_by_user',
        'deleted_at',
    ];

    // Relaciones

    // Relación con el proceso de tesis
    public function thesisProcess()
    {
        return $this->belongsTo(ThesisProcess::class, 'thesis_process_id');
    }

    // Relación con el estudiante
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    // Relación con el periodo académico
    public function periodAcademic()
    {
        return $this->belongsTo(PeriodAcademic::class, 'period_academic_id');
    }


}
