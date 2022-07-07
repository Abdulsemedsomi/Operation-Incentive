@extends('layouts.backend')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Engagement</h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-success" href="{{ route('engagement.create') }}"> Create New Engagement</a>
                </div>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif

        <table class="table table-bordered">
            <tr>
                <th style="text-align: center;">#</th>
                <th style="text-align: center;">Perspective</th>
                <th style="text-align: center;">Objective</th>
                <th style="text-align: center;">Measure</th>
                <th style="text-align: center;">Target</th>
                <th style="text-align: center;">Weight(%)</th>
                <th width="280px" style="text-align: center;">Action</th>
            </tr>
            <?php
            $i = 0;
            ?>
            @foreach ($engagements as $engagement)
                <tr>
                    <td>{{ ++$i }}</td>
                    @if($engagement->Perspective == 0)
                        <td style="text-align: center;"><span class="badge-success rounded-pill">Excellence</span></td>
                        @else
                        <td style="text-align: center;"><span class="badge-danger rounded-pill">Descipline</span></td>
                    @endif
                    <td>{{ $engagement->Objective }}</td>
                    <td>{{ $engagement->Measure }}</td>
                    <td>{{ $engagement->Target }}</td>
                    <td>{{ $engagement->Weight }} %</td>
                    <td>
                        <form action="{{ route('engagement.destroy',$engagement->id) }}" method="POST">

                            <a class="btn btn-primary" href="{{ route('engagement.edit',$engagement->id) }}">Edit</a>

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>

    </div>
@endsection
