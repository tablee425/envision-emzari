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
            <li>Continous Injection</li>
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
        <table id="continuous-injections" class="table responsive table-striped table-bordered table-hover compact" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Area</th>
                    <th>Field</th>
                    <th>Location</th>
                    <th>Location Description</th>
                    <th>Date</th>
                    <th>Gas Prod.</th>
                    <th>Oil Prod.</th>
                    <th>Water Prod.</th>
                    <th>Total</th>
                    <th>Chemical</th>
                    <th>Start Date</th>
                    <th>Days In Month</th>
                    <th>Chemical Type</th>
                    <th>Based On</th>
                    <th>Inv - Start</th>
                    <th>Inv - Delivered</th>
                    <th>Inv - End</th>
                    <th>Chem Used</th>
                    <th>Usage Rate</th>
                    <th>Chem Days Remaining</th>
                    <th>Target Rate</th>
                    <th>Target PPM</th>
                    <th>Vendor Target</th>
                    <th>Min Rate</th>
                    <th>Over/Under</th>
                    <th>Unit Cost</th>
                    <th>Over Cost</th>
                    <th>Vendor Budget</th>
                    <th>Target Budget</th>
                    <th>Actual Rate</th>
                    <th>Total Cost</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                @foreach($injections as $injection)
                <tr>
                    <td>{{ $injection->location->field->area->name }}</td>
                    <td>{{ $injection->location->field->name }}</td>
                    <td>{{ $injection->location->name }}</td>
                    <td>{{ $injection->location->description }}</td>
                    <td>{{ $injection->date->format('Y-m') }}</td>
                    <td>{{ $injection->production->avg_gas }}</td>
                    <td>{{ $injection->production->avg_oil }}</td>
                    <td>{{ $injection->production->avg_water }}</td>
                    <td>{{ $injection->production->totalProduction() }}</td>
                    <td>{{ $injection->name }}</td>
                    <td>{{ $injection->start_date->format('m-d-Y') }}</td>
                    <td>{{ $injection->days_in_month }}</td>
                    <td>{{ $injection->chemical_type }}</td>
                    <td>{{ $injection->based_on }}</td>
                    <td>{{ $injection->chemical_start }}</td>
                    <td>{{ $injection->chemical_delivered }}</td>
                    <td>{{ $injection->chemical_end }}</td>
                    <td>{{ $injection->chemicalUsed() }}</td>
                    <td>{{ $injection->usageRate() }}</td>
                    <td>{{ $injection->daysRemaining() }}</td>
                    <td>{{ $injection->targetRate() }}</td>
                    <td>{{ $injection->target_ppm }}</td>
                    <td>{{ $injection->vendor_target }}</td>
                    <td>{{ $injection->min_rate }}</td>
                    <td>{{ $injection->overUnder() }}</td>
                    <td>{{ $injection->unit_cost * 0.01 }}</td>
                    <td>{{ $injection->overCost() }}</td>
                    <td>{{ $injection->vendorBudget() }}</td>
                    <td>{{ $injection->targetBudget() }}</td>
                    <td>{{ $injection->actualRate() }}</td>
                    <td>{{ $injection->totalMonthlyCost() }}</td>
                    <td>{{ $injection->comments }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td data-search="Area"></td>
                    <td data-search="Field"></td>
                    <td data-search="Location"></td>
                    <td data-search="Location Description"></td>
                    <td data-search="Date"></td>
                    <td data-search="Gas"></td>
                    <td data-search="Oil"></td>
                    <td data-search="Water"></td>
                    <td data-search="Chemical"></td>
                    <td data-search="Total"></td>
                    <td data-search="Days In Month"></td>
                    <td data-search="Chemical Type"></td>
                    <td data-search="Based On"></td>
                    <td data-search="Chemical Inventory - Start"></td>
                    <td data-search="Checmical Delivered"></td>
                    <td data-search="Chemical Inventory - End"></td>
                    <td data-search="Chemical Used"></td>
                    <td data-search="Current Usage Rate"></td>
                    <td data-search="Chemical Inventory - Days Remaining"></td>
                    <td data-search="Target Rate"></td>
                    <td data-search="Vendor Target"></td>
                    <td data-search="Minimum Rate"></td>
                    <td data-search="Over/Under"></td>
                    <td data-search="Unit Cost"></td>
                    <td data-search="Monthly Over Injection Cost"></td>
                    <td data-search="Vendor Budget"></td>
                    <td data-search="Target Budget"></td>
                    <td data-search="Actual Rate"></td>
                    <td data-search="Total Monthly Cost"></td>
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

        // Setup - add a text input to each footer cell
        $('#continuous-injections tfoot td').each( function (el) {
            var title = $('#continuous-injections tfoot td').eq( el ).data('search');
            $(this).html( '<input type="text" placeholder="Search '+title+'" tabindex="'+ el +'"/>' );
        } );

        var oTable = $('#continuous-injections').DataTable({
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
                    extend: 'colvisGroup',
                    text: 'Default View',
                    show: [ 1, 7, 16, 17, 24, 19, 25 ],
                    hide: [ 2, 3, 4, 5, 6, 8,9,10,11,12,13,14,15,18,20,21,22,23 ]
                },
                {
                    extend: 'colvisGroup',
                    text: 'Chemical Data View',
                    show: [ 1,7,8,10,11,12,14,15,17 ],
                    hide: [ 2,3,4,5,6,9,13,16,18,19,20,21,22,23,24,25 ]
                },
                {
                    extend: 'colvisGroup',
                    text: 'Production Data View',
                    show: [1,2,3,4,5],
                    hide: [6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25]
                },
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