<?php

namespace App\Repositories\Student;

use App\Models\Student;
use App\Repositories\Student\Contracts\StudentRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\BaseRepository;

/**
 * Class StudentRepository
 */
class StudentRepository extends BaseRepository implements StudentRepositoryInterface
{
	/**
	 * @return string
	 */
	protected function getModelClass(): string
	{
		return Student::class;
	}
}
