<!DOCTYPE html>
<html>
<head>
   <style>
        html{
                    font-family: sans-serif;
                    line-height: 1.15;
                    -webkit-text-size-adjust: 100%;
                    -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
                }
            body{
                    
                    background: url("{{ URL::asset('images') }}/cert2.png"); background-size: 1024px 722px; background-repeat: no-repeat;
                    margin: 0;
                    font-family: "Nunito Sans", -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
                    font-size: 1rem;
                    font-weight: 400;
                    line-height: 1.5;
                    color: #575757;
                    text-align: left;
                }
            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                margin-top: 0;
                margin-bottom: 20px;
            }
            caption {
                    padding-top: 10px;
                    padding-bottom: 10px;
                    color: #6c757d;
                    text-align: left;
                    caption-side: bottom;
            }

            .block {
                    margin-bottom: 24px;
                    background-color: #fff;
                    box-shadow: 0 1px 1px #e4e7ed;
                }
            .block-header{
                            display: flex;
                            flex-direction: row;
                            justify-content: space-between;
                            align-items: center;
                            padding: 14px 20px;
                            transition: opacity 0.2s ease-out;
                        }
            .block-title {
                            flex: 1 1 auto;
                            min-height: 28px;
                            margin: 0;
                            font-size: 1.142857143rem;
                            font-weight: 400;
                            line-height: 28px;
                        }
            .block-content {
                            transition: opacity 0.2s ease-out;
                            margin: 0 auto;
                            padding: 20px 20px 1px;
                            width: 100%;
                            overflow-x: visible;
                        }
            .block-content .pull-r-l {
                                    margin-right: -20px;
                                    margin-left: -20px;
                        }
            .block-content .pull-r {
                                    margin-right: -20px;
                        }
            address {
                        margin-bottom: 1rem;
                        font-style: normal;
                        line-height: inherit;

                    }
            .pull-right {
                    float: right;
                }
            .container {
                            width: 100%;
                            padding-right: 15px;
                            padding-left: 15px;
                            margin-right: auto;
                            margin-left: auto;
                        }
            .row {
                display: flex;
                flex-wrap: wrap;
                margin-right: -15px;
                margin-left: -15px;
                }
            .col-lg,
            .col-lg-auto,
            .col-lg-12,
            .col-lg-11,
            .col-lg-10,
            .col-lg-9,
            .col-lg-8,
            .col-lg-7,
            .col-lg-6,
            .col-lg-5,
            .col-lg-4,
            .col-lg-3,
            .col-lg-2,
            .col-lg-1,
            .col-md,
            .col-md-auto,
            .col-md-12,
            .col-md-11,
            .col-md-10,
            .col-md-9,
            .col-md-8,
            .col-md-7,
            .col-md-6,
            .col-md-5,
            .col-md-4,
            .col-md-3,
            .col-md-2,
            .col-md-1,
            .col-sm,
            .col-sm-auto,
            .col-sm-12,
            .col-sm-11,
            .col-sm-10,
            .col-sm-9,
            .col-sm-8,
            .col-sm-7,
            .col-sm-6,
            .col-sm-5,
            .col-sm-4,
            .col-sm-3,
            .col-sm-2,
            .col-sm-1,
            .col,
            .col-auto,
            .col-12,
            .col-11,
            .col-10,
            .col-9,
            .col-8,
            .col-7,
            .col-6,
            .col-5,
            .col-4,
            .col-3,
            .col-2,
            .col-1 {
                position: relative;
                width: 100%;
                padding-right: 15px;
                padding-left: 15px;
            }

            .col {
                flex-basis: 0;
                flex-grow: 1;
                max-width: 100%;
            }

            .row-cols-1>* {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .row-cols-2>* {
                flex: 0 0 50%;
                max-width: 50%;
            }

            .row-cols-3>* {
                flex: 0 0 33.3333333333%;
                max-width: 33.3333333333%;
            }

            .row-cols-4>* {
                flex: 0 0 25%;
                max-width: 25%;
            }

            .row-cols-5>* {
                flex: 0 0 20%;
                max-width: 20%;
            }

            .row-cols-6>* {
                flex: 0 0 16.6666666667%;
                max-width: 16.6666666667%;
            }

            .col-auto {
                flex: 0 0 auto;
                width: auto;
                max-width: 100%;
            }

            .col-1 {
                flex: 0 0 8.3333333333%;
                max-width: 8.3333333333%;
            }

            .col-2 {
                flex: 0 0 16.6666666667%;
                max-width: 16.6666666667%;
            }

            .col-3 {
                flex: 0 0 25%;
                max-width: 25%;
            }

            .col-4 {
                flex: 0 0 33.3333333333%;
                max-width: 33.3333333333%;
            }

            .col-5 {
                flex: 0 0 41.6666666667%;
                max-width: 41.6666666667%;
            }

            .col-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            .col-7 {
                flex: 0 0 58.3333333333%;
                max-width: 58.3333333333%;
            }

            .col-8 {
                flex: 0 0 66.6666666667%;
                max-width: 66.6666666667%;
            }

            .col-9 {
                flex: 0 0 75%;
                max-width: 75%;
            }

            .col-10 {
                flex: 0 0 83.3333333333%;
                max-width: 83.3333333333%;
            }

            .col-11 {
                flex: 0 0 91.6666666667%;
                max-width: 91.6666666667%;
            }

            .col-12 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            .block.block-bordered {
                    border: 1px solid #e4e7ed;
                    box-shadow: none;
                }
            .table {
                        width: 98%;
                        margin-bottom: 1rem;
                        color: #575757;
                        border-collapse: collapse;
                    }
            .table-bordered {
                border: 1px solid #e4e7ed;
            }

            .table-bordered th,
            .table-bordered td {
                border: 1px solid #e4e7ed;
            }

            .table-bordered thead th,
            .table-bordered thead td {
                border-bottom-width: 2px;
            }
            .table-borderless th,
            .table-borderless td,
            .table-borderless thead th,
            .table-borderless tbody+tbody {
                border: 0;
                        }
            .float-right {
                float: right !important;
            }
    </style>
</head>

<body>
     <div class="container">
        <div class="block">
            <div class="block-content">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td><img src="{{url('images/IE logo.png')}}" style="margin-left:20px; height: 70px; weight: 70px;"></td>
                            <td style="font-size:10px;">
                                <ul>IE Network Solutions Private Limited Company</ul>
                                <ul>Ethiopia, Addis Ababa, Haya Hulet, Festival 22 Blg 7th Floor</ul>
                                <ul>Tel:+251-115-570544/+251-911-511275/+251-930-105789/+251-911-210654</ul>
                                <ul>Fax No.+251-115-570543, P.O.Box 122521, email: info@ienetworksolutions.com</ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                
              
                <p>Ref: <b style="text-decoration: underline;"> IE/PPL/{{Carbon\Carbon::parse($engagement->created_at)->format('ymd')}}/{{rand(1,100)}} </b>
                </p>
                <h2 class="font-w300" style="text-align: center;">Reprimand Notice</h2>
        <table class="table table-bordered table-vcenter">
            <tbody style="font-size: 20px;">
               
                    <tr>
                        <td style="width:50px; height: 100px;" rowspan="2">To</td>
                    <td>Name: <b>{{App\User::find($engagement->issued_to)->fname . " ". App\User::find($engagement->issued_to)->lname}}</b></td>
                    </tr>
                    <tr>
                        <td style="width:450px;">Position: <b>{{App\User::find($engagement->issued_to)->position}}</b></td>
                    </tr>
                    <tr>
                        <td style="font-size: smaller;width:50%;">
                            Reprimand for: 
                        </td>
                        <td style="font-size: smaller;width:50%;">
                           
                            {{App\Kpiform::find($engagement->kpiform_id)?App\Kpiform::find($engagement->kpiform_id)->objective:"" }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Detail Reason
                        </td>
                        <td>
                           {{$engagement->reason}}
                        </td>
                    </tr>
                    <tr style="height: 40px;">
                        <td>
                            Action
                        </td>
                        <td>
                            {{$engagement->action}}
                        </td>
                    </tr>
                    <tr style="height: 50px;">
                        <td>
                            Expected Improvement
                        </td>
                        <td>
                            {{$engagement->improvement}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width:200px; height:40px">
                            Supervisor Name
                        </td>
                        <td>
                            {{App\User::find($engagement->issuer)->fname . " ". App\User::find($engagement->issuer)->lname }}
                        </td>
                    </tr>
                    <tr style="height: 40px;">
                        <td style="width:200px; height:40px">
                            <p>Supervisor Signature</p>
                        </td>
                        @php
    $sign =  App\User::find($engagement->issuer)? (App\User::find($engagement->issuer)->signature != null? 'uploads/signature/' . App\User::find($engagement->issuer)->signature: 'images/Sign.png') :  'images/Sign.png';
    @endphp 
                        <td>
                            <img src="{{$sign}}"  alt="ie-logo" style="marigin-left:55%; top: 0.5%;height: 6em; weight: 6em;">
                        </td>
                    </tr>
                    <tr style="height: 40px;">
                        <td>
                            Date
                        </td>
                        <td>
                            {{$engagement->created_at}}
                        </td>
                    </tr>
                    <tr>
                        <td style="width:200px; height:40px"><b>CC:</b></td>
                        <td>- {{App\User::find($engagement->cc)?App\User::find($engagement->cc)->fname . " ". App\User::find($engagement->cc)->lname: "Employee file" }}</td>
                    </tr>
                    <tr>
                        <td>

                        </td>
                        <td><img src="images/Stampn.png" class="float-right" alt="ie-logo" style="marigin-left:10em; height: 10em; weight: 10em;"></td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>
