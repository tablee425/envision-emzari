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
            <li>Delivery Tickets</li>
           {{--  <li>Piggings</li> --}}
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-plus"></i> 
               Delivery Tickets
            <span>>  
                
            </span>
        </h1>
    </div>

    <div class="col-md-10 col-lg-offset-1 form-row">
        <a class="btn btn-primary btn-lg" href="{{ action('DeliveryTicketController@getAreaSelection') }}">
            <i class="fa fa-plus"></i> Create Delivery Ticket</a>
    </div>
    
    <div class="col-lg-12" style="margin-bottom: 80px;">
        <table id="delivery-tickets" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ticket Number</th>
                    <th>Status</th>
                    <th>Sales Rep</th>
                    <th>Delivery Date</th>
                    <th>Purchase Order Number</th>
                    <th>Ordered By</th>
                    <th>Delivered By</th>
                    <th>Total Items</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td data-search="ID"></td>
                    <td data-search="Ticket Number"></td>
                    <td data-search="Status"></td>
                    <td data-search="Sales Rep"></td>
                    <td data-search="Delivery Date"></td>
                    <td data-search="Purchase Order Number"></td>
                    <td data-search="Ordered By"></td>
                    <td data-search="Delivered By"></td>
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
    
        // Setup - add a text input to each footer cell
        $('#delivery-tickets tfoot td').each( function (el) {
            var title = $('#delivery-tickets tfoot td').eq( el ).data('search');
            $(this).html( '<input type="text" placeholder="Search '+title+'" tabindex="'+ el +'"/>' );
        } );

        var oTable = $('#delivery-tickets').DataTable({
            "processing" : true,
            "serverSide" : true,
            "stateSave" : true,
            "ajax" : { 'url' : "{{ action('DeliveryTicketController@postData') }}",
                       'type' : 'POST'
                   },
            dom: 'Bfrltip',
            autoWidth: false,
            order: [[1, "asc"]],
            buttons: [
                'columnsToggle',
                'csv'
            ],
            // keys : {
            //     editor : editor,
            //     columns: '.editable'
            // },
            
            select: {
                // style:    'os',
                // selector: 'td:first-child'
                style:    'os',
                selector: 'td:first-child',
                blurable: true
            },
            "columns": [
                {data: "DT_RowId", name: "delivery_tickets.id", visible: false, searchable: true },
                {data: "ticket_number", name: "delivery_tickets.ticket_number", width: "140px", orderable: true, searchable: true },
                {data: "status", name: "delivery_tickets.status", width: "140px", orderable: true, searchable: true, render: function(status) { return status.charAt(0).toUpperCase() + status.slice(1)} },
                {data: "sales_rep", orderable: true, searchable: true, },
                {data: "delivery_date", name: "delivery_tickets.delivery_date", orderable: true, searchable: true, render: function(date) { return moment(date).format('MM-DD-YYYY'); } },
                {data: "purchase_order_number", name: "delivery_tickets.purchase_order_number", orderable: true, searchable: true },
                {data: "ordered_by", name: "delivery_tickets.ordered_by", orderable: true, searchable: true },
                {data: "delivered_by", name: "delivery_tickets.delivered_by", orderable: true, searchable: true },
                {data: "total", orderable: false, searchable: false },
                {data: "action", orderable: false, searchable :false, className: 'action' }
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
        // editor
        //     .on( 'open', function ( e, mode, action ) {
        //         console.log('open - ' + mode );
        //         if ( mode === 'main' ) {   // (mode === 'main')
        //             oTable.keys.disable();
        //         }
        //     } )
        //     .on( 'close', function () {
        //         console.log('close');
        //         oTable.keys.enable();
        //     } );
    });
</script>
@endsection