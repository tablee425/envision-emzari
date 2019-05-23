<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{ public_path('/css/bootstrap.min.css') }}">
        <style>
            .box {
                background-color: lightgrey;
                border: 1px solid black;
                text-align: center;
            },
            .clear-box {
                border: 1px solid black;
                text-align: center;
            },
            .name {
                border-bottom: 1px solid black;
            }
            .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
                border:0;
                padding:0;
            }
        </style>
    </head>
    <body style="width: 100%">
        <div class="container">
            <div class="row">
                <div class="col-xs-3">
                    <img src="{{ public_path('/logos/sterling.jpg') }}" />
                </div>
                <div class="col-xs-8">
                    <br /><br />
                    <strong>P.O. Box 1098 - Estevan, Saskatchewan - S4A 2H7</strong><br />
                    <strong>PHONE: Estevan (306) 634-6549</strong><br />
                    <strong>PHONE: Carnduff (306) 485-7377</strong><br />
                    <strong>FAX: (306) 634-6556</strong>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-xs-3 box">
                    COMPANY BILLING
                </div>
                <div class="col-xs-3 box">
                    AREA
                </div>
                <div class="col-xs-2 col-xs-offset-2 box">
                    Delivery Ticket
                </div>
                <div class="col-xs-2 clear-box">
                    {{ isset($ticket->ticket_number) ? $ticket->ticket_number : '&nbsp;' }}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-3" style="text-align: center;">
                    {{ $ticket->items->first()->location->field->area->company->name }}
                </div>
                <div class="col-xs-3" style="text-align: center;">
                    {{ $ticket->items->first()->location->field->area->name }}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4 box">
                    DATE
                </div>
                <div class="col-xs-4 box">
                    ORDERED BY
                </div>
                <div class="col-xs-4 box">
                    P.O. NUMBER
                </div>
            </div>
            <div class="row" style="margin-top: -2px;">
                <div class="col-xs-4 clear-box">
                    {{ $ticket->delivery_date ? $ticket->delivery_date->format('F j, Y') : 'None Provided' }}
                </div>
                <div class="col-xs-4 clear-box">
                    {{ $ticket->ordered_by }}
                </div>
                <div class="col-xs-4 clear-box">
                    {{ $ticket->purchase_order_number ?: '&nbsp;'}}
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-xs-4 box">
                    LOCATION
                </div>
                <div class="col-xs-2 box">
                    CC
                </div>
                <div class="col-xs-2 box">
                    PRODUCT
                </div>
                <div class="col-xs-2 box">
                    QTY(L)
                </div>
                <div class="col-xs-2 box">
                    PACKAGING
                </div>
            </div>

            <!-- Location Iteration -->
            @foreach($ticket->items as $item)
                <div class="row" style="margin-top: -2px;">
                    <div class="col-xs-4 clear-box">
                        {{ $item->location->name }}
                    </div>
                    <div class="col-xs-2 clear-box">
                        {{ $item->location->cost_centre }}
                    </div>
                    <div class="col-xs-2 clear-box">
                        {{ $item->chemical }}
                    </div>
                    <div class="col-xs-2 clear-box">
                        {{ $item->quantity }}
                    </div>
                    <div class="col-xs-2 clear-box">
                        {{ ucfirst($item->packaging) }}
                    </div>
                </div>
            @endforeach
            <br />
            <!-- End Location Iteration -->
            <div class="row">
                <div class="col-xs-4 name">
                    SIGNATURE:
                </div>
                <div class="col-xs-4 name">
                    PRINT:
                </div>
                <div class="col-xs-2 col-xs-offset-1 clear-box" style="margin-left: 10px;">
                    {{ $ticket->items->sum('quantity') }}
                </div>
            </div>
            <div class="row">
                <div class="col-xs-10 clear-box" style="text-align: left; padding-left: 4px; text-align: center;">
                       <div>Care Must Be Taken With Product Handling and Usage</div>
                       <div>Observe All Safety Information Marked On Containers</div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-10 name">
                    DELIVERED BY: Sterling Chemicals Ltd. - {{ $ticket->delivered_by }}
                </div>
                <div class="col-xs-2 name">
                    Unit: #301
                </div>
            </div>
            
        </div>
    </body>
</html>