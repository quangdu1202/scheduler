<?php

namespace App\Repositories\StudentMark;

use App\Models\StudentMark\StudentMark;
use App\Repositories\StudentMark\Contracts\StudentMarkRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\BaseRepository;

/**
 * Class StudentMarkRepository
 */
class StudentMarkRepository extends BaseRepository implements StudentMarkRepositoryInterface
{
	/**
	 * @return string
	 */
	protected function getModelClass(): string
	{
		return StudentMark::class;
	}
}