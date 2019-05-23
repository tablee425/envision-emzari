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
            <li>Company</li>
            <li>Accounts</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-home"></i> 
                Company 
            <span>>  
                Accounts
            </span>
        </h1>
    </div>

    <div class="col-md-10 col-lg-offset-1 form-row">
        <a class="btn btn-primary btn-lg" href="{{ action('CompanyController@getCreate') }}">
            <i class="fa fa-plus"></i> Add Company</a>
    </div>

    <div class="col-lg-10 col-lg-offset-1" style="margin-bottom: 80px;">
        <table id="accounts" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tfoot>
                <td data-search="ID"></td>
                <td data-search="Name"></td>
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
        $('#accounts tfoot td').each( function (el) {
            var title = $('#accounts tfoot td').eq( el ).data('search');
            $(this).html( '<input type="text" placeholder="Search '+title+'" tabindex="'+ el +'"/>' );
        } );

        var table = $('#accounts').DataTable({
            "processing" : true,
            "serverSide" : true,
            "ajax" : { 'url' : "/companies/data",
                       'type' : 'POST'
                   },
            dom: 'Bfrltip',
            buttons: [
                'columnsToggle',
                'csv'
            ],
            "columns": [
                {data: "DT_RowId", name: 'id', orderable: true, visible: false },
                {data: "name", name: 'name', orderable: true, searchable: true},
                {data: 'action', orderable: false, searchable :false }
            ]

        });

        // Apply the search
        table.columns().eq( 0 ).each( function ( colIdx ) {
            $( 'input', table.column( colIdx ).footer() ).on( 'keyup change', function () {
                table
                    .column( colIdx )
                    .search( this.value )
                    .draw();
            });
        });
    });
</script>
@endsection