<?php

namespace App\Models\Academic\Thesis\Requirement;

use App\Models\Academic\Student\Student;
use App\Models\Academic\Thesis\Observations\ObservationRequirement;
use App\Models\Academic\Thesis\ThesisProcessPhases;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequirementsStudent extends Model
{
    use SoftDeletes, HasFactory, HasUuids;

    protected $table = 'student_requirements';

    protected $primaryKey = 'student_requirements_id';

    protected $fillable = [
        'student_id',
        'period_academic_id',
        'thesis_process_phases_id',
        'requirements_id',
        'requirements_data',
        'approved',
        'approved_by_user',
        'url_file',
        'send_date',
        'approved_date',
        'approved_role',
        'status',
    ];

    protected $casts = [
        'send_date' => 'datetime',
        'approved_date' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function thesisProcessPhase()
    {
        return $this->belongsTo(ThesisProcessPhases::class, 'thesis_process_phases_id', 'thesis_process_phases_id');
    }

    public function requirement()
    {
        return $this->belongsTo(Requirement::class, 'requirements_id', 'requirements_id');
    }

    public function observations()
    {
        return $this->hasMany(ObservationRequirement::class, 'student_requirements_id', 'student_requirements_id');
    }
}
