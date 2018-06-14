<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.06.18
 * Time: 16:21
 */

namespace App\Models\Traits;

use Carbon\Carbon;

trait TimeConstrains
{
    /**
     * @param $query
     * @param $timeConstrains
     * @return mixed
     */
    public function addDateConstrains($query, $timeConstrains)
    {
        if (array_key_exists('date_from', $timeConstrains)) {
            $query = $this->dateFrom($query, $timeConstrains);
        }
        if (array_key_exists('date_from', $timeConstrains)) {
            $query = $this->dateTo($query, $timeConstrains);
        }

        return $query;
    }

    /**
     * @param $query
     * @param $timeConstrains
     * @return mixed
     */
    public function dateFrom($query, $timeConstrains)
    {
        $from = Carbon::createFromFormat('m/d/Y', $timeConstrains['date_from']);
        $query->where('updated_at', '>', $from);

        return $query;
    }

    /**
     * @param $query
     * @param $timeConstrains
     * @return mixed
     */
    public function dateTo($query, $timeConstrains)
    {
        $to = Carbon::createFromFormat('m/d/Y', $timeConstrains['date_to']);
        $query->where('updated_at', '<', $to);

        return $query;
    }
}