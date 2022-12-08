<?php


namespace PriorityApi\Builder;


use Closure;
use DateTimeInterface;
use InvalidArgumentException;

class Filter
{
    protected $operators = [
        '==' => 'eq',
        '!=' => 'ne',
        '>'  => 'gt',
        '>=' => 'ge',
        '<'  => 'lt',
        '<=' => 'le',
    ];

    protected $filters = [];

    /**
     * Prepare the value and operator for a filter
     *
     * @param string $value
     * @param string $operator
     * @param bool   $useDefault
     *
     * @return array
     *
     * @throws InvalidArgumentException
     */
    public function prepareValueAndOperator($value, $operator, $useDefault = false)
    {
        if ($useDefault) {
            return [$operator, 'eq'];
        }

        return [$value, $operator];
    }

    public static function wrapValue($value)
    {
        // Check if is an integer
        if (is_int($value)) {
            return $value;
        }

        // Check if is a date
        if ($value instanceof DateTimeInterface) {
            $date = $value->format('Y-m-d\TH:i:sP');
            return str_replace('+', '%2B', $date);
        }

        return "'$value'";
    }

    protected function translateOperator($operator)
    {
        if (in_array($operator, $this->operators)) {
            return $operator;
        } else if (array_key_exists($operator, $this->operators)) {
            return $this->operators[$operator];
        }

        return $operator;
    }

    /**
     * Determine if the given operator is supported.
     *
     * @param string $operator
     *
     * @return bool
     */
    protected function invalidOperator($operator)
    {
        return !in_array($operator, $this->operators, true);
    }

    public function filter($property, $operator = null, $value = null, $boolean = 'and')
    {
        if ($property instanceof Closure) {

            $filter = new Filter();

            call_user_func_array($property, [$filter]);

            $this->filters[] = '(' . ((string)$filter) . ')';
            return $this;
        }

        // Here we will make some assumptions about the operator. If only 2 values are
        // passed to the method, we will assume that the operator is an equals sign
        // and keep going. Otherwise, we'll require the operator to be passed in.
        [$value, $operator] = $this->prepareValueAndOperator(
            $value, $operator, func_num_args() === 2
        );

        $operator = $this->translateOperator($operator);

        // If the given operator is not found in the list of valid operators we will
        // assume that the developer is just short-cutting the 'eq' operators and
        // we will set the operators to 'eq' and set the values appropriately.
        if ($this->invalidOperator($operator)) {
            list($value, $operator) = [$operator, 'eq'];
        }

        $value = $this->wrapValue($value);

        if (!count($this->filters)) {
            $this->filters[] = "$property $operator $value";
        } else {
            $this->filters[] = "$boolean $property $operator $value";
        }

        return $this;
    }

    public function orFilter($property, $operator = null, $value = null)
    {
        return $this->filter($property, $operator, $value, 'or');
    }

    public function __toString()
    {
        return implode(' ', $this->filters);
    }
}