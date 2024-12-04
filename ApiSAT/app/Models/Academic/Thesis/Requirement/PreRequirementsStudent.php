<?php

namespace App\Models\Academic\Thesis\Requirement;

use App\Models\Academic\Student\Student;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreRequirementsStudent extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'student_prerequirements';

    protected $primaryKey = 'student_prerequirements_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'pre_requirements_id',
        'student_id',
        'thesis_process_phases_id',
        'status',
        'observation',
        'approved',
        'approved_date',
        'approved_by_user',
        'url_file',
        'created_by_user',
        'updated_by_user',
        'deleted_by_user'
    ];

    public function preRequirement()
    {
        return $this->belongsTo(PreRequirements::class, 'pre_requirements_id', 'pre_requirements_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }
}
