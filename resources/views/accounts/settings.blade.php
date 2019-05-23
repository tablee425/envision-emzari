@extends('layouts.main')

@section('css_assets')
   
@endsection

@section('content')
<<!-- #MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>User</li>
            <li>Settings</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-home"></i> 
                Companies 
            <span>>  
                Set Active
            </span>
        </h1>
    </div>
    <div class="col-lg-10 col-lg-offset-1">
        <div class="jarviswidget" id="company-widget" data-widget-editbutton="false" data-widget-custombutton="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                <h2>Active Companies</h2>
            </header>

            <div>
                <div class="jarviswidget-editbox">
                    
                </div>


                <div class="widget-body no-padding">
                    <form class="smart-form" method="POST" action="{{ action('AccountController@postSettings') }}" novalidate="novalidate">
                        {{ csrf_field() }}
                        <header>
                            Update Active Company
                        </header>
                        <fieldset>
                            <div class="row">
                                <section class="col col-3">
                                    <label class="label">Companies</label>
                                    <label class="select">
                                        <select name="company_id">
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" @if($company->id == Auth::user()->activeCompany()->id) selected @endif>{{ $company->name }}</option>
                                        @endforeach
                                        </select> <i></i>
                                    </label>
                                </section>
                            </div>   
                        </fieldset>
                        
                        <footer>
                            <button type="submit" class="btn btn-primary">
                                Select Company
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

@endsection