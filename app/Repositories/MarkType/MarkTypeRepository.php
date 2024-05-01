<?php

namespace App\Repositories\MarkType;

use App\Models\MarkType\MarkType;
use App\Repositories\MarkType\Contracts\MarkTypeRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\BaseRepository;

/**
 * Class MarkTypeRepository
 */
class MarkTypeRepository extends BaseRepository implements MarkTypeRepositoryInterface
{
	/**
	 * @return string
	 */
	protected function getModelClass(): string
	{
		return MarkType::class;
	}
}