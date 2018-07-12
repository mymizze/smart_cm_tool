<!DOCTYPE html>
<html lang="en-us" id="extr-page">
<head>
    <meta charset="utf-8">
    <title>TOTO Standard v2.0</title>
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
    <link rel="shortcut icon" href="<?=GD_ASSETS_PATH?>/img/favicon/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?=GD_ASSETS_PATH?>/img/favicon/favicon.ico" type="image/x-icon">

    <!-- #GOOGLE FONT -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

    <!-- 사용자 설정 CSS -->
    <link href="<?=GD_ASSETS_PATH?>/css/custom.common.css?time=<?=time()?>" rel="stylesheet">
    <link href="<?=GD_ASSETS_PATH?>/css/custom.css?time=<?=time()?>" rel="stylesheet">

    <!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/pace/pace.min.js"></script>

    <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script> if (!window.jQuery) { document.write('<script src="<?=GD_ASSETS_PATH?>/js/libs/jquery-3.2.1.min.js"><\/script>');} </script>

    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script> if (!window.jQuery.ui) { document.write('<script src="<?=GD_ASSETS_PATH?>/js/libs/jquery-ui.min.js"><\/script>');} </script>

    <!-- IMPORTANT: APP CONFIG -->
    <script src="<?=GD_ASSETS_PATH?>/js/app.config.js"></script>

    <!-- JS TOUCH : include this plugin for mobile drag / drop touch events
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

    <!-- BOOTSTRAP JS -->
    <script src="<?=GD_ASSETS_PATH?>/js/bootstrap/bootstrap.min.js"></script>

    <!-- JQUERY VALIDATE -->
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/jquery-validate/jquery.validate.min.js"></script>

    <!-- JQUERY MASKED INPUT -->
    <script src="<?=GD_ASSETS_PATH?>/js/plugin/masked-input/jquery.maskedinput.min.js"></script>

    <!--[if IE 8]>

        <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

    <![endif]-->

    <!-- MAIN APP JS FILE -->
    <script src="<?=GD_ASSETS_PATH?>/js/app.min.js"></script>

    <!-- 사용자 설정 JS -->
    <script src="<?=GD_ASSETS_PATH?>/js/custom.bootstrap.js?time=<?=time()?>"></script>
    <script src="<?=GD_ASSETS_PATH?>/js/function.system.js?time=<?=time()?>"></script>
    <script src="<?=GD_ASSETS_PATH?>/js/function.util.js?time=<?=time()?>"></script>
    <script src="<?=GD_ASSETS_PATH?>/js/function.ui.js?time=<?=time()?>"></script>
</head>

<body class="animated fadeInDown">

    {yield}

</body>
</html>