@extends('layouts.backend')

@section('content')


<div class="neotable mt-50">
    <div class="container">
        <div class="row">
            <div class="col-md">

                <div class="row">
                    <div class="col-md-10">
                        <h2>
                            Sessions
                        </h2>
                    </div>
                    <div>
                        @if(Gate::any(['newrole']))
                        <a id="currentsession" type="button" class="btn btn-rounded btn-outline-info float-right"
                            href="{{ route('okrsession', '9')}}'"> Current session</a>

                        @elseif(Gate::any(['okr', 'addSession']))
                        <button type="submit" class="btn btn-rounded btn-outline-primary float-right"
                            data-toggle="modal" data-target="#addSessionmodal">Add Session</button>
                        @endif

                    </div>
                </div>
                @if($message = Session::get('success'))
                <div class="alert alert-success alert-block col-md-7">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
                @elseif($message = Session::get('error'))
                <div class="alert alert-danger alert-block col-md-7">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
                @endif
                <table class="table table-hover">
                    <thead>
                        <tr>

                            <th scope="col">Session</th>
                            <th scope="col">Start</th>
                            <th scope="col">End</th>
                            <th scope="col">Status</th>
                            @can('crud')
                            <th scope="col">Action</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody id="sessionlist">
                        <?php
                                $count = 1;
                                ?>
                        @foreach($sessions as $session)
                        <tr id="sessiondata{{$session->id}}" class='clickable-row'>

                            <td>
                                <div class="clickable-field" data-href='{{ route('okr', $session->id)}}'>
                                    {{$session->session_name}}</div>
                            </td>
                            <td>{{ Carbon\Carbon::parse($session->start_date)->format('M d Y')}}</td>
                            <td>{{Carbon\Carbon::parse($session->end_date)->format('M d Y')}}</td>
                            <td>{{$session->status}}</td>
                            <td>
                                @if(Gate::any(['okr', 'editSession']))
                                <button class="btn btn-sm btn-alt-secondary " value={{$session->id}} data-toggle="modal"
                                    data-target="#editSessionmodal{{$session->id}}"><i
                                        class="si si-pencil"></i></button> &nbsp;
                                @endif
                                @if(Gate::any(['okr', 'deleteSession']))
                                <button class="btn btn-sm btn-alt-danger " value={{$session->id}} data-toggle="modal"
                                    data-target="#deleteSessionmodal{{$session->id}}"><i
                                        class="si si-trash"></i></button>
                                @endif
                            </td>
                        </tr>
                        @include('includes.editdeletesession-modals')
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
@include('includes.session-modals')

@endsection