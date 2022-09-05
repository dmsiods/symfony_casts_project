$(function(){
    $("#addquote").on("click", function(event){
        $.ajax({
            url:        '/add-quote',
            type:       'POST',
            dataType:   'json',
            async:      true,
            data:       $.param({params: {param1: 'p1', param2: 'p2', param3: 'p3'}}),

            success: function(data, status) {
                console.log('ok');
            },
            error : function(xhr, textStatus, errorThrown) {
                alert('Ajax request failed.');
            }
        });
    });
});
