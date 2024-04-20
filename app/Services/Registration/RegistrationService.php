<?php

namespace App\Services\Registration;

use App\Services\Registration\Contracts\RegistrationServiceInterface;
use App\Repositories\Registration\Contracts\RegistrationRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;

/**
 * Class RegistrationService
 */
class RegistrationService extends BaseCrudService implements RegistrationServiceInterface
{
	/**
	 * @return string
	 */
	protected function getRepositoryClass(): string
	{
		return RegistrationRepositoryInterface::class;
	}
}