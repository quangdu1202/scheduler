<?php

namespace App\Models\Student;

use App\Models\Registration\Registration;
use App\Models\StudentMark\StudentMark;
use App\Models\StudentModuleClass\StudentModuleClass;
use App\Models\User;
use Carbon\Carbon;
use App\Models\OriginalClass\OriginalClass;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Class Student
 *
 * @property integer $id
 * @property integer $original_class_id
 * @property string $student_code
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 * @property OriginalClass $originalClass
 * @property StudentModuleClass[]|Collection $studentModuleClasses
 * @property Registration[]|Collection $registrations
 */
class Student extends BaseModel
{
    use HasFactory;

	/**
	 * @var array
	 */
	protected $fillable = [
        'original_class_id',
        'student_code',
        'created_at',
        'updated_at'
    ];

    /**
     * @return MorphOne
     */
    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }

    /**
     * @return BelongsTo
     */
    public function originalClass(): BelongsTo
    {
        return $this->belongsTo(OriginalClass::class);
    }

    /**
     * @return HasMany
     */
    public function studentModuleClasses(): HasMany
    {
        return $this->hasMany(StudentModuleClass::class);
    }

    /**
     * @return HasMany
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * @return HasMany
     */
    public function studentMarks(): HasMany
    {
        return $this->hasMany(StudentMark::class, 'student_id', 'id');
    }
}
