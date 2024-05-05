<?php

namespace App\Models\PracticeRoom;

use Carbon\Carbon;
use App\Models\Schedule\Schedule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;

/**
 * Class PracticeRoom
 * 
 * @property integer $id
 * @property string $name
 * @property string $location
 * @property integer $pc_qty
 * @property boolean $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Schedule[]|Collection $schedules
 */
class PracticeRoom extends BaseModel
{
    use HasFactory;

	/**
	 * @var array
	 */
	protected $fillable = [
		'name',
		'location',
		'pc_qty',
		'status',
		'created_at',
		'updated_at'
	];

	/**
	 * @return HasMany
	 */
	public function schedules(): HasMany
	{
		return $this->hasMany(Schedule::class, 'practice_room_id', 'id');
	}
}