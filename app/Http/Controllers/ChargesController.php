<?php

namespace App\Http\Controllers;

use App\Services\Charges\ChargesRepository;
use Illuminate\Http\Request;
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
     * @param ChargesRepository $chargesRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, ChargesRepository $chargesRepository)
    {
        $data =  $chargesRepository->charges($request->input());
        return view('pages.admin.charges.index',
            [
                'charges'   => $data['charges'],
                'clients'   => $data['clients'],
                'date_from' => $data['date_from'],
                'date_to'   => $data['date_to']
            ]);
    }
}
