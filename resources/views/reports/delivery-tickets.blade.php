@extends('layouts.main')

@section('css_assets')
    @include('assets.DTeditor')
    <link rel="stylesheet" type="text/css" https://cdn.datatables.net/responsive/2.1.0/css/responsive.dataTables.min.css" />
    <style>
        .fa { display: inline; }
        .action { white-space: nowrap; }
        .action i { cursor: pointer; }
        @media (min-width: 1200px)
        {   .col-lg-offset-1 {
                margin-left: 0.5%;
            }
        }
    </style>
@endsection

@section('content')
<!-- #MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>Chemical Deliveries</li>
            <li>Reports</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-plus"></i> 
                {{ $title }} 
        </h1>
    </div>
    
    <div class="col-lg-10 col-lg-offset-1" style="margin-bottom: 80px;">
        <table id="delivery-items" class="table responsive table-striped table-bordered table-hover compact" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Ticket Number</th>
                    <th>Area</th>
                    <th>Field</th>
                    <th>Location</th>
                    <th>Chemical</th>
                    <th>Delivery Date</th>
                    <th>Injection Type</th>
                    <th>Quantity</th>
                    <th>Packaging</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->deliveryTicket->company->name }}</td>
                    <td>{{ $item->deliveryTicket->ticket_number }}</td>
                    <td>{{ $item->location->field->area->name }}</td>
                    <td>{{ $item->location->field->name }}</td>
                    <td>{{ $item->location->name }}</td>
                    <td>{{ $item->chemical }}</td>
                    <td>{{ $item->delivery_date }}</td>
                    <td>{{ $item->injection_type }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->packaging }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td data-search="Company"></td>
                    <td data-search="Ticket Number"></td>
                    <td data-search="Area"></td>
                    <td data-search="Field"></td>
                    <td data-search="Location"></td>
                    <td data-search="Chemical"></td>
                    <td data-search="Delivery Date"></td>
                    <td data-search="Injection Type"></td>
                    <td data-search="Quantity"></td>
                    <td data-search="Packaging"></td>
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
        $('#delivery-items tfoot td').each( function (el) {
            var title = $('#delivery-items tfoot td').eq( el ).data('search');
            $(this).html( '<input type="text" placeholder="Search '+title+'" tabindex="'+ el +'"/>' );
        } );

        var oTable = $('#delivery-items').DataTable({
            dom: 'Bfrltip',
            buttons: [
                'columnsToggle'
            ],
            order: [[1, "asc"],[2, "asc"], [3, 'desc']]
        });

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
                    extend: 'csv',
                    text: 'CSV Export',
                    exportOptions: {
                        columns: ':visible'
                    }
                }

            ]
        });

        oTable.buttons(1, null).container().appendTo(oTable.table().container());

    });
</script>
@endsection