<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 01.06.18
 * Time: 16:53
 */

namespace App\Services\Charges;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use App\Models\Role;
use Stripe\Charge;

/**
 * Class ChargesRepository
 * @package App\Services\Charges
 */
class ChargesRepository
{
    /**
     * @param array $params
     * @return array
     */
    public function charges(array $params)
    {
        $data['date_from'] = now()->subYear(1)->format('m/d/Y');
        $data['date_to']   = now()->format('m/d/Y');
        $data['customer'] = [];
        try {
            $data = $this->filter($params, $data);
            $data['charges'] = $this->chargesPerCustomer($data);
            $data['clients'] = Cache::remember('clients', 60, function () {
                return User::withRole(Role::CLIENT)->get();
            });
        } catch (\Exception $e) {
            abort(500);
        }
        return $data;
    }

    /**
     * @param array $params
     * @param array $data
     * @return array
     */
    public function filter(array $params, array $data)
    {
        $temp['customer'] = !array_key_exists('customer', $params)
            ? []
            : ['customer' => $params['customer']];
        if (array_key_exists('date_from', $params)) {
            $from                   = Carbon::createFromFormat('m/d/Y', $params['date_from']);
            $temp['created']['gte'] = $from->timestamp;
            $temp['date_from']      = $params['date_from'];
        }
        if (array_key_exists('date_to', $params)) {
            $to                     = Carbon::createFromFormat('m/d/Y', $params['date_to']);
            $temp['created']['lte'] = $to->timestamp;
            $temp['date_to']        = $params['date_to'];
        }
        if(!empty($temp) && !empty($temp['customer'])) {
            $data = $temp;
        }
        return $data;
    }

    /**
     * @param array $data
     * @return \Illuminate\Support\Collection|\Stripe\Collection
     */
    public function chargesPerCustomer(array $data)
    {
        $charges = Charge::all($data['customer']);
        $charges = collect($charges->data);
        $charges->transform(function (Charge $charge) {
            $charge->customer = User::where('stripe_id', $charge->customer)->first();
            return $charge;
        });
        return $charges;
    }
}