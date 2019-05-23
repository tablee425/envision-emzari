@extends('layouts.main')

@section('css_assets')
    <!-- removed include('assets.DTeditor') Manually inserted two links below -->
    <link href="https://cdn.datatables.net/keytable/2.1.1/css/keyTable.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.2.0/css/buttons.dataTables.min.css" rel="stylesheet">
    <style>
        .fa { display: inline; }
        .action { white-space: nowrap; }
        .action i { cursor: pointer; }
    </style>
@endsection

@section('content')
<!-- #MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>Pig Runs</li>
            <li>Entry</li>
        </ol>
    </div>

    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-home"></i> 
                Pig Runs 
            <span>>  
                 Entry
            </span>
        </h1>
    </div>

    <div class="col-lg-10 col-lg-offset-1"  style="margin-bottom: 80px;">
        <div class="jarviswidget" id="company-widget" data-widget-editbutton="false" data-widget-custombutton="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                <h2>Pig Location Selection</h2>
            </header>

            <div>
                <div class="jarviswidget-editbox">
                    
                </div>


                <div class="widget-body no-padding">
                    <form class="smart-form" method="GET" action="{{ action('PigRunController@index') }}" novalidate="novalidate">
                        {{ csrf_field() }}
                        <header>
                            Pig Location Selection
                        </header>
                        <fieldset>
                            <div class="row">
                                <section class="col col-3">
                                    <label class="label">Areas</label>
                                    <label class="select">
                                        <select name="area_id">
                                        @foreach($areas as $area)
                                            <option value="{{ $area->id }}" @if(isset($piggingArea) && $piggingArea->id == $area->id) selected @endif>{{ $area->name }}</option>
                                        @endforeach
                                        </select> <i></i>
                                    </label>
                                </section>
                                <section class="col col-2">
                                    <label class="label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary">
                                        Select Area
                                    </button>  
                                </section>
                            </div> 
                        </fieldset>
                    </form>
                    @if($fields)
                    <form class="smart-form" method="GET" action="{{ action('PigRunController@index') }}" novalidate="novalidate">
                        {{ csrf_field() }}
                        <fieldset>
                            <div class="row">
                                <section class="col col-3">
                                    <label class="label">Fields</label>
                                    <label class="select">
                                        <select name="field_id">
                                        @foreach($fields as $field)
                                            <option value="{{ $field->id }}" @if(isset($piggingField) && $piggingField->id == $field->id) selected @endif>{{ $field->name }}</option>
                                        @endforeach
                                        </select> <i></i>
                                    </label>
                                </section>
                                <input type="hidden" name="area_id" value="{{ $piggingArea->id }}" />
                            </div>
                            <div class="row">
                                <section class="col col-3">
                                    <label class="label">Months</label>
                                    <label class="select">
                                        <select name="month">
                                            <option value="{{ Carbon\Carbon::today()->startOfMonth()->subMonth()->month }}" @if($piggingMonth && $piggingMonth == Carbon\Carbon::today()->startOfMonth()->subMonth()->month) selected @endif>{{ Carbon\Carbon::today()->startOfMonth()->subMonth()->format('F') }}</option>
                                            <option value="{{ Carbon\Carbon::today()->month }}" @if(!$piggingMonth || $piggingMonth == Carbon\Carbon::today()->month) selected @endif>{{ Carbon\Carbon::today()->format('F') }}</option>
                                            <option value="{{ Carbon\Carbon::today()->startOfMonth()->addMonth()->month }}" @if($piggingMonth && $piggingMonth == Carbon\Carbon::today()->startOfMonth()->addMonth()->month) selected @endif>{{ Carbon\Carbon::today()->startOfMonth()->addMonth()->format('F') }}</option>
                                        </select> <i></i>
                                    </label>
                                </section> 
                            </div>
                            <div class="row">
                                <section class="col col-3">
                                    <label class="label">Operator</label>
                                    <label class="input">
                                        <input type="text" name="operator" @if($piggingOperator) value="{{ $piggingOperator }}" @endif/>
                                    </label>
                                </section>
                            </div>  
                            <div class="row">
                                <section class="col col-3">
                                    <label class="label">Run Type</label>
                                    <label class="select">
                                        <select name="run_type">
                                            <option value="maintenance">Maintenance</option>
                                            <option value="corrosion">Corrosion Batch</option>
                                            <option value="pressure">Pressure Check</option>
                                        </select> <i></i>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-2">
                                    <label class="label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary">
                                        Select Field
                                    </button>  
                                </section>
                            </div>   
                        </fieldset>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer_assets')
<script>
</script>
@endsection