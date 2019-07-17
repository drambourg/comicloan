function checkScroll() {
    var startY = $('.navbar').height(); //The point where the navbar changes in px

    if ($(window).scrollTop() > startY) {
        $('.navbar').addClass("bg-dark");
        $('.navbar').addClass("bg-custom");
        $('.jumbo-fluidvh').removeClass("active");
        $('.jumbo-fluidvh').addClass("unactive");

    } else {
        $('.navbar').removeClass("bg-dark");
        $('.navbar').removeClass("bg-custom");
        $('.jumbo-fluidvh').addClass("active");
        $('.jumbo-fluidvh').removeClass("unactive");
    }
}

$(".navbar-toggler").click(function(){
    if ($('#togglerButton').attr('aria-expanded')=='false') {
        $("#iconlogonavbar").fadeOut(300);
    } else {
        $("#iconlogonavbar").fadeIn(1000);
    }
    console.log($('#togglerButton').attr('aria-expanded'));
});



if ($('.navbar').length > 0) {
    $(window).on("scroll load resize", function () {
        checkScroll();
    });
}
