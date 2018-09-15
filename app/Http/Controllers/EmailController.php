<?php
namespace App\Http\Controllers;
use App\Jobs\SendEmailJob;
use Auth;
use Carbon\Carbon;

class EmailController extends Controller {
	public function sendEmail() {
		$user = Auth::user();
		$emailJob = (new SendEmailJob($user))->delay(Carbon::now()->addSeconds(10));
		dispatch($emailJob);
		echo 'email sent';

	}
}