<?php

namespace App\Services\Module;

use App\Services\Module\Contracts\ModuleServiceInterface;
use App\Repositories\Module\Contracts\ModuleRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;

/**
 * Class ModuleService
 */
class ModuleService extends BaseCrudService implements ModuleServiceInterface
{
	/**
	 * @return string
	 */
	protected function getRepositoryClass(): string
	{
		return ModuleRepositoryInterface::class;
	}
}