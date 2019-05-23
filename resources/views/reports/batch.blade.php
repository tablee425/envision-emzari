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
            <li>Batch Injection</li>
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
        <table id="batch-injections" class="table responsive table-striped table-bordered table-hover compact" cellspacing="0" width="100%">
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
                    <th>Chemical Type</th>
                    <th>Batch Size</th>
                    <th>Circulation Time</th>
                    <th>Diluent Req Water</th>
                    <th>Actual Frequency</th>
                    <th>Unit Cost</th>
                    <th>Target Frequency</th>
                    <th>Batch Cost</th>
                    <th>Target Cost</th>
                    <th>Over Injection</th>
                    <th>Under Injection</th>
                    <th>Corrosion Inhibitor Ration</th>
                    <th>Paraffin Ratio</th>
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
                    <td>{{ $injection->chemical_type }}</td>
                    <td>{{ $injection->batch_size }}</td>
                    <td>{{ $injection->circulation_time }}</td>
                    <td>{{ $injection->diluent_required }}</td>
                    <td>{{ $injection->scheduled_batches }}</td>
                    <td>{{ $injection->unit_cost * 0.01}}</td>
                    <td>{{ $injection->target_frequency }}</td>
                    <td>{{ $injection->batchCost() }}</td>
                    <td>{{ $injection->targetCost() }}</td>
                    <td>{{ $injection->overInjectionCost() }}</td>
                    <td>{{ $injection->underInjectionCost() }}</td>
                    <td>{{ $injection->corrosionInhibitorRatio() }}</td>
                    <td>{{ $injection->paraffinRatio() }}</td>
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
                    <td data-search="Total"></td>
                    <td data-search="Chemical"></td>
                    <td data-search="Chemical Type"></td>
                    <td data-search="Batch Size"></td>
                    <td data-search="Circulation Time"></td>
                    <td data-search="Diluent Req Water"></td>
                    <td data-search="Actual Frequency"></td>
                    <td data-search="Unit Cost"></td>
                    <td data-search="Target Frequency"></td>
                    <td data-search="Batch Cost"></td>
                    <td data-search="Target Cost"></td>
                    <td data-search="Over Injection Cost"></td>
                    <td data-search="Under Injection Cost"></td>
                    <td data-search="Corrosion Inhibitor Ration"></td>
                    <td data-search="Paraffin Ratio"></td>
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
        $('#batch-injections tfoot td').each( function (el) {
            var title = $('#batch-injections tfoot td').eq( el ).data('search');
            $(this).html( '<input type="text" placeholder="Search '+title+'" tabindex="'+ el +'"/>' );
        } );

        var oTable = $('#batch-injections').DataTable({
            dom: 'Bfrltip',
            buttons: [
                'columnsToggle'
            ],
            order: [[1, "asc"],[2, "asc"], [3, 'desc']]
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