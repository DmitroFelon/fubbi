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
 * Class PendingChargesComposer
 * @package App\ViewComposers\Pages\Admin
 */
class PendingChargesComposer
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

        $I = $this->request->input();

        $pending_charges = Cache::remember('pending_charges', 60, function () {
            $all_charges     = Charge::all(['limit' => 100]);
            $pending_charges = collect($all_charges->data);
            return $pending_charges->filter(function (Charge $charge) {
                if ($charge->status == 'pending') {
                    $charge->customer = User::where('stripe_id', $charge->customer)->first();
                    return $charge;
                }
            });
        })->take(10);

        return $view->with(compact('pending_charges'));
    }
}