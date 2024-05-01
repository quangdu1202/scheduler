<?php

namespace App\Http\Resources\ModuleClass;

use Illuminate\Http\JsonResponse;
use App\Models\ModuleClass\ModuleClass;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ModuleClassResource
 * 
 * @mixin ModuleClass
 */
class ModuleClassResource extends JsonResource
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
			'module_class_code' => $this->module_class_code,
			'module_class_name' => $this->module_class_name,
			'module_id' => $this->module_id,
			'teacher_id' => $this->teacher_id,
			'start_date' => $this->start_date,
			'end_date' => $this->end_date,
			'student_qty' => $this->student_qty,
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