$(document).ready(function(){
        $("#frm-orderTubeForm-order_id").keyup(function()
        {
            var input = $(this).val();
            if (input !== "") {
                $.ajax({
                    url:"excess",
                    method: "POST",
                    data: {input:input},
                    success:function (data){
                        $("#search_result").html(data);
                    }
                })

            }

        });

 });

$('#frm-orderTubeForm-order_id').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
        e.preventDefault();
        return false;
    }
});