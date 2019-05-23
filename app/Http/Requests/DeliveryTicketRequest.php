<?php

namespace Arrow\Http\Requests;

use Arrow\Http\Requests\Request;

class DeliveryTicketRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'salesrep_id' => 'required|exists:sales_reps,id',
            'area_id' => 'required|sometimes|exists:areas,id',
            'status' => 'required|in:pending,complete,approved', 
            'delivery_date' => 'required|date_format:m-d-Y', 
            'purchase_order_number' => '', 
            'ordered_by' => 'required', 
            'delivered_by' => 'required', 
            'injection_type' => 'in:continuous,batch', 
            'chemical' => 'required', 
            'quantity' => 'required', 
            'packaging.*' => 'required|in:drum,pail,tote,bulk,jug'
        ];
    }
}
