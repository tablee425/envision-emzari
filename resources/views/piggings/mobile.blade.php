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
                    <th>Date Scheduled</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td data-search="ID"></td>
                    <td data-search="Start Location"></td>
                    <td data-search="End Location"></td>
                    <td data-search="Date Scheduled"></td>
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
                    label: "License #",
                    name: "license"
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
                    name: "scheduled_on"
                    // type:  'datetime',
                    // def:   function () { return new Date(); }
                }, 
                {
                    label: "Date Shipped",
                    name: "shipped_on"
                }, 
                {
                    label: "Date Pulled",
                    name: "pulled_on"
                }, 
                {
                    label: "Date Cancelled/Checked",
                    name: "cancelled_on"
                }, 
                {
                    label: "Pig Size",
                    name: "pig_size"
                }, 
                {
                    label: "Pig Number",
                    name: "pig_number"
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
                buttons: { label: '&gt;', fn: function() { this.submit(); }}
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
            "ajax" : { 'url' : "/piggings/data",
                       'type' : 'POST',
                       'data' : { type: @if($type == 'area') "area", 
                                  @elseif($type == 'field') "field",
                                  @elseif($type == 'location') "location", 
                                  @else "not_defined",
                                  @endif
                                  id  : {{ $id }} }
                   },
            dom: 'frltip',
            autoWidth: false,
            order: [[1, "asc"]],
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
                {data: "scheduled_on", name: "piggings.scheduled_on", width: "80px", orderable: true, searchable: true, className: 'editable'},
                {data: 'action', orderable: false, searchable :false, className: 'action' }
            ],
            "createdRow": function( row, data, dataIndex ) {
                if (1 == 2) $(row).children().removeClass('editable');
                console.log(data);
            }
        });

        // NOT WORKING oTable.buttons().container().insertBefore('#products_filter');

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