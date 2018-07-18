
<!-- #HEADER -->
<header id="header">
    <div id="logo-group">

        <!-- 로고 -->
        <span id="logo">
            <a href="<?=GD_HOME_PATH?>">
                <img src="<?=GD_IMAGE_BASE_PATH?>/logo.png" alt="SmartAdmin">
            </a>
        </span>
        <!--// 로고 -->

        <!-- Note: The activity badge color changes when clicked and resets the number to 0
             Suggestion: You may want to set a flag when this happens to tick off all checked messages / notifications -->
        <span id="activity" class="activity-dropdown"> <i class="fa fa-user"></i> <b class="badge"> 21 </b> </span>

        <!-- AJAX-DROPDOWN : control this dropdown height, look and feel from the LESS variable file -->
        <div class="ajax-dropdown">

            <!-- the ID links are fetched via AJAX to the ajax container "ajax-notifications" -->
            <div class="btn-group btn-group-justified" data-toggle="buttons">
                <label class="btn btn-default">
                    <input type="radio" name="activity" id="ajax/notify/mail.html">
                    Msgs (14) </label>
                <label class="btn btn-default">
                    <input type="radio" name="activity" id="ajax/notify/notifications.html">
                    notify (3) </label>
                <label class="btn btn-default">
                    <input type="radio" name="activity" id="ajax/notify/tasks.html">
                    Tasks (4) </label>
            </div>

            <!-- notification content -->
            <div class="ajax-notifications custom-scroll">

                <div class="alert alert-transparent">
                    <h4>Click a button to show messages here</h4>
                    This blank page message helps protect your privacy, or you can show the first message here automatically.
                </div>

                <i class="fa fa-lock fa-4x fa-border"></i>

            </div>
            <!-- end notification content -->

            <!-- footer: refresh area -->
            <!-- <span> Last updated on: 12/12/2013 9:43AM
                <button type="button" data-loading-text="<i class='fa fa-refresh fa-spin'></i> Loading..." class="btn btn-xs btn-default pull-right">
                    <i class="fa fa-refresh"></i>
                </button> </span> -->
            <!-- end footer -->

        </div>
        <!-- END AJAX-DROPDOWN -->
    </div>
<script type="text/javascript">
    $(function () {
        $('.project-context [data-toggle=dropdown]').hover(function(){
          $(this).trigger('click');
        });
    })
</script>
<style>
    .project-context .badge {
        font-size: 10px;
        font-weight: normal;
    }
    .project-context li b.badge {
        margin-top: -5px;
    }
</style>
    <!-- #PROJECTS: projects dropdown -->
    <div class="project-context hidden-xs">

        <span class="label">QUICK <b class="badge bg-color-red bounceIn animated"> 7 </b></span>
        <span class="project-selector dropdown-toggle" data-toggle="dropdown">신청현황 <i class="fa fa-angle-down"></i></span>

        <!-- Suggestion: populate this list with fetch and push technique -->
        <ul class="dropdown-menu">
            <li>
                <a href="javascript:void(0);">충전신청 <b class="badge bg-color-red bounceIn animated"> 5 </b></a>
            </li>
            <li>
                <a href="javascript:void(0);">환전신청 <b class="badge bg-color-red bounceIn animated"> 2 </b></a>
            </li>
            <li>
                <a href="javascript:void(0);">총판환전 <b class="badge bg-color-red bounceIn animated"> 0 </b></a>
            </li>
        </ul>
        <!-- end dropdown-menu-->
    </div>
    <!-- end projects dropdown -->

    <!-- #PROJECTS: projects dropdown -->
    <div class="project-context hidden-xs">

        <span class="label">QUICK <b class="badge bg-color-red bounceIn animated"> 3 </b></span>
        <span class="project-selector dropdown-toggle" data-toggle="dropdown">고객센터 <i class="fa fa-angle-down"></i></span>

        <!-- Suggestion: populate this list with fetch and push technique -->
        <ul class="dropdown-menu">
            <li>
                <a href="javascript:void(0);">회원가입 <b class="badge bg-color-red bounceIn animated"> 2 </b></a>
            </li>
            <li>
                <a href="javascript:void(0);">1:1문의 <b class="badge bg-color-red bounceIn animated"> 0 </b></a>
            </li>
        </ul>
        <!-- end dropdown-menu-->
    </div>
    <!-- end projects dropdown -->

    <!-- #PROJECTS: projects dropdown -->
    <div class="project-context hidden-xs">

        <span class="label">QUICK <b class="badge bg-color-red bounceIn animated"> 5 </b></span>
        <span class="project-selector dropdown-toggle" data-toggle="dropdown">배팅현황 <i class="fa fa-angle-down"></i></span>

        <!-- Suggestion: populate this list with fetch and push technique -->
        <ul class="dropdown-menu">
            <li>
                <a href="javascript:void(0);">자동경기1 <b class="badge bg-color-red bounceIn animated"> 3 </b></a>
            </li>
            <li>
                <a href="javascript:void(0);">고액배팅(스포츠) <b class="badge bg-color-red bounceIn animated"> 2 </b></a>
            </li>
            <li>
                <a href="javascript:void(0);">고액배팅(미니게임) <b class="badge bg-color-red bounceIn animated"> 1 </b></a>
            </li>
            <li>
                <a href="javascript:void(0);">배팅알람대상ID <b class="badge bg-color-red bounceIn animated"> 0 </b></a>
            </li>
        </ul>
        <!-- end dropdown-menu-->
    </div>
    <!-- end projects dropdown -->


    <!-- #TOGGLE LAYOUT BUTTONS -->
    <!-- pulled right: nav area -->
    <div class="pull-right">

        <!-- collapse menu button -->
        <div id="hide-menu" class="btn-header pull-right">
            <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
        </div>
        <!-- end collapse menu -->

        <!-- #MOBILE -->
        <!-- Top menu profile link : this shows only when top menu is active -->
        <ul id="mobile-profile-img" class="header-dropdown-list hidden-xs padding-5">
            <li class="">
                <a href="#" class="dropdown-toggle no-margin userdropdown" data-toggle="dropdown">
                    <img src="<?=GD_IMAGE_BASE_PATH?>/avatars/sunny.png" alt="<?=$session->name?>" class="online" />
                </a>
                <ul class="dropdown-menu pull-right">
                    <li>
                        <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0"><i class="fa fa-cog"></i> Setting</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="profile.html" class="padding-10 padding-top-0 padding-bottom-0"> <i class="fa fa-user"></i> <u>P</u>rofile</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="toggleShortcut"><i class="fa fa-arrow-down"></i> <u>S</u>hortcut</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i> Full <u>S</u>creen</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="login.html" class="padding-10 padding-top-5 padding-bottom-5" data-action="userLogout"><i class="fa fa-sign-out fa-lg"></i> <strong><u>L</u>ogout</strong></a>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- 로그아웃 버튼 -->
        <div id="logout" class="btn-header transparent pull-right">
            <span> <a href="/logout" title="Sign Out" data-action="userLogout" data-logout-msg="브라우저를 닫기 전에 로그아웃을 하면 보안상 더욱 안전합니다."><i class="fa fa-sign-out"></i></a> </span>
        </div>
        <!--// 로그아웃 버튼 -->

        <!-- search mobile button (this is hidden till mobile view port) -->
        <!-- <div id="search-mobile" class="btn-header transparent pull-right">
            <span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
        </div> -->
        <!-- end search mobile button -->

        <!-- 검색 -->
        <!-- <form action="search.html" class="header-search pull-right">
            <input id="search-fld" type="text" name="param" placeholder="Find reports and more">
            <button type="submit">
                <i class="fa fa-search"></i>
            </button>
            <a href="javascript:void(0);" id="cancel-search-js" title="Cancel Search"><i class="fa fa-times"></i></a>
        </form> -->
        <!--// 검색 -->

        <!-- 전체화면 버튼 -->
        <!-- <div id="fullscreen" class="btn-header transparent pull-right">
            <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
        </div> -->
        <!--// 전체화면 버튼 -->

        <!-- 언어선택 -->
        <!-- <ul class="header-dropdown-list hidden-xs">
            <li>
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <img src="<?=GD_IMAGE_BASE_PATH?>/blank.gif" class="flag flag-us" alt="United States"> <span> English (US) </span> <i class="fa fa-angle-down"></i> </a>
                <ul class="dropdown-menu pull-right">
                    <li class="active">
                        <a href="javascript:void(0);"><img src="<?=GD_IMAGE_BASE_PATH?>/blank.gif" class="flag flag-us" alt="United States"> English (US)</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);"><img src="<?=GD_IMAGE_BASE_PATH?>/blank.gif" class="flag flag-fr" alt="France"> Français</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);"><img src="<?=GD_IMAGE_BASE_PATH?>/blank.gif" class="flag flag-es" alt="Spanish"> Español</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);"><img src="<?=GD_IMAGE_BASE_PATH?>/blank.gif" class="flag flag-de" alt="German"> Deutsch</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);"><img src="<?=GD_IMAGE_BASE_PATH?>/blank.gif" class="flag flag-jp" alt="Japan"> 日本語</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);"><img src="<?=GD_IMAGE_BASE_PATH?>/blank.gif" class="flag flag-cn" alt="China"> 中文</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);"><img src="<?=GD_IMAGE_BASE_PATH?>/blank.gif" class="flag flag-it" alt="Italy"> Italiano</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);"><img src="<?=GD_IMAGE_BASE_PATH?>/blank.gif" class="flag flag-pt" alt="Portugal"> Portugal</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);"><img src="<?=GD_IMAGE_BASE_PATH?>/blank.gif" class="flag flag-ru" alt="Russia"> Русский язык</a>
                    </li>
                    <li>
                        <a href="javascript:void(0);"><img src="<?=GD_IMAGE_BASE_PATH?>/blank.gif" class="flag flag-kr" alt="Korea"> 한국어</a>
                    </li>

                </ul>
            </li>
        </ul> -->
        <!--// 언어선택 -->

    </div>
    <!-- end pulled right: nav area -->

</header>
<!-- END HEADER -->
