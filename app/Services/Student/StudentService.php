<?php

namespace App\Services\Student;

use App\Services\Student\Contracts\StudentServiceInterface;
use App\Repositories\Student\Contracts\StudentRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;

/**
 * Class StudentService
 */
class StudentService extends BaseCrudService implements StudentServiceInterface
{
	/**
	 * @return string
	 */
	protected function getRepositoryClass(): string
	{
		return StudentRepositoryInterface::class;
	}
}