<?php

namespace App\Services\StudentModuleClass;

use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;
use App\Services\StudentModuleClass\Contracts\StudentModuleClassServiceInterface;
use App\Repositories\StudentModuleClass\Contracts\StudentModuleClassRepositoryInterface;

/**
 * Class StudentModuleClassService
 */
class StudentModuleClassService extends BaseCrudService implements StudentModuleClassServiceInterface
{
	/**
	 * @return string
	 */
	protected function getRepositoryClass(): string
	{
		return StudentModuleClassRepositoryInterface::class;
	}
}