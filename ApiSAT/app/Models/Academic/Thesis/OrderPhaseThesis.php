<?php

namespace App\Models\Academic\Thesis;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPhaseThesis extends Model
{

    use HasFactory, HasUuids;

    protected $table = 'order_phases_thesis';
    protected $primaryKey = 'order_phases_thesis_id';
    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'order_phase_thesis_id',
        'thesis_phases_id',
        'order',
    ];

    public function phase()
    {
        return $this->belongsTo(ThesisPhase::class, 'thesis_phases_id');
    }
}
