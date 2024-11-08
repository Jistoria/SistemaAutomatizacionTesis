<?php

namespace App\Models\Academic\Thesis;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{

    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'thesis_modules';
    protected $primaryKey = 'thesis_module_id';
    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'name',
        'description',
        'order',
        'created_by_user',
        'updated_by_user',
        'deleted_by_user'
    ];

    public function phases()
    {
        return $this->hasMany(ThesisPhase::class, 'thesis_module_id');
    }
}
