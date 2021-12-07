$(document).ready(function() {
    $("div #container-description >p").each(function(i) {
        var btn = $(this).next();
        var ancho = $("#container-description").width();
        var altodiv = $("#container-description").height();
        var altop = $(this).height();
        if (altop > altodiv) {
            $(this).addClass("block-ellipsis");
        }
    });
});

