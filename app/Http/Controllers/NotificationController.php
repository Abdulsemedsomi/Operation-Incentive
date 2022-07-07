<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function fetch(Request $request){
        $notifications = auth()->user()->unreadNotifications()->paginate(15);
        
        
        if ($request->ajax()) {
    		$view = view('includes.notification',compact('notifications'))->render();
            return response()->json(['html'=>$view]);
        }
        return view('seeMoreNotifications',compact('notifications'));
    }


    public function showReadNotifications(Request $request){
        $notifications = auth()->user()->readNotifications()->paginate(15);
        
        
        if ($request->ajax()) {
    		$view = view('includes.readNotifications',compact('notifications'))->render();
            return response()->json(['html'=>$view]);
        }
        return view('showReadNotifications',compact('notifications'));
    }
    public function markAsUnRead($id){
        auth()->user()->readNotifications->where('id', $id)->markAsUnRead();
        return back();
    }

    public function markAsRead($id){
        auth()->user()->unreadNotifications->where('id', $id)->markAsRead();
        return back();
    }
    public function markAllAsRead(){
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        return back();
    }

    public function markNotification(Request $request){
        auth()->user()
        ->unreadNotifications
        ->when($request->input('id'),function($query) use($request){
            return $query->where('id',$request->input('id'));
        })
        ->markAsRead();
        return response()->noContent();
    }
}
