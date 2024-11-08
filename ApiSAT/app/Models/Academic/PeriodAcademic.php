<?php

namespace App\Models\Academic;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
class PeriodAcademic extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $primaryKey = 'period_academic_id';
    protected $table = 'period_academic';
    public $incrementing = false;



    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'created_by_user',
        'updated_by_user',
        'deleted_by_user',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    // Relación con el modelo User (creador)
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user');
    }

    // Relación con el modelo User (actualizador)
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_user');
    }

    // Relación con el modelo User (eliminador)
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by_user');
    }
}
