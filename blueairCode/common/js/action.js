$(function () {


    // why blue air page slide
    
    if ($('body').data("pagename") === "whyBlueAir") {
        var slickWrap = $('#whyBlueAirSlide');
        var winWidth = $(window).width();
        var winHeight = $(window).height();

        $('.slick-container').height(winHeight).width(winWidth);

        slickWrap.slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            dots: true,
            infinite: false,
        });
    }
    // end slide
    
     // why need cleaner page parallax
    if ($('body').data("pagename") === "whycleaner") {
        $.stellar({
            horizontalScrolling: false,
            hideDistantElements: false
        });
    }
    // end parallax

    // nav
    $('#navBtn').on('click', function (event) {
        var e = $(event.currentTarget);
        switch (e.data("state")) {
        case "efficient":
            whyBAShow();
            $('#efficient').removeClass("inshow");
            resetNavBtn();
            break;
        case "mute":
            whyBAShow();
            $('#mute').removeClass("inshow");
            resetNavBtn();
            break;
        case "energy":
            whyBAShow();
            $('#energy').removeClass("inshow");
            resetNavBtn();
            break;
        case "byproduct":
            whyBAShow();
            $('#byproduct').removeClass("inshow");
            resetNavBtn();
            break;
        case "whyEfficient":
            $("#efficientLight").removeClass("dismiss");
            $("#whyEfficient").removeClass("inshow");
            resetNavBtn("efficient");
            break;
        case "classic":
            classic.removeClass('inshow');
            series.removeClass('dismiss');
            resetNavBtn();
            break;
        case "sense":
            sense.removeClass('inshow');
            series.removeClass('dismiss');
            resetNavBtn();
            break;
        case "filter":
            filter.removeClass('inshow');
            series.removeClass('dismiss');
            resetNavBtn();
            break;
        case "pro":
            pro.removeClass('inshow');
            series.removeClass('dismiss');
            resetNavBtn();
            break;
        default:
            $('#nav').addClass('actived');
            $("#navCloseBtn").addClass('actived');
        }
    });

    $('#navCloseBtn').on('click', function () {
        $('#nav').removeClass('actived');
        $('#navCloseBtn').removeClass('actived');
    });
    // end nav

    // whyBlueAir
    $('#honorIdenty').on('click', function (event) {
        $('.honor-content').removeClass('actived');
        $('#honorIdentyContent').addClass('actived');
        $('.honor-navitem').removeClass('actived');
        $(event.currentTarget).addClass('actived');
    });

    $('#honorReport').on('click', function (event) {
        $('.honor-content').removeClass('actived');
        $('#honorReportContent').addClass('actived');
        $('.honor-nav').removeClass('actived');
        $(event.currentTarget).addClass('actived');
    });
    
    // jump
    function whyBADismiss() {
        $("#whyBlueAirContainer").addClass("dismiss");
    }

    function whyBAShow() {
        $("#whyBlueAirContainer").removeClass("dismiss");
    }

    function changeNavBtn(stateStr) {
        if (stateStr == "whyEfficient") {
            $("#navBtn").removeClass("upArrow").addClass("leftArrow");
        } else {
            $("#navBtn").addClass("upArrow");
        }
        $("#navBtn").data("state", stateStr);
    }

    function resetNavBtn(stateStr) {
        if (stateStr) {
            $("#navBtn").removeClass("leftArrow").addClass("upArrow");
        } else {
            stateStr = "normal";
            $("#navBtn").removeClass("upArrow");
        }
        $("#navBtn").data("state", stateStr);
    }

    $('#jumpEfficient').on('click', function (event) {
        whyBADismiss();
        changeNavBtn("efficient");
        $('#efficient').addClass("inshow");
    });

    $('#jumpMute').on('click', function () {
        whyBADismiss();
        changeNavBtn("mute");
        $('#mute').addClass("inshow");
    });

    $('#jumpEnergy').on('click', function () {
        whyBADismiss();
        changeNavBtn("energy");
        $('#energy').addClass("inshow");
    });

    $('#jumpByproduct').on('click', function () {
        whyBADismiss();
        changeNavBtn("byproduct");
        $('#byproduct').addClass("inshow");
    });
    // end jump

    $('#openBlueAir').on('click', function (event) {
        $('#efficientDark').addClass('dismiss');
        setTimeout(function () {
            $('#efficientDark').addClass('disable');
        }, 1000);
    });

    $('#btnWhyEfficient').on('click', function (event) {
        $('#efficientLight').addClass('dismiss');
        $('#whyEfficient').addClass('inshow');
        changeNavBtn("whyEfficient");
    });


    $('#energyBtn1').on('click', function () {
        $('#energy1Img').addClass("inshow");
        $('#energy1Content').addClass("inshow");
    });

    $('#energyBtn2').on('click', function () {
        $('#energy2Img').addClass("inshow");
        $('#energy2Content').addClass("inshow");
    });

    $('#energyBtn3').on('click', function () {
        $('#energy3Img').addClass("inshow");
        $('#energy3Content').addClass("inshow");
    });

    $('#energyBtn4').on('click', function () {
        $('#energy4Img').addClass("inshow");
        $('#energy4Content').addClass("inshow");
        $('#energyHonor').addClass("inshow");
    });
    // end whyBlueAir


    // which one 
    var series = $('#series');
    var filter = $('#filter');
    var classic = $('#classic');
    var sense = $('#sense');
    var pro = $('#pro');

    $('#classic-btn').on('click', function () {
        classic.addClass('inshow');
        series.addClass('dismiss');
        changeNavBtn("classic");
    });

    $('#filter-btn').on('click', function () {
        series.addClass('dismiss');
        filter.addClass('inshow');
        changeNavBtn('filter');
    });

    $('#sense-btn').on('click', function () {
        series.addClass('dismiss');
        sense.addClass('inshow');
        changeNavBtn('sense');
    });

    $('#pro-btn').on('click', function () {
        series.addClass('dismiss');
        pro.addClass('inshow');
        changeNavBtn('pro');
    });
    // end which one

});