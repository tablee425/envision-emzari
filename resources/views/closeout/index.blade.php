@extends('layouts.main')

@section('content')

<!-- #MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>Chemical</li>
            <li>Closeout</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-home"></i> 
                Delivery Ticket 
            <span>>  
                Integration
            </span>
        </h1>
    </div>
    <div class="col-lg-10 col-lg-offset-1">
        <div class="jarviswidget" id="company-widget" data-widget-editbutton="false" data-widget-custombutton="false">
    <header>
        <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
        <h2>Chemical Closeout</h2>
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
        <div class="row">
            <div class="col-md-6">
                <h3>Continuous Injections</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h3>Batch Injections</h3>
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