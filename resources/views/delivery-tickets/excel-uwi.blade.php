<html>
    <tr></tr>
    <tr>
        <td>
            COMPANY BILLING
        </td>
        <td>
            AREA
        </td>
        <td>
            Delivery Ticket
        </td>
        <td>
            {{ isset($ticket->ticket_number) ? $ticket->ticket_number : '' }}
        </td>
    </tr>
    <tr>
        <td>
            {{ $ticket->items->first()->location->field->area->company->name }}
        </td>
        <td>
            {{ $ticket->items->first()->location->field->area->name }}
        </td>
    </tr>
    <tr></tr>
    <tr>
        <td>
            DATE
        </td>
        <td>
            ORDERED BY
        </td>
        <td>
            P.O. NUMBER
        </td>
    </tr>
    <tr>
        <td>
            {{ $ticket->delivery_date ? $ticket->delivery_date->format('F j, Y') : 'None Provided' }}
        </td>
        <td>
            {{ $ticket->ordered_by }}
        </td>
        <td>
            {{ $ticket->purchase_order_number }}
        </td>
    </tr>
           
    <tr>
        <td>
            LOCATION
        </td>
        <td>
            UWI
        </td>
        <td>
            CC
        </td>
        <td>
            PRODUCT
        </td>
        <td>
            QTY(L)
        </td>
        <td>
            PACKAGING
        </td>
    </tr>

    <!-- Location Iteration -->
    @foreach($ticket->items as $item)
        <tr>
            <td>
                {{ $item->location->name }}
            </td>
            <td>
                {{ $item->location->description }}
            </td>
            <td>
                {{ $item->location->cost_centre }}
            </td>
            <td>
                {{ $item->chemical }}
            </td>
            <td>
                {{ $item->quantity }}
            </td>
            <td>
                {{ ucfirst($item->packaging) }}
            </td>
        </tr>
    @endforeach
           
    <!-- End Location Iteration -->
    <tr></tr>
    <tr>
        <td>
            SIGNATURE:
        </td>
        <td>
            PRINT:
        </td>
        <td>
           
        </td>
    </tr>
           
    <tr>
        <td>
               <h4>Care Must Be Taken With Product Handling and Usage</h4>
               <h4>Observe All Safety Information Marked On Containers</h4>
        </td>
    </tr>
    <tr>
        <td>
            DELIVERED BY: Sterling Chemicals Ltd. - {{ $ticket->delivered_by }}
        </td>
        <td>
            Unit: #301
        </td>
    </tr>
</html>