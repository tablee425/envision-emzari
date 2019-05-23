<?php

namespace Arrow\Http\Controllers;

use Illuminate\Http\Request;

use Arrow\Http\Requests;
use Carbon\Carbon;
use Arrow\Area;
use Arrow\Field;
use Arrow\Pigging;
use Arrow\Injection;
use Arrow\Composition;
use Arrow\Analysis;
use Arrow\DeliveryTicket;
use Arrow\DeliveryTicketItem;
use Auth;
use DB;

class ReportController extends Controller
{
    public function getIndex()
    {
        $start_date = new Carbon('first day of last month');

        $end_date = new Carbon('today');
        $dtYears = array_reverse(range(2017, (int)Carbon::now()->format('Y')));
        // $areas = Auth::user()->areas;

        return view('reports.filters', compact('start_date', 'end_date', 'dtYears'));
    }

    public function postFilter(Request $request)
    {
        $title = ucfirst($request->type).' - '. (new Carbon($request->start_date))->toFormattedDateString() . ' to '. (new Carbon($request->end_date))->toFormattedDateString();
        if($request->type == "continuous")
        {
            $injections = Injection::with('location.field.area')
                ->join('production', function($join) {
                    $join->on('production.location_id', '=', 'injections.location_id');
                    $join->on(DB::raw("DATE_FORMAT(production.date, '%Y-%m')"),'=', DB::raw("DATE_FORMAT(injections.date, '%Y-%m')"));
                })
                ->whereIn('injections.id', $this->_injectionIDs($request, 'CONTINUOUS'))
                ->select('injections.*', 'production.id as production_id')
                ->get();
                $injections->load('production');
            return view('reports.continuous', compact('injections', 'title'));
        }
        elseif($request->type == "batch")
        {
            $injections = Injection::with('location.field.area')
                ->join('production', function($join) {
                    $join->on('production.location_id', '=', 'injections.location_id');
                    $join->on(DB::raw("DATE_FORMAT(production.date, '%Y-%m')"),'=', DB::raw("DATE_FORMAT(injections.date, '%Y-%m')"));
                })
                ->whereIn('injections.id', $this->_injectionIDs($request, "BATCH"))
                ->select('injections.*', 'production.id as production_id')
                ->get();
                $injections->load('production');
            return view('reports.batch', compact('injections', 'title'));
        }
        elseif($request->type === "pigging")
        {
            $ids = Field::find($request->field_id)->locations->pluck('id');
            $piggings = Pigging::where('scheduled_on', '>=', (new Carbon($request->start_date)))
                               ->where('scheduled_on', '<=', (new Carbon($request->end_date))->addDays(1))
                               ->whereIn('start_location_id', $ids)
                               ->get();
            return view('reports.pigging', compact('piggings', 'title'));
        }
        elseif($request->type == "analysis")
        {
            $analysis = Analysis::where('date', '>=', (new Carbon($request->start_date)))
                                 ->where('date', '<=', (new Carbon($request->end_date))->addDays(1))

                                 ->get();
            return view('reports.analysis', compact('analysis', 'title'));
        }
        elseif($request->type == "composition")
        {
            $composition = Composition::where('date', '>=', (new Carbon($request->start_date)))
                                 ->where('date', '<=', (new Carbon($request->end_date))->addDays(1))
                                 ->get();
            return view('reports.composition', compact('composition', 'title'));
        }
        elseif($request->type == "delivery-tickets")
        {
            $title = 'Delivery Tickets for '. Carbon::create($request->dt_year, $request->dt_month, 1, 0, 0, 0, 'America/Toronto')->format('m Y');
            $items = DeliveryTicketItem::join('delivery_tickets', function($join) use ($request) {
                $join->on('delivery_ticket_items.delivery_ticket_id', '=', 'delivery_tickets.id')
                     ->where('delivery_tickets.company_id', '=', auth()->user()->activeCompany()->id)
                     ->where(DB::raw('DATE_FORMAT(delivery_tickets.delivery_date,\'%Y-%m\')'), '=', "$request->dt_year-$request->dt_month");
                    //  ->where(DB::raw('MONTH(delivery_tickets.delivery_date)'), '=', $request->dt_month);
                if(! auth()->user()->isAdmin())
                {
                    $join->whereIn('delivery_tickets.area_id', auth()->user()->areas()->pluck('area_id')->all());
                }
            })->with('deliveryTicket.company','location.field.area')->get();
            return view('reports.delivery-tickets', compact('items', 'title'));
        }
    }

    protected function _injectionIDs($request, $type)
    {
        $injections = DB::table('areas')
                            ->leftJoin('fields', 'fields.area_id', '=', 'areas.id')
                            ->leftJoin('locations', 'fields.id', '=', 'locations.field_id')
                            ->leftJoin('injections', 'locations.id', '=', 'injections.location_id');

        if ($request->area_id)
        {
            $injections = $injections->where('areas.id', $request->area_id);
        } else {
            $injections = $injections->whereIn('areas.id', Auth::user()->activeCompany()->areas->pluck('id'));
        }

        $injections = $injections->where('date', '>=', (new Carbon($request->start_date)))
                                 ->where('date', '<=', (new Carbon($request->end_date))->addDays(1))
                                 ->where('type', $type);

        $injections = collect($injections->select('injections.id')->get());
        return $injections->pluck('id')->toArray();

    }

}
