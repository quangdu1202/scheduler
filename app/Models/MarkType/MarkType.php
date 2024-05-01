<?php

namespace App\Models\MarkType;

use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;
use App\Models\StudentMark\StudentMark;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class MarkType
 *
 * @property integer $id
 * @property string $type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property StudentMark[]|Collection $studentMarks
 */
class MarkType extends BaseModel
{
    /**
     * @var array
     */
    protected $fillable = ['type', 'created_at', 'updated_at'];

    /**
     * @return HasMany
     */
    public function studentMarks(): HasMany
    {
        return $this->hasMany(StudentMark::class, 'mark_type_id', 'id');
    }
}