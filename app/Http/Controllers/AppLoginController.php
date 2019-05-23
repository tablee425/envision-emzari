<?php

namespace Arrow\Http\Controllers;

use Illuminate\Http\Request;
use Arrow\Pigging;
use Arrow\Location;
use Arrow\User;
use Arrow\Area;
use Arrow\Field;
use Arrow\Token;
use DB;

class AppLoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *  
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    function random_string($length) {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));
    
        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
    
        return $key;
    }

    public function applogin(Request $request) {
        $found = User::where('email', $request->email)->get();
        if (sizeof($found) > 0) { // email exist
            if (password_verify($request->password, $found[0]->password)) { // password correct
                $new_token = bin2hex(random_bytes(30));
                User::where('email', $request->email)->update(['remember_token' => $new_token]);
                DB::table('tokens')->insert(array('token' => $new_token, 'email' => $request->email, 'name' => $found[0]->name));
                return response()->json(['success' => true, 'name' => $found[0]->name, 'access_token' => $new_token]);
            } else { // password incorrect
                return response()->json(['success' => false, 'message' => 'invalid password']);
            }    
        } else { // email doesn't exist
            return response()->json(['success' => false, 'message' => 'invalid user']);
        }

    }

    public function tokenlogin(Request $request) {
        $found = User::where('remember_token', $request->access_token)->get();
        if (sizeof($found) > 0) { // email exist
            return response()->json(['success' => true, 'name' => $found[0]->name, 'size' => sizeof($found)]);
        } else { // email doesn't exist
            return response()->json(['success' => false, 'message' => 'invalid user']);
        }
    }

    public function logout(Request $request) {
        $found = Token::where('token', $request->access_token)->get();
        if (sizeof($found) > 0) { // email exist
            Token::where('token', $request->access_token)->delete();
            return response()->json(['success' => true]);
        } else { // token doesn't exist
            return response()->json(['success' => true]);
        }
    }

    public function getAreas(Request $request) {
        return Area::where('company_id', $request->company_id)->get();
    }

    public function getFields(Request $request) {
        return Field::where('area_id', $request->area_id)->get();
    }

    public function getOnlineDatas() {
        $area = Area::all();
        $field = Field::all();
        $location = Location::all();
        $pigging = Pigging::all();
        $token = Token::all();
        return ['area' => $area, 'field' => $field, 'location' => $location, 'pigging' => $pigging, 'token' => $token];
    }

    public function getAllPigings(Request $request) {
        $piggings = DB::table('areas')->join('locations', DB::raw('locations.field_id'), '=', DB::raw($request->field_id));
        return $piggings->get();
    }

    public function getPiggings(Request $request) {
        // $year = $this->__getAppropriateYear($request->month);
        $year = $request->year;
        if ($request->operator == 'undefined') {
            if ($request->view == '1' || $request->view == 1) {
                $piggings = DB::table('areas')
                ->leftJoin('locations', DB::raw('locations.field_id'), '=', DB::raw($request->field_id))
                ->join('piggings', DB::raw('piggings.start_location_id'), '=', DB::raw('locations.id'))
                ->leftJoin('locations as end', DB::raw('end.id'), '=', DB::raw('piggings.end_location_id'))
                ->select([DB::raw('piggings.id as DT_RowId'), DB::raw('locations.name as start_location'), DB::raw('end.name as end_location'),
                    DB::raw('piggings.order'), DB::raw('piggings.od'), DB::raw('piggings.license'), DB::raw('piggings.frequency'),
                    DB::raw('piggings.scheduled_on'), DB::raw('piggings.shipped_on'), DB::raw('piggings.pulled_on'),
                    DB::raw('piggings.line_type'), DB::raw('piggings.line_pressure'), DB::raw('piggings.pressure_switch'), DB::raw('piggings.MOP'),
                    DB::raw('piggings.cancelled_on'), DB::raw('piggings.pig_size'), DB::raw('piggings.pig_number'), DB::raw('piggings.wax'), DB::raw('piggings.condition'),
                    DB::raw('piggings.corr_inh_vol'), DB::raw('piggings.biocide_vol'), DB::raw('piggings.water_vol'), DB::raw('piggings.gauged'),
                    DB::raw('piggings.field_operator'), DB::raw('piggings.comments')])   
                ->whereNotNull(DB::raw('locations.name'))
                ->whereNotNull(DB::raw('locations.id'))
                ->whereNull(DB::raw('piggings.cancelled_on'))
                ->whereNull(DB::raw('piggings.pulled_on'))
                ->where(DB::raw('DATE_FORMAT(piggings.scheduled_on, "%Y-%c")'), $year."-".$request->month)
                ->groupBy(DB::raw('piggings.id'))->get();    
            } else {
                $piggings = DB::table('areas')
                ->leftJoin('locations', DB::raw('locations.field_id'), '=', DB::raw($request->field_id))
                ->join('piggings', DB::raw('piggings.start_location_id'), '=', DB::raw('locations.id'))
                ->leftJoin('locations as end', DB::raw('end.id'), '=', DB::raw('piggings.end_location_id'))
                ->select([DB::raw('piggings.id as DT_RowId'), DB::raw('locations.name as start_location'), DB::raw('end.name as end_location'),
                    DB::raw('piggings.order'), DB::raw('piggings.od'), DB::raw('piggings.license'), DB::raw('piggings.frequency'),
                    DB::raw('piggings.scheduled_on'), DB::raw('piggings.shipped_on'), DB::raw('piggings.pulled_on'),
                    DB::raw('piggings.line_type'), DB::raw('piggings.line_pressure'), DB::raw('piggings.pressure_switch'), DB::raw('piggings.MOP'),
                    DB::raw('piggings.cancelled_on'), DB::raw('piggings.pig_size'), DB::raw('piggings.pig_number'), DB::raw('piggings.wax'), DB::raw('piggings.condition'),
                    DB::raw('piggings.corr_inh_vol'), DB::raw('piggings.biocide_vol'), DB::raw('piggings.water_vol'), DB::raw('piggings.gauged'),
                    DB::raw('piggings.field_operator'), DB::raw('piggings.comments')])   
                ->whereNotNull(DB::raw('locations.name'))
                ->whereNotNull(DB::raw('locations.id'))
                ->where(DB::raw('DATE_FORMAT(piggings.scheduled_on, "%Y-%c")'), $year."-".$request->month)
                ->groupBy(DB::raw('piggings.id'))->get();    
            }
        } else {
            if ($request->view == '1' || $request->view == 1) {
                $piggings = DB::table('areas')
                ->leftJoin('locations', DB::raw('locations.field_id'), '=', DB::raw($request->field_id))
                ->join('piggings', DB::raw('piggings.start_location_id'), '=', DB::raw('locations.id'))
                ->leftJoin('locations as end', DB::raw('end.id'), '=', DB::raw('piggings.end_location_id'))
                ->select([DB::raw('piggings.id as DT_RowId'), DB::raw('locations.name as start_location'), DB::raw('end.name as end_location'),
                    DB::raw('piggings.order'), DB::raw('piggings.od'), DB::raw('piggings.license'), DB::raw('piggings.frequency'),
                    DB::raw('piggings.scheduled_on'), DB::raw('piggings.shipped_on'), DB::raw('piggings.pulled_on'),
                    DB::raw('piggings.line_type'), DB::raw('piggings.line_pressure'), DB::raw('piggings.pressure_switch'), DB::raw('piggings.MOP'),
                    DB::raw('piggings.cancelled_on'), DB::raw('piggings.pig_size'), DB::raw('piggings.pig_number'), DB::raw('piggings.wax'), DB::raw('piggings.condition'),
                    DB::raw('piggings.corr_inh_vol'), DB::raw('piggings.biocide_vol'), DB::raw('piggings.water_vol'), DB::raw('piggings.gauged'),
                    DB::raw('piggings.field_operator'), DB::raw('piggings.comments')])   
                ->whereNotNull(DB::raw('locations.name'))
                ->whereNotNull(DB::raw('locations.id'))
                ->where(DB::raw('DATE_FORMAT(piggings.scheduled_on, "%Y-%c")'), $year."-".$request->month)
                ->where('piggings.field_operator', $request->operator)
                ->whereNull(DB::raw('piggings.cancelled_on'))
                ->whereNull(DB::raw('piggings.pulled_on'))
                ->groupBy(DB::raw('piggings.id'))->get();    
            } else {
                $piggings = DB::table('areas')
                ->leftJoin('locations', DB::raw('locations.field_id'), '=', DB::raw($request->field_id))
                ->join('piggings', DB::raw('piggings.start_location_id'), '=', DB::raw('locations.id'))
                ->leftJoin('locations as end', DB::raw('end.id'), '=', DB::raw('piggings.end_location_id'))
                ->select([DB::raw('piggings.id as DT_RowId'), DB::raw('locations.name as start_location'), DB::raw('end.name as end_location'),
                    DB::raw('piggings.order'), DB::raw('piggings.od'), DB::raw('piggings.license'), DB::raw('piggings.frequency'),
                    DB::raw('piggings.scheduled_on'), DB::raw('piggings.shipped_on'), DB::raw('piggings.pulled_on'),
                    DB::raw('piggings.line_type'), DB::raw('piggings.line_pressure'), DB::raw('piggings.pressure_switch'), DB::raw('piggings.MOP'),
                    DB::raw('piggings.cancelled_on'), DB::raw('piggings.pig_size'), DB::raw('piggings.pig_number'), DB::raw('piggings.wax'), DB::raw('piggings.condition'),
                    DB::raw('piggings.corr_inh_vol'), DB::raw('piggings.biocide_vol'), DB::raw('piggings.water_vol'), DB::raw('piggings.gauged'),
                    DB::raw('piggings.field_operator'), DB::raw('piggings.comments')])   
                ->whereNotNull(DB::raw('locations.name'))
                ->whereNotNull(DB::raw('locations.id'))
                ->where(DB::raw('DATE_FORMAT(piggings.scheduled_on, "%Y-%c")'), $year."-".$request->month)
                ->where('piggings.field_operator', $request->operator)
                ->groupBy(DB::raw('piggings.id'))->get();
            }
        }
        return $piggings;
    }

    private function __getAppropriateYear($month)
    {
        $today = Carbon::now();
        if(($month == 1 || $month == 2 || $month ==3) && ($today->format('m') == 12 || $today->format('m') == 11 || $today->format('m') == 10))
        {
            return $today->addYear()->format('Y');
        }
        elseif(($month == 12 || $month == 11 || $month == 10) && ($today->format('m') == 1 || $today->format('m') == 2 || $today->format('m') ==3))
        {
            return $today->subYear()->format('Y');
        }
        else
        {
            return $today->format('Y');
        }
    }

    public function updatePiggings(Request $request) {
        if ($request->pulled_on || $request->cancelled_on) {
            DB::table('piggings')
            ->where('id', $request->pigging_id)
            ->update(['order' => $request->order, 'od' => $request->od, 'pressure_switch' => $request->pressure_switch, 'line_pressure' => $request->line_pressure, 'frequency' => $request->frequency, 'MOP' => $request->MOP, 'scheduled_on' => $request->scheduled_on, 'shipped_on' => $request->shipped_on, 'field_operator' => $request->field_operator, 'gauged' => $request->gauged, 'condition' => $request->condition, 'wax' => $request->wax, 'comments' => $request->comments, 'pig_number' => $request->pig_number]);
            return ['success' => $request->pigging_id . ' ' . $request->scheduled_on];    
        } else {
            DB::table('piggings')
            ->where('id', $request->pigging_id)
            ->update(['order' => $request->order, 'od' => $request->od, 'pressure_switch' => $request->pressure_switch, 'line_pressure' => $request->line_pressure, 'frequency' => $request->frequency, 'MOP' => $request->MOP, 'scheduled_on' => $request->scheduled_on, 'shipped_on' => $request->shipped_on, 'pulled_on' => $request->pulled_on, 'cancelled_on' => $request->cancelled_on, 'field_operator' => $request->field_operator, 'gauged' => $request->gauged, 'condition' => $request->condition, 'wax' => $request->wax, 'comments' => $request->comments, 'pig_number' => $request->pig_number]);
            return ['success' => $request->pigging_id . ' ' . $request->scheduled_on];    
        }
    }

    public function multipleUpdate(Request $requests) {
        for ($x = 0; $x < count($requests->all()); $x++) {
            $request = $requests[$x];
            DB::table('piggings')
            ->where('id', $request['pigging_id'])
            // ->update(['order' => $request->order, 'od' => $request->od, 'pressure_switch' => $request->pressure_switch, 'line_pressure' => $request->line_pressure, 'frequency' => $request->frequency, 'MOP' => $request->MOP, 'scheduled_on' => $request->scheduled_on, 'shipped_on' => $request->shipped_on, 'pulled_on' => $request->pulled_on, 'cancelled_on' => $request->cancelled_on, 'field_operator' => $request->field_operator, 'gauged' => $request->gauged, 'condition' => $request->condition, 'wax' => $request->wax, 'comments' => $request->comments, 'pig_number' => $request->pig_number]);
            ->update(['order' => $request['order']]);
        }
        return ['success' => true];
    }
}
