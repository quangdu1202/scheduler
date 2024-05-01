<?php

namespace App\Services\StudentMark;

use App\Services\StudentMark\Contracts\StudentMarkServiceInterface;
use App\Repositories\StudentMark\Contracts\StudentMarkRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;

/**
 * Class StudentMarkService
 */
class StudentMarkService extends BaseCrudService implements StudentMarkServiceInterface
{
	/**
	 * @return string
	 */
	protected function getRepositoryClass(): string
	{
		return StudentMarkRepositoryInterface::class;
	}
}