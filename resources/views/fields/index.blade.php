@extends('layouts.main')

@section('css_assets')
    <!-- removed include('assets.DTeditor') Manually inserted two links below -->
    <link href="https://cdn.datatables.net/keytable/2.1.1/css/keyTable.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.2.0/css/buttons.dataTables.min.css" rel="stylesheet">
    <style>
        .fa { display: inline; }
        .action { white-space: nowrap; }
        .action i { cursor: pointer; }
    </style>
@endsection

@section('content')
<!-- #MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>Fields</li>
            <li>Locations</li>
        </ol>
    </div>
    @if (Session::has('okInjections'))
        <div class="flash-message">
            <p class="alert alert-success">
                {{Session::get('okInjections')}}
            </p>
        </div>
    @endif
    @if (Session::has('warningInjections'))
        <div class="flash-message">
            <p class="alert alert-warning">
                {{Session::get('warningInjections')}}
            </p>
        </div>
    @endif
    @if (Session::has('errorContinuous'))
        <div class="flash-message">
            <p class="alert alert-danger">
                
                @foreach(Session::get('errorContinuous') as $location)
                    Continuous Injection doesn't have Ending Inventory for {{ $location['injectionName'] }}. Click <a href="/injections/continuous?type=location&id={{ $location['locationId'] }}&view=chemical">here</a> to edit<br />
                @endforeach
                
            </p>
        </div>
    @endif
    @if (Session::has('errorBatch'))
        <div class="flash-message">
            <p class="alert alert-danger">
                
                @foreach(Session::get('errorBatch') as $location)
                    Batch Injection doesn't have Actual Frequency for {{ $location['injectionName'] }}. Click <a href="/injections/batch?type=location&id={{ $location['locationId'] }}">here</a> to edit <br />
                @endforeach
                
            </p>
        </div>
    @endif
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-home"></i> 
                Fields 
            <span>>  
                Locations
            </span>
        </h1>
    </div>
    
    @if (Auth::user()->admin)
    <div class="col-md-10 col-lg-offset-1 form-row">
        <a class="btn btn-primary btn-lg" href="/fields/create?area_id={{ $area_id }}">
            <i class="fa fa-plus"></i> Add Field</a>
    </div>
    @endif

    <div class="col-lg-10 col-lg-offset-1" style="margin-bottom: 80px;">
        <table id="fields" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Field Name</th>
                    <th>Locations</th>
                    <th>Piggings</th>
                    <th>Continous</th>
                    <th>Batch</th>
                    <th>Composition</th>
                    <th>Analysis</th>
                    <th>Files</th>
                    <th>Close Out</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tfoot>
                <th data-search="ID"></th>
                <th data-search="Field Name"></th>
                <th data-search="Locations"></th>
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
        $('#fields tfoot th').each( function (el) {
            var title = $('#fields tfoot th').eq( el ).data('search');
            $(this).html( '<input type="text" placeholder="Search '+title+'" tabindex="'+ el +'"/>' );
        } );

        var oTable = $('#fields').DataTable({
            "processing" : true,
            "serverSide" : true,
            "ajax" : { 'url' : "/fields/data",
                       'type' : 'POST',
                       'data' : { area_id: {{ $area_id ?: '0' }} }
                   },
            dom: 'Bfrltip',
            buttons: [
                'columnsToggle'
            ],
            "columns": [
                {data: "DT_RowId", name: "fields.id", orderable: true, visible: false },
                {data: "name", name: "products.name", orderable: true, searchable: true,
                 render: function(data, type, row) {
                    return "<a href=/locations?field_id="+ row.DT_RowId + ">" + data + "</a>";
                    } 
                },
                {data: "location_count", orderable: true, searchable: false},
                {data: 'piggings', orderable: false, searchable :false, className: 'action' },
                {data: 'continuous', orderable: false, searchable :false, className: 'action' },
                {data: 'batch', orderable: false, searchable :false, className: 'action' },
                {data: 'composition', orderable: false, searchable :false, className: 'action' },
                {data: 'analysis', orderable: false, searchable :false, className: 'action' },
                {data: 'files', orderable: false, searchable :false, className: 'action' },
                {data: 'close', orderable: false, searchable :false, className: 'action' },
                {data: 'action', orderable: false, searchable :false, className: 'action' }
            ]
        });
        // Apply the search
        // oTable.columns().eq( 0 ).each( function ( colIdx ) {
        //     $( 'input', oTable.column( colIdx ).footer() ).on( 'keyup change', function () {
        //         oTable
        //             .column( colIdx )
        //             .search( this.value )
        //             .draw();
        //     });
        // });
    });
</script>
@endsection