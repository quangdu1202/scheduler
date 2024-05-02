<?php

namespace App\Models\Semester;

use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Semester
 *
 * @property integer $id
 * @property string $semester_code
 * @property integer $semester
 * @property Carbon $academic_year
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Semester extends BaseModel
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = [
        'semester_code',
        'semester',
        'academic_year',
        'start_date',
        'end_date',
        'created_at',
        'updated_at'
    ];
}