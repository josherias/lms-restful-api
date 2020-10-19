<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

trait ApiResponser
{

    private function successResponser($data, $code)
    {
        return response()->json($data, $code);
    }


    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }


    protected function showAll(Collection $collection, $code = 200)
    {
        if ($collection->isEmpty()) {
            return $this->successResponser(['data' => $collection], $code);
        }

        //obtain transformer from one of the models
        $transfomer = $collection->first()->transformer;
        $collection = $this->filterData($collection, $transfomer);
        $collection = $this->sortData($collection, $transfomer);
        $collection = $this->paginate($collection);
        $collection = $this->transformData($collection, $transfomer);
        $collection = $this->cacheResponse($collection);

        return $this->successResponser($collection, $code);
    }


    protected function showOne(Model $instance, $code = 200)
    {
        $transfomer = $instance->transformer;

        $instance = $this->transformData($instance, $transfomer);

        return $this->successResponser($instance, $code);
    }

    protected function showMessage($message, $code = 200)
    {
        return $this->successResponser(['data' => $message, 'code' => $code], $code);
    }

    protected function filterData(Collection $collection, $transfomer)
    {
        //loop thru all the query strings in the request
        foreach (request()->query() as $query => $value) {
            $attribute = $transfomer::originalAttribute($query);

            if (isset($attribute, $value)) {
                $collection = $collection->where($attribute, $value);
            }
        }
        return $collection;
    }

    protected function sortData(Collection $collection, $transfomer)
    {
        if (request()->has('sort_by')) {

            $attribute = request()->sort_by;

            $attribute = $transfomer::originalAttribute($attribute); //transform the query string from the request

            $collection = $collection->sortBy->{$attribute};
        }

        return $collection;
    }

    protected function paginate(Collection $collection)
    {
        $rules = [
            'per_page' => 'integer|min:2|max:50'
        ];

        Validator::validate(request()->all(), $rules);

        $page = LengthAwarePaginator::resolveCurrentPage();

        $perPage = 15;

        if (request()->has('per_page')) {
            $perPage = (int) request()->per_page;
        }

        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        $count = $collection->count();

        $paginated = new LengthAwarePaginator($results, $count, $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        $paginated->appends(request()->all()); //add all query params that r passed from request wic wud hav been ignored by the paginator

        return $paginated;
    }


    protected function transformData($data, $transfomer)
    {
        $transformation = fractal($data, new $transfomer);

        return $transformation->toArray();
    }

    protected function cacheResponse($data)
    {
        $url = request()->url();

        $queryParams =  request()->query();

        ksort($queryParams); //sort the query parameters basing on keys

        $queryString = http_build_query($queryParams); //build new query string

        $fullUrl = "{$url}?{$queryString}";

        return Cache::remember($fullUrl, 60 / 60, function () use ($data) {
            return $data;
        });
    }
}
