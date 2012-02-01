// Custom example logic
$(function() {
    var uploader_big = new plupload.Uploader({
        runtimes : 'html4,html5',
        browse_button : 'pickfiles_big',
        container : 'container_big',
        max_file_size : '10mb',
        chunk_size : '500kb',
        unique_names:true,
        url : '/admin/product/upload',
        flash_swf_url : '/plupload/js/plupload.flash.swf',
        filters : [
        {
            title : "Image files",
            extensions : "jpg,gif,png,tif"
        },

        ],
    });


    var uploader_small = new plupload.Uploader({
        runtimes : 'html4,html5',
        browse_button : 'pickfiles_small',
        container : 'container_small',
        max_file_size : '10mb',
        chunk_size : '1mb',
        unique_names:true,
        url : '/admin/product/upload',
        flash_swf_url : '/plupload/js/plupload.flash.swf',
        filters : [
        {
            title : "Image files",
            extensions : "jpg,gif,png,tif"
        },

        ],
    });

    uploader_big.bind('Init', function(up, params) {
        $('#filelist_big').html("<div>Current runtime: " + params.runtime + "</div>");
    });

    uploader_small.bind('Init', function(up, params) {
        $('#filelist_small').html("<div>Current runtime: " + params.runtime + "</div>");
    });


    uploader_big.init();
    uploader_small.init();

    uploader_big.bind('FilesAdded', function(up, files) {
        $.each(files, function(i, file) {
            $('#filelist_big').append(
                '<div id="' + file.id + '">' +
                file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
                '</div>');
        });
        uploader_big.start();

        up.refresh(); // Reposition Flash/Silverlight
    });

    uploader_small.bind('FilesAdded', function(up, files) {
        $.each(files, function(i, file) {
            $('#filelist_small').append(
                '<div id="' + file.id + '">' +
                file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
                '</div>');
        });
        uploader_small.start();

        up.refresh(); // Reposition Flash/Silverlight
    });

    uploader_big.bind('UploadProgress', function(up, file) {
        $('#' + file.id + " b").html(file.percent + "%");
    });
    uploader_small.bind('UploadProgress', function(up, file) {
        $('#' + file.id + " b").html(file.percent + "%");
    });


    uploader_big.bind('Error', function(up, err) {
        $('#filelist_big').append("<div>Error: " + err.code +
            ", Message: " + err.message +
            (err.file ? ", File: " + err.file.name : "") +
            "</div>"
            );

        up.refresh(); // Reposition Flash/Silverlight
    });

    uploader_small.bind('Error', function(up, err) {
        $('#filelist_small').append("<div>Error: " + err.code +
            ", Message: " + err.message +
            (err.file ? ", File: " + err.file.name : "") +
            "</div>"
            );

        up.refresh(); // Reposition Flash/Silverlight
    });

    uploader_big.bind('FileUploaded', function(up, file) {
        $('#' + file.id + " b").html("100%");
        $('#container_big').html('<a href="/uploads/products/'+file.target_name+'" target="_blank"><img height="200px" src="/uploads/products/'+file.target_name+'"></a>\n\
\n\
<input type="hidden" name="image_big" value="'+file.target_name+'" />');
    });
    uploader_small.bind('FileUploaded', function(up, file) {
        $('#' + file.id + " b").html("100%");
        $('#container_small').html('<a href="/uploads/products/'+file.target_name+'" target="_blank"><img height="100px" src="/uploads/products/'+file.target_name+'"></a>\n\
\n\
<input type="hidden" name="image_small" value="'+file.target_name+'" />');
    });
});
