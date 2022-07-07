{{-- Modal for Reprimand KPI--}}
<div class="modal" id="repkpimodal" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <form action="{{ route('fill_engagement.store') }}" method="POST" id="reprimandk">
                @csrf
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Discipline (KPI)</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="container">
                    <div class="block-content">
                        <input type="hidden" name="Perspective" value=1>
                        <input type="hidden" name="project_id" id="kpirepplan">
                         <input type="hidden" name="apptype" value=2>

                        <div class="form-group row">
                            <label class="col-4" for="example-select">Employee name</label>
                            
                            <select class="form-control col-md-5 mr-10 round" id="kpirepuname" name="user_id"  required>
                                @php
                                 $pms = App\Projectmember::where('project_id', $project->id)->where('projectmembers.position', '!=', 'PM')->join('users', 'users.id', '=', 'projectmembers.user_id')->get();
                                @endphp
                                <option selected disabled>Select User</option>
                                    @foreach($pms as $pm)
                                         <option value="{{$pm->user_id}}">{{$pm->fname . " " . $pm->lname}}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="form-group row">
                            <label class="mr-10 mt-5" for="example-select">Position</label>
                            <select class="form-control col-md-5 mr-10 round" id="positionchoicer" name="position" onchange="positionchoicerep()" required>
                                <option selected disabled>Select Position</option>

                            </select>
                            <label class="mr-10 mt-5" for="example-select">Perspective</label>
                            <select class="form-control col-md-4 round" id="perspectivechoicer" name="pperspective" disabled onchange="perspectivechoicerep()" required>
                                <option selected disabled>Select pesrspective</option>

                            </select>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="example-select">Objective</label>
                            <select class="form-control round appmod" id="objectiveselr" name="objective" disabled required>
                                <option selected disabled>Select Objective</option>
    
                            </select>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="example-select">Detailed Reason</label>
                            <textarea class="form-control round" id="detailedreasonkr" name="Reason" rows="4" placeholder="Detailed Reason" disabled required></textarea>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="example-select">Action</label>
                            <input type="text" class="form-control round " placeholder="Action" name="Action" id="actionr" disabled required>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="example-select">Expected Improvement</label>
                            <textarea class="form-control round" id="improver" name="Improvement" rows="4" placeholder="Expected Improvement" disabled required></textarea>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="example-select">CC</label>
                            <select class="form-control round" id="cckr" name="cc" disabled>
                                @php
                                $musers = App\User::where('ismanager', 1)->orderby('fname', 'asc')->where('active', 1)->where('id', '!=', 86)->get();
                                @endphp
                                 <option value="0" selected>Select Person</option>
                                @foreach($musers as $user)
                                <option value="{{$user->id}}" >{{$user->fname . " ". $user->lname}}</option>
                               
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-alt-success" id="repk" onclick="Codebase.loader('show', 'bg-gd-sea');setTimeout(function () { Codebase.loader('hide'); }, 3500);">
                        <i class="fa fa-check"></i> Submit
                    </button>
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
    
    
    {{-- Modal for Reprimand Engagement--}}
<div class="modal" id="repengmodal" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <form action="{{ route('fill_engagement.store') }}" method="POST" id="reprimande">
                @csrf
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Discipline (Engagement)</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="container">
                    <div class="block-content">
                        <input type="hidden" name="Perspective" value=1>
                        <input type="hidden" name="project_id" id="engrepplan" value=0>
                         <input type="hidden" name="apptype" value=1>
                        <div class="form-group row">
                            <label class="col-4" for="example-select">Employee name</label>
                            <select class="form-control col-md-5 mr-10 round" id="engrepuname" name="user_id"  required>
                                <option selected disabled>Select User</option>
                                @php
                                 $pms = App\Projectmember::where('project_id', $project->id)->where('projectmembers.position', '!=', 'PM')->join('users', 'users.id', '=', 'projectmembers.user_id')->get();
                                @endphp
                              
                                    @foreach($pms as $pm)
                                         <option value="{{$pm->user_id}}">{{$pm->fname . " " . $pm->lname}}</option>
                                    @endforeach
                            </select>
                            
                        </div>
                        
                       

                    <div class="form-group row">
                        <label class="col-12" for="example-select">Objective</label>
                        <select class="form-control round appmod" id="objectiveselreng" name="objective" required>
                            <option  disabled>Select Objective</option>
                            @php
                            $engagements = App\Engagement::where('Perspective', 1)->get();
                            @endphp
                            @foreach($engagements as $eng)
                                <option value="{{$eng->id}}">{{$eng->Objective}}</option>
                            @endforeach
                        </select>
                    </div>
                        <div class="form-group row">
                            <label class="col-10" for="example-select">Detailed Reason</label>
                            <textarea class="form-control round" id="detailedreasonr" name="Reason" rows="4" placeholder="Detailed Reason" required></textarea>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="example-select">Action</label>
                            <input type="text" class="form-control round " placeholder="Action" name="Action" id="action" required>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="example-select">Expected Improvement</label>
                            <textarea class="form-control round" id="improve" name="Improvement" rows="4" placeholder="Expected Improvement" required></textarea>
                        </div>
                        <div class="form-group row">
                            <label class="col-10" for="example-select">CC</label>
                            <select class="form-control round" id="ccengr" name="cc" >
                                 @php
                                $musers = App\User::where('ismanager', 1)->orderby('fname', 'asc')->where('active', 1)->where('id', '!=', 86)->get();
                                @endphp
                                 <option value="0" selected>Select Person</option>
                                @foreach($musers as $user)
                                <option value="{{$user->id}}" >{{$user->fname . " ". $user->lname}}</option>
                               
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-alt-success" id="repe" onclick="Codebase.loader('show', 'bg-gd-sea');setTimeout(function () { Codebase.loader('hide'); }, 3500);">
                        <i class="fa fa-check"></i> Submit
                    </button>
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>    
    
{{-- Modal for Appreciation KPI--}}
<div class="modal" id="appkpimodal" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <form action="{{ route('fill_engagement.store') }}" method="POST" id="appreciationk">
                @csrf
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Excellence (KPI)</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="container">
                    <div class="block-content">
                        <input type="hidden" name="Perspective" value=0>
                        <input type="hidden" name="project_id" id="kpiappplan">
                         <input type="hidden" name="apptype" value=2>

                        <div class="form-group row">
                            <label class="col-4" for="example-select">Employee name</label>
                          
                             <select class="form-control col-md-5 mr-10 round" id="kpiappuname" name="user_id"  required>
                                <option selected disabled>Select User</option>
                                 @php
                                 $pms = App\Projectmember::where('project_id', $project->id)->where('projectmembers.position', '!=', 'PM')->join('users', 'users.id', '=', 'projectmembers.user_id')->get();
                                @endphp
                               
                                    @foreach($pms as $pm)
                                         <option value="{{$pm->user_id}}">{{$pm->fname . " " . $pm->lname}}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="form-group row">
                            <label class="mr-10 mt-5" for="example-select">Position</label>
                            <select class="form-control col-md-5 mr-10 round" id="positionchoice" name="position" onchange="positionchoiceapp()" required>
                                <option selected disabled>Select Position</option>

                            </select>
                            <label class="mr-10 mt-5" for="example-select">Perspective</label>
                            <select class="form-control col-md-4 round" id="perspectivechoice" name="pperspective" disabled onchange="perspectivechoiceapp()" required>
                                <option selected disabled>Select pesrspective</option>

                            </select>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="example-select">Objective</label>
                            <select class="form-control round appmod" id="objectivesel" name="objective" disabled required>
                                <option selected disabled>Select Objective</option>
    
                            </select>
                        </div>
                        <div class="form-group row">
                            <label class="col-12" for="detailedreasonk">Detailed Reason</label>
                            <textarea class="form-control round" id="detailedreasonk" name="Reason" rows="4" placeholder="Detailed Reason" disabled required></textarea>
                        </div>
                      
                        <div class="form-group row">
                            <label class="col-12" for="example-select">CC</label>
                            <select class="form-control round" id="cck" name="cc" disabled>
                               @php
                                $musers = App\User::where('ismanager', 1)->orderby('fname', 'asc')->where('active', 1)->where('id', '!=', 86)->get();
                                @endphp
                                 <option value="0" selected>Select Person</option>
                                @foreach($musers as $user)
                                <option value="{{$user->id}}" >{{$user->fname . " ". $user->lname}}</option>
                               
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-alt-success" id="appk" onclick="Codebase.loader('show', 'bg-gd-sea');setTimeout(function () { Codebase.loader('hide'); }, 3500);">
                        <i class="fa fa-check"></i> Submit
                    </button>
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>


    {{-- Modal for Appreciation Engagement--}}
<div class="modal" id="appengmodal" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">
            <form action="{{ route('fill_engagement.store') }}" method="POST" id="appreciatione">
                @csrf
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Excellence (Engagement)</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="container">
                    <div class="block-content">
                        <input type="hidden" name="Perspective" value=0>
                        <input type="hidden" name="project_id" id="engappplan">
                         <input type="hidden" name="apptype" value=1>
                        <div class="form-group row">
                            <label class="col-4" for="example-select">Employee name</label>
                            
                             <select class="form-control col-md-5 mr-10 round" id="engappuname" name="user_id"  required>
                                <option selected disabled>Select User</option>
                                 @php
                                 $pms = App\Projectmember::where('project_id', $project->id)->where('projectmembers.position', '!=', 'PM')->join('users', 'users.id', '=', 'projectmembers.user_id')->get();
                                @endphp
                                
                                    @foreach($pms as $pm)
                                         <option value="{{$pm->user_id}}">{{$pm->fname . " " . $pm->lname}}</option>
                                    @endforeach
                            </select>
                            
                        </div>
                        
                       

                    <div class="form-group row">
                        <label class="col-12" for="example-select">Objective</label>
                        <select class="form-control round appmod" id="objectiveselaeng" name="objective" required>
                            <option  disabled>Select Objective</option>
                            @php
                            $engagements = App\Engagement::where('Perspective', 0)->get();
                            @endphp
                            @foreach($engagements as $eng)
                                <option value="{{$eng->id}}">{{$eng->Objective}}</option>
                            @endforeach
                        </select>
                    </div>
                        <div class="form-group row">
                            <label class="col-10" for="example-select">Detailed Reason</label>
                            <textarea class="form-control round" id="detailedreasoneng" name="Reason" rows="4" placeholder="Detailed Reason" required></textarea>
                        </div>
                       
                        <div class="form-group row">
                            <label class="col-10" for="example-select">CC</label>
                            <select class="form-control round" id="cceng" name="cc" >
                               @php
                                $musers = App\User::where('ismanager', 1)->orderby('fname', 'asc')->where('active', 1)->where('id', '!=', 86)->get();
                                @endphp
                                 <option value="0" selected>Select Person</option>
                                @foreach($musers as $user)
                                <option value="{{$user->id}}" >{{$user->fname . " ". $user->lname}}</option>
                               
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-alt-success" id="appe" onclick="Codebase.loader('show', 'bg-gd-sea');setTimeout(function () { Codebase.loader('hide'); }, 3500);">
                        <i class="fa fa-check"></i> Submit
                    </button>
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>    
<script>
$(document).ready(function () {
    $("#appreciatione").submit(function () {
        $("#appe").attr("disabled", true);
        return true;
    });
});
$(document).ready(function () {
    $("#appreciationk").submit(function () {
        $("#appk").attr("disabled", true);
        return true;
    });
});
$(document).ready(function () {
    $("#reprimande").submit(function () {
        $("#repe").attr("disabled", true);
        return true;
    });
});
$(document).ready(function () {
    $("#reprimandk").submit(function () {
        $("#repk").attr("disabled", true);
        return true;
    });
});
</script>