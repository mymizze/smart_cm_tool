
<aside id="left-panel">

    <!-- 사용자 정보 -->
    <div class="login-info">
        <span> <!-- User image size is adjusted inside CSS, it should stay as it -->

            <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
                <img src="<?=GD_IMAGE_BASE_PATH?>/avatars/sunny.png" alt="me" class="online" />
                <span><?=$session->name?></span>
                <i class="fa fa-angle-down"></i>
            </a>

        </span>
    </div>
    <!--// 사용자 정보  -->

    <!-- 사이드 메뉴 -->
    <nav>
        <ul>
            <li class="<?=$util->compare($page['depth1'],'','active')?>">
                <a href="<?=GD_HOME_PATH?>" title="대쉬보드"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">대쉬보드</span></a>
            </li>
            <li>
                <a href="#"><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i> <span class="menu-item-parent">SmartAdmin Intel</span></a>
                <ul>
                    <li class="">
                        <a href="layouts.html" title="Dashboard"><i class="fa fa-lg fa-fw fa-gear"></i> <span class="menu-item-parent">App Layouts</span></a>
                    </li>
                    <li class="">
                        <a href="skins.html" title="Dashboard"><i class="fa fa-lg fa-fw fa-picture-o"></i> <span class="menu-item-parent">Prebuilt Skins</span></a>
                    </li>
                    <li>
                        <a href="applayout.html"><i class="fa fa-cube"></i> App Settings</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="inbox.html"><i class="fa fa-lg fa-fw fa-inbox"></i> <span class="menu-item-parent">Outlook</span> <span class="badge pull-right inbox-badge margin-right-13">14</span></a>
            </li>
        </ul>
    </nav>
    <!--// 사이드 메뉴 -->

    <!-- 사이드 메뉴 열기/닫기 토글 버튼 -->
    <span class="minifyme" data-action="minifyMenu">
        <i class="fa fa-arrow-circle-left hit"></i>
    </span>
    <!--// 사이드 메뉴 열기/닫기 토글 버튼 -->
</aside>
