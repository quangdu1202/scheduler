<?php

namespace App\Models\ModuleClass;

use App\Models\Module\Module;
use App\Models\StudentModuleClass\StudentModuleClass;
use App\Models\Teacher\Teacher;
use Carbon\Carbon;
use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class ModuleClass
 *
 * @property integer $id
 * @property string $module_class_code
 * @property string $module_class_name
 * @property integer $module_id
 * @property integer $teacher_id
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property int $student_qty
 * @property int $status
 * @property StudentModuleClass $studentModuleClasses
 * @property Module $module
 * @property Teacher $teacher
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ModuleClass extends BaseModel
{
    use HasFactory;

	/**
	 * @var array
	 */
	protected $fillable = [
        'module_class_code',
        'module_class_name',
        'module_id',
        'teacher_id',
        'start_date',
        'end_date',
        'student_qty',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * @return HasMany
     */
    public function studentModuleClasses(): HasMany
    {
        return $this->hasMany(StudentModuleClass::class);
    }

    /**
     * @return BelongsTo
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * @return BelongsTo
     */
    public  function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
