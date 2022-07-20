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
        success: function (data, status) {
            console.log(status);
        }
    });
}