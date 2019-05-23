@extends('layouts.main')

@section('css_assets')
    @include('assets.DTeditor')
    <link rel="stylesheet" type="text/css" https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css" />
    <style>
        .fa { display: inline; }
        .action { white-space: nowrap; }
        .action i { cursor: pointer; }
        .editable {
            background-color: #ECF3F8;
            font-weight: bold;
        }
        table{
          margin: 0 auto;
          width: 100%;
          clear: both;
          border-collapse: collapse;
          table-layout: fixed;
          word-wrap:break-word;
        }
        td input {
            width: 96%;
            text-align: center;
        }
    </style>
@endsection

@section('content')
<!-- #MAIN PANEL -->
<div id="main" role="main">
   <div id="ribbon">
        <ol class="breadcrumb">
            <li>{{ ucfirst($injection_title) }}</li>
            <li>Continuous Injections</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">

            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-plus"></i>
                {{ ucfirst($injection_title) }}
            <span>>
                Continuous Injections
            </span>
        </h1>
    </div>

    @if ($type == "location" && Auth::user()->admin)
    <div class="col-md-10 col-lg-offset-1 form-row">
        <a class="btn btn-primary btn-lg" href="/injections/create?type=continuous&location_id={{ $id }}">
            <i class="fa fa-plus"></i> Add Continuous Injection</a>
    </div>
    @endif

    <div class="col-lg-10 col-lg-offset-1" style="margin-bottom: 80px;"">
        <table id="continuous-injections" class="table responsive table-striped table-bordered table-hover compact" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Location</th>
                    <th>Location Desc</th>
                    <th>Date</th>
                    <th>Gas Prod.</th>
                    <th>Oil Prod.</th>
                    <th>Water Prod.</th>
                    <th>Total</th>
                    <th>Chemical</th>
                    <th>Program Start Date</th>
                    <th>Chemical Type</th>
                    <th>Days In Month</th>
                    <th>Based On</th>
                    <th>Inv - Start</th>
                    <th>Inv - Delivered</th>
                    <th>Inv - End</th>
                    <th>Chem Used</th>
                    <th>Usage Rate</th>
                    <th>Est Usage Rate</th>
                    <th>Est - Inv</th>
                    <th>Chem Days Remaining</th>
                    <th>Target Rate</th>
                    <th>Target PPM</th>
                    <th>Actual PPM</th>
                    <th>Vendor Target</th>
                    <th>Min Rate</th>
                    <th>Over/Under</th>
                    <th>Unit Cost</th>
                    <th>Over Cost</th>
                    <th>Cost Centre</th>
                    <th>Vendor Budget</th>
                    <th>Target Budget</th>
                    <th>Total Cost</th>
                    <th>Comments</th>
                    <th>Tank Capacity</th>
                    <th>Action</th>

                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td data-search="ID"></td>
                    <td data-search="Location"></td>
                    <td data-search="Location Desc"></td>
                    <td data-search="Date"></td>
                    <td data-search="Gas"></td>
                    <td data-search="Oil"></td>
                    <td data-search="Water"></td>
                    <td data-search="Chemical"></td>
                    <td data-search="Chemical Type"></td>
                    <td data-search="Program Start Date"></td>
                    <td data-search="Total"></td>
                    <td data-search="Days In Month"></td>
                    <td data-search="Based On"></td>
                    <td data-search="Chemical Inventory - Start"></td>
                    <td data-search="Checmical Delivered"></td>
                    <td data-search="Chemical Inventory - End"></td>
                    <td data-search="Chemical Used"></td>
                    <td data-search="Current Usage Rate"></td>
                    <td data-search="Est Usage Rate"></td>
                    <td data-search="Est - Inv"></td>
                    <td data-search="Chemical Inventory - Days Remaining"></td>
                    <td data-search="Target Rate"></td>
                    <td data-search="Target PPM"></td>
                    <td data-search="Actual PPM"></td>
                    <td data-search="Vendor Target"></td>
                    <td data-search="Minimum Rate"></td>
                    <td data-search="Over/Under"></td>
                    <td data-search="Unit Cost"></td>
                    <td data-search="Over Cost"></td>
                    <td data-search="Cost Centre"></td>
                    <td data-search="Vendor Budget"></td>
                    <td data-search="Target Budget"></td>
                    <td data-search="Total Monthly Cost"></td>
                    <td data-search="Tank Capacity"></td>
                    <td data-search="Comments"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

@section('footer_assets')
@include('scripts.datatables')
<script>
    $(document).ready(function() {

        editor = new $.fn.dataTable.Editor( {
            ajax: "{{ action('InjectionController@postContinuousUpdate') }}",
            table: "#continuous-injections",
            fields: [
                {
                    label: "Injection Date",
                    name: "date"
                },
                {
                    label: "Gas Production",
                    name: "avg_gas"
                },{
                    label: "Oil Production",
                    name: "avg_oil"
                },{
                    label: "Water Production",
                    name: "avg_water"
                },
                {
                    label: "Chemical",
                    name: "name"
                },
                {
                    label: "Chemical Type",
                    name: "chemical_type",
                    type: "select",
                    options: [
                        { label: "Demulsifier", value: "demulsifier" },
                        { label: "Corrosion Inhibitor", value: "corrosion_inhibitor" },
                        { label: "Paraffin Solvent", value: "paraffin_solvent" },
                        { label: "Demulsifer Wax", value: "demulsifier_wax" },
                        { label: "Biocide", value: "biocide" },
                        { label: "Scale Inhibitor", value: "scale_inhibitor" },
                        { label: "Scale Corrosion Combo", value: "scale_corrosion_combo" },
                        { label: "Vapour Phase Corrosion Inhibitor", value: "vapour_phase_corrosion_inhibitor" },
                        { label: "Iron Oxide Dissolver", value: "iron_oxide_dissolver" },
                        { label: "Oxygen Scavenger", value: "oxygen_scavenger" },
                        { label: "H2S Scavenger", value: "h2s_scavenger" },
                        { label: "Defoamer", value: "defoamer" },
                        { label: "Wax Dispersant", value: "wax_dispersant" },
                        { label: "Methanol", value: "methanol" },
                        { label: "Ethylene Glycol", value: "ethylene_glycol" },
                        { label: "Varsol", value: "varsol" },
                        { label: "Slugging Demulsifier", value: "slugging_demulsifer" },
                        { label: "Iron Control", value: "iron_control" },
                        { label: "Friction Reducer", value: "friction_reducer" },
                        { label: "Surfactant", value: "surfactant" },
                        { label: "Foamer", value: "foamer" },
                        { label: "Paraffin Inhibitor", value: "paraffin_inhibitor" }
                    ]
                },
                {
                    label: "Days In Month",
                    name: "days_in_month"
                },
                {
                    label: "Based On",
                    name: "based_on",
                    type: "select",
                    options: [
                        { label: "Gas", value: "gas" },
                        { label: "Oil", value: "oil" },
                        { label: "Water", value: "water" },
                        { label: "Oil and Water", value: "oil_and_water" },
                        { label: "All", value: "all" }

                    ]
                },
                {
                    label: "Chemical Start",
                    name: "chemical_start"
                },
                {
                    label: "Chemical Delivered",
                    name: "chemical_delivered"
                },
                {
                    label: "Chemical End",
                    name: "chemical_end"
                },
                {
                    label: "Usage Rate",
                    name: "usage_rate"
                },
                {
                    label: "Estimate Usage Rate",
                    name: "estimate_usage_rate"
                },
                {
                    label: "Estimate Inventoty",
                    name: "estimate_inventory"
                },
                {
                    label: "Target Rate",
                    name: "target_rate"
                },
                {
                    label: "Target PPM",
                    name: "target_ppm"
                },
                {
                    label: "Vendor Target",
                    name: "vendor_target"
                },
                {
                    label: "Minimum Rate",
                    name: "min_rate"
                },
                {
                    label: "Unit Cost",
                    name: "unit_cost"
                },
                // {
                //     label: "Cost Centre",
                //     name: "cost_centre"
                // },
                {
                    label: "Comments",
                    name: "comments"
                }

            ]
        } );


        $('#continuous-injections').on( 'click', 'tbody td.editable', function () {
            var parent = $(this).parent('tr');
            var elemStatus = parent.find('#js-injection-status');
            if (elemStatus.attr('data-status') == 1) {
                parent.find('.editable').removeClass('editable')
                return false;
            }

            editor.inline( this, {
                buttons: { label: '&gt;', fn: function() { this.submit(); }},
                drawType: 'page'
            } );
        });

        // Setup - add a text input to each footer cell
        $('#continuous-injections tfoot td').each( function (el) {
            var title = $('#continuous-injections tfoot td').eq( el ).data('search');
            $(this).html( '<input type="text" placeholder="Search '+title+'" tabindex="'+ el +'"/>' );
        } );

        var oTable = $('#continuous-injections').DataTable({
            "processing" : true,
            "serverSide" : true,
            "stateSave" : true,
            "ajax" : { 'url' : "/injections/continuous-data",
                       'type' : 'POST',
                       'data' : { @if($type == 'area') area_id
                                  @elseif($type == 'field') field_id
                                  @elseif($type == 'location') location_id
                                  @endif  : "{{ $id }}",
                                  type: "CONTINUOUS" }
                   },
            dom: 'Bfrltip',
            autoWidth: false,
            buttons: [
            {
                    extend: 'colvisGroup',
                    text: 'Default View',
                    show: [ 1, 3, 8, 10, 17, 21, 22, 23, 24, 26, 32, 33, 34], // 23,25,31,32],
                    hide: [ 2, 4, 5, 6, 7, 9, 11,12,13,14,15,16,18,19,20,25,27,28,29,30,31]// 24,26,27,28,29,30]
                },
                {
                    extend: 'colvisGroup',
                    name: 'chem',
                    text: 'Chemical Data View',
                    show: [ 1,2,3,8,13,14,15,16,17,18,19,20,21,24,33,34],
                    hide: [ 4,5,6,7,9,10,11,12,22,23,25,26,27,28,29,30,31,32]
                },
                {
                    extend: 'colvisGroup',
                    text: 'Production Data View',
                    show: [1,2,3,4,5,6,34],
                    hide: [7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33]
                },
                {
                    extend: 'colvisGroup',
                    text: 'Show All',
                    show: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34]
                },
                'columnsToggle'
            ],
            order: [[3, "desc"],[1, "asc"], [7, 'asc']],
            keys : {
                editor : editor,
                columns: '.editable'
            },
            select: {
                style:    'os',
                selector: 'td:first-child',
                blurable: true
            },
            "columns": [
                {data: "DT_RowId", name: "injections.id", visible: false, searchable: true },
                {data: "location", name: "locations.name", width: "120px", orderable: true, searchable: true},
                {data: "location_desc", name: "locations.description", width: "120px", orderable: true, searchable: true, visible: false},
                {data: "date", name: "injections.date", width: "60px", orderable: true, searchable: true, className: "editable"},
                {data: "avg_gas", name: "production.avg_gas", visible: false, width: "45px", orderable: true, searchable: true, className: "editable"},
                {data: "avg_oil", name: "production.avg_oil", visible: false, width: "45px", orderable: true, searchable: true, className: "editable"},
                {data: "avg_water", name: "production.avg_water", visible: false, width: "45px", orderable: true, searchable: true, className: "editable"},
                {data: "total_production", name: "total_production", visible: false, width: "45px", orderable: true, searchable: false},
                {data: "name", name: "injections.name", width: "110px", orderable: true, searchable: true, className: "editable"},
                {data: "start_date", name: "injections.start_date", width: "120px", visible: false, orderable: true, searchable: true},
                {data: "chemical_type", name: "injections.chemical_type", width: "110px", orderable: true, searchable: true, className: "editable"},
                {data: "days_in_month", name: "injections.days_in_month", visible: false, width: "40px", orderable: true, searchable: true, className: "editable"},
                {data: "based_on", name: "injections.based_on", width: "40px", visible: false, orderable: true, searchable: true, className: "editable"},
                {data: "chemical_start", name: "injections.chemical_start", visible: false, width: "40px", orderable: true, searchable: true, className: "editable"},
                {data: "chemical_delivered", name: "injections.chemical_delivered", visible: false, width: "40px", orderable: true, searchable: true, className: "editable"},
                {data: "chemical_end", name: "injections.chemical_end", visible: false, width: "40px", orderable: true, searchable: true, className: "editable"},
                {data: "chemical_used", visible: false, width: "40px", orderable: true, searchable: false},
                {data: "usage_rate", name: "injections.usage_rate", visible: true, width: "40px", render: function(data,type,row) { return Math.round(data * 100) / 100; }, orderable: true, searchable: true},
                {data: "estimate_usage_rate", name: "injections.estimate_usage_rate", visible: false, width: "40px", render: function(data,type,row) { return Math.round(data * 100) / 100; }, orderable: false, searchable: false, className: "editable"},
                {data: "estimate_inventory", name: "injections.estimate_inventory", visible: false, width: "40px", render: function(data,type,row) { return Math.round(data * 100) / 100; }, orderable: false, searchable: false},
                {data: "days_remaining", name: "days_remaining", visible: false, width: "40px", render: function(data,type,row) { return Math.round(data * 100) / 100; }, searchable: false, orderable: false},
                {data: "target_rate", name: "injections.target_rate", width: "40px", render: function(data,type,row) { return Math.round(data * 100) / 100; }, searchable: true, orderable: true, className: "editable"},
                {data: "target_ppm", name: "injections.target_ppm", width: "40px", render: function(data,type,row) { return Math.round(data * 100) / 100; }, searchable: true, orderable: true, className: "editable"},
                {data: "actual_ppm", name: "injections.actual_ppm", width: "50px", render: function(data,type,row) { return Math.round(data * 100) / 100; }, searchable: false, orderable: false },
                {data: "vendor_target", name: "injections.vendor_target", width: "50px", searchable: true, orderable: true, className: "editable"},
                {data: "min_rate", name: "injections.min_rate", visible: false, width: "40px", searchable: true, orderable: true, className: "editable"},
                {data: "over_under", name: "over_under", width: "40px", render: function(data,type,row) { return Math.round(data * 100) / 100; }, searchable: false, orderable: true},
                {data: "unit_cost", name: "injections.unit_cost", visible: false, width: "50px", render: function(data,type,row) { return "$" + parseFloat(data).toFixed(2); }, searchable: true, orderable: true, className: "editable"},
                {data: "over_cost", visible: false, width: "40px", render: function(data,type,row) { return Math.round(data * 100) / 100; }, searchable: false, orderable: true},
                {data: "cost_centre", name: "locations.cost_centre", visible: false, width: "70px", orderable: true, searchable: true },
                {data: "vendor_budget", name: "vendor_budget", visible: false, width: "60px", render: function(data,type,row) { return "$" + parseFloat(data).toFixed(2); }, searchable: false, orderable: true},
                {data: "target_budget", name: "target_budget", visible: false, width: "70px", render: function(data,type,row) { return "$" + parseFloat(data).toFixed(2); }, searchable: false, orderable: true},
                {data: "total_monthly_cost", name: "total_monthly_cost", width: "70px", render: function(data,type,row) { return "$" + parseFloat(data).toFixed(2); }, searchable: false, orderable: true},
                {data: "comments", name: "injections.comments", width: "110px", visible: true, orderable: true, searchable: true, className: "editable"},
                {data: "tank_capacity", name: "injections.tank_capacity", visible: false, width: "45px", orderable: true, searchable: true },
                {data: 'action', width: "40px", orderable: false, searchable :false, className: 'action' }
            ],
            "createdRow": function( row, data, dataIndex ) {
                if (data.status == 1 && {{ auth()->user()->isAdmin() ? "false" : "true" }}) $(row).children().removeClass('editable');
            }
        });

        // Restore state
        var state = oTable.state.loaded();
        if ( state ) {
            oTable.columns().eq( 0 ).each( function ( colIdx ) {
                var colSearch = state.columns[colIdx].search;
                if ( colSearch.search && colIdx < oTable.columns().footer().length - 1) {
                    $( 'input', oTable.column( colIdx ).footer() ).val( colSearch.search );
                }
            } );

            oTable.draw();
        }

        // Apply the search
        oTable.columns().eq( 0 ).each( function ( colIdx ) {
            $( 'input', oTable.column( colIdx ).footer() ).on( 'keyup change', function () {
                oTable
                    .column( colIdx )
                    .search( this.value )
                    .draw();
            });
        });

        new $.fn.dataTable.Buttons(oTable, {
                buttons: [
                {
                    extend: 'colvisGroup',
                    text: 'Default View',
                    show: [ 1, 3, 8, 10, 17, 21, 22, 23,25,31,32,33,34],
                    hide: [ 2, 4, 5, 6, 7, 9, 11,12,13,14,15,16,18,19, 20,24,26,27,28,29,30]
                },
                {
                    extend: 'colvisGroup',
                    name: 'chem',
                    text: 'Chemical Data View',
                    show: [ 1,2,3,8,13,14,15,16,17,18,19,20,21,24,34],
                    hide: [ 4,5,6,7,9,10,11,12,22,23,25,26,27,28,29,30,31,32,33]
                },
                {
                    extend: 'colvisGroup',
                    text: 'Production Data View',
                    show: [1,2,3,4,5,6],
                    hide: [7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32]
                },
                {
                    extend: 'colvisGroup',
                    text: 'Show All',
                    show: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32]
                } //,
                // {
                //     extend: 'csv',
                //     text: 'CSV Export'
                // }

            ]
        });

        oTable.buttons(1, null).container().appendTo(oTable.table().container());

       // Disable KeyTable while the main editing form is open
        editor
            .on( 'open', function ( e, mode, action ) {
                console.log('open - ' + mode );
                if ( mode === 'main' ) {   // (mode === 'main')
                    oTable.keys.disable();
                }
            } )
            .on( 'close', function () {
                console.log('close');
                oTable.keys.enable();
            } );

        // Enable chemical view default if redirected from a close out error.
        @if($chem_view)
            oTable.button('chem:name').trigger();
        @endif
    });
</script>
@endsection