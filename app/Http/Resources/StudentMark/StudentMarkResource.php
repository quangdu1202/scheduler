<?php

namespace App\Http\Resources\StudentMark;

use Illuminate\Http\JsonResponse;
use App\Models\StudentMark\StudentMark;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class StudentMarkResource
 * 
 * @mixin StudentMark
 */
class StudentMarkResource extends JsonResource
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
			'module_class_id' => $this->module_class_id,
			'practice_class_id' => $this->practice_class_id,
			'student_id' => $this->student_id,
			'mark_type_id' => $this->mark_type_id,
			'mark_value' => $this->mark_value,
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