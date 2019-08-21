function checkScroll() {
    var startY = $('.navbar').height(); //The point where the navbar changes in px

    if ($(window).scrollTop() > startY) {
        $('.navbar').addClass("bg-dark");
        $('.navbar').addClass("bg-custom");
        $('.dropdown-menu-nav').addClass("bg-dark");
        $('.dropdown-menu-nav').addClass("bg-custom");
        $('.jumbo-fluid').removeClass("active");
        $('.jumbo-fluid').addClass("unactive");

    } else {
        $('.navbar').removeClass("bg-dark");
        $('.navbar').removeClass("bg-custom");
        $('.dropdown-menu-nav').removeClass("bg-dark");
        $('.dropdown-menu-nav').removeClass("bg-custom");
        $('.jumbo-fluid').addClass("active");
        $('.jumbo-fluih').removeClass("unactive");
    }
}

$(".navbar-toggler").click(function(){
    if ($('#togglerButton').attr('aria-expanded')=='false') {
        $("#iconlogonavbar").fadeOut(200);
    } else {
        $("#iconlogonavbar").fadeIn(1000);
    }
});



if ($('.navbar').length > 0) {
    $(window).on("scroll load resize", function () {
        checkScroll();
    });
}
