<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\DB;

class ZoadilackController extends Controller{
    
    public function index(){                
        $connection = config('database');
        $sqlResult = DB::connection('mysql')->select('select * from email_settings');          
        $resultsSqlite = DB::connection('sqlite2')->select('select * from email_configurations');            
        return view('zoadilack.index',compact('connection','sqlResult','resultsSqlite'));                     
    }
    
    public function notify(Request $request){        
        if ($request->isMethod('post') ) {            
            if ($request->input('dbName')=='sqlite') {  
                try{
                    DB::connection('sqlite2')->table('email_configurations')->update(['email_body_template' => $request->input('email_body_template')]);
                    return redirect('/zoadilack')->with('sqlite_success', 'Details are Updated');   
                }catch (\Exception $e) {
                    return redirect('/zoadilack')->with('sqlite_failure', 'Details are not Updated');
                }
            }else if ($request->input('dbName')=='mysql') {   
                try{
                    DB::connection('mysql')->table('email_settings')->update(['email_from' => $request->input('email_from'),'email_subject' => $request->input('email_subject')]); 
                    return redirect('/zoadilack')->with('mysql_success', 'Details are Updated');
                }catch (\Exception $e) {
                    return redirect('/zoadilack')->with('sqlite_failure', 'Details are not Updated');
                }
            }else{                                               
                $validatedData = $request->validate([                    
                    'email' => 'required|email',
                ]);
                $sqlResult = DB::connection('mysql')->select('select * from email_settings');    
                $resultsSqlite = DB::connection('sqlite2')->select('select * from email_configurations');
                $fromEmail=$request->input('email'); 
                if($fromEmail=='') $fromEmail=env('SITE_NOTIFICATION_FROM_EMAIL', false);
                $sqlResult[0]->email_from=$fromEmail;                
                $sqlResult[0]->to=env('SITE_NOTIFICATION_TO_EMAIL', false);
                $sqlResult[0]->fromName=env('SITE_NOTIFICATION_FROM_NAME', false);                
                try{            
                    Mail::send('emails.'.$resultsSqlite[0]->email_body_template, $sqlResult, function ($message) use ($sqlResult) {                    
                        $message->from($sqlResult[0]->email_from, $sqlResult[0]->fromName);
                        $message->subject($sqlResult[0]->email_subject);
                        $message->to($sqlResult[0]->to);
                    });
                    return redirect('/zoadilack')->with('email_success', 'Email sent successfully');
                }catch (\Exception $e) {
                    return redirect('/zoadilack')->with('email_failure', 'Details are Updated');
                }                
            }            
        }            
    }    
}
