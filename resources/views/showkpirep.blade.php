
@extends('layouts.backend')
@section('content')
<hr>
<div class="container">
    <div class="block">
        <div class="block">
            <div style="width: 100%; background-color:#ced4da!important;">
                <div class="block-content">
                    <address>
                    <b>IE Network Solutions Private Limited Company</b>
                    <p style="margin-bottom: 5px !important;"> Ethiopia, Addis Ababa, Kazanchis, Enat Tower 9th Floor Room No:903-1</p>
                    <p style="margin-bottom: 5px !important;">Tel:+251-115-570544/+251-911-511275/+251-930-105789/+251-911-210654</p>
                    <p style="margin-bottom: 5px !important;">Fax No.+251-115-570543, P.O.Box 122521, email: info@ienetworksolutions.com</p>
                    </address>
                </div>
            </div>
        </div>
        <hr>
        <div class="block-content">
            <p style="padding-bottom:3rem;">Ref: <b style="text-decoration: underline;">IE/HRF/200516/51</b></p>
            <h2 class="font-w300" style="text-align: center;">Reprimand Notice</h2>
            <table class="table table-bordered table-vcenter">
                <tbody style="font-size: 20px;">
                    <tr>
                        <td style="width:50px; height: 100px;" rowspan="2">To</td>
                    <td>Name: {{App\User::find($kpi->issued_to)->fname . " ". App\User::find($kpi->issued_to)->lname}}</td>
                    </tr>
                    <tr>
                        <td style="width:450px;">Position: {{App\User::find($kpi->issued_to)->position}}</td>
                    </tr>
                <tr>
                    <td>
                        <p>Discipline on : </p>
                    </td>
                    <td>
                   {{App\Filledkpilist::find($kpi->filledkpilistid)?App\Kpiform::find(App\Filledkpilist::find($kpi->filledkpilistid)->kpiform_id)->objective:"" }}
                      
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Detail Reason</p>
                    </td>
                    <td>
                    {{$kpi->reason}}
                    </td>
                </tr>
                <tr style="height: 40px;">
                    <td>
                        <p>action</p>
                    </td>
                    <td>
                        <p>{{$kpi->action}}</p>
                    </td>
                </tr>
                <tr style="height: 50px;">
                    <td>
                        <p>Expected Improvement</p>
                    </td>
                    <td>
                        <p><p>{{$kpi->improvement}} </p></p>
                    </td>
                </tr>

                <tr style="height: 40px;">
                    <td>
                        <p>Supervisor Name</p>
                    </td>
                    <td>
                        <p>{{App\User::find($kpi->issuer)->fname . " ". App\User::find($kpi->issuer)->lname }}</p>
                    </td>
                </tr>
                <tr style="height: 40px;">
                <td>
                    <p>Supervisor Signature</p>
                    </td>
                    <td>
                        <img src="{{url('images/Eliyas Signature.png')}}"  alt="ie-logo" style="marigin-left:55%; top: 0.5%;height: 6em; weight: 6em;">
                    </td>
                </tr>
                <tr style="height: 40px;">
                    <td>
                        <p>Date</p>
                    </td>
                    <td>
                        <p>{{$kpi->created_at}}</p>
                    </td>
                </tr>

                <tr style="height: 70px;">
                    <td colspan="2"><b>CC:</b> <br>
                        <p style="margin-left:5%;"> &#9679; Employee File</p>
                        <p style="margin-left:5%;"> &#9679; Employee</p>
                    </td>
                </tr>
                <tr>
                    <td>
                    </td>
                    <td>
                        <img src="{{url('images/stamp.png')}}"  alt="ie-logo" style="marigin-left:55%; top: 0.5%;height: 10em; weight: 10em;">
                    </td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
