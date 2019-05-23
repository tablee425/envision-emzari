<?php

namespace Arrow\Http\Controllers;

use Illuminate\Http\Request;

use Arrow\Http\Requests;
use Arrow\DeliveryTicket;
use PDF;
use Excel;

class DeliveryTicketDocumentController extends Controller
{
    // Typehint the tickets then
    public function generateTicket(Request $request, DeliveryTicket $ticket)
    {
        if($request->uwi)
        {
            $pdf = PDF::loadView('delivery-tickets.document-uwi', compact('ticket'));
            return $pdf->download("delivery-ticket-uwi-$ticket->id.pdf");
        }
        $pdf = PDF::loadView('delivery-tickets.document', compact('ticket'));
        return $pdf->download("delivery-ticket-$ticket->id.pdf");
    }

    public function exportExcel(Request $request, DeliveryTicket $ticket)
    {
        return Excel::create('New file', function($excel) {

            $excel->sheet('New sheet', function($sheet) use($ticket) {

                $sheet->loadView('delivery-tickets.document')->with('ticket');

            });

        })->download();
    }   
}
