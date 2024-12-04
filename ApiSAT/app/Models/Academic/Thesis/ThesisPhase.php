<?php

namespace App\Models\Academic\Thesis;

use App\Models\Academic\Thesis\Requirement\PreRequirements;
use App\Models\Academic\Thesis\Requirement\PreRequirementsStudent;
use App\Models\Academic\Thesis\Requirement\Requirement;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThesisPhase extends Model
{

    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'thesis_phases';
    protected $primaryKey = 'thesis_phases_id';
    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'thesis_phases_id',
        'name',
        'thesis_module_id',
        'created_by_user',
        'updated_by_user',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class, 'thesis_module_id');
    }

    public function requirements()
    {
        return $this->hasMany(Requirement::class, 'thesis_phases_id');
    }

    public function order()
    {
        return $this->hasOne(OrderPhaseThesis::class, 'thesis_phases_id');
    }

    public function preRequirements()
    {
        return $this->hasMany(PreRequirements::class, 'thesis_phases_id');
    }

    public function preRequirementsStudent()
    {
        return $this->hasMany(preRequirementsStudent::class, 'thesis_phases_id');
    }

}
