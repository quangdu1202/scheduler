<?php

namespace App\Models\StudentModuleClass;

use App\Models\Module\Module;
use App\Models\Student\Student;
use Carbon\Carbon;
use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class StudentModuleClass
 *
 * @property integer $id
 * @property integer $student_id
 * @property integer $module_class_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Student $student
 * @property Module $module
 */
class StudentModuleClass extends BaseModel
{
    use HasFactory;

	/**
	 * @var array
	 */
	protected $fillable = ['student_id', 'module_class_id', 'created_at', 'updated_at'];

    /**
     * @return BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * @return BelongsTo
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}
