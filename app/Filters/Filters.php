<?php
/**
 * User: adrian
 * Date: 12/10/17
 * Time: 4:03 PM
 */

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class Filters
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    protected $filters = [];

    /**
     * ThreadFilters contructor.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if(method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    /**
     * @return array
     */
    protected function getFilters(): array
    {
        return $this->request->only($this->filters);
    }
}