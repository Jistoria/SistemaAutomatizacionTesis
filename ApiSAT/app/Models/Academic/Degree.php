<?php

namespace App\Models\Academic;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Degree extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $primaryKey = 'degree_id';
    public $incrementing = false;
    protected $keyType = 'uuid';

    protected $fillable = [
        'name',
    ];
}
