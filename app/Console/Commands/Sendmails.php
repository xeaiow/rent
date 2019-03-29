<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Recipient;
use Illuminate\Support\Facades\Mail;

class Sendmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending emails to the users.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = Recipient::where('switch', 1)->get();
        
        foreach ($users as $key => $value)
        {
            $name = $value['name'];
            $email = $value['email'];

            $data = [
                'name' => '嗨~'.$name.'，已完成今日的教室預約系統的資料備份。'
            ];

            Mail::send('email.welcome', $data, function ($message) use ($email) {
                $message->to($email)->subject('中原資管教室預約資料庫備份')->attach(storage_path('app\backups\rent.sql'));
            });
        }

        
        return 'Your email has been sent successfully!';
    }
}
