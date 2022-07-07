<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewComment;
use App\Notifications\NewPlanComment;
use Xetaio\Mentions\Parser\MentionParser;
use App\Plan;
use App\Report;
use App\Team;
use App\Project;
use App\Projectcheckin;
use App\Projectmember;
use App\User;

class CommentController extends Controller
{
    //
    public function index(Request $request)

    {
        //

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
      public function markAsRead(Request $request){
        auth()->user()->unreadNotifications->find($request->id)->markAsRead();
    }
    public function store(Request $request)
    {
       
        $input = $request->all();
        
        $input['commentor_id'] = Auth::user()->id;
        $comment = Comment::create($input);
        $parser = new MentionParser($comment);
        $content = $parser->parse($comment->comment);
       
         if($request->has('report_id')){
             
              $report = Report::find($input['report_id']);
              if($report && $comment->commentor_id ==$report->reportsTo ){
                    $report->isCommented = 1;
                    $report->save();
              }
            
         }
          if($request->has('plan_id')){
              $plan = Plan::find($input['plan_id']);
              if($plan && $comment->commentor_id == $plan->reportsTo ){
              $plan->isCommented = 1;
              $plan->save();
              }
         }
          $comment->comment = $content;
           $IsMentioned = strstr( $content, '@' );
        
        if($IsMentioned == false){
            $user = 0;
                    //notification when comment is given on reports(daily and weekly)
                    if($request->has('report_id')){
                        $report = Report::find($input['report_id']);
                        //dd($report);
                        $user = Report::find($input['report_id'])->user_id;
                        if(Report::find($input['report_id'])->user_id != $input['commentor_id']){
                            $fs = User::find($user);
                            //notification to the reporter
                            $fs->notify(new \App\Notifications\ReportComment($report));
                        }
                        else{
                            //get team of a user
                            $manager = Team::where('team_name', Auth::user()->team)->first();
                            //get team manager
                            $managerid = $manager->manager_id;
                            if($managerid != null){
                                $muser = User::find($managerid);
                                //notification to the manager
                                $muser->notify(new \App\Notifications\ToManagerReportComment($report));
                            }
                        }
                    }
                    //notification when comment is given on plans (daily, weekly)
                    if($request->has('plan_id')){
                        $plan = Plan::find($input['plan_id']);
                        $user = Plan::find($input['plan_id'])->userid;
                        if(Plan::find($input['plan_id'])->userid != $input['commentor_id']){
                            $fs = User::find($user);
                            //notification to the planner
                            $fs->notify(new \App\Notifications\NewComment($plan));
                        }
                        else{
                            //get team of a user
                            $manager = Team::where('team_name', Auth::user()->team)->first();
                            //dd($manager);
                            //get team manager
                            $managerid = $manager->manager_id;
                            if($managerid != null){
                                $muser = User::find($managerid);
                                //notification to the manager
                                $muser->notify(new \App\Notifications\ToManager($plan));
                            }
                        }
                        
                    }
                    //  if($request->has('project_id')){
                    //     $plan = Projectcheckin::find($input['project_id']);
                    //     $user = Projectcheckin::find($input['project_id'])->userid;
                    //     if(Projectcheckin::find($input['project_id'])->userid != $input['commentor_id']){
                    //         $fs = User::find($user);
                    //         //notification to the planner
                    //         $fs->notify(new \App\Notifications\NewComment($plan));
                    //     }
                    //     else{
                    //         //get team of a user
                    //         $manager = Team::where('team_name', Auth::user()->team)->first();
                    //         //dd($manager);
                    //         //get team manager
                    //         $managerid = $manager->manager_id;
                    //         if($managerid != null){
                    //             $muser = User::find($managerid);
                    //             //notification to the manager
                    //             $muser->notify(new \App\Notifications\ToManager($plan));
                    //         }
                    //     }
                        
                    // }
        }
  
        return redirect()->back()
            ->with('success', 'Comment added successfully.');
        //

      
   
   
    }
        public function editaccess(Request $request)
    {
       
        $input = $request->all();
    
        $input['commentor_id'] = Auth::user()->id;
        $comment = Comment::create($input);
        $all = -1;
        $date = $comment->created_at;
        if($request->has('plan_id')){
            $date = Comment::where('plan_id', $input['plan_id'])->where('type', 2)->first()?Comment::where('plan_id', $input['plan_id'])->where('type', 2)->first()->created_at: $comment->created_at;
            $comment->created_at =$date; 
            $comment->save();
            $c = Comment::where('plan_id', $input['plan_id'])->where('type', 2)->delete();
            $all = Comment::where('plan_id', $input['plan_id'])->get()->count();
        }
        if($request->has('report_id')){
             $date = Comment::where('report_id', $input['report_id'])->where('type', 2)->first()?Comment::where('report_id', $input['report_id'])->where('type', 2)->first()->created_at: $comment->created_at;
              $comment->created_at =$date; 
              $comment->save();
            $c = Comment::where('report_id', $input['report_id'])->where('type', 2)->delete();
            $all = Comment::where('report_id', $input['report_id'])->get()->count();
        }
        $parser = new MentionParser($comment);
        $content = $parser->parse($comment->comment);
        $comment->comment = $content;
        
           $IsMentioned = strstr( $content, '@' );
        
        if($IsMentioned == false){
            $user = 0;
                    //notification when comment is given on reports(daily and weekly)
                    if($request->has('report_id')){
                        $report = Report::find($input['report_id']);
                        //dd($report);
                        $user = Report::find($input['report_id'])->user_id;
                        if(Report::find($input['report_id'])->user_id != $input['commentor_id']){
                            $fs = User::find($user);
                            //notification to the reporter
                            $fs->notify(new \App\Notifications\ReportComment($report));
                        }
                        else{
                            //get team of a user
                            $manager = Team::where('team_name', Auth::user()->team)->first();
                            //get team manager
                            $managerid = $manager->manager_id;
                            if($managerid != null){
                                $muser = User::find($managerid);
                                //notification to the manager
                                $muser->notify(new \App\Notifications\ToManagerReportComment($report));
                            }
                        }
                    }
                    //notification when comment is given on plans (daily, weekly)
                    if($request->has('plan_id')){
                        $plan = Plan::find($input['plan_id']);
                        $user = Plan::find($input['plan_id'])->userid;
                        if(Plan::find($input['plan_id'])->userid != $input['commentor_id']){
                            $fs = User::find($user);
                            //notification to the planner
                            $fs->notify(new \App\Notifications\NewComment($plan));
                        }
                        else{
                            //get team of a user
                            $manager = Team::where('team_name', Auth::user()->team)->first();
                            //dd($manager);
                            //get team manager
                            $managerid = $manager->manager_id;
                            if($managerid != null){
                                $muser = User::find($managerid);
                                //notification to the manager
                                $muser->notify(new \App\Notifications\ToManager($plan));
                            }
                        }
                        
                    }
                
        }
  
        return $all;

      
   
   
    }
    public function deleteboost(Request $request){
        $message = 0;
        $input = $request->all();
           if($request->has('plan_id')){
                $plan = Plan::find($input['plan_id']);
            Comment::where('plan_id', $input['plan_id'])->where( 'commentor_id', Auth::user()->id)->where('type', 2)->delete();
                
                //are there other comments by the manager?
                $comment = $plan?Comment::where('plan_id', $input['plan_id'])->where( 'commentor_id', $plan->reportsTo)->first():null;
                if(!$comment){
                    $plan->isCommented = 0;
                    $plan->save();
                }
            $message = 1;
        }
        if($request->has('report_id')){
            $report = Report::find($input['report_id']);
            Comment::where('report_id', $input['report_id'])->where( 'commentor_id', Auth::user()->id)->where('type', 2)->delete();
               $message = 1;
                $comment = $report? Comment::where('report_id', $input['report_id'])->where( 'commentor_id', $report->reportsTo)->first(): null;
                if(!$comment){
                    $report->isCommented = 0;
                    $report->save();
                }
        }
        return $message;
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //


    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $input = $request->all();
        
     
       Comment::where('id',$id)->update(['comment' => $input['comment']]);
        return redirect()->back()
            ->with('success', 'Comment updated successfully.');
    
    
    }
    public function updateStatus(Request $request){

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
          $comment = Comment::find($id);
           Comment::destroy($id);
          if($comment->plan_id != null){
                $plan = Plan::find($comment->plan_id);
                if($plan){
                     //are there other comments by the manager?
                $commentv = Comment::where('plan_id', $plan->id)->where( 'commentor_id', $plan->reportsTo)->first();  
                   if(!$commentv){
                    $plan->isCommented = 0;
                    $plan->save();
                }  
                }
                
             
               
         
        }
        if($comment::whereNotNull('report_id')){
            $report = Report::find($comment->report_id);
            
                if($report){  

                     $commentv = Comment::where('report_id', $report->id)->where( 'commentor_id', $report->reportsTo)->first();
                   
                if(!$commentv){
                   
                    $report->isCommented = 0;
                    $report->save();
                }
                }
               
        }
        
        
         return redirect()->back()
         
         ->with('success', 'Comment deleted successfully');

    }
}
