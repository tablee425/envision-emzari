@extends('layouts.main')

@section('content')

<!-- #MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>Areas</li>
            <li>{{ $button }}</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-home"></i> 
                Areas 
            <span>>  
                {{ $button }}
            </span>
        </h1>
    </div>
    <div class="col-lg-10 col-lg-offset-1">
        <div class="jarviswidget" id="company-widget" data-widget-editbutton="false" data-widget-custombutton="false">
    <header>
        <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
        <h2>Area Information</h2>
    </header>

    <div>
        <div class="jarviswidget-editbox">
            
        </div>

        <div class="widget-body no-padding">
            <form class="smart-form" method="POST" action="{{ action($action) }}" novalidate="novalidate">
                {{ csrf_field() }}
                <header>
                    Area Data 
                </header>
                <fieldset>
                    <div class="row">
                        <section class="col col-3">
                            <label class="label">Name</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input type="text" name="name" placeholder="Area Name" value="{{ $area->name }}" />
                            </label>
                        </section>
                    </div>
                </fieldset>
                @if ($button == "Update Area")
                    <input name="_method" type="hidden" value="PUT" />
                    <input name="area_id" type="hidden" value="{{ $area->id }}" />
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