@extends('layouts.backend')
@section('content')
<div class="container">
        <div class="mt-50 mb-10 text-center">
            <h2 class="font-w700 text-black mb-10">Fill Key Performance Indicators</h2>
            <hr>
        </div>
        <div class="col-md-8">
            @if($message = Session::get('success'))
            <div class="alert alert-success alert-block">
             <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
            </div>
            @elseif($message = Session::get('error'))
            <div class="alert alert-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                       <strong>{{ $message }}</strong>
               </div>
            @endif
         </div>
    <form method="post" action="{{route('scorekpis.store')}}">
        @csrf
    <div class="container mt-20 mb-10">
        <div class="container mb-10">
            <div class="row">
                <div class="col-xl-4">
                <label>Employee</label>
                    <select class="form-control" id="val-skill" name="employee_id">
                        @foreach($users as $user)
                                <option value={{$user->id}}>{{$user->fname . " ". $user->lname}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-3">
                    <label>Sessions</label>
                    <select class="form-control" id="val-skill" name="session_id">
                        @foreach($sessions as $session)
                        <option value={{$session->id}}>{{$session->session_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-3">
                    <label>Date</label>
                    <input type="date" id="startdate" class="form-control" name="date" required>
                </div>
            </div>
        </div>
        <div class="container mt-20 mb-10">


            {{-- @foreach( $list as $formdata) --}}
            @if($generalmajor->count() >0)
            <div class="text-center"><h4>General Expectation</h4></div>
            <hr class="style1">
            @foreach($generalmajor as $gm)

            <div class="block">


                <div class="block-content block-content-full">

                <div class="row">
                    <div class="col-xl-10">
                        <h3>{{$gm->criteria}}</h3>
                    </div>

                    <div class="col-xl-2">
                        <div class="row">
                            <div class="col-xl-8">
                            <input type="number" class="form-control" placeholder="%" name="mcriteriaweight{{$gm->id}}">
                            </div>

                            <div class="col-xl-1 my-5">
                                <a href="#"><i class="fa fa-plus" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>

                </div>
                @php
                $subcriterias = App\Form::where('parent_criteria_id', $gm->id)->get();
                @endphp

                             <ul>
                                  @foreach($subcriterias as $subc)
                                <li>
                                <div class="row">
                                    <div class="col-xl-8">
                                        <ul>
                                            {{$subc->criteria}}
                                        </ul>
                                    </div>
                                    <div class="col-xl-4">
                                        <div class="container">
                                        <div class="rating">
                                        <input type="radio" name="rating{{$subc->id}}" value=5 id="{{$subc->id}} rating-5" >
                                            <label for="{{$subc->id}} rating-5"></label>
                                            <input type="radio" name="rating{{$subc->id}}" value=4 id="{{$subc->id}} rating-4">
                                            <label for="{{$subc->id}} rating-4"></label>
                                            <input type="radio" name="rating{{$subc->id}}" value=3 id="{{$subc->id}} rating-3">
                                            <label for="{{$subc->id}} rating-3"></label>
                                            <input type="radio" name="rating{{$subc->id}}" value=2 id="{{$subc->id}} rating-2">
                                            <label for="{{$subc->id}} rating-2"></label>
                                            <input type="radio" name="rating{{$subc->id}}" value=1 id="{{$subc->id}} rating-1">
                                            <label for="{{$subc->id}} rating-1"></label>
                                          </div>
                                        </div>
                                    </div>
                            </div>
                        </li>
                                  @endforeach
                                </ul>

                </div>

            </div>
    @endforeach
    @endif
    @if($departmentmajor->count()>0)
    <div class="text-center"><h4>Department Expectation</h4></div>
    <hr class="style1">
            @foreach($departmentmajor as $ngm)

            <div class="block">
                <div class="block-content block-content-full">

                    <div class="row">
                        <div class="col-xl-10">
                            <h3>{{$ngm->criteria}}</h3>
                        </div>

                        <div class="col-xl-2">
                            <div class="row">
                                <div class="col-xl-8">
                                    <input type="number" class="form-control" placeholder="%" name="mcriteriaweight{{$ngm->id}}">
                                </div>

                                <div class="col-xl-1 my-5">
                                    <a href="#"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>

                    </div>
                    @php
                    $subcriterias = App\Form::where('parent_criteria_id', $ngm->id)->get();
                    @endphp

                                 <ul>
                                      @foreach($subcriterias as $subc)
                                    <li>
                                    <div class="row">
                                        <div class="col-xl-8">
                                            <ul>
                                                {{$subc->criteria}}
                                            </ul>
                                        </div>
                                        <div class="col-xl-4">
                                            <div class="container">
                                            <div class="rating">
                                                <input type="radio" name="rating{{$subc->id}}" value=5 id="{{$subc->id}} rating-5">
                                                <label for="{{$subc->id}} rating-5"></label>
                                                <input type="radio" name="rating{{$subc->id}}" value=4 id="{{$subc->id}} rating-4">
                                                <label for="{{$subc->id}} rating-4"></label>
                                                <input type="radio" name="rating{{$subc->id}}" value=3 id="{{$subc->id}} rating-3">
                                                <label for="{{$subc->id}} rating-3"></label>
                                                <input type="radio" name="rating{{$subc->id}}" value=2 id="{{$subc->id}} rating-2">
                                                <label for="{{$subc->id}} rating-2"></label>
                                                <input type="radio" name="rating{{$subc->id}}" value=1 id="{{$subc->id}} rating-1">
                                                <label for="{{$subc->id}} rating-1"></label>
                                              </div>
                                            </div>
                                        </div>
                                </div>
                            </li>
                                      @endforeach
                                    </ul>

                    </div>

            </div>
    @endforeach
    @endif
        {{-- Main ends here --}}

    </div>

    <div class="text-right">
        <button type="submit" class="btn btn-rounded btn-outline-success min-width-125 mb-10">Submit</button>
    </div>
</div>
    </form>

@endsection
