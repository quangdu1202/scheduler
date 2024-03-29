<?php

namespace App\Repositories\PracticeRoom;

use App\Models\PracticeRoom;
use App\Repositories\PracticeRoom\Contracts\PracticeRoomRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\BaseRepository;

/**
 * Class PracticeRoomRepository
 */
class PracticeRoomRepository extends BaseRepository implements PracticeRoomRepositoryInterface
{
	/**
	 * @return string
	 */
	protected function getModelClass(): string
	{
		return PracticeRoom::class;
	}
}
