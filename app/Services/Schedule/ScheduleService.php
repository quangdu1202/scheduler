<?php

namespace App\Services\Schedule;

use App\Services\Schedule\Contracts\ScheduleServiceInterface;
use App\Repositories\Schedule\Contracts\ScheduleRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;

/**
 * Class ScheduleService
 */
class ScheduleService extends BaseCrudService implements ScheduleServiceInterface
{
	/**
	 * @return string
	 */
	protected function getRepositoryClass(): string
	{
		return ScheduleRepositoryInterface::class;
	}
}