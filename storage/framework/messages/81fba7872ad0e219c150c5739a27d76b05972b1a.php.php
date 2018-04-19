<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class WebhookController extends CashierController
{
	public function handleInvoicePaymentSucceeded($payload)
	{
		Log::debug(\GuzzleHttp\json_encode($payload));
		return ['ok'];
	}

	public function handleInvoiceUpcoming($payload)
	{
		Log::debug(\GuzzleHttp\json_encode($payload));
		return ['ok'];
	}

	public function handleInvoiceCreated($payload)
	{
		Log::debug(\GuzzleHttp\json_encode($payload));
		return ['ok'];
	}
}
