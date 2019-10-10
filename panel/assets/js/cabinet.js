var check_pass = false,
    new_image = null,
    document_image = [],
    form_surname,
    form_name,
    form_patronymic,
    form_date,
    form_state,
    form_address,
    ID_DOC;


const birdhtDay = flatpickr(document.getElementById('field-birdthday'), {
    locale: {
        firstDayOfWeek: 1,
        weekdays: {
          shorthand: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
          longhand: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],         
        }, 
        months: {
          shorthand: ['Янв', 'Фев', 'Март', 'Апр', 'Май', 'Июнь', 'Июль', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
          longhand: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
        },
    },
    dateFormat: "d.m.Y"
});

getCabinet();

var myDropzone = new Dropzone(".uploadDropzone", 
    { 
        url: "/",
        createImageThumbnails: true,
        maxFilesize: 10,
        previewTemplate: '<div style="display:none"></div>',
        autoProcessQueue : false,
    }
);

var myDropzoneDoc = new Dropzone(".uploadDropzoneDoc", 
    { 
        url: "/",
        createImageThumbnails: true,
        maxFilesize: 10,
        previewTemplate: '<div style="display:none"></div>',
        autoProcessQueue : false,
    }
);


var el = document.getElementById('cropp');


var resize = new Croppie(el, {
    viewport: {width: 140,height: 140, type: 'circle'},
    showZoomer: false,
    aspectRatio: 16 / 9,
    autoCropArea: 1,
    movable: false,
    cropBoxResizable: true,
    url: '/assets/images/no-image.png',
});


myDropzone.on("addedfile", function(file) {
    new_image = new Image();
    new_image.src = URL.createObjectURL(file);
    resize.bind({
        url: new_image.src,
    });

    this.removeFile(file);
});
myDropzoneDoc.on("addedfile", function(file) {
    if(file){
        modalProgressOff();
        $(".spinner-border").show();
        $(".modal-body").html("<p> Документ добавляется </p>");
        modalProgress();
        $("#modalConfirm").modal("show");
        var formData = new FormData();
        document_image.push(file);

        if(document_image.length > 0){
            $.each(document_image, function(k, v){
                formData.append('document', document_image[k]);
            });

            $.ajax({
                url: "/pa/uploadDocument", 
                type: "POST", 
                cache: false,
                contentType: false,
                processData: false,
                data: formData
            })
            .progress(function(){
                /* do some actions */
                modalProgress();
            })
                .done(function(e){
                    document_image = [];
                    $(".modal-body").html('<p> Документ добавлен </p>');
                    $('#btnModalSecondary').show();
                    $('#btnModalPrimary').hide();
                    initBtnSave();
                    getCabinet();

            });    
        }
    }
    this.removeFile(file);
   
});

$('body').on('keyup', '#field-old-password', function() {
    checkOldPass($("#field-old-password").val());
});

$('body').on('click', '#btnSave', function() {
    var loadingText = "<span id='preloader-save'><i class='fa fa-spinner fa-spin '></i> Сохранение </span>";
    $(this).html(loadingText);
    $(this).attr('disabled', '');

    if(user_status != 'no_active') saveData();
    else {
        alert('Не активным пользователям не доступно данное действие');
        $(this).removeAttr('disabled');
        $(this).html('Сохранение');
    }
});

$('body').on('click', '#btnModalPrimary', function() {
    deleteDocument(ID_DOC);
});

function editCabinetProgress(){
    var loadingText = "<span id='preloader-save'><i class='fa fa-spinner fa-spin '></i> Сохранение </span>";
    $('#btnSave').html(loadingText);
    $('#btnSave').attr('disabled', '');
}

function modalProgress(){
    $(".modal-body").html("<p>Загрузка. . . </p>");
    $('.spinner-border').show();
    $('#btnModalSecondary').hide();
    $('.btn-spiner').attr('disabled', '');
}

function modalProgressOff(){
    $('#btnModalPrimary').removeAttr('disabled');
    $('.spinner-border').hide();
    $('.btn-spiner').hide();
    $('.btn-spiner').removeAttr('disabled');
}

$('body').on('click', '#btnRemoveAccount', function() {
    $('.btn-spiner').show();
    $(".modal-body").html("Вы действиельно хотите удалить аккаунт?");
    $("#modalAlert").modal('show');
});

$('body').on('click', '.select-file', function() {
    myDropzone.hiddenFileInput.click();
});

$('body').on('click', '.select-document', function() {
    myDropzoneDoc.hiddenFileInput.click();
});

$('body').on('click', '.btn-delete-document', function() {
     modalProgressOff();
     ID_DOC = $(this).attr('data');
     $('.btn-spiner').show();
    $(".modal-body").html('<p> Действительно хотите удалить документ? </p>');
    $("#modalConfirm").modal("show");
});



function getCabinet(){ 
    ddSendAjax ('', '/pa/getUserPersonData', function(data) {
        var obj = jQuery.parseJSON(JSON.stringify(data));
        if(obj.user_person_data != '' && obj.user_card != ''){
            if(obj.user_card.status != 'no_active')setDataUser(obj);
        }else{
            alert("Ошибка получения данных Личного кабинета");
        }
    }, function(data){
        console.log('error ' + data);
    } );
}

function setDataUser(obj){
    $("#field-lastname").val(obj.user_person_data.surname);
    form_surname = obj.user_person_data.surname;
    $("#field-name").val(obj.user_person_data.name);
    form_name = obj.user_person_data.name;
    $("#field-patronymic").val(obj.user_person_data.patronymic);
    form_patronymic = obj.user_person_data.patronymic;
    var bird = obj.user_person_data.date_of_birth.split("-");
    $("#field-birdthday").val(bird[2] + "." + bird[1] + "." + bird[0]);
    form_date = bird[2] + "." + bird[1] + "." + bird[0];
    $("#inputState").val(obj.user_person_data.gender);
    form_state = obj.user_person_data.gender;
    $("#field-adress").text(obj.user_person_data.address);
    form_address = obj.user_person_data.address;
    if(obj.user_card.user_type == 'handicapped') $("#block-files").show();

    if(obj.user_card.user_type == 'handicapped'){
        $('#field-type').val("ЛОВЗ");
    }
    if(obj.user_card.user_type == 'volunteer'){
        $('#field-type').val("Волонтер");
    }
    if(obj.user_card.user_type == 'handicapped'){
        if(obj.user_person_data.doc_photo != null){
            setDocumentTable(obj);
        }
    }
    else {$('.files-list').html('');}
}

function setDocumentTable(obj){
    var html = '';
    var count = 0;
    $.each(obj.user_person_data.doc_photo.url, function(k,v){
        count++;
        var type = v.split('.').pop();
        html += '<div class="row my-4 pb-4">\
                <div class="col px-0"><span class="btn btn-sm btn-success text-uppercase">'+type+'</span></div>\
                <div class="col-10 d-flex align-self-center"><a href="'+v+'" target="_blank">Документ '+ count+'</a></div>\
                <div class="col ml-auto px-0 d-flex align-self-center justify-content-end btn-delete-document" data="'+v+'"><i class="fas fa-times"></i></div>\
                </div>';
    });
    $('.files-list').html(html);
}


function checkOldPass(p){
    ddSendAjax ('pass=' + p, '/pa/checkOldPass', function(data) {
        var obj = jQuery.parseJSON(JSON.stringify(data));
        if(obj.check_pass == 'true'){
            check_pass = true;
            $('#msg-old-password').text('Старый пароль введён верно!');
            $('#msg-old-password').css('color', '#68c61f');
            $("#block-repass-input").show();
            $("#field-new-repassword").prop("disabled", false);

        }else{
            check_pass = false;
            $('#msg-old-password').text('Старый пароль неверный!');
            $('#msg-old-password').css('color', 'red');
            $("#block-repass-input").hide();
            $("#field-new-repassword").prop("disabled", true);
        }
    }, function(data){
        console.log('error ' + data);
    } );
}

function saveData(){
    var json = {};
    if(check_pass == true){
        if($("#field-new-password").val() == $("#field-new-repassword").val()){
            json.new_pass = $("#field-new-password").val();
            $("#field-old-password").val('');
            $("#msg-old-password").html('');
            $("#block-repass-input").hide();
            $("#form-repass")[0].reset();
        } else {
            alert ("Введенные пароли не совпадают");
        }
    }

    json.surname = $("#field-lastname").val();
    json.name = $("#field-name").val();
    json.patronymic = $("#field-patronymic").val();
    json.date_of_birth = $("#field-birdthday").val();
    json.gender = $("#inputState").val();
    json.address = $("#field-adress").val();
    
    if(check_pass == true && json.new_pass != ''){
        $.post("/pa/editCabinet", json,
        function(data, status){
            if(status == 'success'){
               initBtnSave();
               getCabinet();
            }
            
        });
    }else if(new_image != null){
            resize.result('base64').then(function(image) {
                var base64ImageContent = image.replace(/^data:image\/(png|jpg);base64,/, "");
                var blob = base64ToBlob(base64ImageContent, 'image/png');
                var formData = new FormData();
                formData.append('picture', image);
                formData.append('images', '1');

                $.ajax({
                    url: "/pa/setImageProfile", 
                    type: "POST", 
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData})
                        .done(function(e){
                            initBtnSave();
                });     
            });
    }else{
        initBtnSave();
    }
    
    if(document_image.length > 0){
        var formData = new FormData();
        $.each(document_image, function(k, v){
            formData.append('document', document_image[k]);
        });

        $.ajax({
            url: "/pa/uploadDocument", 
            type: "POST", 
            cache: false,
            contentType: false,
            processData: false,
            data: formData
        })
        .progress(function(){
            /* do some actions */
            editCabinetProgress();
        })
            .done(function(e){
                document_image = [];
                initBtnSave();

        });    
    }
    getCabinet();
}

function base64ToBlob(base64, mime) {
    mime = mime || '';
    var sliceSize = 1024;
    var byteChars = window.atob(base64);
    var byteArrays = [];

    for (var offset = 0, len = byteChars.length; offset < len; offset += sliceSize) {
        var slice = byteChars.slice(offset, offset + sliceSize);

        var byteNumbers = new Array(slice.length);
        for (var i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }

        var byteArray = new Uint8Array(byteNumbers);
        byteArrays.push(byteArray);
    }
    return new Blob(byteArrays, {type: mime});
}

function initBtnSave(){
    $("#btnSave").html("Сохранить изменения");
    $("#btnSave").removeClass('btn-success');
    $("#btnSave").addClass('btn-orange');
    $("#btnSave").removeAttr('disabled');
}


function deleteDocument(url){
    modalProgress();
    var json = {};
    json.url = url;
    $.ajax({
            url: "/pa/deleteDocument?url=" + url, 
            type: "GET", 
            cache: false,
            contentType: false,
            processData: false
        })
        .progress(function(){
            /* do some actions */
        })
            .done(function(e){
                $(".modal-body").html('<p> Документ удален</p>');
                $('#btnModalPrimary').hide();
                $('#btnModalSecondary').show();
                getCabinet();

        });   
}


function deleteAccount(){ 
    $("#modalAlert").modal('show');
    modalProgress();
    ddSendAjax ('', '/pa/deleteAccount', function(data) {
        var obj = jQuery.parseJSON(JSON.stringify(data));
        if(obj.delete_account == 'true'){
            $(".modal-body").html("<p>Ваш аккаунт успешно удален!</p>");
            modalProgressOff();

            $.removeCookie("security_token", { path: '/' , domain: '.qzo.su'});
            window.location.replace(api_server);
        }else if (obj.delete_account == 'error'){
            $(".modal-body").html("<p>"+obj.delete_account_msg+"</p>");
            modalProgressOff();
        }
        else {
            $(".modal-body").html("<p>Ошибка удаления аккаунта, пожалуйста обратитесь в тех. поддержку </p>");
            modalProgressOff();
        }
    }, function(data){
        console.log('error ' + data);
    } );
}