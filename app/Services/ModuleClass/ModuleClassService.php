<?php

namespace App\Services\ModuleClass;

use App\Services\ModuleClass\Contracts\ModuleClassServiceInterface;
use App\Repositories\ModuleClass\Contracts\ModuleClassRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;

/**
 * Class ModuleClassService
 */
class ModuleClassService extends BaseCrudService implements ModuleClassServiceInterface
{
	/**
	 * @return string
	 */
	protected function getRepositoryClass(): string
	{
		return ModuleClassRepositoryInterface::class;
	}
}