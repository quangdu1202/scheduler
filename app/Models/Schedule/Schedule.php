<?php

namespace App\Models\Schedule;

use Carbon\Carbon;
use App\Models\PracticeRoom\PracticeRoom;
use App\Models\PracticeClass\PracticeClass;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;

/**
 * Class Schedule
 * 
 * @property integer $id
 * @property integer $practice_class_id
 * @property integer $practice_room_id
 * @property Carbon $schedule_date
 * @property integer $session
 * @property string $session_id
 * @property integer $shift
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property PracticeClass $practiceClass
 * @property PracticeRoom $practiceRoom
 */
class Schedule extends BaseModel
{
	/**
	 * @var array
	 */
	protected $fillable = [
		'practice_class_id',
		'practice_room_id',
		'schedule_date',
		'session',
		'session_id',
		'shift',
		'created_at',
		'updated_at'
	];

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
	public function practiceRoom(): BelongsTo
	{
		return $this->belongsTo(PracticeRoom::class, 'practice_room_id', 'id');
	}
}