$(document).ready(function() {

    $(".js-sidebar-toggle").click(function() {
        $("#sidebar").toggleClass("collapsed");

    });

    $(".sidebar-item").click((event) => {
        $(event.currentTarget.querySelector(".dropdown-content")).toggle();
    });


});