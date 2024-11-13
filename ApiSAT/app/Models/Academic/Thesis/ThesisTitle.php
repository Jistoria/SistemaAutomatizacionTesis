<?php

namespace App\Models\Academic\Thesis;

use App\Models\General\CategoryArea;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThesisTitle extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $primaryKey = 'thesis_id';
    public $incrementing = false;

    protected $fillable = [
        'title',
    ];

    public function thesisProcess()
    {
        return $this->hasMany(ThesisProcess::class, 'thesis_id');
    }


    public function categoryAreas()
    {
        return $this->belongsToMany(CategoryArea::class, 'category_thesis', 'thesis_id', 'category_area_id');
    }

}
