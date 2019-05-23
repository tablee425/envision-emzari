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
            <li>Composition</li>
            <li>{{ ucfirst($type) }} View</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-plus"></i> 
                Composition Section 
            <span>>  
                Composition Section
            </span>
        </h1>
    </div>
    <div class="col-md-12 form-row">
        <a href="{{ action('CompositionController@getCreate', $location_id) }}" class="btn btn-info">+ Add New Composition</a>
    </div>
    
    <div class="col-lg-10 col-lg-offset-1" style="margin-bottom: 80px;">
        <table id="composition" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Location</th>
                    <th>Location Desc</th>
                    <th>Date</th>
                    <th>Iron</th>
                    <th>Manganese</th>
                    <th>Chloride</th>
                    <th>Comments</th>
                    <th>Files</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td data-search="ID"></td>
                    <td data-search="Location"></td>
                    <td data-search="Location Desc"></td>
                    <td data-search="Date"></td>
                    <td data-search="Iron"></td>
                    <td data-search="Manganese"></td>
                    <td data-search="Chloride"></td>
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
        $('#composition tfoot td').each( function (el) {
            var title = $('#composition tfoot td').eq( el ).data('search');
            $(this).html( '<input type="text" placeholder="Search '+title+'" tabindex="'+ el +'"/>' );
        } );

        var oTable = $('#composition').DataTable({
            "processing" : true,
            "serverSide" : true,
            "ajax" : { 'url' : "/composition/data",
                       'type' : 'POST',
                       'data' : { @if($type == 'area') area_id 
                                  @elseif($type == 'field') field_id
                                  @elseif($type == 'location') location_id 
                                  @endif  : "{{ $id }}" }
                   },
            order: [[3, "desc"], [1, "asc"]],
            // dom: 'Bfrltip',
            autoWidth: false,
            "columns": [
                {data: "DT_RowId", name: "composition.id", visible: false, searchable: true },
                {data: "location", name: "locations.name", orderable: true, searchable: true },
                {data: "location_desc", name: "locations.description", width: "120px", orderable: true, searchable: true },
                {data: "date", name: "date", width: "60px", orderable: true, searchable: true, className: 'editable'},
                {data: "iron", name: "iron", width: "60px", orderable: true, searchable: true, className: 'editable'},
                {data: "manganese", name: "manganese", width: "60px", orderable: true, searchable: true, className: 'editable'},
                {data: "chloride", name: "chloride", width: "60px", orderable: true, searchable: true, className: 'editable'},
                {data: "comments", name: "comments", width: "110px", visible: true, orderable: true, searchable: true, className: "editable"},
                {data: "files" },
                {data: "action" }
                // {data: 'action', orderable: false, searchable :false, className: 'action' }
            ],
            "createdRow": function( row, data, dataIndex ) {
                if (data.status == 1 && {{ auth()->user()->isAdmin() ? "false" : "true" }}) $(row).children().removeClass('editable');
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
    });
</script>
@endsection