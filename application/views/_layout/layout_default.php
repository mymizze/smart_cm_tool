<!DOCTYPE html>
<html lang="ko-kr">
<head>
    <meta charset="utf-8">
    <title><?=$adminSiteInfo->title?></title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <meta http-equiv="p3p" content='CP="CAO DSP AND SO " policyref="/w3c/p3p.xml"'>
    <meta name="title" content="TOTO Standard" />
    <meta name="author" content="TOTO Standard" />
    <meta name="description" content="TOTO Standard" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- #CSS Links -->
    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?=GD_ASSETS_PATH?>/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?=GD_ASSETS_PATH?>/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?=GD_ASSETS_PATH?>/css/smartadmin-production-plugins.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?=GD_ASSETS_PATH?>/css/smartadmin-production.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?=GD_ASSETS_PATH?>/css/smartadmin-skins.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?=GD_ASSETS_PATH?>/css/demo.min.css">
    <link rel="shortcut icon" href="<?=GD_IMAGE_BASE_PATH?>/favicon/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?=GD_IMAGE_BASE_PATH?>/favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
    <link href="<?=GD_ASSETS_PATH?>/js/plugin/switchery/switchery.min.css" rel="stylesheet" />
    <link rel="apple-touch-icon" href="img/splash/sptouch-icon-iphone.png">
    <link rel="apple-touch-icon" sizes="76x76" href="img/splash/touch-icon-ipad.png">
    <link rel="apple-touch-icon" sizes="120x120" href="img/splash/touch-icon-iphone-retina.png">
    <link rel="apple-touch-icon" sizes="152x152" href="img/splash/touch-icon-ipad-retina.png">

    <!-- 사용자 설정 CSS -->
    <link href="<?=GD_ASSETS_PATH?>/css/custom.common.css?time=<?=time()?>" rel="stylesheet">
    <link href="<?=GD_ASSETS_PATH?>/css/custom.css?time=<?=time()?>" rel="stylesheet">


    <script data-pace-options='{ "restartOnRequestAfter": true }' src="<?=GD_ASSETS_PATH?>/js/plugin/pace/pace.min.js"></script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        if (!window.jQuery) {
            document.write('<script src="<?=GD_ASSETS_PATH?>/js/libs/jquery-3.2.1.min.js"><\/script>');
        }
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script>
        if (!window.jQuery.ui) {
            document.write('<script src="<?=GD_ASSETS_PATH?>/js/libs/jquery-ui.min.js"><\/script>');
        }
    </script>
    <script src="<?=GD_ASSETS_PATH?>/js/libs/jquery-migrate-1.1.0.min.js"></script>
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script>
    <script src="<?=GD_ASSETS_PATH?>/js/bootstrap/bootstrap.min.js"></script>
    <script src="<?=GD_ASSETS_PATH?>/js/notification/SmartNotification.min.js"></script>
    <script src="<?=GD_ASSETS_PATH?>/js/smartwidgets/jarvis.widget.min.js"></script>
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/sparkline/jquery.sparkline.min.js"></script>
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/jquery-validate/jquery.validate.min.js"></script>
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/masked-input/jquery.maskedinput.min.js"></script>
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/select2/select2.min.js"></script>
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/fastclick/fastclick.min.js"></script>
    <script src="<?=GD_ASSETS_PATH?>/js/form-slider-switcher.demo.min.js"></script>
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/switchery/switchery.min.js"></script>
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/parsley/dist/parsley.js"></script>

    <!--[if IE 8]>
    <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
    <![endif]-->

    <!-- 사용자 설정 JS -->
    <script src="<?=GD_ASSETS_PATH?>/js/custom.bootstrap.js?time=<?=time()?>"></script>
    <script src="<?=GD_ASSETS_PATH?>/js/function.system.js?time=<?=time()?>"></script>
    <script src="<?=GD_ASSETS_PATH?>/js/function.util.js?time=<?=time()?>"></script>
    <script src="<?=GD_ASSETS_PATH?>/js/function.ui.js?time=<?=time()?>"></script>

    <script>
        $(document).ready(function() {
            // 페이지 로딩 완료시 페이지 로딩 스피너 삭제
            $("#page_spinner").remove();

            // 페이지 셋업
            pageSetUp();

            /**
             * Parsley: hidden 영역일 경우 required 설정이 되어있어도 메세지 출력이 되지 않도록 설정
             */
            $.listen('parsley:field:validated', function(fieldInstance){
                if (fieldInstance.$element.is(":hidden")) {
                    // hide the message wrapper
                    fieldInstance._ui.$errorsWrapper.css('display', 'none');
                    // set validation result to true
                    fieldInstance.validationResult = true;
                    return true;
                }
            });
        });
    </script>
</head>

<body class="">

    <!-- 헤더 -->
    <?php include(APPPATH."views/_inc/header.php");?>
    <!--// 헤더 -->

    <!-- 사이드바 GNB -->
    <?php include(APPPATH."views/_inc/sidemenu.php");?>
    <!--// 사이드바 GNB -->

    <!-- 메인 판넬 -->
    <div id="main" role="main">
        <!-- 페이지 제목 및 경로정보 -->
        <?php include(APPPATH."views/_inc/breadcrumb.php");?>
        <!--// 페이지 제목 및 경로정보 -->

        <!-- 페이지 로딩 스피너 -->
        <div style="padding-left:20px">
            <h1 id="page_spinner" class="ajax-loading-animation"><i class="fa fa-cog fa-spin"></i> Loading...</h1>
        </div>
        <!--// 페이지 로딩 스피너 -->

        <!-- 컨텐츠 -->
        {yield}
        <!--// 컨텐츠 -->
    </div>
    <!--// 메인판넬 -->

    <!-- 푸터 -->
    <?php include(APPPATH."views/_inc/footer.php");?>
    <!--// 푸터 -->

    <!-- 숏컷 영역: 사용자명 클릭시 큰 사이즈 메뉴 -->
    <?php include(APPPATH."views/_inc/shortcut.php");?>
    <!--// 숏컷 영역 -->


    <!-- IMPORTANT: APP CONFIG -->
    <script src="<?=GD_ASSETS_PATH?>/js/app.config.js"></script>

    <!-- Demo purpose only -->
    <script src="<?=GD_ASSETS_PATH?>/js/demo.min.js?time=<?=time()?>"></script>

    <!-- MAIN APP JS FILE -->
    <script src="<?=GD_ASSETS_PATH?>/js/app.min.js"></script>
</body>
</html>