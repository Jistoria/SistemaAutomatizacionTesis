<?php

namespace App\Models\Academic\Thesis;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThesisTitle extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $primaryKey = 'thesis_id';
    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'title',
    ];
}
