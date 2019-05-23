<?php

namespace Arrow\Http\Requests;

use Arrow\Http\Requests\Request;

class PiggingRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_location_id' => 'required|numeric',
            'end_location_id' => 'required|numeric',
            'od' => 'nullable|numeric',
            'frequency' => 'nullable|integer',
            'diluent' => 'nullable|numeric',
            'scheduled_on' => 'nullable|date_format:Y-m-d',
            'shipped_on' => 'nullable|date_format:Y-m-d',
            'pulled_on' => 'nullable|date_format:Y-m-d',
            'cancelled_on' => 'nullable|date_format:Y-m-d',
            'corr_inh_vol' => 'nullable|numeric',
            'biocide_vol' => 'nullable|numeric',
            'water_vol' => 'nullable|numeric',
        ];
    }
}
