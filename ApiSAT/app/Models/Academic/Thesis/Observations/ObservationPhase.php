<?php

namespace App\Models\Academic\Thesis\Observations;

use App\Models\Academic\Thesis\ThesisProcessPhases;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class ObservationPhase extends Model
{
    protected $table = 'observations_phases';
    protected $primaryKey = 'observation_phase_id';
    protected $fillable = [
        'comment',
        'created_by_user',
        'thesis_process_phases_id',
    ];

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by_user', 'id');
    }

    public function thesisProcessPhase()
    {
        return $this->belongsTo(ThesisProcessPhases::class, 'thesis_process_phases_id', 'id');
    }
}
