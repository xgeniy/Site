function ddSendAjax ( parametrs, host, functionSuccess, functionError, method = 'GET') {
    $.getJSON( {
        url: host ,
        type: method,
        dataType: 'json',
        data: parametrs,
        contentType: 'application/json',
        success: function ( data ) { functionSuccess (data); },
        error: function ( data ) { functionError (data); }
    });
}

function age(date){
	var birthdate = new Date(date);
    var cur = new Date();
    var diff = cur-birthdate; 
    return Math.floor(diff/31557600000); 
}



function $_GET(key) {
    var s = window.location.search;
    s = s.match(new RegExp(key + '=([^&=]+)'));
    return s ? s[1] : false;
}

