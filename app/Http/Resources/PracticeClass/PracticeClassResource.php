<?php

namespace App\Http\Resources\PracticeClass;

use Illuminate\Http\JsonResponse;
use App\Models\PracticeClass\PracticeClass;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PracticeClassResource
 * 
 * @mixin PracticeClass
 */
class PracticeClassResource extends JsonResource
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
			'practice_class_name' => $this->practice_class_name,
			'schedule_date' => $this->schedule_date,
			'session' => $this->session,
			'module_id' => $this->module_id,
			'practice_room_id' => $this->practice_room_id,
			'teacher_id' => $this->teacher_id,
			'recurring_id' => $this->recurring_id,
			'registered_qty' => $this->registered_qty,
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