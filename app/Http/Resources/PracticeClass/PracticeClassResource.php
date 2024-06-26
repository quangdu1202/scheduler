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
			'module_id' => $this->module_id,
			'teacher_id' => $this->teacher_id,
			'practice_class_code' => $this->practice_class_code,
			'practice_class_name' => $this->practice_class_name,
			'registered_qty' => $this->registered_qty,
			'shift_qty' => $this->shift_qty,
			'max_qty' => $this->max_qty,
			'status' => $this->status,
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