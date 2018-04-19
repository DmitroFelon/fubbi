<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 1/13/18
 * Time: 4:24 PM
 */

namespace App\ViewComposers\Pages\Admin;


use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Stripe\Charge;

class LastChargesComposer
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function compose(View $view)
    {

        $key ='last_charges'.$this->request->input('customer').$this->request->input('date_from').$this->request->input('date_to');
        $last_charges = Cache::remember($key, Carbon::MINUTES_PER_HOUR*Carbon::HOURS_PER_DAY, function (){
            $data = [];

            if ($this->request->has('customer')) {
                $user = User::search($this->request->input('customer'))->first();
                $data = (!$user) ? [] : ['customer' => $user->stripe_id];
            }

            if ($this->request->has('date_from')) {
                $from                   = Carbon::createFromFormat('m/d/Y', $this->request->input('date_from'));
                $data['created']['gte'] = $from->timestamp;
            }

            if ($this->request->has('date_to')) {
                $to                     = Carbon::createFromFormat('m/d/Y', $this->request->input('date_to'));
                $data['created']['lte'] = $to->timestamp;
            }

            $charges = Charge::all($data);

            $last_charges = collect($charges->data);

            $last_charges->transform(function (Charge $charge) {
                $charge->customer = User::where('stripe_id', $charge->customer)->first();
                return $charge;
            });

            return $last_charges;
        });
        
        
        return $view->with(compact('last_charges'));
    }
}