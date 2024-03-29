<?php

namespace App\Repositories\OriginalClass;

use App\Models\OriginalClass;
use App\Repositories\OriginalClass\Contracts\OriginalClassRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\BaseRepository;

/**
 * Class OriginalClassRepository
 */
class OriginalClassRepository extends BaseRepository implements OriginalClassRepositoryInterface
{
	/**
	 * @return string
	 */
	protected function getModelClass(): string
	{
		return OriginalClass::class;
	}
}
