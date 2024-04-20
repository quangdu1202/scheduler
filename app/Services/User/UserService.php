<?php

namespace App\Services\User;

use App\Services\User\Contracts\UserServiceInterface;
use App\Repositories\User\Contracts\UserRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;

/**
 * Class UserService
 */
class UserService extends BaseCrudService implements UserServiceInterface
{
	/**
	 * @return string
	 */
	protected function getRepositoryClass(): string
	{
		return UserRepositoryInterface::class;
	}
}