<?php

namespace App\Models\Academic\Thesis;

use App\Models\Academic\PeriodAcademic;
use App\Models\Academic\Student\Student;
use App\Models\Academic\Teacher\Teacher;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThesisProcessPhases extends Model
{

    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'thesis_process_phases';
    protected $primaryKey = 'thesis_process_phases_id';
    public $incrementing = false;
    protected $keyType = 'uuid';


    protected $fillable = [
        'thesis_process_id',
        'teacher_id',
        'student_id',
        'thesis_id',
        'period_academic_id',
        'thesis_phases_id',
        'approval',
        'state_now',
        'date_start',
        'date_end',
        'observations',
    ];

    public function thesisProcess()
    {
        return $this->belongsTo(ThesisProcess::class, 'thesis_process_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function thesis()
    {
        return $this->belongsTo(ThesisTitle::class, 'thesis_id');
    }

    public function periodAcademic()
    {
        return $this->belongsTo(PeriodAcademic::class, 'period_academic_id');
    }


    public function phase()
    {
        return $this->belongsTo(ThesisPhase::class, 'thesis_phases_id');
    }


}
