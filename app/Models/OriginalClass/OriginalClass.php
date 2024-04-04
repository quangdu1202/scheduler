<?php

namespace App\Models\OriginalClass;

use App\Models\Student\Student;
use Carbon\Carbon;
use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class OriginalClass
 *
 * @property integer $id
 * @property string $original_class_name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Student $students
 */
class OriginalClass extends BaseModel
{
    use HasFactory;

	/**
	 * @var array
	 */
	protected $fillable = ['original_class_name', 'created_at', 'updated_at'];

    /**
     * @return HasMany
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
