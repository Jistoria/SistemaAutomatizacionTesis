<?php

namespace App\Models\Academic\Thesis\Observations;

use App\Models\Academic\Thesis\Requirement\RequirementsStudent;
use App\Models\Auth\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObservationRequirement extends Model
{

    use SoftDeletes, HasFactory, HasUuids;

    protected $table = 'observations_requirements';
    protected $primaryKey = 'observation_requirement_id';
    protected $fillable = [
        'comment',
        'created_by_user',
        'student_requirements_id',
    ];

    protected $appends = ['formatted_created_at'];

    public function getFormattedCreatedAtAttribute()
    {
        return Carbon::parse($this->created_at)->format('d-m-Y');
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by_user', 'id');
    }

    public function studentRequirement()
    {
        return $this->belongsTo(RequirementsStudent::class, 'student_requirements_id', 'id');
    }
}
