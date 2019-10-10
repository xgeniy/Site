// A $( document ).ready() block.
$( document ).ready(function() {
    get_microservice();

    function get_microservice(){
    	  $.ajax({
            url: '/panel/getMicroservice',
            type: 'GET',
            dataType: 'json',
            data: "",
            success: function(data) {
                var data = jQuery.parseJSON(JSON.stringify(data));
                console.log(data);

                if( data.result.length > 0 ){
                    set_list_microservice(data.result);
                }
                
            },
            error: function(request, status, error) {
                alert('Ошибка получения списка микросервисов!');
            }
        });
    }

    function set_list_microservice(result){

        var html = '';

        $.each(result, function(k, v){
            html += '\
            <div class="custom-control custom-switch">\
                <input type="checkbox" checked class="custom-control-input switch-microservices" id="'+ v +'">\
                <label class="custom-control-label" for="'+ v +'">'+ v +'</label>\
            </div>';
            // html +='\
            // <div class="form-group form-check">\
            //     <input type="checkbox" class="form-check-input" id="checkbox-m-'+ v +'">\
            //     <label class="form-check-label" for="checkbox-m-'+ v +'">'+ v +'</label>\
            // </div>';

        });

        $("#div-list-microservice").html(html);
    }

    function change_microservice(name, include){
        
        var json = {};

        json.name = name;
        json.include = include;

        $.ajax({
            url: '/panel/changeMicroservice',
            type: 'GET',
            dataType: 'json',
            data: json,
            success: function(data) {
                var data = jQuery.parseJSON(JSON.stringify(data));
                console.log(data);

                if( data.result.length > 0 ){
                    set_list_microservice(data.result);
                }
                
            },
            error: function(request, status, error) {
                alert('Ошибка запроса');
            }
        });
    }

    //include  or  shutdown microservice
    $("#div-list-microservice").on('click', '.switch-microservices', function(e){
        var name_microservice = $(this).attr("id");
        var include = $(this).prop('checked');
        change_microservice(name_microservice, include);
    });

});