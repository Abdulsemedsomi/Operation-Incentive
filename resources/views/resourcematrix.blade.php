@extends('layouts.backend')
@section('content')
<style>
/*    html {*/
/*  font-family: verdana;*/
/*  font-size: 10pt;*/
/*  line-height: 25px;*/
/*}*/

/*table {*/
/*  border-collapse: collapse;*/
/*  width: 300px;*/
  
/*  display: block;*/
/*}*/

/*thead {*/
/*  background-color: #EFEFEF;*/
/*}*/

/*thead,*/
/*tbody {*/
/*  display: block;*/
/*}*/

/*tbody {*/
/*  overflow-y: scroll;*/
/*  overflow-x: scroll;*/
/*  height: 740px;*/
/*}*/

/*td,*/
/*th {*/
/*  min-width: 100px;*/
/*  height: 25px;*/
/*  border: dashed 1px lightblue;*/
/*  overflow: hidden;*/
/*  text-overflow: ellipsis;*/
/*  max-width: 100px;*/
/*}*/
</style>
<main class="mx-30">
    <div class="block">
        <div class="block-content">
            <div class="container">
                <div class="mt-30 mb-10 text-center">
                    <h2 class="font-w700 mb-10">Project Resource Matrix</h2>
                </div>
                <hr>
            </div>
                <table class="table table-striped1 table-vcenter table-sm" id="matrix">
                    <thead class="bg-gray-light">
                        <tr>
                            <th>ID</th>
                            <th>Resource Name</th>
                            @foreach($projects as $project)
                            <th>{{$project->project_name}}</th>
                            @endforeach
    
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $count = 1;
                        @endphp
                        @foreach($users as $user)
                        @php
                            $projectmemeber = App\Projectmember::where('user_id', $user->id)->first();
                        @endphp
                            @if($projectmemeber)
                            <tr>
                            <th class="text-center" scope="row">{{$count++}}</th>
                            <td>{{$user->fname . " ". $user->lname}}</td>
                            @foreach($projects as $project)
                            @php
                            $member = App\Projectmember::where('project_id', $project->id)->where('user_id', $user->id)->first();
                               
                            @endphp
                            <th>{{$member? $member->position:"  "}}</th>
                            @endforeach
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>
</main>
<script>
    $(document).ready(function() {
    $('#matrix').DataTable({
        "ordering": false,
        "info":     false,
         "scrollX": true,
          "lengthMenu": [[20, 30, 50, -1], [20, 30, 50, "All"]]
    });
} );
</script>
@endsection
