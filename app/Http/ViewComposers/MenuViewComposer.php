<?php
namespace Arrow\Http\ViewComposers;

use Illuminate\Contracts\View\View;

use Arrow\Area;

class MenuViewComposer {

    public function compose(View $view) {
        $areas = '';
        if (auth()->check()) {
            $areas1 = auth()->user()->activeCompany()->areas;
            $areas2 = auth()->user()->areas;
            $intersect = $areas1->intersect($areas2);
            if (!empty($intersect->all())) {
                $areas = $intersect->all();
            }
            else {
                $areas = $areas1;
            }
            
        }
        $view->with('areas', $areas);
    }
}