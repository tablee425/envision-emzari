@extends('layouts.main')

@section('css_assets')
    @include('assets.DTeditor') 
    <!-- Manually inserted two links below 
    <link href="https://cdn.datatables.net/keytable/2.1.1/css/keyTable.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.2.0/css/buttons.dataTables.min.css" rel="stylesheet"> -->
    <style>
        .fa { display: inline; }
        .action { white-space: nowrap; }
        .action i { cursor: pointer; }
        .editable {
            background-color: #ECF3F8;
            font-weight: bold;
        }
        td input {
            width: 96%;
            text-align: center;
        }
        @media (min-width: 1200px)
        {   .col-lg-offset-1 {
                margin-left: 0.5%;
            }
        }
        div.DTE_Inline input {
            border: none;
            background-color: transparent;
            padding: 0 !important;
            font-size: 90%;
        }
     
        div.DTE_Inline input:focus {
            outline: none;
            background-color: transparent;
        }
    </style>
@endsection

@section('content')
<!-- #MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>{{ ucfirst($injection_title) }}</li>
            <li>Batch Injections</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-plus"></i> 
                {{ ucfirst($injection_title) }} 
            <span>>  
                Batch Injections
            </span>
        </h1>
    </div>

    @if ($type == "location" && Auth::user()->admin)
    <div class="col-md-10 col-lg-offset-1 form-row">
        <a class="btn btn-primary btn-lg" href="/injections/create?type=batch&location_id={{ $id }}">
            <i class="fa fa-plus"></i> Add Batch Injection</a>
    </div>
    @endif
    
    <div class="col-lg-10 col-lg-offset-1" style="margin-bottom: 80px;">
        <table id="batch-injections" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
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
                    <th>Chemical Type</th>
                    <th>Batch Size</th>
                    <th>Circulation Time</th>
                    <th>Diluent Req Water</th>
                    <th>Actual Frequency</th>
                    <th>Unit Cost</th>
                    <th>Target Frequency</th>
                    <th>Batch Cost</th>
                    <th>Target Cost</th>
                    <th>Cost Centre</th>
                    <th>Over Injection</th>
                    <th>Under Injection</th>
                    <th>Corrosion Inhibitor Ratio</th>
                    <th>Paraffin Solvent Ratio</th>
                    <th>Comments</th>
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
                    <td data-search="Total"></td>
                    <td data-search="Chemical"></td>
                    <td data-search="Chemical Type">Chemical Type</td>
                    <td data-search="Batch Size"></td>
                    <td data-search="Circulation Time"></td>
                    <td data-search="Diluent Req Water"></td>
                    <td data-search="Actual Frequency"></td>
                    <td data-search="Unit Cost"></td>
                    <td data-search="Target Frequency"></td>
                    <td data-search="Batch Cost"></td>
                    <td data-search="Target Cost"></td>
                    <td data-search="Cost Centre"></td>
                    <td data-search="Over Injection Cost"></td>
                    <td data-search="Under Injection Cost"></td>
                    <td data-search="Corrosion Inhibitor Ration"></td>
                    <td data-search="Paraffin Ratio"></td>
                    <td data-search="Comments"></td>

                    <!-- <th data-search="Target Frequency">Target Frequency</th>
                    <th data-search="Batch Cost">Batch Cost</th>
                    <th data-search="Target Cost">Target Cost</th> -->
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
            ajax: "/injections/batch-update",
            table: "#batch-injections",
            fields: [ {
                    label: "Location",
                    name: "location"
                }, 
                 {
                    label: "Injection Date",
                    name: "injection_date"
                }, {
                    label: "Gas Production",
                    name: "avg_gas"
                },{
                    label: "Oil Production",
                    name: "avg_oil"
                },{
                    label: "Water Production",
                    name: "avg_water"
                },{
                    label: "Chemical",
                    name: "chemical"
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
                    label: "Batch Size",
                    name: "batch_size"
                }, {
                    label: "Circulation Time",
                    name: "circulation_time"
                }, {
                    label: "Diluent Required",
                    name: "diluent_required"
                }, {
                    label: "Actual Frequency",
                    name: "scheduled_batches"
                },{
                    label: "Unit Cost",
                    name: "unit_cost"
                },{
                    label: "Target Frequency",
                    name: "target_frequency"
                },
                {
                    label: "Batch Cost",
                    name: "batch_cost"
                },
                {
                    label: "Target Cost",
                    name: "target_cost"
                },
                // {
                //     label: "Cost Centre",
                //     name: "cost_centre"
                // },
                {
                    label: "Over Injection Cost",
                    name: "over_injection"
                },
                {
                    label: "Under Injection",
                    name: "under_injection"
                },
                {
                    label: "Corrosion Inhibitor Ration",
                    name: "inhibitor_ration"
                },
                {
                    label: "Paraffin Ratio",
                    name: "paraffin_ratio"
                },
                {
                    label: "Active",
                    name: "active"
                },
                {
                    label: "Comments",
                    name: "comments"
                }

            ]
        } );
       
        // Activate an inline edit on click of a table cell
        $('#batch-injections').on( 'click', 'tbody .editable', function (e) {
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
        } );

        // Setup - add a text input to each footer cell
        $('#batch-injections tfoot td').each( function (el) {
            var title = $('#batch-injections tfoot td').eq( el ).data('search');
            $(this).html( '<input type="text" placeholder="Search '+title+'" tabindex="'+ el +'"/>' );
        } );

        var oTable = $('#batch-injections').DataTable({
            "processing" : true,
            "serverSide" : true,
            "stateSave" : true,
            "ajax" : { 'url' : "/injections/batch-data",
                       'type' : 'POST',
                       'data' : { @if($type == 'area') area_id 
                                  @elseif($type == 'field') field_id
                                  @elseif($type == 'location') location_id 
                                  @endif  : "{{ $id }}",
                                  type: "BATCH" }
                   },
            dom: 'Bfrltip',
            autoWidth: false,
            order: [[3, "desc"], [1, "asc"], [8, "asc"]],
            buttons: [
                {
                    extend: 'colvisGroup',
                    text: 'Default View',
                    show: [1,3,8,9,10,11,12,13,14,15,18,23],
                    hide: [2,4,5,6,7,16,17,19,20,21,22]
                },
                {
                    extend: 'colvisGroup',
                    text: 'Chemical Data View',
                    show: [1,3,8,10,13,15,21,22,23],
                    hide: [2,4,5,6,7,9,11,12,14,16,17,18,19,20]
                },
                {
                    extend: 'colvisGroup',
                    text: 'Production Data View',
                    show: [1,2,3,4,5,6],
                    hide: [7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23]
                },
                {
                    extend: 'colvisGroup',
                    text: 'Show All',
                    show: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23]
                },
                'columnsToggle' //,
                // 'csv'
            ],
            keys : {
                editor : editor,
                columns: '.editable'
            },
            select: {
                // style:    'os',
                // selector: 'td:first-child'
                style:    'os',
                selector: 'td:first-child',
                blurable: true
            },
            "columns": [
                {data: "DT_RowId", name: "injections.id", visible: false, searchable: true },
                {data: "location", name: "locations.name", orderable: true, searchable: true },
                {data: "location_desc", name: "locations.description", width: "120px", orderable: true, searchable: true, visible: false},
                {data: "injection_date", name: "injections.date", width: "60px", orderable: true, searchable: true, className: 'editable'},
                {data: "avg_gas", name: "production.avg_gas", orderable: true, searchable: true, visible: false, className: 'editable' },
                {data: "avg_oil", name: "production.avg_oil", orderable: true, searchable: true, visible: false, className: 'editable' },
                {data: "avg_water", name: "production.avg_water", orderable: true, searchable: true, visible: false, className: 'editable' },
                {data: "total_production", name: "total_production", orderable: true, searchable: false, visible: false },
                {data: "chemical", name: 'injections.name', width: "120px", orderable: true, searchable: true, className: 'editable'},
                {data: "chemical_type", name: "injections.chemical_type", width: "110px", orderable: true, searchable: true, className: "editable"},
                {data: "batch_size", name: "injections.batch_size", orderable: true, searchable: true, className: 'editable'},
                {data: "circulation_time", name: "injections.circulation_time", orderable: true, searchable: true, className: 'editable'},
                {data: "diluent_required", name: "injections.diluent_required", orderable: true, searchable: true, className: 'editable'},
                {data: "scheduled_batches", name: "injections.scheduled_batches", orderable: true, searchable: true, className: 'editable'},
                {data: "unit_cost", name: "injections.unit_cost", render: function(data,type,row) { return "$" + parseFloat(data).toFixed(2) }, orderable: true, searchable: true, className: 'editable'},
                {data: "target_frequency", name: "injections.target_frequency", orderable: true, searchable: true, className: 'editable'},
                {data: "batch_cost", name: "batch_cost", render: function(data,type,row) { return "$" + parseFloat(data).toFixed(2) }, orderable: true, searchable: false, visible: false  },
                {data: "target_cost", name: "target_cost", render: function(data,type,row) { return "$" + parseFloat(data).toFixed(2) }, orderable: true, searchable: false, visible: false  },
                {data: "cost_centre", name: "locations.cost_centre", width: "70px", orderable: true, searchable: true },
                {data: "over_injection", name: "over_injection", orderable: true, searchable: false, visible: false  },
                {data: "under_injection", name: "under_injection", orderable: true, searchable: false, visible: false  },
                {data: "inhibitor_ration", name: "inhibitor_ration", orderable: true, searchable: false, visible: false  },
                {data: "paraffin_ratio", name: "paraffin_ratio", orderable: true, searchable: false, visible: false  },
                {data: "comments", name: "injections.comments", width: "110px", visible: true, orderable: true, searchable: true, className: "editable"},
                {data: 'action', orderable: false, searchable :false, className: 'action' }
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
                    show: [1,3,8,9,10,11,12,13,14,15,18,23],
                    hide: [2,4,5,6,7,16,17,19,20,21,22]
                },
                {
                    extend: 'colvisGroup',
                    text: 'Chemical Data View',
                    show: [1,3,8,10,13,15,21,22,23],
                    hide: [2,4,5,6,7,9,11,12,14,16,17,18,19,20]
                },
                {
                    extend: 'colvisGroup',
                    text: 'Production Data View',
                    show: [1,2,3,4,5,6],
                    hide: [7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23]
                },
                {
                    extend: 'colvisGroup',
                    text: 'Show All',
                    show: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23]
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
    });
</script>
@endsection