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
            <li>{{ Auth::user()->activeCompany()->name }} </li>
            <li>Areas</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-home"></i> 
                {{ Auth::user()->activeCompany()->name }} 
            <span>>  
                Areas
            </span>
        </h1>
    </div>

    <div class="col-lg-10 col-lg-offset-1">
        <table id="areas" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Area Name</th>
                    <th>Piggings</th>
                </tr>
            </thead>
            <tfoot>
                <th data-search="ID"></th>
                <th data-search="Area Name"></th>
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
        $('#areas tfoot th').each( function (el) {
            var title = $('#areas tfoot th').eq( el ).data('search');
            $(this).html( '<input type="text" placeholder="Search '+title+'" tabindex="'+ el +'"/>' );
        } );

        var oTable = $('#areas').DataTable({
            "processing" : true,
            "serverSide" : true,
            "ajax" : { 'url' : "/areas/data",
                       'type' : 'POST'
                   },
            dom: 'frltip',
            buttons: [
                'columnsToggle'
            ],
            "columns": [
                {data: "DT_RowId", name: "areas.id", orderable: true, visible: false },
                {data: "name", name: "areas.name", orderable: true, searchable: true, 
                 render: function(data, type, row){
                    
                    return "<a href=/fields?area_id="+ row.DT_RowId + ">" + data + "</a>";
                    } 
                },
                {data: 'piggings', orderable: false, searchable :false, className: 'action' }
            ]
        });

        // NOT WORKING oTable.buttons().container().insertBefore('#products_filter');

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