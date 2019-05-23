@extends('layouts.main')

@section('content')

<!-- #MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>Fields</li>
            <li>Locations</li>
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
        <h2>Field Information</h2>
    </header>

    <div>
        <div class="jarviswidget-editbox">

        </div>

        <div class="widget-body no-padding">
            <form class="smart-form" method="POST" action="{{ action($action, $field) }}" novalidate="novalidate">
                {{ csrf_field() }}
                <header>
                    Field Data for
                </header>
                <fieldset>
                    <div class="row">
                        <section class="col col-3">
                            <label class="label">Name</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input type="text" name="name" placeholder="Field Name" value="{{ $field->name }}" />
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Area</label>
                            <label class="textarea"> <i class="icon-prepend fa fa-briefcase"></i>
                                <select id="area" name="area_id" type="text" placeholder="Select Parent Area ..." style="width: 220px;">
                                    @foreach($companyAreas as $companyArea)
                                        <option value="{{ $companyArea->id }}" @if($field->area_id == $companyArea->id || (isset($area) && $companyArea->id == $area->id)) selected @endif>{{ $companyArea->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </section>
                    </div>
                </fieldset>

                @if ($button == "Update Field")
                    <input name="_method" type="hidden" value="PUT" />
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
        $('#area').select2();
    });
</script>
@endsection