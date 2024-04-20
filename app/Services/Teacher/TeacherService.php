<?php

namespace App\Services\Teacher;

use App\Services\Teacher\Contracts\TeacherServiceInterface;
use App\Repositories\Teacher\Contracts\TeacherRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;

/**
 * Class TeacherService
 */
class TeacherService extends BaseCrudService implements TeacherServiceInterface
{
	/**
	 * @return string
	 */
	protected function getRepositoryClass(): string
	{
		return TeacherRepositoryInterface::class;
	}
}