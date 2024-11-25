<?php

namespace App\Models\Academic\Thesis\Requirement;

use App\Models\Academic\Thesis\ThesisPhase;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PreRequirements extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'pre_requirements';

    protected $primaryKey = 'pre_requirements_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'thesis_phases_id',
        'name',
        'description',
        'type',
        'extension',
        'url_resource',
        'approval_role',
        'created_by_user',
        'updated_by_user',
        'deleted_by_user'
    ];

    protected $dates = ['deleted_at'];

    public function thesisPhase()
    {
        return $this->belongsTo(ThesisPhase::class, 'thesis_phases_id', 'thesis_phases_id');
    }

}
