<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

trait ApiResponser
{
	/**
	 * Success response
	 * @param type $data
	 * @param type $code
	 * @return array
	 */
	private function successResponse($data, $code)
	{
		return response()->json($data, $code);
	}

	/**
	 * Error response
	 * @param type $message
	 * @param type $code
	 * @return json
	 */
	protected function errorResponse($message, $code)
	{
		return response()->json(['error' => $message, 'code' => $code], $code);
	}

	/**
	 * Show all
	 * @param Collection $collection
	 * @param type|int $code
	 * @return array
	 */
	protected function showAll(Collection $collection, $code = 200)
	{
		if ($collection->isEmpty())
		{
			return $this->successResponse(['data' => $collection], $code);
		}

		$transformer = $collection->first()->transformer;

		$collection = $this->filterData($collection, $transformer);
		$collection = $this->sortData($collection, $transformer);
		$collection = $this->paginateData($collection);

		$collection = $this->transformData($collection, $transformer);

		$collection = $this->cacheResponse($collection);

		return $this->successResponse($collection, $code);
	}

	/**
	 * Show one
	 * @param Model $instance
	 * @param type|int $code
	 * @return array
	 */
	protected function showOne(Model $instance, $code = 200)
	{
		$transformer = $instance->transformer;
		$instance = $this->transformData($instance, $transformer);

		return $this->successResponse($instance, $code);
	}

	/**
	 * Show message
	 * @param type $message
	 * @param type|int $code
	 * @return json
	 */
	protected function showMessage($message, $code = 200)
	{
		return response()->json(['data' => $message], $code);
	}

	/**
	 * Filter Data
	 * @param Collection $collection
	 * @param type $transformer
	 * @return collection
	 */
	protected function filterData(Collection $collection, $transformer)
	{
		foreach (request()->query() as $query => $value)
		{
			$attribute = $transformer::originalAttribute($query);
		}

		if (isset($attribute, $value))
		{
			$collection = $collection->where($attribute, $value);
		}

		return $collection;
	}

	/**
	 * Sort data
	 * @param Collection $collection
	 * @param type $transformer
	 * @return collection
	 */
	protected function sortData(Collection $collection, $transformer)
	{
		if (request()->has('sort_by'))
		{
			$attribute = $transformer::originalAttribute(request()->sort_by);

			$collection = $collection->sortBy($attribute); // ó $collection->sortBy->{$attribute};
		}

		return $collection;
	}

	/**
	 * Paginate date
	 * @param Collection $collection
	 * @return collection
	 */
	protected function paginateData(Collection $collection)
	{
		$rules = [
			'per_page' => 'integer|min:2|max:50',
		];

		Validator::validate(request()->all(), $rules);

		$page = LengthAwarePaginator::resolveCurrentPage();

		if (request()->has('per_page'))
			$perPage = (int)(request()->per_page);
		else
			$perPage = 15;

		$result = $collection->slice(($page - 1) * $perPage, $perPage)->values();

		$paginated = new LengthAwarePaginator($result, $collection->count(), $perPage, $page, [
			'path' => LengthAwarePaginator::resolveCurrentPath(),
		]);

		$paginated->appends(request()->all());

		return $paginated;
	}

	/**
	 * Respuesta transformada
	 * @param array $data
	 * @param type $transformer
	 * @return array
	 */
	protected function transformData($data, $transformer)
	{
		$transformation = fractal($data, new $transformer);

		return $transformation->toArray();
	}

	/**
	 * Cache de la respuesta
	 * @param array $data
	 * @return array
	 */
	protected function cacheResponse($data)
	{
		$url = request()->url();

		$queryParams = request()->query(); 	// Obtenemos los parametros de la query
		ksort($queryParams);				// Ordenamos los parametros de la query
		$queryString = http_build_query($queryParams);	//Reconstruimos los parametros de la query
		$fullUrl = "{$url}?{$queryString}";				//Reconstruimos la url ordenada

 		// Tiempo en minutos => 30/60 = 30 segundos
		return Cache::remember($fullUrl, 30/60, function() use ($data) {
			return $data;
		});
	}

}