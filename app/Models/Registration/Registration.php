<?php

namespace App\Models\Registration;

use Carbon\Carbon;
use App\Models\Student\Student;
use App\Models\PracticeClass\PracticeClass;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;

/**
 * Class Registration
 * 
 * @property integer $id
 * @property integer $student_id
 * @property integer $practice_class_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property PracticeClass $practiceClass
 * @property Student $student
 */
class Registration extends BaseModel
{
	/**
	 * @var array
	 */
	protected $fillable = ['student_id', 'practice_class_id', 'created_at', 'updated_at'];

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