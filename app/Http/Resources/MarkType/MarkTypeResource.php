<?php

namespace App\Http\Resources\MarkType;

use Illuminate\Http\JsonResponse;
use App\Models\MarkType\MarkType;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class MarkTypeResource
 * 
 * @mixin MarkType
 */
class MarkTypeResource extends JsonResource
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
			'type' => $this->type,
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