var Products = {
    init: function () {
        var self = this;
        $(function () {
            $.post('getStoreProductions', function (data) {
                self.handleGetAllProducts(data);
            }, 'json');
        });
    },
    productsData: [],
    handleGetAllProducts: function (data) {
        var self = this;
        this.productsData = data;

        var senseNav = $('#sense-nav');
        var proNav = $('#pro-nav');
        var classicData = data[0].production;
        var proData = data[1].production;
        var senseData = data[2].production;
        var insertHtml = "";

        var classic200Html = "";
        var classic300Html = "";
        var classic400Html = "";
        var classic500Html = "";
        var classic600Html = "";
        var classic200 = $("#classic200-nav");
        var classic300 = $("#classic300-nav");
        var classic400 = $("#classic400-nav");
        var classic500 = $("#classic500-nav");
        var classic600 = $("#classic600-nav");

        for (var j = 0; j < classicData.length; j++) {
            var itemName = classicData[j].productName;
            switch (itemName.replace(/[^0-9]/ig, "").substr(0, 1)) {
            case "2":
                classic200Html += "<li data-seriesindex=" + 0 + " data-index=" + j + ">" + itemName + "</li>";
                break;
            case "3":
                classic300Html += "<li data-seriesindex=" + 0 + " data-index=" + j + ">" + itemName + "</li>";
                break;
            case "4":
                classic400Html += "<li data-seriesindex=" + 0 + " data-index=" + j + ">" + itemName + "</li>";
                break;
            case "5":
                classic500Html += "<li data-seriesindex=" + 0 + " data-index=" + j + ">" + itemName + "</li>";
                break;
            case "6":
                classic600Html += "<li data-seriesindex=" + 0 + " data-index=" + j + ">" + itemName + "</li>";
                break;
            }
        }
        classic200.html(classic200Html);
        classic300.html(classic300Html);
        classic400.html(classic400Html);
        classic500.html(classic500Html);
        classic600.html(classic600Html);

        $("#classic200").on('click', function () {
            $('.series-nav ul').removeClass('active');
            $('.series-nav img').removeClass('active');
            classic200.addClass("active");
            $("#classic200 img").addClass("active");
        });
        $("#classic300").on('click', function () {
            $('.series-nav ul').removeClass('active');
            $('.series-nav img').removeClass('active');
            classic300.addClass("active");
            $("#classic300 img").addClass("active");
        });
        $("#classic400").on('click', function () {
            $('.series-nav ul').removeClass('active');
            $('.series-nav img').removeClass('active');
            classic400.addClass("active");
            $("#classic400 img").addClass("active");
        });
        $("#classic500").on('click', function () {
            $('.series-nav ul').removeClass('active');
            $('.series-nav img').removeClass('active');
            classic500.addClass("active");
            $("#classic500 img").addClass("active");
        });
        $("#classic600").on('click', function () {
            $('.series-nav ul').removeClass('active');
            $('.series-nav img').removeClass('active');
            classic600.addClass("active");
            $("#classic600 img").addClass("active");
        });


        insertHtml = "";
        for (var i = 0; i < proData.length; i++) {
            insertHtml += "<li data-seriesindex=" + 1 + " data-index='" + i + "'>" + proData[i].productName + "</li>";
        }
        proNav.html(insertHtml);

        insertHtml = "";
        for (var k = 0; k < senseData.length; k++) {
            insertHtml += "<li data-seriesindex=" + 2 + " data-index='" + k + "'>" + senseData[k].productName + "</li>";
        }
        senseNav.html(insertHtml);

        $(".series-nav").on('click', 'li', this.handleProductItem);

        $('#seri-contrast').on('click', function () {
            // 首页进入机型对比
            Nav.navPush($('#compare-list-container'));
            self.setComparelist(data);
        });

        $('.jumpto-compare').on('click', function () {
            // 详细页进入机型对比
            var comparePage = $('#compare-list-container');
            if (Nav.navStack.length == 4) {
                // 点开了详细页
                Nav.navPush($(this).parents('.container'), $('#compare-list-container'));
            } else {
                Nav.navPush($('#compare-list-container'));
            }
            self.setComparelist(data);
        });

    },
    handleProductItem: function (event) {
        var self = Products;
        var productContainer = $('#product-container');
        var curSeriesIndex = $(this).data("seriesindex");
        var curIndex = $(this).data("index");
        var product = self.productsData[curSeriesIndex].production[curIndex];

        $('#compare-list-container').data({
            "seriesindex": curSeriesIndex,
            "index": curIndex
        });

        if (self.checkIfChecked(curSeriesIndex, curIndex)) {
            $('#addToContrast').addClass('check');
        } else {
            $('#addToContrast').removeClass('check');
        }

        $('#addToContrast').off('click');
        $('#addToContrast').on('click', function () {
            if ($(this).hasClass('check')) {
                $(this).removeClass('check');
                self.removeCheckItem(curSeriesIndex, curIndex);
            } else {
                $(this).addClass('check');
                var array = [curSeriesIndex, curIndex];
                self.checkedItem.push(array);
                if (self.checkedItem.length > 3) {
                    self.checkedItem.shift();
                }
            }
        });
        if (Nav.navStack.length != 4) {
            Nav.navPush($('.cps-container'), $('#product-container'));
        }

        $('.series-nav li').removeClass('active');
        $(this).addClass('active');

        self.setProductInfo(product);
    },
    setProductInfo: function (data) {
        $('#pd-title').html(data.productName);
        $('#pd-label').html(data.label);

        var tipHtml = "";
        for (var tipi = 0; tipi < data.tips.length; tipi++) {
            tipHtml += "<div class='pd-tip'>" + data.tips[tipi].tips + "</div>";
        }
        $('#pd-tips').html(tipHtml);

        var featureHtml = "";
        featureHtml += '<p id="pd-description" class="subp">' + data.description + '</p>';
        featureHtml += '<div id="pd-img-container"><div class="pd-img-scroll">';
        for (var pici = 0; pici < data.pic.length; pici++) {
            featureHtml += '<img class="pd-img" src="' + data.pic[pici].pic_path + '"  alt="img" />';
        }
        featureHtml += '</div></div>';
        featureHtml += '<div id="pd-features" class="row">';
        for (var fti = 0; fti < data.feature.length; fti++) {
            if (fti == 3) {
                featureHtml += '</div><div id="pd-features" class="row">';
            }
            featureHtml += '<div class="pd-ft"><div class="pt-ft-title">' + data.feature[fti].featureTitle + '</div><div class="subp">' + data.feature[fti].featureContent + '</div></div>';
        }
        featureHtml += '</div>';
        $('#pd-ft-container').html(featureHtml);

        // parameter
        $('#para-area').html(data.area);
        $('#para-CADR_PM').html(data.CADR_PM);
        $('#para-CADR_oxymethylene').html(data.CADR_oxymethylene);
        $('#para-CCM_PM').html(data.CCM_PM);
        $('#para-level_PM').html(data.level_PM);
        $('#para-level_oxymethylene').html(data.level_oxymethylene);
        $('#para-size').html(data.height + '*' + data.width + '*' + data.thickness);
        $('#para-noiseAcousticalPower').html(data.noiseAcousticalPower);
        $('#para-noiseSoundPressure').html(data.noiseSoundPressure);
        $('#para-power_consume').html(data.power_consume);
        $('#para-wheel').html(data.wheel);
        $('#para-producting_area').html(data.producting_area);
        $('#para-warranty').html(data.warranty);
        $('#para-particleFilter').html(data.particleFilter);
        $('#para-combineFilter').html(data.combineFilter);
        $('#para-charcoalFilter').html(data.charcoalFilter);
        $('#para-filter_num').html(data.filter_num);
        $('#para-filter').html(data.filter);
        if (data.airIntelligent == null) {
            $('#pd-para-sensor').addClass('dismiss');
        } else {
            $('#para-airIntelligent').html(data.airIntelligent);
        }
    },
    checkedItem: [],
    setComparelist: function (data) {
        var self = this;
        var cplistHtml = "";
        var cplist = $('#cplist-pd-slide');
        for (var cplisti = 0; cplisti < data.length; cplisti++) {
            for (var cplistj = 0; cplistj < data[cplisti].production.length; cplistj++) {
                var checkinfo = this.checkIfChecked(cplisti, cplistj) ? " check" : "";
                var imgPath = data[cplisti].production[cplistj].pic[0] ? data[cplisti].production[cplistj].pic[0].pic_path : "";
                cplistHtml += '<div role="button" class="cplist-pd-item' + checkinfo + '" data-seriesindex=' + cplisti + ' data-index=' + cplistj + '>';
                cplistHtml += '<img class="cplist-img" src="' + imgPath + '" alt="productImg">';
                cplistHtml += '<div class="cplist-img-mask"></div>';
                cplistHtml += '<div class="cplist-img-check"></div>';
                cplistHtml += '<p class="text-center">' + data[cplisti].production[cplistj].productName + '</p></div>';
            }
        }
        cplist.html(cplistHtml);
        cplist.off('click', '.cplist-pd-item');
        cplist.on('click', '.cplist-pd-item', function () {
            var cpList = $('#compare-list-container');
            var cpListSer = cpList.data('seriesindex');
            var cpListIndex = cpList.data('index');
            var thisSeries = $(this).data('seriesindex');
            var thisIndex = $(this).data('index');

            if ($(this).hasClass('check')) {
                $(this).removeClass('check');
                self.removeCheckItem(thisSeries, thisIndex);
                if (cpListSer == thisSeries && cpListIndex == thisIndex) {
                    $('#addToContrast').removeClass('check');
                }
            } else {
                $(this).addClass('check');
                if (cpListSer == thisSeries && cpListIndex == thisIndex) {
                    $('#addToContrast').addClass('check');
                }
                var array = [$(this).data('seriesindex'), $(this).data('index')];
                self.checkedItem.push(array);
                if (self.checkedItem.length > 3) {
                    var cplistItem = $('.cplist-pd-item');
                    for (var cpitemi = 0; cpitemi < cplistItem.length; cpitemi++) {
                        if ($(cplistItem[cpitemi]).hasClass('check') && $(cplistItem[cpitemi]).data('seriesindex') == self.checkedItem[0][0] && $(cplistItem[cpitemi]).data('index') == self.checkedItem[0][1]) {
                            $(cplistItem[cpitemi]).removeClass('check');
                        }
                    }
                    self.checkedItem.shift();
                }
            }
        });

        $('#cplist-gotocompare').off('click');
        $('#cplist-gotocompare').on('click', function () {
            Nav.navPush($('#compare-list-container'), $('#compare-container'));

            $('.cp-tb tr td:not(:first-child)').remove();

            for (var cki = 0; cki < self.checkedItem.length; cki++) {
                var product = self.productsData[self.checkedItem[cki][0]].production[self.checkedItem[cki][1]];
                var titleHtml = '<td><div class="cplist-pd-item">';
                titleHtml += '<img class="cplist-img" src="' + product.pic[0].pic_path + '" alt="product">';
                titleHtml += '<div class="cplist-img-mask"></div>';
                titleHtml += '<div class="text-center">' + product.productName + '</div></div></td>';
                $('#cp-title').append(titleHtml);
                $('#cp-area').append('<td>' + product.area + '</td>');
                $('#cp-CADR_PM').append('<td>' + product.CADR_PM + '</td>');
                $('#cp-CADR_oxymethylene').append('<td>' + product.CADR_oxymethylene + '</td>');
                $('#cp-CCM_PM').append('<td>' + product.CCM_PM + '</td>');
                $('#cp-level_PM').append('<td>' + product.level_PM + '</td>');
                $('#cp-level_oxymethylene').append('<td>' + product.level_oxymethylene + '</td>');
                $('#cp-size').append('<td>' + product.height + '*' + product.width + '*' + product.thickness + '</td>');
                $('#cp-noiseAcousticalPower').append('<td>' + product.noiseAcousticalPower + '</td>');
                $('#cp-noiseSoundPressure').append('<td>' + product.noiseSoundPressure + '</td>');
                $('#cp-power_consume').append('<td>' + product.power_consume + '</td>');
                $('#cp-wheel').append('<td>' + product.wheel + '</td>');
                $('#cp-producting_area').append('<td>' + product.producting_area + '</td>');
                $('#cp-warranty').append('<td>' + product.warranty + '</td>');
                $('#cp-particleFilter').append('<td>' + product.particleFilter + '</td>');
                $('#cp-combineFilter').append('<td>' + product.combineFilter + '</td>');
                $('#cp-charcoalFilter').append('<td>' + product.charcoalFilter + '</td>');
                $('#cp-filter_num').append('<td>' + product.filter_num + '</td>');
                $('#cp-filter').append('<td>' + product.filter + '</td>');
            }
        });
    },
    checkIfChecked: function (seriesIndex, index) {
        if (this.checkedItem.length > 0) {
            for (var checki = 0; checki < this.checkedItem.length; checki++) {
                if (this.checkedItem[checki][0] == seriesIndex && this.checkedItem[checki][1] == index) {
                    return true;
                }
            }
            return false;
        }
    },
    removeCheckItem: function (seriesIndex, index) {
        for (var checki = 0; checki < this.checkedItem.length; checki++) {
            if (seriesIndex == this.checkedItem[checki][0] && index == this.checkedItem[checki][1]) {
                this.checkedItem.splice(checki, 1);
                return;
            }
        }
    }
};

Products.init();