@extends('layouts.main')

@section('content')

<!-- #MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>Edit</li>
            <li>Location</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-home"></i> 
                Location 
            <span>>  
                Form Input
            </span>
        </h1>
    </div>
    <div class="col-lg-10 col-lg-offset-1">
        <div class="jarviswidget" id="company-widget" data-widget-editbutton="false" data-widget-custombutton="false">
    <header>
        <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
        <h2>Location Information</h2>
    </header>

    <div>
        <div class="jarviswidget-editbox">
            
        </div>
    @if(count($errors) > 0)    
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
        <div class="widget-body no-padding">
            <form class="smart-form" method="POST" action="{{ action($action) }}" novalidate="novalidate">
                {{ csrf_field() }}
                <header>
                    Location Data for 
                </header>
                <fieldset>
                    <div class="row">
                        <section class="col col-3">
                            <label class="label">Name</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input type="text" name="name" placeholder="Location Name" value="{{ $location->name }}" />
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Unit of Measure</label>
                            <label class="select"> 
                                <select name="unit_of_measure" >
                                    <option value="liters" @if($location->unit_of_measure == "liters") selected @endif>Litres</option>
                                    <option value="barrels" @if($location->unit_of_measure == "barrels") selected @endif>Barrels</option>
                                    <option value="gallons" @if($location->unit_of_measure == "gallons") selected @endif>Gallons</option>
                                </select> <i></i>
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Formation</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input type="text" name="formation" placeholder="Formation" value="{{ $location->formation }}">
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Description</label>
                            <label class="textarea"> <i class="icon-prepend fa fa-briefcase"></i>
                                <textarea rows="5" name="description" placeholder="A description of the location.">{{ $location->description }}</textarea>
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Field</label>
                            <label class="textarea"> <i class="icon-prepend fa fa-briefcase"></i>
                                <select id="field" name="field_id" type="text" placeholder="Select Parent Field ..." style="width: 220px;">
                                    @foreach($companyFields as $companyField)
                                        <option value="{{ $companyField->id }}" @if($location->field_id == $companyField->id || (isset($field) && $companyField->id == $field->id)) selected @endif>{{ $companyField->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Cost Centre</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input type="text" name="cost_centre" placeholder="Cost Centre" value="{{ $location->cost_centre }}">
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-5">
                            <label class="label">Chemicals</label>
                            <div id="chemicals">
                                @if(isset($chemicals))
                                    @foreach($chemicals as $chemical)
                                        <div style="display: block;">
                                            <input name="chemicals[{{ $chemical->id }}]" type="text" value="{{ $chemical->name }}" />
                                            <select name="chemical_types[{{ $chemical->id }}]">
                                                <option value="corrosion_inhibitor" @if($chemical->chemical_type == "corrosion_inhibitor") selected @endif>Corrosion Inhibitor</option>
                                                <option value="demulsifier" @if($chemical->chemical_type == "demulsifier") selected @endif>Demulsifier</option>
                                            </select>
                                            <select name="types[{{ $chemical->id }}]">
                                                <option value="BATCH" @if($chemical->type == "BATCH") selected @endif>Batch</option>
                                                <option value="CONTINUOUS" @if($chemical->type == "CONTINUOUS") selected @endif>Continuous</option>
                                                <option value="BOTH" @if($chemical->type == "BOTH") selected @endif>Both</option>
                                            </select> <i class="fa fa-plus-circle new-chemical" aria-hidden="true" style="font-size: 2em;"></i> <i class="fa fa-remove" aria-hidden="true" style="font-size: 2em;"></i>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </section>
                    </div>
                </fieldset>
                {{-- <input name="field_id" type="hidden" value="{{ $field_id }}" /> --}}
                @if ($button == "Update Location")
                    <input name="_method" type="hidden" value="PUT" />
                    <input name="location_id" type="hidden" value="{{ $location->id }}" />
                @endif
                <footer>
                    <button type="submit" class="btn btn-primary">
                        {{ $button }}
                    </button>
                </footer>
            </form>
        </div>
    </div>
</div>
    </div>
</div>
@endsection
@section('footer_assets')
<script>
$(function() {
    var chemical_index = {{ isset($chemicals) ? $chemicals->count() + 1 : 1 }};
    var addChemical = function() {
        $('#chemicals').append('<div style="display: block;"><input name="chemicals['+ chemical_index +']" type="text" /> <select name="chemical_types['+ chemical_index +']"><option value="corrosion_inhibitor">Corrosion Inhibitor</option><option value="demulsifier">Demulsifier</option></select> <select name="types['+ chemical_index +']"><option value="BATCH">Batch</option><option value="CONTINUOUS">Continuous</option><option value="BOTH">Both</option></select> <i class="fa fa-plus-circle new-chemical" aria-hidden="true" style="font-size: 2em;"></i> <i class="fa fa-remove" aria-hidden="true" style="font-size: 2em;"></i></div>');
        chemical_index++;
    };
    addChemical();
    $('#chemicals').on('click', '.new-chemical', function() {
        addChemical();
    });
    $('#chemicals').on('click', '.fa-remove', function() {
        $(this).parent().remove();
    });

    // Location Search Box
    $('#field').select2();
    // $('#field').select2({
    //     placeholder: "Find Location",
    //     minimumInputLength: 2,
    //     ajax: {
    //         url: "{{ action('CompanyController@getAllFields')}}",
    //         dataType: 'json',
    //         delay: 250,
    //         data: function(params) {
    //             return {
    //                 q: params.term,
    //                 page: params.page
    //             }
    //         },
    //         processResults: function(data, params) {
    //             console.log(data);
    //             return {
    //                 results: $.map(data, function(obj) {
    //                     return { id: obj.id, text: obj.name };
    //                 })
    //             };
    //         }
    //     },
    // });
    // $('#field').on('select2:select', function(e) {
    //     var id = e.params.data.id;
    //     console.log(id);
    //     // window.location = '/locations?field_id=' + id + '&search_term=' + e.params.data.text;
    // });
       
});
</script>
@endsection