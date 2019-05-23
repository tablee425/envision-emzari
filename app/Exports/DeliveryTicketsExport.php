<?php

namespace Arrow\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DeliveryTicketsExport implements FromView
{

    public function __construct($view, $ticket) {
        $this->view = $view;
        $this->ticket = $ticket;
    }

    public function view(): View
    {
        return view($this->view, [
            'ticket' => $this->ticket
        ]);
    }
}
