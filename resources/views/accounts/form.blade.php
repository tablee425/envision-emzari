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
            <li>{{ ucfirst($type) }} Account</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-plus"></i> 
                {{ ucfirst($type) }} Account
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
                    <form class="smart-form" method="POST" action="{{ action($action) }}"novalidate="novalidate">
                        {{ csrf_field() }}
                        <input type="hidden" name="user_id" value="{{ $user->id }}" />
                        <header>
                            Account Details
                        </header>
                        <fieldset>
                            <div class="row">
                                <section class="col col-3">
                                    <label class="label">Name</label>
                                    <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                        <input type="text" name="name" placeholder="Full Name" value="{{ $user->name }}">
                                    </label>
                                </section>
                                <section class="col col-3">
                                    <label class="label">Email</label>
                                    <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                        <input type="text" name="email" placeholder="name@domain.com" value="{{ $user->email }}">
                                    </label>
                                </section>
                                @if($user->id)
                                <section class="col col-3">
                                    <label class="label">Update Password</label>
                                    <label class="input"> <a href="{{ action('AccountController@getEditPassword', ['user_id' => $user->id]) }}">Click here to Update</a>
                                    </label>
                                </section>
                                @else
                                <section class="col col-3">
                                    <label class="label">Password</label>
                                    <label class="input"> <i class="icon-prepend fa fa-lock"></i>
                                        <input type="password" name="password" required>
                                    </label>
                                </section>
                                @endif
                            </div>
                            <div class="row">
                                <section class="col col-6">
                                    <label class="label">Companies</label>
                                    <label class="select"> <i class="icon-prepend fa fa-briefcase form-control"></i>
                                        <select name="companies[]" multiple class="chosen-select" id="companies">
                                            @foreach($companies as $company)
                                                <option value="{{ $company->id }}" @if($user->companies->contains($company->id)) selected @endif>{{ $company->name }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </section>
                                <section class="col col-6">
                                    <label class="label">Areas</label>
                                    <label class="select"> <i class="icon-prepend fa fa-briefcase form-control"></i>
                                        <select name="areas[]" multiple class="chosen-select">
                                            @foreach($companies as $company)
                                                <optgroup value="{{ $company->id }}" label="{{ $company->name }}">
                                                    @foreach($company->areas as $area)
                                                    <option value="{{ $area->id }}" @if($user->areas->contains($area->id)) selected @endif>{{ $area->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </label>
                                </section>
                            </div>
                        </fieldset>
                        <footer>
                            <button type="submit" class="btn btn-primary">
                                {{ ucfirst($type) }} Account
                            </button>
                        </footer>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer_assets')
<script src="/js/chosen.jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
(function() {
     var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
}());
</script>
@endsection