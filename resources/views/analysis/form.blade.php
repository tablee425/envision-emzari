@extends('layouts.main')
@section('content')

<!-- #MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>Analysis</li>
            <li>Details</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-home"></i> 
                Analysis 
            <span>>  
                Details
            </span>
        </h1>
    </div>
    <div class="col-lg-10 col-lg-offset-1" style="margin-bottom: 40px;">
        <div class="jarviswidget" id="company-widget" data-widget-editbutton="false" data-widget-custombutton="false">
    <header>
        <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
        <h2>Analysis Data</h2>
    </header>

    <div>
        <div class="jarviswidget-editbox">
            
        </div>

        <div class="widget-body no-padding">
            <form class="smart-form" method="POST" action="{{ action($action) }}" novalidate="novalidate">
                {{ csrf_field() }}
                @if($analysis->id)
                    <input type="hidden" name="id" value="{{ $analysis->id }}" />
                @endif
                <header>
                    Analysis
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
                                <input  type="text" name="date" placeholder="YYYY-MM" value="{{ $analysis->date ? date('Y-m', strtotime($analysis->date)) : date('Y-m') }}">
                            </label>
                        </section>    
                    </div>

                    <div class="row">
                        <section class="col col-3">
                            <label class="label">Corrosion Residuals (PPM)</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name="corrosion_residuals" placeholder="Corrosion Residuals" value="{{ $analysis->corrosion_residuals }}">
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Scale Residuals (PPM)</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name="scale_residuals" placeholder="Scale Residuals" value="{{ $analysis->scale_residuals }}">
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Water Qualities</label>
                            <label class="input"> <i class="icon-prepend fa fa-briefcase"></i>
                                <input  type="text" name="water_qualities" placeholder="Water Qualities" value="{{ $analysis->water_qualities }}">
                            </label>
                        </section>   
                    </div>
                    <div class="row">
                        <section class="col col-4">
                            <label class="textarea"> <i class="icon-append fa fa-comment"></i>
                                <textarea  rows="5" name="comments" placeholder="Comments">{{ $analysis->comments }}</textarea>
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