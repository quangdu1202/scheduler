<?php

namespace App\Services\PracticeRoom;

use App\Services\PracticeRoom\Contracts\PracticeRoomServiceInterface;
use App\Repositories\PracticeRoom\Contracts\PracticeRoomRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;

/**
 * Class PracticeRoomService
 */
class PracticeRoomService extends BaseCrudService implements PracticeRoomServiceInterface
{
	/**
	 * @return string
	 */
	protected function getRepositoryClass(): string
	{
		return PracticeRoomRepositoryInterface::class;
	}
}