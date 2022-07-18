$(document).ready(function(){
        $("#frm-orderTubeForm-order_id").keyup(function()
        {
            var input = $(this).val();
            //alert(input);
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