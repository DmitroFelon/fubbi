<?php
/**
 * Created by PhpStorm.
 * User: imad
 * Date: 1/13/18
 * Time: 4:24 PM
 */

namespace App\ViewComposers\Pages\Admin;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Stripe\Charge;

/**
 * Class RejectedChargesComposer
 * @package App\ViewComposers\Pages\Admin
 */
class RejectedChargesComposer
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    /**
     * @param View $view
     * @return $this
     */
    public function compose(View $view)
    {

        $rejected_charges = Cache::remember('rejected_charges', 60, function () {
            $all_charges      = Charge::all(['limit' => 100]);
            $rejected_charges = collect($all_charges->data);
            return $rejected_charges->filter(function (Charge $charge) {
                if ($charge->status != 'succeeded') {
                    $charge->customer = User::where('stripe_id', $charge->customer)->first();
                    return $charge;
                }
            });
        })->take(10);

        return $view->with(compact('rejected_charges'));
    }
}