@extends('layouts.main')

@section('css_assets')
    @include('assets.DTeditor') 
    <!-- Manually inserted two links below 
    <link href="https://cdn.datatables.net/keytable/2.1.1/css/keyTable.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.2.0/css/buttons.dataTables.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="/css/chosen/chosen.min.css">
    <style>
        .fa { display: inline; }
        .action { white-space: nowrap; }
        .action i { cursor: pointer; }
        @media (min-width: 1200px)
        {   .col-lg-offset-1 {
                margin-left: 0.5%;
            }
        }
    </style>
@endsection

@section('content')
<!-- #MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>User Accounts</li>
            <li>Update Password</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-plus"></i> 
                Update Password
        </h1>
    </div>
    <div class="col-lg-10 col-lg-offset-1">
        <div class="jarviswidget" id="company-widget" data-widget-editbutton="false" data-widget-custombutton="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                <h2>User Information</h2>
            </header>

            <div>
                <div class="jarviswidget-editbox">
                    
                </div>

                <div class="widget-body no-padding">
                    <form class="smart-form" method="POST" action="{{ action('AccountController@postEditPassword') }}"novalidate="novalidate">
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" value="{{ $user->id }}" />
                        <header>
                            Update Password for {{ $user->email }}
                        </header>
                        <fieldset>
                            <div class="row">
                                <section class="col col-3">
                                    <label class="label">Password</label>
                                    <label class="input"> <i class="icon-prepend fa fa-lock"></i>
                                        <input type="password" name="password" required>
                                    </label>
                                </section>
                            </div>
                        </fieldset>
                        <footer>
                            <button type="submit" class="btn btn-primary">
                                Update Password
                            </button>
                        </footer>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection