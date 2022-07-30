function DeleteData(id){
    deleteExcess(id)
}

function deleteExcess(id){
    if (confirm("Opravdu smazat?")) {
        $.ajax({
            url: "excess",
            method: "POST",
            data: {
                deleteId: id,
            },
            success:function (data){
                $("#search_result").html(data);
            }
        });
    } else {
    }
}

function updateExcess(){
    var quantityUpdate = $('#quantity').val();
    var orderId = $('#orderId').val();
    $.ajax({
        url: "excess",
        method: "POST",
        data: {
            quantityUpdate: quantityUpdate,
            orderId: orderId
        },
        success:function (data){
            $("#search_result").html(data);
        }
    });
}