<?php

namespace App\Helper;

use Illuminate\Database\Eloquent\Builder as BaseBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Builder extends BaseBuilder
{
    protected $model;

    public function __construct(QueryBuilder $query)
    {
        parent::__construct($query);
    }

    /**
     * @todo get count of record
     *
     * @param array $where
     * @param array $join
     * @return int
     */
    public function getTableCount($where = [], $join = [])
    {
        $query = $this->where($where);
        foreach ($join as $j) {
            $query->join($j[0], $j[1], $j[2], $j[3]);
        }
        return $query->get(['*'])->count();
    }

    /**
     * @todo get all records based on condition
     *
     * @param array $where -> array('field', 'operator', 'value', AND/OR)
     * @param boolean $all
     * @param array $select
     * @param int $limit
     * @param int $offset
     * @param string $orderBy
     * @param array|string $rulesOrder
     * @param array of array join (second array [table, firstfield, operator, secondfield)
     *
     * @return array collection
     */
    public function find_v2($where, $all = false, $select = ['*'], $limit = 0, $offset = 0, $orderBy = 'id', $rulesOrder = 'ASC', $join = [])

    {
        $query = $this->where($where)
            ->orderBy($orderBy, $rulesOrder);

        if ($limit != 0) {
            $query->limit($limit)
                ->offset($offset);
        }

        foreach ($join as $j) {
            if (!isset($j[4])) {
                $j[4] = 'inner';
            }
            if ($j[4] == 'inner') {
                $query->join($j[0], $j[1], $j[2], $j[3]);
            } else if ($j[4] == 'left') {
                $query->leftJoin($j[0], $j[1], $j[2], $j[3]);
            }
        }

        if ($all) {
            return $query->get($select);
        }
        return $query->first($select);
    }
}