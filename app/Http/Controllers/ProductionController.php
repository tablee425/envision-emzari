<?php

namespace Arrow\Http\Controllers;

use Illuminate\Http\Request;

use Arrow\Http\Requests;
use Arrow\Production;
use Arrow\Location;
use Arrow\Injection;
use Carbon\Carbon;
use Excel;
use Exception;
use DB;

class ProductionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function getSuccess()
    {
        return dd('success');
    }
}
