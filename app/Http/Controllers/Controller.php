<?php

namespace Arrow\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Arrow\Field;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function isAuthorized($id, $type) {
        $fieldName = $type.'.id';
        if (is_null(\Auth::user()->activeCompany()->$type()->where($fieldName, $id)->first())) {
            return redirect('/dashboard')->withMessage('Access denied.')->send();
        }
        $areas1 = \Auth::user()->activeCompany()->areas;
        $areas2 = \Auth::user()->areas;
        $intersect = $areas1->intersect($areas2);
        switch ($type) {
            case 'areas':
                if (!$areas2->contains($id) && !empty($intersect->all())) {
                    return redirect('/dashboard')->withMessage('Access denied.')->send();
                }
                break;

            case 'fields':
                $area = Field::find($id)->area;
                if (!$areas2->contains($area->id) && !empty($intersect->all())) {
                    return redirect('/dashboard')->withMessage('Access denied.')->send();
                }
                break;
        }
    }
}
