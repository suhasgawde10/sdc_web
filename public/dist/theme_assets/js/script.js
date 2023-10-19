$(document).ready(function(){
    $(".img-header-left").click(function()
    {
        $(".product1-body").removeClass('d-none');
        $(".pd2").addClass('d-none');
        $(".img-header-left").addClass("active");
        $(".img-header-right").removeClass("active");
        $(".img-header-left").addClass("bor");
        $(".img-header-right").removeClass("bor");
    });

    $(".img-header-right").click(function()
    {
        $(".pd2").removeClass('d-none');
        $(".product1-body").addClass('d-none');
        $(".img-header-left").removeClass("active");
        $(".img-header-right").addClass("active");
        $(".img-header-left").removeClass("bor");
        $(".img-header-right").addClass("bor");
    });



    $(".img-header-left").click(function()
    {
        $(".client-body").removeClass('d-none');
        $(".pd2").addClass('d-none');
        $(".img-header-left").addClass("active");
        $(".img-header-right").removeClass("active");
        $(".img-header-left").addClass("bor");
        $(".img-header-right").removeClass("bor");
    });

    $(".img-header-right").click(function()
    {
        $(".pd2").removeClass('d-none');
        $(".client-body").addClass('d-none');
        $(".img-header-left").removeClass("active");
        $(".img-header-right").addClass("active");
        $(".img-header-left").removeClass("bor");
        $(".img-header-right").addClass("bor");
    });
});