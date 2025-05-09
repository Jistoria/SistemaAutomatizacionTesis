<?php

namespace App\Models\Academic\Thesis\Requests;

use App\Models\Academic\Student\Student;
use App\Models\Academic\Thesis\ThesisPhase;
use App\Models\Academic\Thesis\ThesisProcess;
use App\Models\Academic\Thesis\ThesisProcessPhases;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhaseRequest extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'phase_requests';

    protected $primaryKey = 'request_id';

    public $incrementing = false;


    protected $fillable = [
        'thesis_process_id',
        'student_id',
        'requested_phase_id',
        'request_date',
        'state',
        'review_date',
        'approved_date',
        'reviewed_by',
        'comments',
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'review_date' => 'datetime',
        'approved_date' => 'datetime',
    ];

    public function thesisProcess()
    {
        return $this->belongsTo(ThesisProcess::class, 'thesis_process_id', 'thesis_process_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function thesisPhase()
    {
        return $this->belongsTo(ThesisPhase::class, 'requested_phase_id', 'thesis_phases_id');
    }




}
