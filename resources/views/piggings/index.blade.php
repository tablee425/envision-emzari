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
            <li>Piggings</li>
           {{--  <li>Piggings</li> --}}
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-plus"></i> 
               Piggings
            <span>>  
                
            </span>
        </h1>
    </div>

    @if ($type == "location")
    <div class="col-md-10 col-lg-offset-1 form-row">
        <a class="btn btn-primary btn-lg" href="/piggings/create?location_id={{ $id }}">
            <i class="fa fa-plus"></i> Add Pigging</a>
    </div>
    @endif
    
    <div class="col-lg-12" style="margin-bottom: 80px;">
        <table id="piggings" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Start Location</th>
                    <th>End Location</th>
                    <th>Order</th>
                    <th>OD</th>
                    <th>Thickness</th>
                    <th>Line Type</th>
                    <th>License #</th>
                    <th>Length (km)</th>
                    <th>Frequency (days)</th>
                    <th>MOP(kPa)</th>
                    <th>Date Scheduled</th>
                    <th>Date Shipped</th>
                    <th>Date Pulled</th>
                    <th>Date Cancelled/Checked</th>
                    <th>Pig #</th>
                    <th>Gauged</th>
                    <th>Condition</th>
                    <th>Wax</th>
                    <th>Corr Inh Vol (L)</th>
                    <th>Biocide Vol (L)</th>
                    <th>Diluent</th>
                    <th>Water Vol (L)</th>
                    <th>Line Pressure</th>
                    <th>Pressure Switch</th>
                    <th>Field Operator</th>
                    <th>Comments</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td data-search="ID"></td>
                    <td data-search="Start Location"></td>
                    <td data-search="End Location"></td>
                    <td data-search="Order"></td>
                    <td data-search="OD"></td>
                    <td data-search="Thickness"></td>
                    <td data-search="Line Type"></td>
                    <td data-search="License #"></td>
                    <td data-search="Length (km)"></td>
                    <td data-search="Frequency"></td>
                    <td data-search="MOP(kPa)"></td>
                    <td data-search="Date Scheduled"></td>
                    <td data-search="Date Shipped"></td>
                    <td data-search="Date Pulled"></td>
                    <td data-search="Date Cancelled"></td>
                    <td data-search="Pig #"></td>
                    <td data-search="Gauged"></td>
                    <td data-search="Condition"></td>
                    <td data-search="Wax"></td>
                    <td data-search="Corr Inh Vol (L)"></td>
                    <td data-search="Biocide Vol (L)"></td>
                    <td data-search="Diluent"></td>
                    <td data-search="Water Vol (L)"></td>
                    <td data-search="Line Pressure"></td>
                    <td data-search="Pressure Switch"></td>
                    <td data-search="Field Operator"></td>
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
            ajax: "{{ action('PiggingController@postTableUpdate')}}",
            table: "#piggings",
            fields: [ {
                    label: "OD",
                    name: "od"
                }, 
                {
                    label: "Thickness",
                    name: "thickness"
                }, 
                {
                    label: "Order",
                    name: "order"
                }, 
                {
                    label: "License #",
                    name: "license"
                }, 
                {
                    label: "Length (km)",
                    name: "length"
                }, 
                {
                    label: "Frequency",
                    name: "frequency"
                }, 
                {
                    label: "Line Type",
                    name: "line_type"
                }, 
                {
                    label: "Line Pressure",
                    name: "line_pressure"
                }, 
                {
                    label: "Pressure Switch",
                    name: "pressure_switch"
                }, 
                {
                    label: "MOP(kPa)",
                    name: "MOP"
                }, 
                {
                    label: "Date Scheduled",
                    name: "scheduled_on",
                    type: "datetime"
                }, 
                {
                    label: "Date Shipped",
                    name: "shipped_on",
                    type: "datetime"
                }, 
                {
                    label: "Date Pulled",
                    name: "pulled_on",
                    type: "datetime"
                }, 
                {
                    label: "Date Cancelled",
                    name: "cancelled_on",
                    type: "datetime"
                }, 
                {
                    label: "Pig Number",
                    name: "pig_number"
                }, 
                {
                    label: "Gauged",
                    name: "gauged",
                    type: "select",
                    options: [
                        { label: '', value: null },
                        { label: "Yes", value: "Yes" },
                        { label: "No", value: "No" }
                    ],
                }, 
                {
                    label: "Condition",
                    name: "condition",
                    type: "select",
                    options: [
                        { label: '', value: null },
                        { label: "Good", value: "Good" },
                        { label: "Poor", value: "Poor"}
                    ]
                }, 
                {
                    label: "Wax",
                    name: "wax"
                    // type: "select",
                    // options: [
                    //     { label: '', value: null },
                    //     { label: "0 = None", value: 0 },
                    //     { label: "1", value: 1 },
                    //     { label: "2", value: 2 },
                    //     { label: "3", value: 3 },
                    //     { label: "4", value: 4 },
                    //     { label: "5 = High", value: 5 },
                    // ]
                }, 
                {
                    label: "Corr Inh Vol",
                    name: "corr_inh_vol"
                },
                {
                    label: "Biocide Vol",
                    name: "biocide_vol"
                },
                {
                    label: "Diluent",
                    name: "diluent"
                },
                {
                    label: "Water Vol",
                    name: "water_vol"
                },
                {
                    label: "Field Operator",
                    name: "field_operator"
                },
                {
                    label: "Comments",
                    name: "comments"
                }
            ]
        } );
       
        // Activate an inline edit on click of a table cell
        $('#piggings').on( 'click', 'tbody .editable', function (e) {
            // var parent = $(this).parent('tr');
            // var elemStatus = parent.find('#js-injection-status');
            // if (elemStatus.attr('data-status') == 1) {
            //     parent.find('.editable').removeClass('editable')
            //     return false;
            // }
            editor.inline( this, {
                buttons: { label: '&gt;', fn: function() { this.submit(); }},
                drawType: 'page'
            } );
        } );

        // Setup - add a text input to each footer cell
        $('#piggings tfoot td').each( function (el) {
            var title = $('#piggings tfoot td').eq( el ).data('search');
            $(this).html( '<input type="text" placeholder="Search '+title+'" tabindex="'+ el +'"/>' );
        } );

        var oTable = $('#piggings').DataTable({
            "processing" : true,
            "serverSide" : true,
            "stateSave" : true,
            "ajax" : { 'url' : "/piggings/data",
                       'type' : 'POST',
                       'data' : { type: @if($type == 'area') "area", 
                                  @elseif($type == 'field') "field",
                                  @elseif($type == 'location') "location", 
                                  @else "not_defined",
                                  @endif
                                  id  : {{ $id }} }
                   },
            dom: 'Bfrltip',
            autoWidth: false,
            order: [[1, "asc"]],
            buttons: [
                {
                    extend: 'colvisGroup',
                    text: 'Corrosion View',
                    show: [1,2,3,4,5,6,7,9,10,11,12,13,14,15,16,17,18,21,22,23],
                    hide: [8,19,20]
                },
                {
                    extend: 'colvisGroup',
                    text: 'Maintenance View',
                    show: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,19,20,21,22,23],
                    hide: [15,16,17,18]
                },
                'columnsToggle',
                'csv'
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
                {data: "DT_RowId", name: "piggings.id", visible: false, searchable: true },
                {data: "start_location", name: "locations.name", width: "140px", orderable: true, searchable: true },
                {data: "end_location", name: "locations.name", width: "140px", orderable: true, searchable: true },
                {data: "order", name: "piggings.order", orderable: true, searchable: true, className: 'editable' },
                {data: "od", name: "piggings.od", orderable: true, searchable: true, className: 'editable' },
                {data: "thickness", name: "piggings.thickness", orderable: true, searchable: true, className: 'editable' },
                {data: "line_type", name: "piggings.line_type", orderable: true, searchable: true, className: 'editable' },
                {data: "license", name: "piggings.license", orderable: true, searchable: true, className: 'editable' },
                {data: "length", name: "piggings.length", orderable: true, searchable: true, className: 'editable' },
                {data: "frequency", name: "piggings.frequency", orderable: true, searchable: true, className: 'editable' },
                {data: "MOP", name: "piggings.MOP", orderable: true, searchable: true, className: 'editable' },
                {data: "scheduled_on", name: "piggings.scheduled_on", width: "80px", orderable: true, searchable: true, className: 'editable'},
                {data: "shipped_on", name: "piggings.shipped_on", width: "80px", orderable: true, searchable: true, className: 'editable'},
                {data: "pulled_on", name: "piggings.pulled_on", width: "80px", orderable: true, searchable: true, className: 'editable'},
                {data: "cancelled_on", name: "piggings.cancelled_on", width: "80px", orderable: true, searchable: true, className: 'editable'},
                {data: "pig_number", name: "piggings.pig_number", orderable: true, searchable: true, className: 'editable' },
                {data: "gauged", name: "piggings.gauged", orderable: true, searchable: true, className: 'editable' },
                {data: "condition", name: "piggings.condition", orderable: true, searchable: true, className: 'editable' },
                {data: "wax", name: "piggings.wax", orderable: true, searchable: true, className: 'editable' },
                {data: "corr_inh_vol", name: "piggings.corr_inh_vol", orderable: true, searchable: true, className: 'editable' },
                {data: "biocide_vol", name: "piggings.biocide_vol", orderable: true, searchable: true, className: 'editable' },
                {data: "diluent", name: "piggings.diluent", orderable: true, searchable: true, className: 'editable' },
                {data: "water_vol", name: "piggings.water_vol", orderable: true, searchable: true, className: 'editable' },
                {data: "line_pressure", name: "piggings.line_pressure", orderable: true, searchable: true, className: 'editable' },
                {data: "pressure_switch", name: "piggings.pressure_switch", orderable: true, searchable: true, className: 'editable' },
                {data: "field_operator", name: "piggings.field_operator", orderable: true, searchable: true, className: 'editable' },
                {data: "comments", name: "piggings.comments", width: "110px", orderable: true, searchable: true, className: "editable"},
                {data: 'action', orderable: false, searchable :false, className: 'action' }
            ],
            "createdRow": function( row, data, dataIndex ) {
                if (1 == 2) $(row).children().removeClass('editable');
                console.log(data);
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

        // new $.fn.dataTable.Buttons(oTable, {
        //         buttons: [
        //         {
        //             extend: 'colvisGroup',
        //             text: 'Corrosion View',
        //             show: [1,2,3,4,5,6,8,9,10,11,12,13,14,15,16,17,20,21,22,23],
        //             hide: [7,18,19]
        //         },
        //         {
        //             extend: 'colvisGroup',
        //             text: 'Maintenance View',
        //             show: [1,2,3,4,5,6,20],
        //             hide: [7,8,9,10,11,12,13,14,15,16,17,18,19,22]
        //         }
        //     ]
        // });
        //         {
        //             extend: 'colvisGroup',
        //             text: 'Show All',
        //             show: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22]
        //         } //,
        //         // { 
        //         //     extend: 'csv',
        //         //     text: 'CSV Export'
        //         // }

        //     ]
        // });

        // oTable.buttons(1, null).container().appendTo(oTable.table().container());

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