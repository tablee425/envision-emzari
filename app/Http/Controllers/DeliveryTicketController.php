<?php

namespace Arrow\Http\Controllers;

use Illuminate\Http\Request;
use Arrow\Http\Requests\DeliveryTicketRequest;
use Arrow\DeliveryTicket;
use Arrow\DeliveryTicketItem;
use Arrow\Location;
use Arrow\Area;
use Arrow\SalesRep;
use Arrow\Injection;
use Carbon\Carbon;
use Arrow\Exports\DeliveryTicketsExport;

use Datatables;
use Validator;
use DB;
use PDF;
use Excel;

use Arrow\Http\Requests;

class DeliveryTicketController extends Controller
{
    public function getIndex()
    {
        return view('delivery-tickets.index');
    }

    public function getAreaSelection(Request $request)
    {
        return view('delivery-tickets.select-area');
    }

    public function getCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'area_id' => 'required|exists:areas,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $ticket = new DeliveryTicket();
        $area = Area::find($request->area_id);
        $action = 'DeliveryTicketController@postStore';
        $button = 'Create Delivery Ticket';
        $reps = SalesRep::whereIn('user_id', $area->users()->pluck('users.id'))->with('user')->get();
        // Temp for making ticket umber editable
        $ticket->salesRep = $reps->first();
        if($ticket->salesRep)
        {
            $lastTicket = $ticket->salesRep->lastDeliveryTicket;
            if($lastTicket = $ticket->salesRep->lastDeliveryTicket)
            {
                $ticket->ticket_number = $lastTicket->nextTicketNumber();
            }
            else
            {
                $ticket->ticket_number = $ticket->salesRep->code.'-0001';
            }
        }
        return view('delivery-tickets.form', compact('request','ticket', 'action','button','reps'));
    }

    public function getDelete($id)
    {
        DeliveryTicket::where('id', $id)->delete();
        return redirect()->action('DeliveryTicketController@getIndex');
    }

    public function edit(Request $request, DeliveryTicket $ticket)
    {
        $action = 'DeliveryTicketController@update';
        $button = 'Update Delivery Ticket';
        $reps = SalesRep::with('user')->get();
        return view('delivery-tickets.form', compact('request', 'ticket', 'action','button','reps'));
    }

    public function update(DeliveryTicketRequest $request, DeliveryTicket $ticket)
    {
        $dt = $request->all();
        $deliveryDate = Carbon::createFromFormat('m-d-Y', $dt['delivery_date']);
        $dt['delivery_date'] = $deliveryDate;
        $ticket->update($dt);
        $ticket->items()->delete();

        foreach($request['chemical'] as $id => $chem)
        {
            $injection = $this->_currentInjection($dt, $id);
            $chemical = explode('|***|', $request['chemical'][$id]);
            DeliveryTicketItem::create(['delivery_ticket_id' => $ticket->id,
                'location_id' => $dt['location_id'][$id], 'chemical' => $chemical[0],
                'injection_type' => strtolower($chemical[1]),
                'quantity' => $dt['quantity'][$id], 'packaging' => $dt['packaging'][$id]]);
        }
        $ticket->load('items');
        // Deliverable Preperation
        if(isset($request->print))
        {
            if($request->uwi == 1)
            {
                $pdf = PDF::loadView('delivery-tickets.document-uwi', compact('ticket'));
                return $pdf->download("delivery-ticket-uwi-$ticket->id.pdf");
            }
            elseif($request->uwi == 0)
            {
                $pdf = PDF::loadView('delivery-tickets.document', compact('ticket'));
                return $pdf->download("delivery-ticket-$ticket->id.pdf");
            }
        } elseif(isset($request->excel))
        {
            $view = 'delivery-tickets.excel';
            if($request->uwi == 1)
            {
                $view = 'delivery-tickets.excel-uwi';
            }

            return Excel::download(new DeliveryTicketsExport($view, $ticket), 'delivery-tickets.xlsx');

            // return Excel::create('New file', function($excel) use($ticket, $export_file) {

            //     $excel->sheet('New sheet', function($sheet) use($ticket, $export_file) {

            //         $sheet->loadView($export_file, compact('ticket'));

            //     });

            // })->export('xlsx');
        }
        return redirect()->action('DeliveryTicketController@getIndex');
    }

    public function postStore(DeliveryTicketRequest $request)
    {
        $area = \Arrow\Area::find($request->area_id);
        if(! auth()->user()->can('view', \Arrow\Area::find($request->area_id)))
            return redirect()->back()->withErrors('Invalid area selected.');

        $ticket = $request->all();
        $ticket['company_id'] = auth()->user()->activeCompany()->id;

        $deliveryDate = Carbon::createFromFormat('m-d-Y', $ticket['delivery_date']);
        $ticket['delivery_date'] = $deliveryDate;

        $deliveryTicket = DeliveryTicket::create($ticket);
        foreach($request['chemical'] as $id => $chem)
        {
            $injection = $this->_currentInjection($ticket, $id);
            $chemical = explode('|***|', $ticket['chemical'][$id]);
            DeliveryTicketItem::create(['delivery_ticket_id' => $deliveryTicket->id,
                'location_id' => $ticket['location_id'][$id], 'chemical' => $chemical[0],
                'injection_type' => strtolower($chemical[1]),
                'quantity' => $ticket['quantity'][$id], 'packaging' => $ticket['packaging'][$id]]);
        }
        return redirect()->action('DeliveryTicketController@getIndex');
    }

    public function postData()
    {
        $tickets = DeliveryTicket::join('sales_reps','delivery_tickets.salesrep_id','=', 'sales_reps.id')
            ->join('users', 'sales_reps.user_id', '=', 'users.id')
            ->join('delivery_ticket_items as items', 'items.delivery_ticket_id', '=', 'delivery_tickets.id')
            ->where('delivery_tickets.company_id', auth()->user()->activeCompany()->id);
        // Address this.
        if(!auth()->user()->isAdmin()) $tickets = $tickets->whereIn('delivery_tickets.area_id', auth()->user()->areas->pluck('id'));
          $tickets =  $tickets->groupBy('delivery_tickets.id')
            ->select([DB::raw('delivery_tickets.id as DT_RowId'), DB::raw('COUNT(items.id) as total'),
                'ticket_number', DB::raw('delivery_tickets.status'),
                DB::raw('users.name as sales_rep'), 'delivery_date', 'purchase_order_number',
                'ordered_by', 'delivered_by']);

        return Datatables::of($tickets)
            ->addColumn('action', function($ticket){
            $delete = '<a href="/delivery-tickets/delete/'. $ticket->DT_RowId.'" onclick="javascript:if(window.confirm(\'If you delete Injection then all the locations and data associated with it will be deleted as well. Are you sure you want to do this, this is unrecoverable?\')) { return true } else return false;"><i class="fa fa-trash-o fa-2x"></i></a>';
            $edit = '<a href="/delivery-tickets/'. $ticket->DT_RowId.'/edit"><i class="fa fa-edit fa-2x"></i></a>';
            return '<td class="action">'. $edit.$delete.'</td>';
            })
            ->make(true);
    }

    protected function _currentInjection($ticket, $id)
    {
        $chemical = explode('|***|', $ticket['chemical'][$id]);
        $injection = Injection::where('location_id', $ticket['location_id'][$id])
                                  ->where('name', $chemical[0])
                                  ->where('type', strtolower($chemical[1]))
                                  ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), Carbon::now()->format('Y-m'))
                                  ->first();
        return $injection ?:  Injection::where('location_id', $ticket['location_id'][$id])
                                       ->where('name', $chemical[0])
                                       ->where('type', strtolower($chemical[1]))
                                       ->where(DB::raw('DATE_FORMAT(date, "%Y-%m")'), Carbon::now()->subMonth()->format('Y-m'))
                                       ->first();
    }
}
