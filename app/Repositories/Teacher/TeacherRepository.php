<?php

namespace App\Repositories\Teacher;

use App\Models\Teacher\Teacher;
use App\Repositories\Teacher\Contracts\TeacherRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\BaseRepository;

/**
 * Class TeacherRepository
 */
class TeacherRepository extends BaseRepository implements TeacherRepositoryInterface
{
	/**
	 * @return string
	 */
	protected function getModelClass(): string
	{
		return Teacher::class;
	}
}
