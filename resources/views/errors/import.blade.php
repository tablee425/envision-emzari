@extends('layouts.main')

@section('content')
<!-- #MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>Production Import</li>
            <li>Error Report</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">

            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-plus"></i>
                Error Report
        </h1>
    </div>

    <div class="col-lg-10 col-lg-offset-1" style="margin-bottom: 80px;">
        <h2>Not all of your data has been imported. </h2>
        <h2>The following locations are not found in the database.</h2>
        <h3>Please add these locations to the appropriate Field and reupload the file to make sure all data is imported.</h3>
        <ul>
            @foreach($errors as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endsection