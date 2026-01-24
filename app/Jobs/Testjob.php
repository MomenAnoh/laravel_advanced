<?php

namespace App\Jobs;

use App\Models\product;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use function PHPUnit\Framework\throwException;

class Testjob implements ShouldQueue
{

    public $timeout=10;// Timeout -> use it to limit the job time if scape switch to another job, i can use it as function too

    //public $tries=3;  // Tries -> use it to limit the job retry if scape switch to failed job table, i can use it as function too
// public $backoff=3; // mean 3 seconds between each try
// public $backoff=[2,4,6]; // mean 2 seconds between before scound try , 4 scound before third try and 6 seconds before fourth try
    use Queueable;


    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }
    /*
     public function retryUntil()
       {
           return now()->addMinutes(1);  // try until 1 minute if job failed switch it to job failed table
        }

    /*

         if i want retry work jobs which failed i can do it by this command  php artisan queue:retry all
        this command will retry all failed jobs and switch it to job table and try work again
         if i want retry unique job i can use this command php artisan queue:retry job_id (uuid)

     */

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //throw new \Exception('job failed');   // test job failed
        //      sleep(15); // here job will be failed allways as sleap =15 and timeout =10
        Log::info('AHMED');
        //        product::where('id',$this->id)->update([
        //            'name'=>'test'
        //    ]);
    }

}
