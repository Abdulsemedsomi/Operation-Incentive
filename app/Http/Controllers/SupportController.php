<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
class SupportController extends Controller
{
    //
    public function index(){
        return view('support');
     }
    public function oops(){
        return view('oops');
     }
     public function thanks(){
        return view('thanks');
     }
       public function thanks2(){
        return view('thanks2');
     }
     public function guest(){
         return view('support2');
     }
    public function do_contact(){
      
        $attr = request()->validate([
            'name' =>'required',
            'email' =>'required',
            'subject' =>'required',
            'message' =>'required',
        ]);
    
        
       
            $client = new \GuzzleHttp\Client();
            $response= $client ->request('Post', 'https://helprealm.io/api/561ffbbada78e6e40a7d2069049319b4/ticket/create',[
 
                'form_params' =>[
                    'name' =>$attr ['name'],
                    'email' =>$attr ['email'],
                    'subject' =>$attr ['subject'],
                    'text' =>$attr ['message'],
                    'prio' =>'1',
                    'type' =>'144',
                    'apitoken' =>'591d1bb69f6ff3d000aa0da22617b9b5',
                ]
            ]);
            if($response->getStatusCode() == 200){
                if (Auth::check()) {
                 // The user is logged in...
                   return redirect('/thanks2');
                }
                return redirect('/thanks');
            // $result =json_decode($response->getBody());
            // dd($result);

                }else{ 
             return redirect('/oops');
            }

        
    }
}
