<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Stripe\Charge;

/**
 * Class ChargesController
 * @package App\Http\Controllers
 */
class ChargesController extends Controller
{

    /**
     * ChargesController constructor.
     */
    public function __construct()
    {
        $this->middleware('can:index,' . Charge::class)->only(['index']);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {

        $date_from = now()->subYear(1)->format('m/d/Y');
        $date_to   = now()->format('m/d/Y');

        try {
            $data = (!$request->has('customer'))
                ? [] : ['customer' => $request->input('customer')];

            if ($request->has('date_from')) {
                $from                   = Carbon::createFromFormat('m/d/Y', $request->input('date_from'));
                $data['created']['gte'] = $from->timestamp;
                $date_from              = $request->input('date_from');
            }

            if ($request->has('date_to')) {
                $to                     = Carbon::createFromFormat('m/d/Y', $request->input('date_to'));
                $data['created']['lte'] = $to->timestamp;
                $date_to                = $request->input('date_to');
            }

            $charges = \Stripe\Charge::all($data);

            $charges = collect($charges->data);

            $charges->transform(function (Charge $charge) {
                $charge->customer = User::where('stripe_id', $charge->customer)->first();
                return $charge;
            });

            $clients = Cache::remember('clients', 60, function () {
                return User::withRole(Role::CLIENT)->get();
            });

            return view('pages.admin.charges.index',
                [
                    'charges'   => $charges,
                    'clients'   => $clients,
                    'date_from' => $date_from,
                    'date_to'   => $date_to
                ]);

        } catch (\Exception $e) {
            abort(500);
        }
    }
}
