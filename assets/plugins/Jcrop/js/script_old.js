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
function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}
$(document).ready(function () {
    $.globalEval("var cwidth = 100;");
    $.globalEval("var cheight = 100;");
    $.globalEval("var jcrop_api;");
    $.globalEval("var img_avatar = '';");

    $('#myModal').on('hidden.bs.modal', function (e) {
        if (img_avatar != '') {
            $('#img_avatar').attr('src', img_avatar);
        }
    });

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
                        if (percent == 100) {
                            $("#abc .progress-bar").removeClass('active');
                        }
                    }, false);
                }
                return xhr;
            },
            success: function (response) {
                var server = JSON.parse(response);
                if (server.status) {
                    $("#abc .progress-bar").html(server.message);

                    if (jcrop_api) {
                        jcrop_api.destroy();
                    }
                    $('#cropbox').attr('src', server.data.src);
                    initJcrop();
                    $('#myModal').modal('show');
                } else {
                    //ProgressBar.className += ' progress-bar-danger'; //Thêm class Danger
                    //ProgressBar.innerHTML = server.message; //Thông báo
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
        var file = tarr[tarr.length - 1]; // "blue.jpg"
        var file_name = file.split('.')[0];  // "blue"
        $.ajax({
            type: "POST",
            url: url,
            data: $(this).serialize() + '&file_name=' + file_name,
            success: function (data) {
                $("#imgPrev").html(data);
                img_avatar = data.split('"')[1];
            }
        });

        return false;
    });

    $('#f_upload_image').on('submit', function () {
        document.getElementById('progress-group').innerHTML = ''; //Reset lại Progress-group
        var files = document.getElementById('myfile').files;
        for (i = 0; i < files.length; i++) {
            uploadFile(files[i], i);
        }

        return false;
    });
});
function updateCoords(c)
{
    $('#x').val(c.x);
    $('#y').val(c.y);
    $('#w').val(c.w);
    $('#h').val(c.h);
}

function checkCoords()
{
    if (parseInt($('#w').val()))
        return true;
    alert('Please select a crop region then press submit.');
    return false;
}

var http_arr = new Array();

function doUpload() {
    document.getElementById('progress-group').innerHTML = ''; //Reset lại Progress-group
    var files = document.getElementById('myfile').files;
    for (i = 0; i < files.length; i++) {
        uploadFile(files[i], i);
    }
    return false;
}

function uploadFile(file, index) {
    var http = new XMLHttpRequest();
    http_arr.push(http);
    /** Khởi tạo vùng tiến trình **/
    //Div.Progress-group
    var ProgressGroup = document.getElementById('progress-group');
    //Div.Progress
    var Progress = document.createElement('div');
    Progress.className = 'progress';
    //Div.Progress-bar
    var ProgressBar = document.createElement('div');
    ProgressBar.className = 'progress-bar';
    //Div.Progress-text
    var ProgressText = document.createElement('div');
    ProgressText.className = 'progress-text';
    //Thêm Div.Progress-bar và Div.Progress-text vào Div.Progress
    Progress.appendChild(ProgressBar);
    Progress.appendChild(ProgressText);
    //Thêm Div.Progress và Div.Progress-bar vào Div.Progress-group	
    ProgressGroup.appendChild(Progress);


    //Biến hỗ trợ tính toán tốc độ
    var oldLoaded = 0;
    var oldTime = 0;
    //Sự kiện bắt tiến trình
    http.upload.addEventListener('progress', function (event) {
        if (oldTime == 0) { //Set thời gian trước đó nếu như bằng không.
            oldTime = event.timeStamp;
        }
        //Khởi tạo các biến cần thiết
        var fileName = file.name; //Tên file
        var fileLoaded = event.loaded; //Đã load được bao nhiêu
        var fileTotal = event.total; //Tổng cộng dung lượng cần load
        var fileProgress = parseInt((fileLoaded / fileTotal) * 100) || 0; //Tiến trình xử lý
        var speed = speedRate(oldTime, event.timeStamp, oldLoaded, event.loaded);
        //Sử dụng biến
        ProgressBar.innerHTML = fileName + ' đang được upload...';
        ProgressBar.style.width = fileProgress + '%';
        ProgressText.innerHTML = fileProgress + '% Upload Speed: ' + speed + 'KB/s';
        //Chờ dữ liệu trả về
        if (fileProgress == 100) {
            ProgressBar.style.background = 'url("' + base_url + 'assets/images/progressbar.gif")';
        }
        oldTime = event.timeStamp; //Set thời gian sau khi thực hiện xử lý
        oldLoaded = event.loaded; //Set dữ liệu đã nhận được
    }, false);


    //Bắt đầu Upload
    var data = new FormData();
    data.append('filename', file.name);
    data.append('myfile', file);
    http.open('POST', base_url + 'files/upload_ajax', true);
    http.send(data);


    //Nhận dữ liệu trả về
    http.onreadystatechange = function (event) {
        //Kiểm tra điều kiện
        if (http.readyState == 4 && http.status == 200) {
            ProgressBar.style.background = ''; //Bỏ hình ảnh xử lý
            try { //Bẫy lỗi JSON
                var server = JSON.parse(http.responseText);
                if (server.status) {
                    ProgressBar.className += ' progress-bar-success'; //Thêm class Success
                    ProgressBar.innerHTML = server.message; //Thông báo

                    if (jcrop_api) {
                        jcrop_api.destroy();
                    }
                    $('#cropbox').attr('src', server.data.src);
                    initJcrop();
                    $('#myModal').modal('show');
                } else {
                    ProgressBar.className += ' progress-bar-danger'; //Thêm class Danger
                    ProgressBar.innerHTML = server.message; //Thông báo
                }
            } catch (e) {
                ProgressBar.className += ' progress-bar-danger'; //Thêm class Danger
                ProgressBar.innerHTML = 'Có lỗi xảy ra'; //Thông báo
            }
        }
        //http.removeEventListener('progress'); //Bỏ bắt sự kiện
    }
}

function cancleUpload() {
    for (i = 0; i < http_arr.length; i++) {
        http_arr[i].removeEventListener('progress');
        http_arr[i].abort();
    }
    var ProgressBar = document.getElementsByClassName('progress-bar');
    for (i = 0; i < ProgressBar.length; i++) {
        ProgressBar[i].className = 'progress progress-bar progress-bar-danger';
    }
}


function speedRate(oldTime, newTime, oldLoaded, newLoaded) {
    var timeProcess = newTime - oldTime; //Độ trễ giữa 2 lần gọi sự kiện
    if (timeProcess != 0) {
        var currentLoadedPerMilisecond = (newLoaded - oldLoaded) / timeProcess; // Số byte chuyển được 1 Mili giây
        return parseInt((currentLoadedPerMilisecond * 1000) / 1024); //Trả về giá trị tốc độ KB/s
    } else {
        return parseInt(newLoaded / 1024); //Trả về giá trị tốc độ KB/s
    }
}