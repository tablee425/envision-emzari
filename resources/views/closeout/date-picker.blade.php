@extends('layouts.main')

@section('content')

<!-- #MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>Chemical Closeout</li>
            <li>Date Range</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-home"></i> 
                Chemical Closeout
            <span>>  
                Filters
            </span>
        </h1>
    </div>
    <div class="col-lg-10 col-lg-offset-1">
        <div class="jarviswidget" id="company-widget" data-widget-editbutton="false" data-widget-custombutton="false">
        <header>
            <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
            <h2>Chemical Closeout</h2>
        </header>

        <div>
            <div class="jarviswidget-editbox">
                
            </div>
            @if(count($errors) > 0)    
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <h3>Closeout Filters</h3>
                </div>
            </div>
            <form class="smart-form" method="POST" action="{{ action('CloseOutController@process') }}">
            {{ csrf_field() }}
            <input type="hidden" name="field_id" value="{{ $field->id }}" />
            <fieldset>
                <div class="row">
                    <div class="form-group">

                        <div class="col col-2">
                            <label>Select a Start Date</label>
                            <div class="input-group">
                                @if($start_date)
                                    <em>{{ $start_date }}</em>
                                @else
                                    <input type="date" name="start_date" placeholder="Select a Date" class="form-control" />
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                @endif
                            </div>
                        </div>
                        <div class="col col-2" style="margin-left: 16px;">
                            <label>Select an End Date</label>
                            <div class="input-group">
                                @if($end_date)
                                    <em>{{ $end_date }}</em>
                                @else
                                    <input type="date" name="end_date" placeholder="Select a Date" class="form-control" />
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="form-group">
                        <div class="col col-4">
                            <label class="label">Batch Sizes</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <label class="select">
                                    <select name="batch_option" class="form-control" >
                                        <option value="prior_month">Use Prior Month Batch Size</option>    
                                        <option value="delivery_tickets">Use Batch Size from Deliveries</option>
                                    </select>
                                </label>
                            </label>
                        </div>
                    </div>
                </div>
            </fieldset>
            <footer>
                <button  type="submit" class="btn btn-primary">
                    Closeout Field
                </button>
            </footer>
        </div>
    </form>
@endsection
@section('footer_assets')
<script>
</script>
@endsection