$(function () {
    $.nette.init();
});

$(function() {
    setTimeout(function(){
        $('.alert').slideUp(500);
    }, 3000);
});
$.nette.ext("modals", {
    success: function(payload) {
        if (payload.redirect) {
            $(".modal-ajax").modal("hide");
        } else if(payload.isModal) {
            $('.modal-ajax').modal('show');
        }
    }
});

$.nette.ext("ajaxRedirect", {
    success: function (payload) {
        if (payload.redirect) {
            $.nette.ajax(payload.redirect);
        }
    }
});
$(function () {
    $.nette.ext('changeurl',{
        success: function (payload) {
            if (payload.changeurl) {
                window.history.pushState(null, null, payload.changeurl);
            }

        }
    });
});