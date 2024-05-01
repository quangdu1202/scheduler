<?php

namespace App\Services\MarkType;

use App\Services\MarkType\Contracts\MarkTypeServiceInterface;
use App\Repositories\MarkType\Contracts\MarkTypeRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;

/**
 * Class MarkTypeService
 */
class MarkTypeService extends BaseCrudService implements MarkTypeServiceInterface
{
	/**
	 * @return string
	 */
	protected function getRepositoryClass(): string
	{
		return MarkTypeRepositoryInterface::class;
	}
}