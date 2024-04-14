<?php

namespace App\Http\Resources\Module;

use App\Models\Module\Module;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ModuleResource
 * 
 * @mixin Module
 */
class ModuleResource extends JsonResource
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
			'module_name' => $this->module_name,
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