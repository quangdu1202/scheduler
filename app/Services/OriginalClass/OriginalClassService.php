<?php

namespace App\Services\OriginalClass;

use App\Services\OriginalClass\Contracts\OriginalClassServiceInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;
use App\Repositories\OriginalClass\Contracts\OriginalClassRepositoryInterface;

/**
 * Class OriginalClassService
 */
class OriginalClassService extends BaseCrudService implements OriginalClassServiceInterface
{
	/**
	 * @return string
	 */
	protected function getRepositoryClass(): string
	{
		return OriginalClassRepositoryInterface::class;
	}
}