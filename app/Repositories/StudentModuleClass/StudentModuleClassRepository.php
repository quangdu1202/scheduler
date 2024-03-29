<?php

namespace App\Repositories\StudentModuleClass;

use App\Models\StudentModuleClass;
use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\BaseRepository;
use App\Repositories\StudentModuleClass\Contracts\StudentModuleClassRepositoryInterface;

/**
 * Class StudentModuleClassRepository
 */
class StudentModuleClassRepository extends BaseRepository implements StudentModuleClassRepositoryInterface
{
	/**
	 * @return string
	 */
	protected function getModelClass(): string
	{
		return StudentModuleClass::class;
	}
}
