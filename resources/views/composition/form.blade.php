@extends('layouts.main')
@section('content')

<!-- #MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>Composition</li>
            <li>Details</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-home"></i> 
                Composition 
            <span>>  
                Details
            </span>
        </h1>
    </div>
    <div class="col-lg-10 col-lg-offset-1" style="margin-bottom: 40px;">
        <div class="jarviswidget" id="company-widget" data-widget-editbutton="false" data-widget-custombutton="false">
    <header>
        <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
        <h2>Composition Data</h2>
    </header>

    <div>
        <div class="jarviswidget-editbox">
            
        </div>

        <div class="widget-body no-padding">
            <form class="smart-form" method="POST" action="{{ action($action) }}" novalidate="novalidate">
                {{ csrf_field() }}
                @if($composition->id)
                    <input type="hidden" name="id" value="{{ $composition->id }}" />
                @endif
                <header>
                    Composition
                </header>
                <fieldset>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Location</label>
                            <label class="select">
                               <select name="location_id">
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" @if($location->id == $location_id) selected @endif>{{ $location->name }}</option>
                                    @endforeach
                               </select> 
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Date</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name="date" placeholder="YYYY-MM" value="{{ $composition->date ? date('Y-m', strtotime($composition->date)) : date('Y-m') }}">
                            </label>
                        </section>
                        
                    </div>

                    <div class="row">
                        <section class="col col-3">
                            <label class="label">Iron</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name="iron" placeholder="Irons" value="{{ $composition->iron }}">
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Manganese</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name="manganese" placeholder="Manganese" value="{{ $composition->manganese }}">
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Chloride</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name="chloride" placeholder="Chloride" value="{{ $composition->chloride }}">
                            </label>
                        </section>   
                    </div>
                    <div class="row">
                        <section class="col col-4">
                            <label class="textarea"> <i class="icon-append fa fa-comment"></i>
                                <textarea  rows="5" name="comments" placeholder="Comments">{{ $composition->comments }}</textarea>
                            </label>
                        </section>
                    </div>
                </fieldset>
                
                <footer>
                    <button  type="submit" class="btn btn-primary">
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