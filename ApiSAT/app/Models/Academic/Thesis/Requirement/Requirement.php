<?php

namespace App\Models\Academic\Thesis\Requirement;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requirement extends Model
{

    use HasFactory, SoftDeletes, HasUuids;


    protected $table = 'requirements';

    protected $primaryKey = 'requirements_id';

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

    public $incrementing = false;

    protected $keyType = 'string';

    protected $dates = ['deleted_at'];

    public function thesisPhase()
    {
        return $this->belongsTo('App\Models\Academic\Thesis\Phase\ThesisPhase', 'thesis_phases_id', 'thesis_phases_id');
    }
}
