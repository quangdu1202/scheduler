<?php

namespace App\Repositories\Schedule;

use App\Models\Schedule\Schedule;
use App\Repositories\Schedule\Contracts\ScheduleRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\BaseRepository;

/**
 * Class ScheduleRepository
 */
class ScheduleRepository extends BaseRepository implements ScheduleRepositoryInterface
{
	/**
	 * @return string
	 */
	protected function getModelClass(): string
	{
		return Schedule::class;
	}
}