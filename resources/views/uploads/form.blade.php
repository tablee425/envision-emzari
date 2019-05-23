@extends('layouts.main')
@section('css_assets')
    <script src="/js/dropzone.min.js"></script>
    <link rel="stylesheet" href="/css/dropzone.min.css">
@endsection
@section('content')

<!-- #MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <ol class="breadcrumb">
            <li>Upload</li>
            <li>Documents</li>
        </ol>
    </div>
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            
            <!-- PAGE HEADER -->
            <i class="fa-fw fa fa-home"></i> 
                Upload 
            <span>>  
                Documents
            </span>
        </h1>
    </div>
    <div class="col-lg-10 col-lg-offset-1" style="margin-bottom: 40px;">
        <div class="jarviswidget" id="company-widget" data-widget-editbutton="false" data-widget-custombutton="false">
    <header>
        <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
        <h2>Location and Date For File Are Required</h2>
    </header>

    <div>
        <div class="jarviswidget-editbox">
            
        </div>

        <div class="widget-body no-padding">
            <form id="file-upload" class="smart-form dropzone" method="POST" action="{{ action('UploadController@postFileUpload') }}" novalidate="novalidate">
                {{ csrf_field() }}
                <header>
                    File Upload Details
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
                                <input  type="text" name="date" placeholder="YYYY-MM" value="{{ date('Y-m') }}">
                            </label>
                        </section>   
                    </div>
                    <div class="row">
                        <section class="col col-6">
                            <div class="dropzone-previews"></div>
                        </section>
                    </div>
                </fieldset>
                
                <footer>
                    <button type="submit" class="btn btn-primary">
                        Upload Files
                    </button>
                </footer>
            
        </div>
    </div>
</div>
    </div>
</div>
@endsection

@section('footer_assets')
<script>
Dropzone.options.fileUpload = { // The camelized version of the ID of the form element
      // The configuration we've talked about above
      autoProcessQueue: false,
      uploadMultiple: true,
      parallelUploads: 100,
      maxFiles: 100,

      // The setting up of the dropzone
      init: function() {
        var myDropzone = this;

        // First change the button to actually tell Dropzone to process the queue.
        this.element.querySelector("button[type=submit]").addEventListener("click", function(e) {
          // Make sure that the form isn't actually being sent.
          e.preventDefault();
          e.stopPropagation();
          myDropzone.processQueue();
        });

        // Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
        // of the sending event because uploadMultiple is set to true.
        this.on("sendingmultiple", function() {
          // Gets triggered when the form is actually being sent.
          // Hide the success button or the complete form.
        });
        this.on("successmultiple", function(files, response) {
          // Gets triggered when the files have successfully been sent.
          // Redirect user or notify of success.
        });
        this.on("errormultiple", function(files, response) {
          // Gets triggered when there was an error sending the files.
          // Maybe show form again, and notify user of error
        });
  }

}
</script>
@endsection
