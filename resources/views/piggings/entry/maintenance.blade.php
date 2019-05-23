@extends('layouts.main')

@section('css_assets')
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
@endsection
@section('content')

<!-- #MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>Pigging</li>
            <li>Details</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-home"></i> 
                Pigging 
            <span>>  
                Details
            </span>
        </h1>
    </div>

    <div class="col-lg-10 col-lg-offset-1" style="margin-bottom: 40px;">
        <div class="jarviswidget" id="company-widget" data-widget-editbutton="false" data-widget-custombutton="false">
    <header>
        <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
        <h2>Pigging for {{ $location->name }}</h2>
    </header>

    <div>
        <div class="jarviswidget-editbox">
            
        </div>
        <div class="flash-message">
            @if(count($errors))
                @foreach ($errors->all() as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                @endforeach
            @endif
        </div>
        <div class="widget-body no-padding">
            <form id="maintenance-form" class="smart-form" method="POST" action="{{ action('PigRunController@update', [$pigging]) }}" novalidate="novalidate">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="PUT" />
                <input type="hidden" name="id" value="{{ $pigging->id }}" />
                <input type="hidden" name="start_location_id" value="{{ $location->id }}" />
                <input type="hidden" name="tables[area_id]" value="{{ $piggingArea }}" />
                <input type="hidden" name="tables[field_id]" value="{{ $piggingField }}" />
                <input type="hidden" name="tables[month]" value="{{ $piggingMonth }}" />
                <input type="hidden" name="tables[operator]" value="{{ $piggingOperator }}" />
                <input type="hidden" name="tables[run_type]" value="{{ $runType }}" />
                <header>
                    {{ $location->name }} - Pigging Data
                </header>
                <fieldset>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Order</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name="order" placeholder="Order" value="{{ old('order') ?: $pigging->order }}">
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Ending Location</label>
                            <label class="select"> <i class="icon-prepend fa fa-briefcase"></i>
                            <select name="end_location_id" class="end-location form-control">
                                @if($pigging->end_location_id)
                                    <option value="{{ $pigging->end_location_id }}">{{ $pigging->endLocation->name }}</option>
                                @endif  
                            </select>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Date Scheduled</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input type="text" name="scheduled_on" class="pigging-date" placeholder="YYYY-MM-DD" value="{{ old('scheduled_on') ?: ($pigging->scheduled_on ? date('Y-m-d', strtotime($pigging->scheduled_on)) : date('Y-m-d')) }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Date Shipped</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name="shipped_on" class="pigging-date" placeholder="YYYY-MM-DD" value="{{ old('shipped_on') ?: ($pigging->shipped_on ? date('Y-m-d', strtotime($pigging->shipped_on)) : '') }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Date Pulled</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input type="text" name="pulled_on" class="pigging-date" placeholder="YYYY-MM-DD" value="{{ old('pulled_on') ?: ($pigging->pulled_on ? date('Y-m-d', strtotime($pigging->pulled_on)) : '') }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Date Cancelled/Checked</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name="cancelled_on" class="pigging-date" placeholder="YYYY-MM-DD" value="{{ old('cancelled_on') ?: ($pigging->cancelled_on ? date('Y-m-d', strtotime($pigging->cancelled_on)) : '') }}">
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">OD</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name="od" placeholder="OD" value="{{ old('od') ?: $pigging->od }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">MOP(kPa)</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name ="MOP" placeholder="MOP(kPa)" value="{{ old('MOP') ?: $pigging->MOP }}">
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Pig Number</label>
                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input  type="text" name="pig_number" placeholder="Enter Pig Number" value="{{ old('pig_number') ?: $pigging->pig_number }}">
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Line Pressure</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name ="line_pressure" placeholder="Line Pressure" value="{{ old('line_pressure') ?: $pigging->line_pressure }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Pressure Switch</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name ="pressure_switch" placeholder="Pressure Switch" value="{{ old('pressure_switch') ?: $pigging->pressure_switch }}">
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-3">
                        <label class="label">Field Operator</label>
                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input type="text" name="field_operator" placeholder="Field Operator" value="{{ old('field_operator') ?: $pigging->field_operator }}">
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-3">
                            <label class="label">Gauged</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <label class="select">
                                <select name="gauged" class="form-control">
                                    <option value="Yes" @if($pigging->gauged == "Yes") selected @endif>Yes</option>
                                    <option value="No" @if($pigging->gauged == "No") selected @endif>No</option>
                                </select>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Condition</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i></label>
                                <label class="select">
                                    <select name="condition" class="form-control">
                                        <option value="Good" @if($pigging->condition == 'Good') selected @endif>Good</option>
                                        <option value="Poor" @if($pigging->condition == 'Poor') selected @endif>Poor</option>
                                    </select>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Wax (L)</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input  type="text" name="wax" placeholder="Wax (L)" value="{{ old('wax') ?: $pigging->wax }}">
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-4">
                            <label class="textarea"> <i class="icon-append fa fa-comment"></i>
                                <textarea  rows="5" name="comments" placeholder="Comments">{{ old('comments') ?: $pigging->comments }}</textarea>
                            </label>
                        </section>
                    </div>

                </fieldset>
                
                <footer>
                    <button  type="submit" class="btn btn-primary">
                        Update Pigging
                    </button>
                </footer>
            </form>
        </div>
    </div>
</div>
    </div>
</div>
<script>
$(function() {
    $('.pigging-date').datepicker({
        dateFormat: 'yy-mm-dd',
        defaultDate: new Date({{ $pigging->scheduled_on->format('Y') }}, {{ $pigging->scheduled_on->format('m') - 1 }}, {{ $pigging->scheduled_on->format('d')}})
    });

    // $('#maintenance-form').on('submit', function(e) {
    //     var $form = $(this);
        
    //     if($('input[name=pulled_on]').val() || $('input[name=cancelled_on]').val())
    //     {
    //         e.preventDefault();
    //         if (confirm('You have entered a close out date for this pigging. Doing so will remove this from your piggings list. Click OK if you have entered all of your data and want to proceed?'))
    //         {
    //             $form.unbind();
    //             $form.submit();
    //         }
    //     }
    // });

    $('.end-location').select2({
        ajax: {
            url: '{{ action('FieldController@jsonLocations', [$location->field]) }}',
            dataType: 'json',
            // delay: 250,
            processResults: function(data) {
                console.log(data);
                return {
                    results: data
                }
            }
            // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
        }
    });
});
</script>
@endsection