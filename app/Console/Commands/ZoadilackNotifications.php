<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\DB;

class ZoadilackNotifications extends Command{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoadilack:notify {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Zoadilack Notification Email Command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(){
        
        $toEmail=$this->argument('email'); 
        if($toEmail=='') $toEmail=env('SITE_NOTIFICATION_TO_EMAIL', false);        
        
        $sqlResult = DB::connection('mysql')->select('select * from email_settings');    
        $resultsSqlite = DB::connection('sqlite2')->select('select * from email_configurations');      
        $sqlResult[0]->to=$toEmail;
        $sqlResult[0]->fromName=env('SITE_NOTIFICATION_FROM_NAME', false);        
        try{
            Mail::send('emails.'.$resultsSqlite[0]->email_body_template, $sqlResult, function ($message) use ($sqlResult) {                    
                $message->from($sqlResult[0]->email_from,$sqlResult[0]->fromName);
                $message->subject($sqlResult[0]->email_subject);
                $message->to($sqlResult[0]->to);
            });
            $this->info('Successfully Email sent');
        }catch (\Exception $e) {
            $this->error('Email is not sent!');
        }
    }
}
