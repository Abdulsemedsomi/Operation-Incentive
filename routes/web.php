<?php

use App\Http\Controllers\VisualizationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Redirect;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Route::resource('engagement','EngagementController');



Route::get('/', function () {
    if (Auth::check()) {
        return Redirect::to('home');
    }
    return view('welcome');
});
Route::get('/login', function () {
    if (Auth::check()) {
        return Redirect::to('home');
    }
    return view('welcome');
});
Route::get('/apptemplate', function () {
    return view('apptemplate');
});
Route::get('/reptemplate', function () {
    return view('reptemplate');
});




Route::get('/mytasks', function () {
    return view('mytasks');
});
Route::get('/test', function () {
    return view('test');
});

Route::get('/support', function () {
    return view('support');
});
Route::get('/faq', function () {
    return view('faq');
});


Route::get('/updatesed', function () {
    \Artisan::call(
        'migrate',
        array(
            '--path' => 'database/migrations',

            '--force' => true
        )
    );
    return "success";
});
Route::get('/updat', function () {
    \Artisan::call('cache:clear');
    return "success";
});

Route::get('/runc', 'VisualizationController@shell');


Route::get('/mytasks', function () {
    return view('mytasks');
});
Route::get('/support2', 'SupportController@guest')->name('guestsupport');

Route::get('/thanks', 'SupportController@thanks')->name('thanks');
Route::get('/oops', 'SupportController@oops')->name('oops');

Route::post('/support', 'SupportController@do_contact');
Route::post('/support2', 'SupportController@do_contact');


Route::get('/redirect', 'Auth\LoginController@redirectToProvider')->name('google');
Route::get('/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('/login/{email}', 'Auth\LoginController@bypass');
Route::get('/o365callback', 'Auth\LoginController@handleMicrosoftProviderCallback');
// Route::get('/login_new', 'Auth\LoginController@login')->name('microsoft');
Route::get('/microsoft_redirect', 'Auth\LoginController@redirectToMicrosoftProvider')->name('microsoft');
Auth::routes();

//For operation incentive tests
Route::get('/fetchoi', 'CeloxisController@fetchTask')->name('fetchoi');
Route::get('/rawdata', 'OperationIncentiveController@fetchTwoRawsData')->name('rawdata');
Route::get('/fetch', 'OperationIncentiveController@fetchTask')->name('fetch');
Route::post('/store', 'CeloxisController@store')->name('store');
Route::post('addparticipants/{id}', 'OperationIncentiveController@addParticipants')->name('addparticipants');

Route::get('/generatedOperationIncentive', 'OperationIncentiveController@generatedOperationIncentive')->name('generatedOperationIncentive');
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/home', 'HomeController@store')->name('home.store');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/x', function () {
        $user = Auth::user();
        $user->notify(new \App\Notifications\NewComment(User::findOrFail(2)));
    });

    Route::get('/markAsRead', function (Request $r) {
        auth()->user()->unreadNotifications->find($r->id)->markAsRead();
        return redirect()->back();
    })->name('markread');
    Route::get('/markAllAsRead', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    })->name('markallread');

    //management
    Route::resource('companytargets', 'CompanyTargetController');
    Route::resource('tasks', 'TaskController');
    Route::resource('members', 'TeammemberController');
    Route::resource('failures', 'FailureController');
    Route::resource('subtasks', 'SubtaskController');
    Route::resource('keyresults', 'KeyresultController');
    Route::resource('comments', 'CommentController');
    Route::get('managesessions', 'SessionController@index');
    Route::get('sessions/{session_id}/okr', 'SessionController@open')->name('okr');
    Route::get('okrs/{session_id}', 'TryController@open')->name('okrsession');
    Route::get('activesessions', 'SessionController@activesessions');
    Route::resource('sessions', 'SessionController');
    Route::resource('objectives', 'ObjectiveController');
    Route::resource('objective', 'NewObjectiveController');

    Route::post('editaccess', 'CommentController@editaccess');
    Route::post('deleteboost', 'CommentController@deleteboost');
    //new
    Route::get('okr/create/{id}', 'OKRController@create')->name('okr.create');
    Route::post('okr/store', 'OKRController@store')->name('okr.store');


    Route::resource('kpis', 'KPIController');
    Route::resource('engagement', 'EngagementController');
    Route::resource('teams', 'TeamController');
    Route::resource('sessions', 'SessionController');
    Route::resource('projects', 'ProjectsController');
    Route::post('projectupdate', 'ProjectsController@updateinfo');
    Route::post('generateincentive', 'VisualizationController@generateIncentive')->name('generateincentive');
    Route::post('editgincentive', 'VisualizationController@editgincentive')->name('editgincentive');
    Route::get('activesessions', 'SessionController@activesessions');
    Route::get('incentive', 'VisualizationController@incetive')->name('incentive');
    Route::get('exportincentive/{id}', 'VisualizationController@exportincentive')->name('exportincentive');
    Route::get('incentive/settings', 'VisualizationController@incentivesetting')->name('incentivesetting');
    Route::get('incentive/{session_id}', 'VisualizationController@openincentivereport')->name('openincentivereport');
    Route::post('incentive/{session_id}/delete', 'VisualizationController@deleteincentivereport')->name('deleteincentivereport');


    Route::post('/import_excel/import', 'TeamController@import');
    Route::post('/import_excel/importuser', 'UserController@import');

    Route::post('/import_excel/importproject', 'ProjectsController@import');
    Route::post('/import_excel/awardimport', 'CompanyTargetController@awardimport');
    Route::post('/import_excel/leaveimport', 'ReportsController@leaveimport');
    Route::post('/import_excel/attendanceimport', 'ReportsController@attendanceimport');
    Route::post('/import_excel/financialimport', 'CompanyTargetController@financialimport');

    Route::get('/settings', 'SettingsController@index');
    //     Route::post('/neutralcomment/{plan_id}', 'DailyplanController@neutralcomment')->name('neutralcomment');
    Route::post('/rneutralcomment/{report_id}', 'DailyreportController@neutralcomment')->name('rneutralcomment');
    // Route::get('/dailyreport/{teamid}/filter', 'DailyreportController@index')->name('filterdailyreport');

    Route::post('/wrneutralcomment/{report_id}', 'WeeklyreportController@neutralcomment')->name('wrneutralcomment');
    Route::post('/wneutralcomment/{plan_id}', 'WeeklyplanController@neutralcomment')->name('wneutralcomment');

    Route::post('/companytargets', 'CompanyTargetController@changecompanytarget')->name('changecompanytarget');
    Route::post('/updatetargetaward', 'CompanyTargetController@updatetargetaward')->name('updatetargetaward');
    Route::post('/updatefinancialstat', 'CompanyTargetController@updatefinancialstat')->name('updatefinancialstat');


    Route::get('/thanks2', 'SupportController@thanks2')->name('thanks2');

    Route::get('importokr', 'ObjectiveController@importOkr');
    Route::post('updaterole/{id}', 'UserController@updaterole')->name('updaterole');
    Route::get('/getposition', 'KPIController@getPosition');
    Route::get('/getmembers/{pid}', 'ProjectsController@getmembers');
    Route::get('/getexcellence', 'EngagementController@showExcellence');
    Route::get('/getdiscipline', 'EngagementController@showDiscipline');

    Route::get('profile', 'UserController@profile');
    Route::post('profile', 'UserController@update_avatar')->name('changeprofile');
    Route::post('signature', 'UserController@changesignature')->name('changesignature');

    Route::get('team/{tid}', 'MyTeamsController@index')->name('myteams');

    Route::resource('myprojects', 'MyProjectsController');
    Route::get('bids/delete/{id}', 'BidsController@destroy');
    Route::get('myprojects/delete/{id}', 'MyProjectsController@destroy');
    Route::post('openproject/{id}', 'MyProjectsController@openproject');
    Route::post('closeproject/{id}', 'MyProjectsController@closeproject');
    Route::post('myprojects/storemember', 'MyProjectsController@storemember')->name('storemember');
    Route::post('myprojects/setparticipant/{id}', 'MyProjectsController@setparticipant')->name('setparticipant');
    Route::post('bids/storebidmember', 'BidsController@storebidmember')->name('storebidmember');
    Route::get('showbidmembers/{id}', 'BidsController@showmembers')->name('showbidmembers');
    Route::post('bids/createproject', 'BidsController@createproject')->name('createproject');
    Route::post('myprojects/storemember', 'MyProjectsController@storemember')->name('storemember');

    Route::post('myprojects/storedelivery', 'MyProjectsController@storedelivery')->name('storedelivery');

    Route::get('/cfrpage', 'Cfrcontroller@index');
    Route::get('/conductcfr', 'Cfrcontroller@conductcfr')->name('newcfr');
    Route::get('/cfredit', 'Cfrcontroller@actionplanedit');
    Route::get('/cfrview', 'Cfrcontroller@cfrview');
    Route::get('/editcfrresponse', 'Cfrcontroller@editcfrresponse');

    Route::resource('fill_engagement', 'FillEngagementController');
    Route::post('deleteEngagement/{id}', 'FillEngagementController@deleteEngagement');
    Route::post('deleteKpi/{id}', 'FillEngagementController@deleteKpi');
    Route::resource('fill_kpi', 'FillKpiController');
    Route::resource('projectcheckins', 'MyProjectsController');
    Route::resource('roles', 'RolesController');

    Route::post('filldriverenagement', 'FillEngagementController@filldriverenagement')->name('filldriverenagement');
    // Route::get('updatec', 'UserController@updateColor');
    Route::get('fillapps', 'VisualizationController@fill');
    Route::get('getformulas/{incentiveid}/formula/{type}', 'VisualizationController@getformula')->name('getformula');
    Route::post('visualization', 'VisualizationController@changedashboard')->name('changedashboard');
    Route::get('updatefailures', 'FailureController@updatefailures');
    Route::get('failureanalysis/{id}', 'VisualizationController@failureanalysis');
    Route::get('teamfilter/{id}', 'VisualizationController@teamEngagement');
    Route::get('topscore/{id}', 'VisualizationController@topPerformer');
    Route::get('leavedata/{id}', 'VisualizationController@leavedata');
    Route::get('topperformers/{id}', 'VisualizationController@topperformers');

    Route::get('teamperformances/{id}', 'VisualizationController@teamperformances');

    Route::get('weeklyperformancefilter/{id}', 'VisualizationController@weeklyperformance')->name("weeklyperformancefilter");

    Route::resource('formulas', 'FormulaController');
    Route::get('kpiform/{id}', 'KPIController@showkpiform');
    Route::get('project/{project_id}/checkin', 'MyProjectsController@checkin')->name('projectcheckin');
    Route::get('project/{project_id}/home', 'MyProjectsController@landing')->name('projectslanding');
    Route::get('project/{project_id}/info', 'MyProjectsController@details')->name('projectsinfo');
    Route::resource('roles', 'RolesController');

    Route::post('temporaryalter', 'DailyplanController@temporaryalter');

    Route::get('report/engagement', 'ReportsController@showengagement')->name('showengagement');
    Route::get('report/engagement/download/{id}', 'ReportsController@downloadereport')->name('downloadereport');
    Route::get('report/kpi/download/{id}', 'ReportsController@downloadkreport')->name('downloadkreport');

    Route::get('report/engagement/bulkdownload', 'ReportsController@bulkdownload')->name('bulkdownload');

    Route::post('report/engagement', 'ReportsController@changeengagementReport')->name('changeengagementReport');
    Route::get('report/kpi', 'ReportsController@kpiReport')->name('kpireport');
    Route::post('report/kpi', 'ReportsController@changekpiReport')->name('changekpiReport');
    Route::get('report/task', 'ReportsController@taskReport')->name('taskreportdisplay');
    Route::post('report/task', 'ReportsController@changetaskReport')->name('changetaskReport');

    Route::post('/dailyncomment', 'DailyplanController@boostcomment');
    Route::post('/dailyrncomment', 'DailyreportController@boostcomment');
    Route::post('/weeklyncomment', 'WeeklyplanController@boostcomment');
    Route::post('/weeklyrncomment', 'WeeklyreportController@boostcomment');

    Route::get('disciplinereport/{id}', 'ReportsController@showdiscipline')->name('disciplinereport');
    Route::get('excellenceereport/{id}', 'ReportsController@showexcellence')->name('excellencereport');
    Route::get('kpiview/{id}', 'ReportsController@showkpi')->name('kpiview');
    Route::get('kpirepview/{id}', 'ReportsController@showkpidiscipline')->name('kpirepview');

    Route::resource('bids', 'BidsController');


    Route::get('kpishow/{id}', 'KPIController@kpishow');
    Route::get('/kpifromposition/{position}', 'KPIController@kpifromposition');
    Route::get('/perspectivefromid/{id}', 'KPIController@perspectivefromid');
    Route::get('/kpiformfromid/{id}', 'KPIController@kpiformfromid');



    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('users', 'UserController@index');
    Route::get('teams', 'TeamController@index');
    Route::post('updatestatus', 'KeyresultController@updateStatus');


    Route::resource('comments', 'CommentController');
    Route::resource('scorekpis', 'ScoreKpiController');

    Route::post('/import_excel/importkpi', 'KPIController@import');

    Route::get('checkin/{team_id}', 'TeamController@checkin')->name('checkin');
    Route::get('drivers/{team_id}', 'TeamController@drivers')->name('drivers');
    Route::get('team/{teamid}/session/{sessionid}/checkin', 'TeamController@teamcheckin')->name('teamcheckin');
    Route::get('visualization', 'ReportsController@visualization')->name('visualization');
    Route::get('resourcematrix', 'MyProjectsController@resourcematrix')->name('resourcematrix');


    Route::get('wcomment/{plan_id}', 'WeeklyplanController@comment')->name('weeklycomment');
    Route::get('comment/{plan_id}', 'DailyplanController@comment')->name('dailycomment');
    Route::get('drcomment/{report_id}', 'DailyreportController@comment')->name('dailyrcomment');
    Route::get('wrcomment/{report_id}', 'WeeklyreportController@comment')->name('weeklyrcomment');
    Route::get('alignment/{session_id}', 'SessionController@okr')->name('alignment');
    Route::get('/allusers', 'UserController@allUsers');


    Route::get('objectivesbymanager/{obj_id}', 'NewObjectiveController@showbymanager');
    Route::get('objectivesbyuser/{obj_id}', 'NewObjectiveController@showbyuser');
    Route::get('eobjectivesbyuser', 'NewObjectiveController@eshowbyuser');
    Route::get('objectivebyuser/{obj_id}', 'ObjectiveController@showbyuser');
    Route::get('eobjectivebyuser', 'ObjectiveController@eshowbyuser');
    Route::get('objectivebymanager/{obj_id}', 'ObjectiveController@showbymanager');
    Route::get('addtasks/{objective_id}', 'ObjectiveController@addtasks')->name('addtasks');
    Route::get('showkr/{keyresult_id}', 'KeyresultController@showkr')->name('showkr');
    Route::get('showkeyresult/{keyresult_id}', 'KeyresultController@showrestrictedkr')->name('showkeyresult');

    Route::post('updatetaskstatus', 'TaskController@updateStatus');

    Route::get('objectiveslist/{value}', 'ObjectiveController@list');
    Route::get('krslist/{value}', 'KeyresultController@list');
    Route::get('milelist/{value}', 'KeyresultController@milelist');
    Route::get('performance', 'SessionController@performance')->name('performance');

    //weeklyplans
    Route::put('/editweeklyplan/{plan_id}', 'WeeklyplanController@update')->name('editweeklyplan');
    Route::put('/editdailyplan/{plan_id}', 'DailyplanController@update')->name('editdailyplan');
    Route::get('/weeklyplan/{team_id}/edit', 'WeeklyplanController@edit')->name('weeklyplan.edit');
    Route::get('/dailyplan/{team_id}/edit', 'DailyplanController@edit')->name('dailyplan.edit');
    Route::get('/dailyreport/{team_id}/edit', 'DailyreportController@edit')->name('dailyreport.edit');
    Route::put('/editdailyreport/{plan_id}', 'DailyreportController@update')->name('editdailyreport');
    Route::get('/weeklyreport/{team_id}/edit', 'WeeklyreportController@edit')->name('weeklyreport.edit');
    Route::put('/editweeklyreport/{plan_id}', 'WeeklyreportController@update')->name('editweeklyreport');

    //Notitifcation Routes starts here 
    Route::get('/markAllAsRead', 'NotificationController@markAllAsRead')->name('markAllAsRead');
    Route::get('/markAsRead/{id}', 'NotificationController@markAsRead')->name('markAsRead');
    Route::get('/fetchNoti', 'NotificationController@fetch')->name('fetchNoti');
    Route::get('/showReadNotifications', 'NotificationController@showReadNotifications')->name('showReadNotifications');
    Route::get('/markAsUnRead/{id}', 'NotificationController@markAsUnRead')->name('markAsUnRead');
    Route::post('/admin/markNotification', 'NotificationController@markNotification')->name('admin.markNotification');
    //Notitifcation Routes Ends here 

    Route::get('/weeklyplan/{team_id}', 'WeeklyplanController@index')->name('weeklyplan');
    Route::post('/weeklyplans/{team_id}', 'WeeklyplanController@store')->name('weeklyplans');
    Route::get('/dailyplan/{team_id}', 'DailyplanController@index')->name('dailyplan');
    Route::get('/dailyplan/{team_id}', 'DailyplanController@index')->name('dailyplan');
    Route::post('/dailyplans/{team_id}', 'DailyplanController@store')->name('dailyplans');
    Route::get('/dailyreport/{team_id}', 'DailyreportController@index')->name('dailyreport');
    Route::post('/dailyreports/{team_id}', 'DailyreportController@store')->name('dailyreports');
    Route::get('/weeklyreport/{team_id}', 'WeeklyreportController@index')->name('weeklyreport');
    Route::post('/weeklyreports/{team_id}', 'WeeklyreportController@store')->name('weeklyreports');


    //edit team member
    Route::get('team/{teamid}/members/edit', 'TeamController@teammember')->name('editteammembers');

    //planning and reporting

    //View plan/report
    Route::get('team/{teamid}/session/{sessionid}/weeklyplan', 'TeamWeeklyplanController@index')->name('tweeklyplan');
    Route::get('team/{teamid}/session/{sessionid}/dailyplan', 'TeamDailyPlanController@index')->name('tdailyplan');
    Route::get('team/{teamid}/session/{sessionid}/dailyreport', 'TeamDailyreportController@index')->name('tdailyreport');
    Route::get('team/{teamid}/session/{sessionid}/weeklyreport', 'TeamWeeklyreportController@index')->name('tweeklyreport');
    Route::get('team/{teamid}/session/{sessionid}/weeklyplan/{planid}', 'TeamWeeklyplanController@comment')->name('tweeklycomment');
    Route::get('team/{teamid}/session/{sessionid}/dailyplan/{planid}', 'TeamDailyPlanController@comment')->name('tdailycomment');
    Route::get('showplan', 'TeamWeeklyController@show')->name('showplan');


    //add plan/report
    Route::post('team/{teamid}/session/{sessionid}/weeklyplan', 'TeamWeeklyplanController@store')->name('tweeklyplans');

    //edit plan/report
    Route::put('/teditweeklyplan/{plan_id}', 'TeamWeeklyplanController@update')->name('teditweeklyplan');
    Route::get('team/{teamid}/session/{sessionid}/weeklyplan/{planid}/edit', 'TeamWeeklyplanController@edit')->name('tweeklyplan.edit');

    //delete plan/report
    Route::delete('teamweeklyplan/{id}/delete', 'TeamWeeklyplanController@destroy')->name("teamweeklyplan.delete");

    Route::get('projectcomment/{project_id}', 'MyProjectsController@comment')->name('projectcomment');
    Route::post('projectfillengagement', 'MyProjectsController@fillengagement')->name('projectfillengagement');
});
