<?php

namespace App\Http\Resources\Schedule;

use Illuminate\Http\JsonResponse;
use App\Models\Schedule\Schedule;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ScheduleResource
 * 
 * @mixin Schedule
 */
class ScheduleResource extends JsonResource
{
	/**
	 * @var integer
	 */
	protected $statusCode = Response::HTTP_OK;

	/**
	 * @param $resource
	 * @param int $statusCode
	 */
	public function __construct($resource, int $statusCode = Response::HTTP_OK)
	{
		$this->statusCode = $statusCode;
		
		parent::__construct($resource);
	}

	/**
	 * @param $request
	 * 
	 * @return array
	 */
	public function toArray($request): array
	{
		return [
			'id' => $this->id,
			'practice_class_id' => $this->practice_class_id,
			'practice_room_id' => $this->practice_room_id,
			'schedule_date' => $this->schedule_date,
			'session' => $this->session,
			'session_id' => $this->session_id,
			'shift' => $this->shift,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}

	/**
	 * @param $request
	 * 
	 * @return JsonResponse
	 */
	public function toResponse($request): JsonResponse
	{
		return parent::toResponse($request)->setStatusCode($this->statusCode);
	}
}