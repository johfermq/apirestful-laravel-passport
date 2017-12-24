<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

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

		$collection = $this->transformData($collection, $transformer);

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
	 * Respuesta transformada
	 * @param type $data
	 * @param type $transformer
	 * @return array
	 */
	protected function transformData($data, $transformer)
	{
		$transformation = fractal($data, new $transformer);

		return $transformation->toArray();
	}

}