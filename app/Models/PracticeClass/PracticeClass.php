<?php

namespace App\Models\PracticeClass;

use Carbon\Carbon;
use App\Models\Module\Module;
use App\Models\Teacher\Teacher;
use App\Models\Schedule\Schedule;
use App\Models\StudentMark\StudentMark;
use App\Models\Registration\Registration;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;

/**
 * Class PracticeClass
 * 
 * @property integer $id
 * @property integer $module_id
 * @property integer $teacher_id
 * @property string $practice_class_code
 * @property string $practice_class_name
 * @property integer $registered_qty
 * @property integer $shift_qty
 * @property integer $max_qty
 * @property integer $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Module $module
 * @property Teacher $teacher
 * @property Registration[]|Collection $registrations
 * @property Schedule[]|Collection $schedules
 * @property StudentMark[]|Collection $studentMarks
 */
class PracticeClass extends BaseModel
{
    use HasFactory;
	/**
	 * @var array
	 */
	protected $fillable = [
		'module_id',
		'teacher_id',
		'practice_class_code',
		'practice_class_name',
		'registered_qty',
		'shift_qty',
		'max_qty',
		'status',
		'created_at',
		'updated_at'
	];

	/**
	 * @return BelongsTo
	 */
	public function module(): BelongsTo
	{
		return $this->belongsTo(Module::class, 'module_id', 'id');
	}

	/**
	 * @return BelongsTo
	 */
	public function teacher(): BelongsTo
	{
		return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
	}

	/**
	 * @return HasMany
	 */
	public function registrations(): HasMany
	{
		return $this->hasMany(Registration::class, 'practice_class_id', 'id');
	}

	/**
	 * @return HasMany
	 */
	public function schedules(): HasMany
	{
		return $this->hasMany(Schedule::class, 'practice_class_id', 'id');
	}

	/**
	 * @return HasMany
	 */
	public function studentMarks(): HasMany
	{
		return $this->hasMany(StudentMark::class, 'practice_class_id', 'id');
	}
}