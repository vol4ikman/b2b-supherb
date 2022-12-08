<?php

namespace PriorityApi\Builder;

use Closure;
use GuzzleHttp\Exception\ClientException;
use InvalidArgumentException;
use PriorityApi\EntityNotSetException;
use PriorityApi\PriorityClient;

class Builder
{
    protected $subQuery;

    protected $entity;

    protected $properties = [];

    protected $filters = [];

    protected $expands = [];

    protected $orderBy;

    protected $direction;

    protected $top;

    /**
     * @var PriorityClient
     */
    protected $client;

    protected $find;

    /**
     * Builder constructor.
     *
     * @param bool $subQuery
     */
    public function __construct($subQuery = false)
    {
        $this->subQuery = $subQuery;
    }

    public function setClient(PriorityClient $client)
    {
        $this->client = $client;
    }


    /**
     * The entity we want to access
     *
     * @param string $entity
     *
     * @return $this
     */
    public function from(string $entity)
    {
        $this->entity = $entity;

        return $this;
    }

    public function find($id)
    {
        $this->find = Filter::wrapValue($id);
        return $this->get();
    }

    /**
     * @param $properties
     *
     * @return $this
     */
    public function select($properties)
    {
        $this->properties = is_array($properties) ? $properties : func_get_args();

        return $this;
    }

    /**
     * @param string $property
     * @param string $direction
     *
     * @return $this
     */
    public function orderBy(string $property, $direction = 'asc')
    {
        if (!in_array($direction, ['asc', 'desc'], true)) {
            throw new InvalidArgumentException('Order direction must be "asc" or "desc".');
        }

        $this->orderBy = $property;
        $this->direction = $direction;
        return $this;
    }

    public function top(int $value)
    {
        $this->top = $value;

        return $this;
    }

    public function filter(...$args)
    {
        $filter = new Filter();
        call_user_func_array([$filter, 'filter'], $args);

        if (!count($this->filters)) {
            $this->filters[] = (string)$filter;
        } else {
            $this->filters[] = 'and ' . (string)$filter;
        }

        return $this;
    }

    public function orFilter(...$args)
    {
        $filter = new Filter();
        call_user_func_array([$filter, 'orFilter'], $args);

        if (!count($this->filters)) {
            $this->filters[] = (string)$filter;
        } else {
            $this->filters[] = 'or ' . (string)$filter;
        }

        return $this;
    }

    public function expand($subEntity, $closure = null)
    {
        $subQ = '';

        if ($closure instanceof Closure) {
            $builder = new static(true);
            call_user_func_array($closure, [$builder]);

            $subQ = '(' . $builder->toQuery() . ')';
        }

        $this->expands[] = $subEntity . $subQ;

        return $this;
    }

    /**
     * @return string
     * @throws EntityNotSetException
     */
    public function toQuery(): string
    {
        $glue = $this->subQuery ? ';' : '&';

        if (!$this->subQuery && is_null($this->entity)) {
            throw new EntityNotSetException();
        }

        $query = $this->entity;

        if ($this->find) {
            $query .= '(' . $this->find . ')';
            return $query;
        }

        if (count($this->properties)) {
            $query .= $glue . '$select=' . implode(',', $this->properties);
        }

        if (count($this->filters)) {
            $filters = trim(implode(' ', $this->filters));

            $query .= $glue . '$filter=' . $filters;
        }

        if (count($this->expands)) {
            $expands = implode(',', $this->expands);

            $query .= $glue . '$expand=' . $expands;
        }

        if (!is_null($this->orderBy)) {
            $query .= $glue . "\$orderby={$this->orderBy} {$this->direction}";
        }

        if (!is_null($this->top)) {
            $query .= $glue . '$top=' . $this->top;
        }

        if ($this->subQuery) {
            $query = preg_replace('/^;/', '', $query);
        } else {
            $query = preg_replace('/^(' . $this->entity . ')' . $glue . '\$/', '$1?$', $query);
        }

        return $query;
    }

    /**
     * @return string
     * @throws EntityNotSetException
     */
    public function __toString()
    {
        return $this->toQuery();
    }

    /**
     * Get results as a collection
     *
     * @return \Illuminate\Support\Collection
     * @throws EntityNotSetException
     */
    public function get()
    {
        if (is_null($this->client)) {
            throw new \Exception('To use ::get, you must first set client');
        }

        try {
            $result = $this->client->get($this->toQuery());
        } catch (ClientException $exception) {
            if ($exception->getCode() === 404) {
                return null;
            }

            throw $exception;
        }


        if (strpos($result->getHeader('Content-Type')[0], 'application/json') < 0) {
            throw new \Exception('Unexpected response');
        }

        $json = json_decode($result->getBody()->getContents(), true);

        // Assume collection
        if (isset($json['value'])) {
            return collect($json['value'])->map(function ($item) {
                return collect($item);
            });
        }

        // Assume single

        return collect($json)->except('@odata.context');

    }

}
