
# Step 1: Configure the Laravel
Install it by the following command.
- `composer create-project laravel/laravel --prefer-dist queue_email_service`
Now, in the .env file, configure the database and email server.
- `BROADCAST_DRIVER=log`
- `CACHE_DRIVER=file`
- `QUEUE_CONNECTION=database`
- `SESSION_DRIVER=file`
- `SESSION_LIFETIME=120`

- `REDIS_HOST=127.0.0.1`
- `REDIS_PASSWORD=null`
- `REDIS_PORT=6379`

- `MAIL_DRIVER=smtp`
- `MAIL_HOST=smtp.gmail.com`
- `MAIL_PORT=587`
- `MAIL_USERNAME=mymail@gmail.com`
- `MAIL_PASSWORD=password`
- `MAIL_ENCRYPTION=tls`
# Step 2: Create mail configuration for sending mail.

## We will create one route to send an email and also create one controller called EmailController.
- `php artisan make:controller EmailController`
## In routes >> web.php file, add the following code.
- `Route::get('email', 'EmailController@sendEmail');`


### Step 3: Configure Queue.

Now, we will need to create the job that purpose is to send the actual email to the user.
- `php artisan queue:table');`
- `php artisan migrate');`
- `php artisan make:job SendEmailJob');`
Now, the send email function will reside in the job file. So that job file looks like this.
```
<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to('mail@appdividend.com')->send(new SendMailable());
    }
}
```

# Step 4: Configure the EmailController like this

```
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
```


