@extends('layouts.backend')
@section('content')
    <div class="mx-50 mt-50" >
        <div align="right">
            <div class="block-content">
                    <div class="form-group row">
                        <label class="col-md-8 mt-5" for="example-select">Session</label>
                        <div class="col-md-3">
                            <select class="form-control round" id="sessionselect" name="session-select">
                                    <option value="0" disabled>Please select Session</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                        <button type="submit" class="btn btn-rounded btn-outline btn-outline-info ml-5">Select</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <ul class="nav nav-tabs md-tabs" id="myTabMD" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab-md" data-toggle="tab" href="#home-md" role="tab" aria-controls="home-md" aria-selected="true">Response</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab-md" data-toggle="tab" href="#profile-md" role="tab" aria-controls="profile-md"
                aria-selected="false">Question</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-tab-md" data-toggle="tab" href="#contact-md" role="tab" aria-controls="contact-md"
                aria-selected="false">Action plan</a>
            </li>
        </ul>
        <div class="tab-content pt-5" id="myTabContentMD">
            <div class="tab-pane fade show active" id="home-md" role="tabpanel" aria-labelledby="home-tab-md">
                <div class="block">
                    <div class="block-content">
                        <div class="row mb-5" align="right">
                            <div class="col-md-9">
                                <div class="form-group row">
                                    <label class="mt-5" for="example-select">Teams</label>
                                    <div class="col-md-5">
                                        <select class="form-control round" id="sessionselect" name="session-select">
                                                <option value="0" disabled>Please select Team</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <a class="btn btn-rounded btn-outline btn-outline-info ml-5" href="{{ route('newcfr') }}">Conduct CFR</a>
                            </div>
                        </div>
                    </div>
                    <div class="block-content">
                        <h3>Responses</h3>
                        <hr>
                        <table class="table table-hover table-vcenter">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Full Name</th>
                                        <th class="text-left">Discription</th>
                                        <th class="text-left">Conducted on</th>
                                        <th class="text-left">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th class="text-center" scope="row">1</th>
                                        <td>Samuel Negash</td>
                                        <td class="d-none d-sm-table-cell">Samuel Negash's FY2013 Q4 CFR. May 12, 2021</td>
                                        <td class="d-none d-sm-table-cell">May 12, 2021</td>
                                        <td class="d-none d-sm-table-cell">
                                            <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="View" href="{{ url('cfrview') }}">
                                                <i class="si si-eye"></i>
                                            </a>
                                            <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Edit" href="{{ url('editcfrresponse') }}">
                                                <i class="si si-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Delete">
                                                <i class="si si-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="profile-md" role="tabpanel" aria-labelledby="profile-tab-md">
                <div class="block">
                    <div class="block-content">
                        <button class="btn btn-rounded btn-outline btn-outline-info ml-5 float-right" data-toggle="modal" data-target="#add-question">Add Question</button>
                        <h3>Questions</h3>
                        <hr>
                    </div>
                    <div class="block-content">
                        <ol>
                            <div class="row">
                                <h5 class="col-md-11">
                                    <li>In which engagement and KPI measures does he/she has got reprimand in this quarter please mention and review each reprimand issued?</li>
                                </h5>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="modal" data-target="#edit-question">
                                        <i class="si si-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-alt-danger ml-5">
                                        <i class="si si-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <h5 class="col-md-11">
                                    <li>In which engagement and KPI measures does he/she has got appreciation in this quarter please mention and review each appreciation issued?</li>
                                </h5>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="modal" data-target="#edit-question">
                                        <i class="si si-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-alt-danger ml-5">
                                        <i class="si si-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <h5 class="col-md-11">
                                    <li>What could be the reason for the cause of poor performance or the reprimand certificates issued in this quarter?</li>
                                </h5>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="modal" data-target="#edit-question">
                                        <i class="si si-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-alt-danger ml-5">
                                        <i class="si si-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <h5 class="col-md-11">
                                    <li>What are the two or three things could your manager do differently to better manage and increase your performance in the next quarter?</li>
                                </h5>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="modal" data-target="#edit-question">
                                        <i class="si si-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-alt-danger ml-5">
                                        <i class="si si-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <h5 class="col-md-11">
                                    <li>What accomplishment this quarter are you most proud of from your goals/tasks.</li>
                                </h5>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="modal" data-target="#edit-question">
                                        <i class="si si-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-alt-danger ml-5">
                                        <i class="si si-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <h5 class="col-md-11">
                                    <li>What personal strength help you do your job effectively?</li>
                                </h5>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="modal" data-target="#edit-question">
                                        <i class="si si-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-alt-danger ml-5">
                                        <i class="si si-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <h5 class="col-md-11">
                                    <li>What motivates you to get your job done?</li>
                                </h5>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="modal" data-target="#edit-question">
                                        <i class="si si-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-alt-danger ml-5">
                                        <i class="si si-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="contact-md" role="tabpanel" aria-labelledby="contact-tab-md">
                <div class="block">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Action Plan</h3>
                    </div>
                    <div class="block-content">
                        <div class="row mb-5" align="right">
                            <div class="col-md-9">
                                <div class="form-group row">
                                    <label class="mt-5" for="example-select">Teams</label>
                                    <div class="col-md-5">
                                        <select class="form-control round" id="sessionselect" name="session-select">
                                                <option value="0" disabled>Please select Team</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block-content">
                        <table class="table table-hover table-vcenter">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-left">Issue raised</th>
                                    <th class="text-left">What will be done? Steps and tasks</th>
                                    <th class="text-left">Who will do it?</th>
                                    <th class="text-left">who will need to be involved?</th>
                                    <th class="text-left">What resource are needed?</th>
                                    <th class="text-left">Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th class="text-center" scope="row">1</th>
                                    <td>More external projects for BAI</td>
                                    <td>Department managers/Team Lead jointly with Sales team to work to build team expertise and solution development</td>
                                    <td>Hawi Tesfaye</td>
                                    <td>People Department</td>
                                    <td>None</td>
                                    <td>Open</td>
                                    <td class="d-none d-sm-table-cell">
                                        <div class="row">
                                            <a type="button" class="btn btn-sm btn-secondary mr-10" href="{{ url('cfredit') }}" data-toggle="tooltip" title="Edit">
                                            <i class="si si-pencil"></i>
                                        </a>
                                        <a type="button" class="btn btn-sm btn-secondary" data-toggle="tooltip" title="Delete">
                                            <i class="si si-trash"></i>
                                        </a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal" id="add-question" tabindex="-1" role="dialog" aria-labelledby="add-question" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-info">
                        <h3 class="block-title">Add Question</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                        <div class="block-content">
                            <div class="form-group row">
                                <label class="col-12" for="who-will">Question</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control round" id="question" name="question">
                                </div>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-rounded btn-alt-info" data-dismiss="modal">Done</button>
                        <button type="button" class="btn btn-rounded btn-alt-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="edit-question" tabindex="-1" role="dialog" aria-labelledby="edit-question" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-info">
                        <h3 class="block-title">Edit Question</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                        <div class="block-content">
                            <div class="form-group row">
                                <label class="col-12" for="who-will">Question</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control round" id="question" name="question" value="In which engagement and KPI measures does he/she has got reprimand in this quarter please mention and review each reprimand issued?">
                                </div>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-rounded btn-alt-info" data-dismiss="modal">Done</button>
                        <button type="button" class="btn btn-rounded btn-alt-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
