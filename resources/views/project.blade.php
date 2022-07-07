@extends('layouts.backend')
@section('content')
<style>
    .center {
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
}
</style>
<div class="container">
     <div class="mt-50 mb-10 text-center">
         <div class="text-right mb-10 float-right">
        <a id="resourcematrix" type="button" class="btn btn-rounded btn-outline-info" href="{{route('resourcematrix')}}">Resource matrix</a>
    </div>
        <h2 class="font-w700 mb-10">Projects</h2>
        
        <hr>
    </div>
</div>
<div class="container mt-20">
    
    <div class="row gutters-tiny">
        @foreach($projectmember as $projm)
        @php
            $project = App\Project::find($projm->project_id)
        @endphp
        <div class="col-md-4">
            <div class="block {{$project->status == 1?'bg-gd-sea': 'bg-success' }}">
                <div class="block-options border border-secondary">
                    @if($project->status == 1 && Gate::any(['fillengageproject', 'fillkpiproject']))
                    <button type="button" class="btn-block-option  btn-outline-secondary text-white pull-right" onclick = "launchclose({{$project->id}})">
                        Close Project
                    </button>
                    @elseif(Gate::any(['fillengageproject', 'fillkpiproject']))
                     <button type="button" class="btn-block-option  btn-outline-secondary text-white pull-right"  onclick = "launchopen({{$project->id}})">
                        Open Project
                    </button>
                    @endif
                    <button type="button" class="btn-block-option js-tooltip-enabled text-white pull-right" data-toggle="tooltip" title="Delete" data-original-title="Delete">
                        
                    </button>
                </div>
            <a href="{{route('projectslanding', $project->id)}}" style="text-decoration: none; color:#575757;">
                <div class="block-content">
                    <p class="mt-5 text-center">
                        <i class="si si-drawer fa-3x text-white-op"></i>
                    </p>
                    <h5 class="font-w300 text-white text-center">{{$project->project_name}}</h5>
                </div>
            </a>
            </div>
        </div>
        @endforeach

    </div>


</div>
<div class="modal center" id="close" tabindex="-1" role="dialog" aria-labelledby="modal-normal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-content text-center">
                    
                        <i class="fa fa-exclamation text-warning" style="font-size: 5em;"></i>
                        <h4>Are you sure you want to close this project?</h4>
                    
                </div>
                <form action="" id="closeform" method="post">
                @csrf
                <div class="modal-footer">
                    <button type="submit" class="btn btn-alt-danger">
                        Close Project
                    </button>
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Cancel</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal center" id="open" tabindex="-1" role="dialog" aria-labelledby="modal-normal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-content text-center">
                    
                        <i class="fa fa-exclamation text-warning" style="font-size: 5em;"></i>
                        <h4>Are you sure you want to open this project?</h4>
                    
                </div>
                <form action="" id="openform" method="post">
                @csrf
                <div class="modal-footer">
                    <button type="submit" class="btn btn-alt-danger">
                        Open Project
                    </button>
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Cancel</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
     var baseurl = window.location.protocol + "//" + window.location.host + '/pms/';
    function launchclose(id){
         document.getElementById('closeform').action = baseurl + 'closeproject/' + id;
         jQuery("#close").modal('show')
    }
     function launchopen(id){
        document.getElementById('openform').action = baseurl + 'openproject/' + id;
        jQuery("#open").modal('show')
    }
</script>
@endsection

