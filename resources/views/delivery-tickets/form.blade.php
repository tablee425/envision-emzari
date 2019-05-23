@extends('layouts.main')

@section('content')

<!-- #MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>Delivery Tickets</li>
            <li>Details</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">

            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-home"></i>
                Delivery Tickets
            <span>>
                Details
            </span>
        </h1>
    </div>
    <div class="col-lg-10 col-lg-offset-1" style="margin-bottom: 40px;">
        <div class="jarviswidget" id="company-widget" data-widget-editbutton="false" data-widget-custombutton="false">
    <header>
        <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
        <h2>Delivery Ticket</h2>
    </header>

    @include('layouts.errors')
    <div>
        <div class="jarviswidget-editbox">

        </div>

        <div class="widget-body no-padding">
            @if($ticket->id)
            <form class="smart-form" method="POST" action="{{ action($action, [$ticket]) }}" novalidate="novalidate">
                <input type="hidden" name="_method" value="PUT" />
            @else
            <form class="smart-form" method="POST" action="{{ action($action) }}" novalidate="novalidate">
            @endif
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $ticket->id }}" />
                @if(isset($request->area_id))
                    <input type="hidden" name="area_id" value="{{ $request->area_id }}" />
                @endif
                <header>
                    Delivery Ticket
                </header>

                <fieldset>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Ticket Number</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name="ticket_number" placeholder="Ticket Number" value="{{ $ticket->ticket_number }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Sales Rep</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <label class="select">
                                    <select name="salesrep_id" class="form-control">
                                        @foreach($reps as $rep)
                                            <option value="{{ $rep->id }}" @if($ticket->salesrep_id == $rep->id) selected @endif>{{ $rep->user->name .' - '. $rep->code }}</option>
                                        @endforeach
                                    </select>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Status</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <label class="select">
                                <select  name="status" class="form-control">
                                    <option value="pending" @if($ticket->status == 'pending') selected @endif>Pending</option>
                                    @if($ticket->salesRep()->user = auth()->user() || auth()->user()->isAdmin())<option value="approved" @if($ticket->status == 'approved') selected @endif>Approved</option>@endif
                                    @if($ticket->salesRep()->user = auth()->user() || auth()->user()->isAdmin())<option value="complete" @if($ticket->status == 'complete') selected @endif>Complete</option>@endif
                                </select>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Delivery Date</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name="delivery_date" placeholder="MM-DD-YYYY" value="{{ $ticket->delivery_date ? $ticket->delivery_date->format('m-d-Y') : date('m-d-Y')}}">
                            </label>
                        </section>

                    </div>

                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Purchase Order Number</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name="purchase_order_number" placeholder="Purchase Order Number" value="{{ $ticket->purchase_order_number }}">
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Ordered By</label>
                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input type="text" name="ordered_by" placeholder="Ordered By" value="{{ $ticket->ordered_by }}">
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Delivered By</label>
                            <label class="input"> <i class="icon-prepend fa fa-user"></i>
                                <input  type="text" name="delivered_by" placeholder="Delivered By" value="{{ $ticket->delivered_by }}">
                            </label>
                        </section>

                    </div>
                    <h3>Chemicals</h3>
                    <div id="chemical-list" class="row">
                        @foreach($ticket->items as $item)
                            <div class="row">
                                <section class="col col-10" style="padding-left: 28px;">
                                    <select class="find_location" name="location_id[]" type="text" placeholder="Find Location by Name or Cost Centre..." style="width: 420px;">
                                        <option value="{{ $item->location_id }}">{{ $item->location->name }}</option>
                                    </select>
                                    <select name="chemical[]" class="chemical_type">
                                        <option value="{{ $item->chemical }}|***|{{ $item->injection_type }}">{{ $item->chemical }} - {{ strtoupper($item->injection_type) }}</option>
                                    </select>
                                    <input name="quantity[]" placeholder="Quantity (in Litres)" value="{{ $item->quantity }}"/>
                                    <select name="packaging[]">
                                        <option value="drum" @if($item->packaging == 'drum') selected @endif>Drum</option>
                                        <option value="pail" @if($item->packaging == 'pail') selected @endif>Pail</option>
                                        <option value="tote" @if($item->packaging == 'tote') selected @endif>Tote</option>
                                        <option value="bulk" @if($item->packaging == 'bulk') selected @endif>Bulk</option>
                                        <option value="jug" @if($item->packaging == 'jug') selected @endif>Jug</option>
                                    </select>
                                    <i class="fa fa-plus-circle new-chemical" aria-hidden="true" style="font-size: 2em;"></i>
                                    <i class="fa fa-remove" aria-hidden="true" style="font-size: 2em;"></i>
                                </section>
                            </div>
                        @endforeach
                    </div>
                    @if($ticket->area_id)
                        <br />
                        <hr />
                        <br />
                        <div class="row">
                            <div class="col col-6">
                                <button type="submit" name="print" class="btn btn-info" style="padding: 8px;" value="1">
                                    Print Delivery Ticket
                                </button>
                                <button type="submit" name="excel" class="btn btn-primary" style="padding: 8px;" value="1">
                                    Excel Export
                                </button>
                                <input type="hidden" name="uwi" value="1" />
                                <input type="checkbox" name="uwi" value ="0" checked /><strong>Do NOT include UWI</strong>
                            </div>
                        </div>
                        <br />
                    @endif
                </fieldset>
                <footer>
                    <button  type="submit" class="btn btn-primary">
                        {{ $button }}
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
<script>
$(function() {
    var chemical_index = {{ $ticket->items ? $ticket->items->count() + 1 : 0 }};
        // $('body').on('DOMNodeInserted', '#chemical-list', function () {});
    var area = {{ $request->area_id ?: $ticket->area_id }};  // When a location has been selected, only use the area it belongs to for rest of locations
    var initializeSelect = function(){
        $('.find_location').select2({
            placeholder: "Find Location By Name or Cost Centre",
            minimumInputLength: 2,
            ajax: {
                url: "{{ action('CompanyController@getLocationsByCostcentre')}}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                        area_id: area,
                        page: params.page
                    }
                },
                processResults: function(data, params) {
                    console.log(data);
                    return {
                        results: $.map(data, function(obj) {
                            var locationName = obj.name + ( obj.cost_centre ? ' --  (Cost Centre: ' + obj.cost_centre +')' : '');
                            return { id: obj.id, text: locationName };
                        })
                    };
                }
            },
        });
    };

    var fetchChemicals = function() {
        $('.find_location').on("select2:select", function(e) {
            console.log('fetch chemicals.');
            var $chemicalList = $(this).siblings('.chemical_type').first();
            var location_id = $(e.currentTarget).val();
            $.ajax({
                url: '/locations/'+ location_id +'/chemicals',
                type: 'GET',
                success: function(response) {
                    $chemicalList.html('');
                    response.forEach(function(chemical) {
                        console.log(chemical)
                        $chemicalList.append($('<option></option>')
                                     .attr("value", chemical.name+'|***|'+chemical.type)
                                     .text(chemical.name + ' - ' + chemical.type));
                    });
                }
            });
        });
    }


    var addChemical = function() {
        $('#chemical-list').append('<div class="row"><section class="col col-10" style="padding-left: 28px;"><select class="find_location" name="location_id[]" type="text" placeholder="Find Location by Name or Cost Centre..." style="width: 420px;"></select> <select name="chemical[]" class="chemical_type"></select> <input name="quantity[]" placeholder="Quantity (in Litres)" /> <select name="packaging[]"><option value="drum">Drum</option><option value="pail">Pail</option><option value="tote">Tote</option><option value="bulk">Bulk</option><option value="jug">Jug</option></select><i class="fa fa-plus-circle new-chemical" aria-hidden="true" style="font-size: 2em;"></i> <i class="fa fa-remove" aria-hidden="true" style="font-size: 2em;"></i> </section></div>');
        initializeSelect();
        chemical_index++;
    };
    addChemical();
    fetchChemicals();
    $('#chemical-list').on('click', '.new-chemical', function() {
        addChemical();
        fetchChemicals();
    });
    $('#chemical-list').on('click', '.fa-remove', function() {
        $(this).parent().remove();
    });

    // Location Search Box
    $('#field').select2();
});
</script>
@endsection