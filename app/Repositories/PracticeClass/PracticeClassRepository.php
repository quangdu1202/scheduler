<?php

namespace App\Repositories\PracticeClass;

use App\Models\PracticeClass;
use App\Repositories\PracticeClass\Contracts\PracticeClassRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\BaseRepository;

/**
 * Class PracticeClassRepository
 */
class PracticeClassRepository extends BaseRepository implements PracticeClassRepositoryInterface
{
	/**
	 * @return string
	 */
	protected function getModelClass(): string
	{
		return PracticeClass::class;
	}
}
