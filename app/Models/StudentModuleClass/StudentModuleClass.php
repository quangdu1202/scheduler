<?php

namespace App\Models\StudentModuleClass;

use Carbon\Carbon;
use App\Models\Student\Student;
use App\Models\ModuleClass\ModuleClass;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;

/**
 * Class StudentModuleClass
 * 
 * @property integer $id
 * @property integer $student_id
 * @property integer $module_class_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property ModuleClass $moduleClass
 * @property Student $student
 */
class StudentModuleClass extends BaseModel
{
	/**
	 * @var array
	 */
	protected $fillable = ['student_id', 'module_class_id', 'created_at', 'updated_at'];

	/**
	 * @return BelongsTo
	 */
	public function moduleClass(): BelongsTo
	{
		return $this->belongsTo(ModuleClass::class, 'module_class_id', 'id');
	}

	/**
	 * @return BelongsTo
	 */
	public function student(): BelongsTo
	{
		return $this->belongsTo(Student::class, 'student_id', 'id');
	}
}