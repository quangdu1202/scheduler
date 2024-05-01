<?php

namespace App\Models\ModuleClass;

use Carbon\Carbon;
use App\Models\Module\Module;
use App\Models\Teacher\Teacher;
use App\Models\StudentMark\StudentMark;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\StudentModuleClass\StudentModuleClass;
use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;

/**
 * Class ModuleClass
 * 
 * @property integer $id
 * @property integer $module_id
 * @property integer $teacher_id
 * @property string $module_class_code
 * @property string $module_class_name
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property integer $student_qty
 * @property integer $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Module $module
 * @property Teacher $teacher
 * @property StudentMark[]|Collection $studentMarks
 * @property StudentModuleClass[]|Collection $studentModuleClasses
 */
class ModuleClass extends BaseModel
{
	/**
	 * @var array
	 */
	protected $fillable = [
		'module_id',
		'teacher_id',
		'module_class_code',
		'module_class_name',
		'start_date',
		'end_date',
		'student_qty',
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
	public function studentMarks(): HasMany
	{
		return $this->hasMany(StudentMark::class, 'module_class_id', 'id');
	}

	/**
	 * @return HasMany
	 */
	public function studentModuleClasses(): HasMany
	{
		return $this->hasMany(StudentModuleClass::class, 'module_class_id', 'id');
	}
}