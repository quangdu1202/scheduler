<?php

namespace App\Repositories\Registration;

use App\Models\Registration;
use App\Repositories\Registration\Contracts\RegistrationRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\BaseRepository;

/**
 * Class RegistrationRepository
 */
class RegistrationRepository extends BaseRepository implements RegistrationRepositoryInterface
{
	/**
	 * @return string
	 */
	protected function getModelClass(): string
	{
		return Registration::class;
	}
}
