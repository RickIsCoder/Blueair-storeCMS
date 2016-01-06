<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>管理员CMS</title>

    <link rel="stylesheet" href="<?php echo base_url('assets/css/resetStyle.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/common_backend.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/adminCMS.css') ?>">
</head>

<body>
    <div class="mainContainer">

        <!-- left container -->
        <div class="leftContainer">
            <div class="logoWrapper"></div>
            <div class="navContainer">
                <div class="navItemWrapper navItemActived">
                    <img src="<?php echo base_url('assets/images/iconProducts.png'); ?>" alt="Icon" class="navItemIcon">
                    <span>产品库管理</span>
                </div>
                <div class="navItemWrapper">
                    <img src="<?php echo base_url('assets/images/iconUserControl.png'); ?>" alt="Icon" class="navItemIcon">
                    <span>账号管理</span>
                </div>
            </div>
            <div class="backToSaleBtn bgBlueColor">返回销售工具</div>
        </div>

        <!-- right container -->
        <div class="rightContainer">
            <div class="rightTopContainer">
                <span class="userType">你好，<?php if($_SESSION['userType'] == 1) {
                    echo "超级管理员";
                } else {
                    echo "分店管理员";
                } ?></span>
                <span class="button bgBlueColor" id="logoutBtn">登出</span>
                <div class="clearFix"></div>
            </div>
            <div class="topContainerLine"></div>
            <div class="rightMainContainerWrapper">
                <div class="contentSeriesWapper">
                    <div class="contentSeriesContainer">
                        <div class="seriesContainer">
                            <?php foreach ($production as $series): ?>
                                <span class="seriesWrapper <?php if ($series == $production[0]) { echo 'currentSeries';} ?>" id="series<?php echo $series['serieName'] ?>" data-seriesID="<?php echo $series['serieID'] ?>">
                                    <?php echo $series['serieName']; ?>
                                </span>
                            <?php endforeach ?>
                        </div>
                        <div class="seriesBtnContainer">
                            <div class="button bgBlueColor fourTextBtn" id="addProductsBtn">
                                <img class="fourTextBtnIcon" src="<?php echo base_url('assets/images/plusIcon.png'); ?>" alt="plus">添加产品
                            </div>
                            <div class="button bgBlueColor fourTextBtn" id="editSeriesBtn">
                                <img class="fourTextBtnIcon" src="<?php echo base_url('assets/images/plusIcon.png'); ?>" alt="plus">编辑系列
                            </div>
                        </div>
                    </div>
                </div>

                <div class="contentProductsWrapper">
                    <div class="contentProductsContainer">
                        <div class="contentScrollWrapper">
                            <?php foreach ($production as $series): ?>
                            <div class="productsContainer <?php if ($series == $production[0]) { echo 'currentSeries';} ?>" data-seriesID="<?php echo $series['serieID'] ?>">
                                <?php foreach ($series['production'] as $product): ?>
                                <div class="productContainer">
                                    <div class="productImgWrapper">
                                        <img src="<?php $product['pic'][0]; ?>" alt="img" class="productImg">
                                    </div>
                                    <div class="productsInfoWrapper">
                                        <span class="productName"><?php echo $product['productName']; ?></span>
                                        <img class="productIcon" id="productDelete" src="<?php echo base_url('assets/images/deleteIcon.png'); ?>" alt="">
                                        <img class="productIcon" id="productEdit" src="<?php echo base_url('assets/images/editIcon.png'); ?>" alt="">
                                    </div>
                                </div>
                                <?php endforeach ?>
                                <div class="clearFix"></div>
                            </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>

                <!--
                    <div class='productsSeries' data-serieID='<?php echo $serie['serieID']; ?>'>

                        <table>
                            <th><?php echo $serie['serieName']; ?></th>
                            <tr>
                                <td>产品名</td>
                                <td>删除</td>
                                <td>编辑</td>
                            </tr>	

                            <?php foreach ($serie['production'] as $production): ?>
                            <tr class='production' productionID='<?php echo $production['productID']; ?>' >
                                <td><?php echo $production['productName']; ?></td>
                                <td><button class='production-del-btn'>删除产品</button></td>
                                <td>
                                    <a href="<?php echo site_url('pd/'.$production['productID']); ?>"><button>编辑</button></a> 
                                    <a href="<?php echo site_url('feature/'.$production['productID']); ?>"><button>编辑特性</button></a>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </table>
                        <hr>
                    </div>
    -->

                <!--
                    <div id='addSerie'>
                        系列名:<input type="text" id='serieName'>
                        <button class='serie-add-btn'>添加系列</button>
                    </div>
                    <hr>
                    <a id='addProduction' href="<?php echo site_url('pd'); ?>"><button>添加产品</button></a>
                    <span id='tips'></span>
    -->
            </div>
        </div>

        <div class="clearFix"></div>
        
        <div class="alertContainerWrapper">
            <div class="alertConatiner">
                <div class="addProductsAlertWrapper">
                    <div class="addProductsHeaderContainer">
                        <div class="addProductsHeaderItemWrapper positionLeftZero">
                            <p class="addProductsTitle">机型</p>
                            <input type="text" id="addProductName" class="addProductName addProductInputType">
                        </div>
                        <div class="addProductsHeaderItemWrapper positionRightZero">
                            <p class="addProductsTitle">系列</p>
                            <select class="addProductSelectSeries">
                                <option >
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="addProductsContentContainer">
                        <div class="addProductsContentNavWrapper">
                            <div class="addProductsNavItem addProductActivedNavItem addProductsTitle" id="addProductNavFeature">特点</div>
                            <div class="addProductsNavItem addProductsTitle" id="addProductNavParameter">参数</div>
                            <div class="addProductsNavItem addProductsTitle" id="addProductNavImg">图片</div>
                        </div>
                        <div class="addProductsContentWrapper">
                            <div class="addProductScrollWrapper">
                                <div class="addProductContentCharater addProductContentWrapper">
                                    <p class="addProductsTitle">一句话描述</p>
                                    <input type="text" class="addProductInputType" id="addProductBrief">
                                    <p class="addProductsTitle">标签</p>
                                    <input type="text" class="addProductInputType" id="addProductTags">
                                    <span class="button bgBlueColor" id="addProductSaveTagBtn">保存</span>
                                    <div class="addProductTagsWrapper"></div>
                                    <p class="addProductsTitle">概述</p>
                                    <textarea class="addProductInputType" id="addProductDescription"></textarea>
                                    <p class="addProductsTitle">特性</p>
                                    <div class="addProductCharacterWrapper"></div>
                                    <input type="text" placeholder="特性标题" class="addProductInputType" id="addProductCharacterTitle">
                                    <textarea class="addProductInputType" placeholder="特性描述" id="addProductCharacter"></textarea>
                                    <div class="button bgBlueColor" id="addProductCharaterCancel">取消</div>
                                    <div class="button bgBlueColor" id="addProductCharterSave">保存特性</div>
                                    <div class="clearFix"></div>
                                </div>
                                <div class="addProductContentParameter addProductContentWrapper addProductActivedContent">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="产地:">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="质保:">
                                    <input type="text" class="addProductParameterInputType addProductInputType" id="addProductParameterQuality" placeholder="注册质保:">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="高(mm):">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="宽(mm):">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="厚(mm):">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="重量(kg):">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="颜色:">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="电缆线长度:">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="滚轮:">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="滤网寿命(月):">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="烟雾:">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="灰尘:">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="花粉:">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="标定面积(m²):">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="风挡:">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="循环风量:">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="换气次数:">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="耗电:">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="噪音水平:">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="内置滤网:">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="附赠:">
                                    <input type="text" class="addProductParameterInputType addProductInputType addProductParameterInputShort" placeholder="可选配件:">
                                    <div class="clearFix"></div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="button bgBlueColor" id="addProductSaveBtn">保存此产品</div>
                        <div class="button bgBlueColor" id="addProductCancelBtn">取消</div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <script type="text/javascript" src="<?php echo base_url('assets/js/jquery.js') ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/backEndAction.js') ?>"></script>
</body>

</html>