@extends('layouts.backend')
@section('content')
<div class="container mt-20">
    <div class="block">
        <div class="block-content">
            <h3>CFR Questions Response</h3>
            <hr>
            <div class="form-group row">
                <label class="col-md-1 mt-5" for="example-select">Employee</label>
                <div class="col-md-3">
                    <select class="form-control round" id="sessionselect" name="session-select">
                            <option value="0" default disabled>Please select Employee</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="block-content">
            <ol>
                <h5><li>In which engagement and KPI measures does he/she has got reprimand in this quarter please mention and review each reprimand issued?</li></h5>
                <textarea class="form-control round" rows="5" id="response">Check-in on time</textarea>
                <h5><li>In which engagement and KPI measures does he/she has got appreciation in this quarter please mention and review each appreciation issued?</li></h5>
                <textarea class="form-control round" rows="5" id="response">Go beyond and above</textarea>
                <h5><li>What could be the reason for the cause of poor performance or the reprimand certificates issued in this quarter?</li></h5>
                <textarea class="form-control round" rows="5" id="response">None</textarea>
                <h5><li>What are the two or three things could your manager do differently to better manage and increase your performance in the next quarter?</li></h5>
                <textarea class="form-control round" rows="5" id="response">Check in on time</textarea>
                <h5><li>What accomplishment this quarter are you most proud of from your goals/tasks.</li></h5>
                <textarea class="form-control round" rows="5" id="response">Example Achivement</textarea>
                <h5><li>What personal strength help you do your job effectively?</li></h5>
                <textarea class="form-control round" rows="5" id="response">Example strength</textarea>
                <h5><li>What motivates you to get your job done?</li></h5>
                <textarea class="form-control round" rows="5" id="response">Example Motivation</textarea>
                <h5><li>Managers Feedback</li></h5>
                <textarea class="form-control round" rows="5" id="response">Example Feedback</textarea>
            </ol>
        </div>
        <div class="block-content" align="right">
            <button type="button" class="btn btn-rounded btn-lg btn-info">Done</button>
            <a type="button" class="btn btn-rounded btn-lg btn-secondary" href="{{ url('cfrpage') }}">Cancel</a>
        </div>
        <div class="block-content"></div>
    </div>
</div>

 <script>
    tinymce.init({
      selector: 'textarea',
      branding: false,
      height: 300,
      statusbar: false,
      plugins: 'lists',
      toolbar: "formatselect | bold italic backcolor forecolor| alignleft aligncenter alignright alignjustify | bullist numlist | '",
      menubar: 'false'
      });
  </script>
@endsection
