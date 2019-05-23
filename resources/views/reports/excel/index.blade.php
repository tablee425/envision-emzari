@extends('layouts.main')

@section('css_assets')
   
@endsection

@section('content')
<<!-- #MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>User</li>
            <li>Settings</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-home"></i> 
                Generate 
            <span>>  
                Excel Sheets
            </span>
        </h1>
    </div>
    <div class="col-lg-10 col-lg-offset-1">
        <div class="jarviswidget" id="company-widget" data-widget-editbutton="false" data-widget-custombutton="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                <h2>Excel Sheets</h2>
            </header>

            <div>
                <div class="jarviswidget-editbox">
                    
                </div>


                <div class="widget-body no-padding">
                    <form class="smart-form" method="POST" action="{{ action('ExcelReportController@postReport') }}" novalidate="novalidate">
                        {{ csrf_field() }}
                        <header>
                            Generate Excel Sheets for Field
                        </header>
                        <fieldset>
                            <div class="row">
                                <section class="col col-3">
                                    <label class="label">Fields</label>
                                    <label class="select">
                                        <select name="field_id">
                                        @foreach($fields as $field)
                                            <option value="{{ $field->id }}">{{ $field->name }}</option>
                                        @endforeach
                                        </select> <i></i>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-3">
                                    <input type="hidden" name="prior_year" value="0">
                                    <label class="checkbox">
                                            <input type="checkbox" name="prior_year" value="1">
                                            <i></i>Show prior year results.
                                    </label>
                                </section>
                            </div>   
                        </fieldset>
                        
                        <footer>
                            <button type="submit" class="btn btn-primary">
                                Select Field
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

@endsection