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
            <li>Delivery Tickets</li>
            <li>Create Ticket</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-home"></i> 
                Delivery Tickets 
            <span>>  
                Create Ticket
            </span>
        </h1>
    </div>

    <div class="col-lg-10 col-lg-offset-1" style="margin-bottom: 80px;">
        <div class="widget-body no-padding">
            <br />
            <br />
            <form action="{{ action('DeliveryTicketController@getCreate') }}" method="GET" class="smart-form">
                <div class="row">
                    <section class="col col-6">
                        <label class="label">Select Area for Delivery Ticket</label>
                        <label class="select">
                            <select id="area_id" name="area_id" class="input-sm">
                                @foreach(auth()->user()->activeCompany()->areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </label>
                    </section>
                </div>
                
                <div class="row">
                    <section class="col col-2">
                        <button type="submit" class="btn btn-primary btn-lg">Generate Delivery Ticket</button>
                    </section>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection