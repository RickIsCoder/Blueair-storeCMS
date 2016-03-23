$(function () {

    var slickWrap = $('#whyBlueAirSlide');
    if (slickWrap[0] !== undefined) {

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


    // nav
    $('#navBtn').on('click', function (event) {
        var e = $(event.currentTarget);
        switch (e.data("state")) {
        case "efficient":
            whyBAShow();
            $('#efficient').removeClass("inShow");
            resetNavBtn();
            break;
        case "mute":
            whyBAShow();
            $('#mute').removeClass("inShow");
            resetNavBtn();
            break;
        case "energy":
            whyBAShow();
            $('#energy').removeClass("inShow");
            resetNavBtn();
            break;
        case "byproduct":
            whyBAShow();
            $('#byproduct').removeClass("inShow");
            resetNavBtn();
            break;
        case "whyEfficient":
            $("#efficientLight").removeClass("dismiss");
            $("#whyEfficient").removeClass("inShow");
            resetNavBtn("efficient");
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
        $('#efficient').addClass("inShow");
    });

    $('#jumpMute').on('click', function () {
        whyBADismiss();
        changeNavBtn("mute");
        $('#mute').addClass("inShow");
    });

    $('#jumpEnergy').on('click', function () {
        whyBADismiss();
        changeNavBtn("energy");
        $('#energy').addClass("inShow");
    });

    $('#jumpByproduct').on('click', function () {
        whyBADismiss();
        changeNavBtn("byproduct");
        $('#byproduct').addClass("inShow");
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
        $('#whyEfficient').addClass('inShow');
        changeNavBtn("whyEfficient");
    });


    $('#energyBtn1').on('click', function () {
        $('#energy1Img').addClass("inShow");
        $('#energy1Content').addClass("inShow");
    });

    $('#energyBtn2').on('click', function () {
        $('#energy2Img').addClass("inShow");
        $('#energy2Content').addClass("inShow");
    });

    $('#energyBtn3').on('click', function () {
        $('#energy3Img').addClass("inShow");
        $('#energy3Content').addClass("inShow");
    });

    $('#energyBtn4').on('click', function () {
        $('#energy4Img').addClass("inShow");
        $('#energy4Content').addClass("inShow");
        $('#energyHonor').addClass("inShow");
    });
    // end whyBlueAir



    // why need cleaner
    if ($('#wncPart1Bg')[0] !== undefined) {
        // part1
        $('#wncPart1Bg').parallax({
            naturalWidth: 1200,
            naturalHeight: 768
        });

        $('#wncPart1Title').parallax({
            naturalWidth: 1200,
            naturalHeight: 768,
            zIndex: -99,
            speed: 0.4
        });

        // part2
        $('#wncPart2Bg').parallax({
            naturalWidth: 1200,
            naturalHeight: 768
        });

        $('#wncPart2Title').parallax({
            naturalWidth: 1200,
            naturalHeight: 768,
            zIndex: -99,
            speed: 0.4
        });

        // part3
        $('#wncPart3Bg').parallax({
            naturalWidth: 1200,
            naturalHeight: 768
        });

        $('#wncPart3Title').parallax({
            naturalWidth: 1200,
            naturalHeight: 768,
            zIndex: -99,
            speed: 0.4
        });
    }
    // end why need cleaner
    
    // which one 
    // end which one

});