<?php

namespace Arrow\Http\Controllers;

use Illuminate\Http\Request;

use Arrow\Http\Requests;
use Arrow\Upload;
use DB;
use Datatables;
use Auth;

class UploadController extends Controller
{
    public function getIndex(Request $request)
    {
        $type = $request->type ?: 'area';
        $id = $request->id ?: 1;
        $location_id = ($type == "location") ? $id : null;
        return view('uploads.index', compact('type', 'id', 'location_id'));
    }
    public function getNew($location_id = null)
    {
        $locations = auth()->user()->activeCompany()->locations();

        return view('uploads.form', compact('locations', 'location_id'));
    }

    public function getDownload($id)
    {
        $file = Upload::find($id);
        return response()->download(storage_path().'//uploads//'.$file->path, $file->original_name);
    }

    public function postFileUpload(Request $request)
    {
        foreach($request->file as $file)
        {
            $path = $this->__storeFile($file);
            Upload::create(['location_id' => $request->location_id, 'date' => $request->date.'-01',
                            'original_name' => $file->getClientOriginalName(), 'path' => $path]);
        }
    }
    public function postData(Request $request)
    {
        $records = DB::table('companies')
            ->leftJoin('areas', DB::raw('areas.company_id'), '=', DB::raw('companies.id'));

        if ($request->area_id)
            $records = $records->leftJoin('fields', DB::raw('fields.area_id'), '=', DB::raw($request->area_id))
                ->leftJoin('locations', DB::raw('locations.field_id'), '=', DB::raw('fields.id'));
        elseif ($request->field_id)
            $records = $records->leftJoin('locations', DB::raw('locations.field_id'), '=', DB::raw($request->field_id));
        elseif ($request->location_id)
            $records = $records->leftJoin('locations', DB::raw('locations.id'), '=', DB::raw($request->location_id));
        else
            $records = $records->leftJoin('fields', DB::raw('fields.area_id'),'=', DB::raw('areas.id'))
                ->leftJoin('locations', DB::raw('locations.area_id'), '=', DB::raw('fields.id'));
        $records = $records->join('uploads', DB::raw('uploads.location_id'), '=', DB::raw('locations.id'))
            ->where(DB::raw('companies.id'), Auth::user()->activeCompany()->id)
         ->selectRaw('uploads.id as DT_RowId, locations.name as location, locations.description as location_desc,
            DATE_FORMAT(uploads.date, \'%Y-%m\') as date, uploads.original_name')
         ->groupBy(DB::raw('uploads.id'));
        $data = Datatables::of($records);
        $data->addColumn('action', function($file) {
                return '<a class="btn btn-info" href="/files/download/'.$file->DT_RowId.'">Download</a>';
            }
        );
        return $data->make(true);
    }

    private function __storeFile($file)
    {
        $name = md5_file($file->path()).'.'.$file->clientExtension();
        $file->move(storage_path().'/uploads', $name);
        return $name;
    }


}
