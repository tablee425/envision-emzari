@extends('layouts.main')


@section('content')
<!-- #MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>Injection Data</li>
            <li>Import</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">

            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-home"></i>
                Data Input
            <span>>
                Upload Spreadsheet
            </span>
        </h1>
    </div>

    @if(Session::has('upload-status'))
    <div class="row">
        <div class="alert alert-success">{{ Session::get('upload-status') }}</div>
    </div>
    @endif

    <div class="col-md-10 col-lg-offset-1 form-row">
        <h4>Upload production data spreadsheet file for your defined locations.</h4>
        <button class="btn btn-success btn-lg" data-toggle="modal" data-target="#importModal">
            <i class="fa fa-plus"></i> Import Production Spreadsheet File
        </button>
    </div>

    <div class="col-md-10 col-lg-offset-1 form-row">
        <h4>Upload end of month chemical inventory data spreadsheet file for your defined dates and injection sites.</h4>
        <button class="btn btn-info btn-lg" data-toggle="modal" data-target="#endOfMonthModal">
            <i class="fa fa-plus"></i> Import End of Month Inventory Spreadsheet File
        </button>
    </div>

    <div class="col-md-10 col-lg-offset-1 form-row">
        <h4>Upload chemical deliveries spreadsheet file for your defined dates and injection sites.</h4>
        <button class="btn btn-danger btn-lg" data-toggle="modal" data-target="#chemicalDeliveriesModal">
            <i class="fa fa-plus"></i> Import Deliveries Spreadsheet File
        </button>
    </div>

</div>

<!-- Production Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ action('ImportController@production') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Upload Production Spreadsheet</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-2 control-label"> Select File</label>
                                <div class="col-md-10">
                                    <input type="file" name="import" class="btn btn-default" id="importFile" />
                                    <p class="help-block">Please choose a spreadsheet to upload.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Upload File
                    </button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- End of Month Inventory Import Modal -->
<div class="modal fade" id="endOfMonthModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ action('ImportController@endOfMonthInventory') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Upload End of Month Inventory Spreadsheet</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-2 control-label"> Select File</label>
                                <div class="col-md-10">
                                    <input type="file" name="import" class="btn btn-default" id="importFile" />
                                    <p class="help-block">Please choose a spreadsheet to upload.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Upload File
                    </button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Chemical Deliveries  Import Modal -->
<div class="modal fade" id="chemicalDeliveriesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ action('DeliveryImportController@update') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Chemical Deliveries Spreadsheet</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-md-2 control-label"> Select File</label>
                                <div class="col-md-10">
                                    <input type="file" name="import" class="btn btn-default" id="importFile" />
                                    <p class="help-block">Please choose a spreadsheet to upload.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Upload File
                    </button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@section('footer_assets')

@endsection