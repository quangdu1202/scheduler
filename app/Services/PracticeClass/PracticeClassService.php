<?php

namespace App\Services\PracticeClass;

use App\Services\PracticeClass\Contracts\PracticeClassServiceInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;
use App\Repositories\PracticeClass\Contracts\PracticeClassRepositoryInterface;

/**
 * Class PracticeClassService
 */
class PracticeClassService extends BaseCrudService implements PracticeClassServiceInterface
{
	/**
	 * @return string
	 */
	protected function getRepositoryClass(): string
	{
		return PracticeClassRepositoryInterface::class;
	}
}