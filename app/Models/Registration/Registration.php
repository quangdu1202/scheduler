<?php

namespace App\Models\Registration;

use App\Models\PracticeClass\PracticeClass;
use App\Models\Student\Student;
use Carbon\Carbon;
use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Registration
 *
 * @property integer $id
 * @property integer $student_id
 * @property integer $practice_class_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Student $student
 * @property PracticeClass $practiceClass
 */
class Registration extends BaseModel
{
    use HasFactory;

    /**
	 * @var array
	 */
	protected $fillable = ['student_id', 'practice_class_id', 'created_at', 'updated_at'];

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
    public function practiceClass(): BelongsTo
    {
        return $this->belongsTo(PracticeClass::class);
    }
}
