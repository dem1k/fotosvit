<!-- Load Queue widget CSS and jQuery -->
<link type="text/css" href="/assets/js/plupload/css/plupload.queue.css" rel="Stylesheet" />

<!-- Thirdparty intialization scripts, needed for the Google Gears and BrowserPlus runtimes -->
<script type="text/javascript" src="/assets/js/plupload/js/gears_init.js"></script>
<script type="text/javascript" src="/assets/js/plupload/js/browserplus-min.js"></script>



<!-- Load plupload and all it's runtimes and finally the jQuery queue widget -->
<script type="text/javascript" src="/assets/js/plupload/js/plupload.full.min.js"></script>
<script type="text/javascript" src="/assets/js/plupload/js/jquery.plupload.queue.min.js"></script>
<style type="text/css">
    a.plupload_button {display: inline !important;}
</style>
<script type="text/javascript">
    // Convert divs to queue widgets when the DOM is ready
    $(function() {
        $("#uploader").pluploadQueue({
            // General settings
            runtimes : 'html5',
            url : 'upload.php',
            max_file_size : '2048mb',
            chunk_size : '5mb',
            multipart: true,
            unique_names : true,

            // Resize images on clientside if we can
            resize : {width : 320, height : 240, quality : 90},

            // Specify what files to browse for
            filters : [
                {title : "Movie files", extensions : "avi,wmv"},
                {title : "Image files", extensions : "jpg,gif,png"},
                {title : "Zip files", extensions : "zip"}
            ],
            // Flash settings
            flash_swf_url : '/assets/js/plupload/js/plupload.flash.swf',

        });

        // Client side form validation
        $('form').submit(function(e) {
            var uploader = $('#uploader').pluploadQueue();

            // Validate number of uploaded files
            if (uploader.total.uploaded == 0) {
                // Files in queue upload them first
                if (uploader.files.length > 0) {
                    // When all files are uploaded submit form
                    uploader.bind('UploadProgress', function() {
                        if (uploader.total.uploaded == uploader.files.length)
                            $('form').submit();
                    });

                    uploader.start();
                } else
                    alert('You must at least upload one file.');

                e.preventDefault();
            }
        });
    });
</script>


<form >
    <div id="uploader">
        <p>You browser doesn't have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.</p>
    </div>
</form>
