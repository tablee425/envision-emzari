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
    
    <div class="col-lg-12" style="margin-bottom: 80px;">
        <table id="piggings" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Start Location</th>
                    <th>End Location</th>
                    <th>Order</th>
                    <th>OD</th>
                    <th>Line Type</th>
                    <th>License #</th>
                    <th>Frequency (days)</th>
                    <th>MOP(kPa)</th>
                    <th>Date Scheduled</th>
                    <th>Date Shipped</th>
                    <th>Date Pulled</th>
                    <th>Date Cancelled</th>
                    <th>Pig Size</th>
                    <th>Pig #</th>
                    <th>Corr Inh Vol (L)</th>
                    <th>Biocide Vol (L)</th>
                    <th>Diluent</th>
                    <th>Water Vol (L)</th>
                    <th>Line Pressure</th>
                    <th>Pressure Switch</th>
                    <th>Field Operator</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                @foreach($piggings as $pigging)
                <tr>
                    <td>{{ $pigging->id }}</td>
                    <th>{{ $pigging->startLocation->name }}</th>
                    <th>{{ $pigging->endLocation->name }}</th>
                    <th>{{ $pigging->order }}</th>
                    <th>{{ $pigging->od }}</th>
                    <th>{{ $pigging->line_type }}</th>
                    <th>{{ $pigging->license }}</th>
                    <th>{{ $pigging->frequency }}</th>
                    <th>{{ $pigging->MOP }}</th>
                    <th>{{ $pigging->scheduled_on ? $pigging->scheduled_on->format('Y-m-d') : ''}}</th>
                    <th>{{ $pigging->shipped_on ? $pigging->shipped_on->format('Y-m-d') : ''}}</th>
                    <th>{{ $pigging->pulled_on ? $pigging->pulled_on->format('Y-m-d') : ''}}</th>
                    <th>{{ $pigging->cancelled_on ? $pigging->cancelled_on->format('Y-m-d') : ''}}</th>
                    <th>{{ $pigging->pig_size }}</th>
                    <th>{{ $pigging->pig_number }}</th>
                    <th>{{ $pigging->corr_inh_vol }}</th>
                    <th>{{ $pigging->biocide_vol }}</th>
                    <th>{{ $pigging->diluent }}</th>
                    <th>{{ $pigging->water_vol }}</th>
                    <th>{{ $pigging->line_pressure }}</th>
                    <th>{{ $pigging->pressure_switch }}</th>
                    <th>{{ $pigging->field_operator }}</th>
                    <th>{{ $pigging->comments }}</th>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td data-search="ID"></td>
                    <td data-search="Start Location"></td>
                    <td data-search="End Location"></td>
                    <td data-search="Order"></td>
                    <td data-search="OD"></td>
                    <td data-search="Line Type"></td>
                    <td data-search="License #"></td>
                    <td data-search="Frequency"></td>
                    <td data-search="MOP(kPa)"></td>
                    <td data-search="Date Scheduled"></td>
                    <td data-search="Date Shipped"></td>
                    <td data-search="Date Pulled"></td>
                    <td data-search="Date Cancelled"></td>
                    <td data-search="Pig Size"></td>
                    <td data-search="Pig #"></td>
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
        var oTable = $('#piggings').DataTable({
            dom: 'Bfrltip',
            autoWidth: false,
            order: [[1, "asc"]],
            buttons: [
                'columnsToggle'
            ]
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