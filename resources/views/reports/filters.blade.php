@extends('layouts.main')

@section('css_assets')
<style>
.pigging-fields {
   display: none;
}
</style>
@endsection

@section('content')

<!-- #MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>Reports</li>
            <li>Select Fields</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">

            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-home"></i>
                Reports
            <span>>
                Select Fields
            </span>
        </h1>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="widget-body">
                <form class="smart-form" method="POST" action="{{ action('ReportController@postFilter') }}">
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="row">
                            <section class="col col-2">
                                <label class="label">Select Reporting Type</label>
                                <label class="select">
                                    <select name="type">
                                        <option value="continuous">Continuous</option>
                                        <option value="batch">Batch</option>
                                        <option value="pigging">Pigging</option>
                                        <option value="composition">Composition</option>
                                        <option value="analysis">Analysis</option>
                                        <option value="delivery-tickets">Delivery Tickets</option>
                                    </select>
                                </label>
                            </section>
                        </div>
                        <div class="reporting-fields">
                            <div class="row">
                                <section class="col col-2">
                                    <label class="label">Select Area</label>
                                    <label class="select">
                                        <select name="area_id">
                                            <option value="">All Areas</option>
                                            @foreach($areas as $area)
                                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </section>
                            </div>
                            <div class="row pigging-fields">
                                <section class="col col-2">
                                    <label class="label">Select Field</label>
                                    <label class="select">
                                        <select name="field_id">

                                        </select>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col col-2">
                                        <label>Select a Start Date</label>
                                        <div class="input-group">
                                            <input type="date" name="start_date" placeholder="Select a Date" class="form-control" value="{{ $start_date->toDateString() }}" />
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div>
                                    <div class="col col-2" style="margin-left: 16px;">
                                        <label>Select an End Date</label>
                                        <div class="input-group">
                                            <input type="date" name="end_date" placeholder="Select a Date" class="form-control" value="{{ $end_date->toDateString() }}" />
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dt-fields" style="display: none;">
                            <div class="row">
                                <section class="col col-2">
                                    <label class="label">Month</label>
                                    <label class="select">
                                        <select name="dt_month">
                                            <option value="01">January</option>
                                            <option value="02">February</option>
                                            <option value="03">March</option>
                                            <option value="04">April</option>
                                            <option value="05">May</option>
                                            <option value="06">June</option>
                                            <option value="07">July</option>
                                            <option value="08">August</option>
                                            <option value="09">September</option>
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </label>
                                </section>
                                <section class="col col-2">
                                    <label class="label">Year</label>
                                    <label class="select">
                                        <select name="dt_year">
                                            @foreach($dtYears as $year)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </section>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 8px;">
                            <div class="col col-6">
                                <button class ="btn btn-lg btn-primary pull-right" type="submit">Generate Results</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(function() {
    $('select[name=type]').on('change', function(e) {
        if($(this).val() == 'pigging')
        {
            $('.reporting-fields').show();
            $('.pigging-fields').show();
            updateFields();
            return;
        }
        else if($(this).val() == 'delivery-tickets')
        {
            $('.dt-fields').show();
            $('.reporting-fields').hide();
            return;
        }
        $('.reporting-fields').show();
        $('.dt-fields').hide();
        $('.pigging-fields').hide();
    });
    $('select[name=type]').val('continuous');
    $('select[name=area_id]').on('change', function(e) {
        updateFields();
    });



    function updateFields()
    {
        var fields = $.get('/areas/' + $('select[name=area_id]').val() +'/fields')
                      .success(function(data) {
                          $('select[name=field_id]').html('');
                          data.forEach(function(field) {
                              $('select[name=field_id]').append('<option value="' + field.id + '">' + field.name + '</option>')
                          });
                      });
    }
});
</script>
@endsection