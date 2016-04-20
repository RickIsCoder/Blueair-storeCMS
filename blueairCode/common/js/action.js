$(function () {
    $('#navCloseBtn').on('click', function () {
        $('#nav').removeClass('actived');
        $('#navCloseBtn').removeClass('actived');
    });

    Nav.init();

    switch ($('body').data("pagename")) {
    case "whyBlueAir":
        var slickWrap = $('#whyBlueAirSlide');
        var winWidth = $(window).width();
        var winHeight = $(window).height();

        $('#whyBlueAirSlide .container').height(winHeight).width(winWidth);

        slickWrap.slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            dots: true,
            infinite: false
        });

        $('#honorIdenty').on('click', function (event) {
            $('.honor-content').removeClass('actived');
            $('#honorIdentyContent').addClass('actived');
            $('.honor-navitem').removeClass('actived');
            $(this).addClass('actived');
        });

        $('#honorReport').on('click', function (event) {
            $('.honor-content').removeClass('actived');
            $('#honorReportContent').addClass('actived');
            $('.honor-navitem').removeClass('actived');
            $(this).addClass('actived');
        });

        $('#jumpEfficient').on('click', function () {
            Nav.navPush($('#efficient'));
            $('#bacterials-wrap').parallax({
                calibrateX: true,
                calibrateY: true
            });
        });

        $('#jumpMute').on('click', function () {
            Nav.navPush($('#mute'));
        });

        $('#jumpEnergy').on('click', function () {
            Nav.navPush($('#energy'));
        });

        $('#jumpByproduct').on('click', function () {
            Nav.navPush($('#byproduct'));
        });

        $('#openBlueAir').on('click', function (event) {
            $('#efficientDark').addClass('dismiss');
            setTimeout(function () {
                $('#efficientDark').addClass('disable');
            }, 1000);
        });

        $('#btnWhyEfficient').on('click', function (event) {
            Nav.navPush($('#efficientLight'), $('#whyEfficient'));
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

        Nav.init($("#whyBlueAirContainer"));
        break;
    case "whycleaner":
        //        var ua = navigator.userAgent,
        //            isMobileWebkit = /WebKit/.test(ua) && /Mobile/.test(ua);
        //
        //        if (isMobileWebkit) {
        //            $('html').addClass('mobile');
        //        }
        //        var iScrollInstance;
        //        if (isMobileWebkit) {
        //            iScrollInstance = new IScroll('#wrapper');

        //            $("#scroller").stellar({
        //                scrollProperty: 'transform',
        //                positionProperty: 'transform',
        //                horizontalScrolling: false,
        //                hideDistantElements: false
        //            });

        //        } else {
        //            $.stellar({
        //                horizontalScrolling: false,
        //                hideDistantElements: false
        //            });
        //        }

        var ua = navigator.userAgent,
            isMobileWebkit = /WebKit/.test(ua) && /Mobile/.test(ua);

        if (!isMobileWebkit) {
            $(document).on('scroll', changeParaState);
        } else {
            $(document).on('touchmove', changeParaState);
        }


        function changeParaState() {
            if ($(document).scrollTop() < 1400) {
                if (!$('.part1 .parawrap').hasClass('inshow')) {
                    $('.parawrap').removeClass('inshow');
                    $('.part1 .parawrap').addClass('inshow');
                }
            } else if ($(document).scrollTop() > 1400 && $(document).scrollTop() < 4000) {
                if (!$('.part2 .parawrap').hasClass('inshow')) {
                    $('.parawrap').removeClass('inshow');
                    $('.part2 .parawrap').addClass('inshow');
                }
            } else if ($(document).scrollTop() > 4000) {
                if (!$('.part3 .parawrap').hasClass('inshow')) {
                    $('.parawrap').removeClass('inshow');
                    $('.part3 .parawrap').addClass('inshow');
                }
            }
        }
        //        $('#part1Waypoint').waypoint({
        //            handler: function (direction) {
        //                $('.parawrap').removeClass('inshow');
        //                if (direction == 'up') {
        //                    $('.part1 .parawrap').addClass('inshow');
        //                } else {
        //                    $('.part2 .parawrap').addClass('inshow');
        //                }
        //            }
        //        });
        //        $('#part2Waypoint').waypoint({
        //            handler: function (direction) {
        //                $('.parawrap').removeClass('inshow');
        //                if (direction == 'up') {
        //                    $('.part2 .parawrap').addClass('inshow');
        //                } else {
        //                    $('.part3 .parawrap').addClass('inshow');
        //                }
        //            }
        //        });
        break;
    case "whichone":
        var series = $('#series');
        var filter = $('#filter');
        var classic = $('#classic');
        var sense = $('#sense');
        var pro = $('#pro');

        $('#classic-btn').on('click', function () {
            Nav.navPush(classic);
        });

        $('#filter-btn').on('click', function () {
            Nav.navPush(filter);
        });

        $('#sense-btn').on('click', function () {
            Nav.navPush(sense);
        });

        $('#pro-btn').on('click', function () {
            Nav.navPush(pro);
        });

        $('#pd-nav-ft').on('click', function () {
            $('#pd-para-container').removeClass('inshow');
            $('#pd-ft-container').addClass('inshow');
            $(this).addClass('active');
            $('#pd-nav-para').removeClass('active');
        });

        $('#pd-nav-para').on('click', function () {
            $('#pd-para-container').addClass('inshow');
            $('#pd-ft-container').removeClass('inshow');
            $(this).addClass('active');
            $('#pd-nav-ft').removeClass('active');
        });

        Nav.init($("#series"));
        break;
    }
});


var Nav = {
    navStack: [],
    init: function (item) {
        this.navStack = [];
        if (item) {
            this.navStack.push(item);
        }
        $('#navBtn').off('click');
        $('#navBtn').on('click', this, this.navClickHandle);
    },
    navPush: function () {
        if (arguments.length == 1) {
            this.navStack[this.navStack.length - 1].addClass('dismiss');
            arguments[0].addClass('inshow');
        } else {
            var lastNav = this.navStack[this.navStack.length - 1];
            if (lastNav.attr('id') == 'product-container') {
                lastNav.addClass('dismiss');
            }
            arguments[0].addClass('dismiss');
            arguments[1].addClass('inshow');
        }
        $.merge(this.navStack, arguments);
        this.changeNavState();
    },
    navPop: function () {

    },
    changeNavState: function () {
        if (this.navStack.length <= 3 || this.navStack.length == 6) {
            $("#navBtn").addClass("upArrow");
        } else {
            $("#navBtn").removeClass('upArrow').addClass('leftArrow');
        }
    },
    navClickHandle: function (event) {
        var self = event.data;
        switch (self.navStack.length) {
        case 2:
            $("#navBtn").removeClass('upArrow');
            /*falls through*/
        case 3:
            var popItem = self.navStack.pop();
            popItem.removeClass('inshow');
            self.navStack[self.navStack.length - 1].removeClass('dismiss');
            break;
        case 4:
            $('#navBtn').removeClass('leftArrow').addClass('upArrow');
            self.navClickHandleCase();
            self.navStack[self.navStack.length - 1].removeClass('dismiss');
            break;
        case 5:
        case 8:
            $('#navBtn').removeClass('leftArrow').addClass('upArrow');
            self.navClickHandleCase();
            break;
        case 6:
            self.navClickHandleCase();
            $('#navBtn').removeClass('upArrow').addClass('leftArrow');
            self.navStack[self.navStack.length - 1].removeClass('dismiss');
            break;
        default:
            $('#nav').addClass('actived');
            $("#navCloseBtn").addClass('actived');
        }
    },
    navClickHandleCase: function () {
        var popItem1 = this.navStack.pop();
        popItem1.removeClass('inshow');
        var popItem2 = this.navStack.pop();
        popItem2.removeClass('dismiss');
        if (popItem2.hasClass('cps-container')) {
            $('.series-nav li').removeClass('active');
        }
    }
};