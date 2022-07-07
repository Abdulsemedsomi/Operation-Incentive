@extends('layouts.backend')
@section('content')
<div class="container">
        <div class="mt-50 mb-10 text-center">
        <h2 class="font-w700 text-black mb-10">Key Performance Indicator</h2>
            <hr>
        </div>


    <div class="container mt-20 mb-10">
        <div class="container mb-10 text-center">
            <div class="row">
                <div class="col-xl-4">
                <label>Employee</label>
                   <label> {{$filledkpi->fname . " " . $filledkpi->lname}}</label>
                </div>
                <div class="col-xl-3">
                    <label>Sessions</label>
                    <label> {{App\Session::find($filledkpi->session_id)->session_name}}</label>
                </div>
                <div class="col-xl-3">
                    <label>Date</label>
                    <label> {{$filledkpi->created_at}}</label>
                </div>
            </div>
        </div>
        <div class="container mt-20 mb-10">


            {{-- @foreach( $list as $formdata) --}}
            @if($scoreratinggeneral->count() >0)
            <div class="text-center"><h4>General Expectation</h4></div>
            <hr class="style1">
            @foreach($scoreratinggeneral as $gm)

            <div class="block">


                <div class="block-content block-content-full">

                <div class="row">
                    <div class="col-xl-10">
                        <h3>{{$gm->criteria}}</h3>
                    </div>

                    <div class="col-xl-2">
                        <div class="row">
                            <div class="col-xl-12">
                            <label>{{$gm->attainment}}% out of {{$gm->tpercentage}}%</label>
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
                                        <input type="radio" name="rating{{$subc->id}}" value=5 id="{{$subc->id}} rating-5" {{App\Kpiscoring::where('criteria_id', $subc->id)->where('filled_kpi_id', $filledkpi->id)->first()->rating == 5? "checked": ""}} disabled>
                                            <label for="{{$subc->id}} rating-5"></label>
                                            <input type="radio" name="rating{{$subc->id}}" value=4 id="{{$subc->id}} rating-4" {{App\Kpiscoring::where('criteria_id', $subc->id)->where('filled_kpi_id', $filledkpi->id)->first()->rating == 4? "checked": ""}} disabled>
                                            <label for="{{$subc->id}} rating-4"></label>
                                            <input type="radio" name="rating{{$subc->id}}" value=3 id="{{$subc->id}} rating-3" {{App\Kpiscoring::where('criteria_id', $subc->id)->where('filled_kpi_id', $filledkpi->id)->first()->rating == 3? "checked": ""}} disabled>
                                            <label for="{{$subc->id}} rating-3"></label>
                                            <input type="radio" name="rating{{$subc->id}}" value=2 id="{{$subc->id}} rating-2" {{App\Kpiscoring::where('criteria_id', $subc->id)->where('filled_kpi_id', $filledkpi->id)->first()->rating == 2? "checked": ""}} disabled>
                                            <label for="{{$subc->id}} rating-2"></label>
                                            <input type="radio" name="rating{{$subc->id}}" value=1 id="{{$subc->id}} rating-1" {{App\Kpiscoring::where('criteria_id', $subc->id)->where('filled_kpi_id', $filledkpi->id)->first()->rating == 1? "checked": ""}} disabled>
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
    @if($scoreratingdept->count()>0)
    <div class="text-center"><h4>Department Expectation</h4></div>
    <hr class="style1">
            @foreach($scoreratingdept as $ngm)

            <div class="block">
                <div class="block-content block-content-full">

                    <div class="row">
                        <div class="col-xl-10">
                            <h3>{{$ngm->criteria}}</h3>
                        </div>

                        <div class="col-xl-2">
                            <div class="row">
                                <div class="col-xl-12">
                            <label>{{$ngm->attainment}}%  out of {{$ngm->tpercentage}}%</label>

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
                                            <input type="radio" name="rating{{$subc->id}}" value=5 id="{{$subc->id}} rating-5" {{App\Kpiscoring::where('criteria_id', $subc->id)->where('filled_kpi_id', $filledkpi->id)->first()->rating == 5? "checked": ""}} disabled>
                                                <label for="{{$subc->id}} rating-5"></label>
                                                <input type="radio" name="rating{{$subc->id}}" value=4 id="{{$subc->id}} rating-4" {{App\Kpiscoring::where('criteria_id', $subc->id)->where('filled_kpi_id', $filledkpi->id)->first()->rating == 4? "checked": ""}} disabled>
                                                <label for="{{$subc->id}} rating-4"></label>
                                                <input type="radio" name="rating{{$subc->id}}" value=3 id="{{$subc->id}} rating-3" {{App\Kpiscoring::where('criteria_id', $subc->id)->where('filled_kpi_id', $filledkpi->id)->first()->rating == 3? "checked": ""}} disabled>
                                                <label for="{{$subc->id}} rating-3"></label>
                                                <input type="radio" name="rating{{$subc->id}}" value=2 id="{{$subc->id}} rating-2" {{App\Kpiscoring::where('criteria_id', $subc->id)->where('filled_kpi_id', $filledkpi->id)->first()->rating == 2? "checked": ""}} disabled>
                                                <label for="{{$subc->id}} rating-2"></label>
                                                <input type="radio" name="rating{{$subc->id}}" value=1 id="{{$subc->id}} rating-1" {{App\Kpiscoring::where('criteria_id', $subc->id)->where('filled_kpi_id', $filledkpi->id)->first()->rating == 1? "checked": ""}} disabled>
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

</div>


@endsection
