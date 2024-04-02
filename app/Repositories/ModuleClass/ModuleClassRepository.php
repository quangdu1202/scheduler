<?php

namespace App\Repositories\ModuleClass;

use App\Models\ModuleClass\ModuleClass;
use App\Repositories\ModuleClass\Contracts\ModuleClassRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\BaseRepository;

/**
 * Class ModuleClassRepository
 */
class ModuleClassRepository extends BaseRepository implements ModuleClassRepositoryInterface
{
	/**
	 * @return string
	 */
	protected function getModelClass(): string
	{
		return ModuleClass::class;
	}
}