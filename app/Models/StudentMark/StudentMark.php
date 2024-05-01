<?php

namespace App\Models\StudentMark;

use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;
use App\Models\MarkType\MarkType;
use App\Models\ModuleClass\ModuleClass;
use App\Models\PracticeClass\PracticeClass;
use App\Models\Student\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class StudentMark
 *
 * @property integer $id
 * @property integer $module_class_id
 * @property integer $practice_class_id
 * @property integer $student_id
 * @property integer $mark_type_id
 * @property float $mark_value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property MarkType $markType
 * @property ModuleClass $moduleClass
 * @property PracticeClass $practiceClass
 * @property Student $student
 */
class StudentMark extends BaseModel
{
    /**
     * @var array
     */
    protected $fillable = [
        'module_class_id',
        'practice_class_id',
        'student_id',
        'mark_type_id',
        'mark_value',
        'created_at',
        'updated_at'
    ];

    /**
     * @return BelongsTo
     */
    public function markType(): BelongsTo
    {
        return $this->belongsTo(MarkType::class, 'mark_type_id', 'id');
    }

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
    public function practiceClass(): BelongsTo
    {
        return $this->belongsTo(PracticeClass::class, 'practice_class_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}