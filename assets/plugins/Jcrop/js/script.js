function initJcrop() {
    $('#cropbox').Jcrop({
        aspectRatio: 1,
        setSelect: [0, 0, cwidth, cheight],
        allowResize: false,
        allowSelect: false,
        onSelect: updateCoords
    }, function () {
        jcrop_api = this;
    });
}
function updateCoords(c) {
    $('#x').val(c.x);
    $('#y').val(c.y);
    $('#w').val(c.w);
    $('#h').val(c.h);
}
function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            //$('#blah').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}
$(document).ready(function () {
    $.globalEval("var cwidth = 150;");
    $.globalEval("var cheight = 150;");
    $.globalEval("var jcrop_api;");
    $.globalEval("var img_avatar = '';");

    $('.fileUpload').on('click', function () {
        $("#uploadFile").trigger("click");
    });

    $("#uploadFile").change(function () {
        readURL(this);
        var formData = new FormData();
        formData.append('myfile', this.files[0]);
        $("#abc .progress-bar").removeClass('progress-bar-danger');
        $("#abc .progress-bar").width("40%");
        $("#abc .progress-bar").html("40%");
        $("#abc").removeClass('hide');
        $("#abc .progress-bar").addClass('active');
        //$('#myModal').modal('show');

        $.ajax({
            url: base_url + 'files/upload_ajax',
            type: 'POST',
            xhr: function () {
                var xhr = $.ajaxSettings.xhr();
                if (xhr.upload) {
                    xhr.upload.addEventListener('progress', function (evt) {
                        var percent = parseInt((evt.loaded / evt.total) * 100);
                        $("#abc .progress-bar").width(percent + "%");
                        $("#abc .progress-bar").html(percent + "%");
                        if (percent === 100) {
                            $("#abc .progress-bar").removeClass('active');
                        }
                    }, false);
                }
                return xhr;
            },
            success: function (response) {
                var server = JSON.parse(response);
                if (server.status) {
                    $("#abc").addClass('hide');
                    $("#abc .progress-bar").html(server.message);

                    if (jcrop_api) {
                        jcrop_api.destroy();
                    }
                    $('#cropbox').attr('src', server.data.src);
                    initJcrop();
                    $('#myModal').modal('show');
                } else {
                    $("#abc .progress-bar").addClass('progress-bar-danger');
                    $("#abc .progress-bar").html(server.message);
                }
                $("#uploadFile").closest("form").trigger("reset");
            },
            error: function () {
                $("#uploadFile").closest("form").trigger("reset");
            },
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        }, 'json');
    });

    initJcrop();


    $('#f_crop_image').on('submit', function () {
        if (!parseInt($('#w').val())) {
            alert('Please select a crop region then press submit.');
            return false;
        }
        var url = base_url + 'files/crop_image_ajax';

        //get file name
        var src = $("#cropbox").attr('src'); // "static/images/banner/blue.jpg"
        var tarr = src.split('/');      // ["static","images","banner","blue.jpg"]
        var file_name = tarr[tarr.length - 1]; // "blue.jpg"
        //var file_name = file.split('.')[0];  // "blue"
        $.ajax({
            type: "POST",
            url: url,
            data: $(this).serialize() + '&file_name=' + file_name,
            success: function (data) {
                $("#imgPrev").html(data);
                img_avatar = data.split('"')[1];
                var tarr = img_avatar.split('/');      // ["static","images","banner","blue.jpg"]
                var file_name = tarr[tarr.length - 1]; // "blue.jpg"
                $('#id_avatar').val(file_name);
                if (img_avatar != '') {
                    $('#img_avatar').attr('src', img_avatar);
                }
            }
        });

        return false;
    });
});