$(document).ready(function () {
    $('.counter').each(function () {
        $(this).fadeIn(3000);
        var $this = $(this),
            countTo = $this.attr('data-count');
        $({countNum: $this.text()}).animate({
                countNum: countTo
            },
            {
                duration: 3000,
                easing: 'linear',
                step: function () {
                    $this.text(Math.floor(this.countNum));
                },
                complete: function () {
                    $this.text(this.countNum);

                }

            });


    });
});
