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

    <!-- SmartAdmin Styles : Caution! DO NOT change the order -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?=GD_ASSETS_PATH?>/css/smartadmin-production-plugins.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?=GD_ASSETS_PATH?>/css/smartadmin-production.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?=GD_ASSETS_PATH?>/css/smartadmin-skins.min.css">

    <!-- SmartAdmin RTL Support -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?=GD_ASSETS_PATH?>/css/smartadmin-rtl.min.css">

    <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?=GD_ASSETS_PATH?>/css/demo.min.css">

    <!-- #FAVICONS -->
    <link rel="shortcut icon" href="<?=GD_IMAGE_BASE_PATH?>/favicon/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?=GD_IMAGE_BASE_PATH?>/favicon/favicon.ico" type="image/x-icon">

    <!-- #GOOGLE FONT -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

    <!-- Specifying a Webpage Icon for Web Clip
     Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
    <link rel="apple-touch-icon" href="img/splash/sptouch-icon-iphone.png">
    <link rel="apple-touch-icon" sizes="76x76" href="img/splash/touch-icon-ipad.png">
    <link rel="apple-touch-icon" sizes="120x120" href="img/splash/touch-icon-iphone-retina.png">
    <link rel="apple-touch-icon" sizes="152x152" href="img/splash/touch-icon-ipad-retina.png">

    <!-- 사용자 설정 CSS -->
    <link href="<?=GD_ASSETS_PATH?>/css/custom.common.css?time=<?=time()?>" rel="stylesheet">
    <link href="<?=GD_ASSETS_PATH?>/css/custom.css?time=<?=time()?>" rel="stylesheet">

    <!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
    <script data-pace-options='{ "restartOnRequestAfter": true }' src="<?=GD_ASSETS_PATH?>/js/plugin/pace/pace.min.js"></script>

    <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
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

    <!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script>

    <!-- BOOTSTRAP JS -->
    <script src="<?=GD_ASSETS_PATH?>/js/bootstrap/bootstrap.min.js"></script>

    <!-- CUSTOM NOTIFICATION -->
    <script src="<?=GD_ASSETS_PATH?>/js/notification/SmartNotification.min.js"></script>

    <!-- JARVIS WIDGETS -->
    <script src="<?=GD_ASSETS_PATH?>/js/smartwidgets/jarvis.widget.min.js"></script>

    <!-- EASY PIE CHARTS -->
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

    <!-- SPARKLINES -->
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/sparkline/jquery.sparkline.min.js"></script>

    <!-- JQUERY VALIDATE -->
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/jquery-validate/jquery.validate.min.js"></script>

    <!-- JQUERY MASKED INPUT -->
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/masked-input/jquery.maskedinput.min.js"></script>

    <!-- JQUERY SELECT2 INPUT -->
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/select2/select2.min.js"></script>

    <!-- JQUERY UI + Bootstrap Slider -->
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>

    <!-- browser msie issue fix -->
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>

    <!-- FastClick: For mobile devices -->
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/fastclick/fastclick.min.js"></script>

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