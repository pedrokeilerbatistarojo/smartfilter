<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Traits;

use Pedrokeilerbatistarojo\Smartfilter\Exceptions\ValidationException;

trait TraitUseFilter
{
    protected array $joins = [];

    protected array $joinTables = [];

    protected array $functions = [];

    /**
     * @throws ValidationException
     * @throws \Exception
     */
    protected function applyFilters(): void
    {
        $payload = $this->payload;
        sort($payload->filters);
        $table1 = $this->entity->getTable();
        $valid_filters = $this->validFilters ?? [];

        $start_date = '';
        $end_date = '';
        $date_field1 = 'start_date';
        $date_field2 = 'end_date';

        $skips = [];
        $rewrite = [];
        $errors = [];

        foreach ($payload->filters as $filter) {

            $field = $filter[0];

            if (count($filter) < 3) {
                $errors[$field] = "$field filter is invalid";
            }

            if (! isset($valid_filters[$field])) {
                $errors[$field] = "$field is not allowed filter";
            }
        }

        if (count($errors)) {
            $errors = [
                'valid_filters' => $valid_filters,
                'errors' => $errors,
            ];
            throw new ValidationException($errors, 'Invalid search filter');
        }

        $pos = 0;
        foreach ($payload->filters as &$filter) {

            [$field1, $operator, $value] = $filter;
            $old_field = $field1;

            //rewrite default filters
            if (isset($valid_filters[$field1])) {
                //field, operator, real field
                [$operator, $real_field, $type] = $valid_filters[$field1];
                $filter = [$real_field, $operator, $value];
                [$field1, $operator, $value] = $filter;
            }

            //Todo: move to better place
            if ($old_field === 'month') {
                $skips[] = $pos;
                $rewrite[$old_field] = $filter;
            } elseif ($old_field === 'year') {
                $skips[] = $pos;
                $rewrite[$old_field] = $filter;
            } elseif ($old_field === 'start_date') {
                $date_field1 = $field1;
                $start_date = $value;
                $skips[] = $pos;
            } elseif ($old_field === 'end_date') {
                $date_field1 = $field1;
                $end_date = $value;
                $skips[] = $pos;
            }

            $pos++;
        }

        foreach ($skips as $skip) {
            unset($payload->filters[$skip]);
        }

        if (isset($rewrite['year'])) {
            [,, $value] = $rewrite['year'];

            /*if(!isset($rewrite['month'])){
                $this->functions[] = ['whereMonth', "$table1.created_at", '=', intval(date('n'))];
            }*/

            $this->functions[] = ['whereYear', "$table1.created_at", '=', intval($value)];
        }

        if (isset($rewrite['month'])) {

            if (! isset($rewrite['year'])) {
                $this->functions[] = ['whereYear', "$table1.created_at", '=', intval(date('Y'))];
            }

            [,, $value] = $rewrite['month'];
            $this->functions[] = ['whereMonth', "$table1.created_at", '=', intval($value)];
        }

        if ($date_field1 === 'timestamp') {
            $date_field2 = $date_field1;
        }

        if (strlen($start_date) && strlen($end_date)) {
            $start = new \DateTime($start_date);
            $end = new \DateTime($end_date);
            $start->setTime(0, 0, 0);
            $end->setTime(23, 59, 59);

            if ($date_field1 === 'timestamp') {
                $this->functions[] = ['whereBetween', "$table1.$date_field1", '=', [$start, $end]];
            } else {
                $this->functions[] = ['whereDate', "$table1.$date_field1", '>=', $start];
                $this->functions[] = ['whereDate', "$table1.$date_field2", '<=', $end];
            }

        } elseif (strlen($start_date)) {
            $this->functions[] = ['whereDate', "$table1.$date_field1", '>=', $start_date];
        } elseif (strlen($end_date)) {
            $this->functions[] = ['whereDate', "$table1.$date_field2", '<=', $end_date];
        }

        if ($payload->sortField === 'id') {
            $payload->sortField = $table1.'.id';
        }
    }
}
