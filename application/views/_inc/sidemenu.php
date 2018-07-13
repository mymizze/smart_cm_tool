
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
                <a href="<?=GD_HOME_PATH?>" title="관리자홈">
                    <i class="fa fa-lg fa-fw fa-home"></i>
                    <span class="menu-item-parent">관리자홈</span>
                </a>
            </li>
            <?
            # 메뉴 목록 출력(접근 권한 없는 메뉴 제외)
            $prevDepth1 = "";
            foreach ($menuList as $key => $item) {
                // 같은 1단계에 포함된 2단계 메뉴 출력하다가 다음 1단계 메뉴가 나온 경우
                if ($item['depth1'] != $prevDepth1) {
                    // 하단 막는 태그를 선처리 하므로 첫번째 동작을 제외하고 실행
                    if ($key > 0) {
            ?>
                </ul>
            </li>
            <?
                    }

                    // 현재 선택된 메뉴 depth1 활성화 여부
                    $currActive1 = ($item['depth1'] == $page['depth1']) ? "active" : "";
                    $iconClass = ($item['icon'] == "") ? "fa-fw fa-desktop" : $item['icon'];
            ?>
            <li class="<?=$currActive1?>">
                <a href="#">
                    <i class="fa fa-lg fa-fw <?=$iconClass?>"></i>
                    <span class="menu-item-parent"><?=$item['dep1name']?></span>
                </a>
                <ul>
            <?
                }

                // 현재 선택된 메뉴 depth2 활성화 여부
                $currActive2 = ($currActive1 == "active" && $item['depth2'] == $page['depth2']) ? "active" : "";

                // 메뉴 depth
                $params = array(
                    'depth1' => $item['depth1'],
                    'depth2' => $item['depth2'],
                );
                $params = http_build_query($params);

                // 링크 경로 설정
                $linkUrl = GD_HOME_PATH.$item['linkUrl']."?".$params.$item['linkParams'];
            ?>
                    <li class="<?=$currActive2?>">
                        <a href="<?=$linkUrl?>" title="<?=$item['dep2name']?>">
                            <span class="menu-item-parent"><?=$item['dep2name']?></span>
                        </a>
                    </li>
            <?
                $prevDepth1 = $item['depth1'];
            }

            # 2단계 메뉴 하단 태그는 강제로 출력을 해야하나 데이터가 전혀 없는 경우는 제외
            if (count($menuList) > 0) {
            ?>
                </ul>
            </li>
            <?
            }
            ?>
        </ul>
    </nav>
    <!--// 사이드 메뉴 -->

    <!-- 사이드 메뉴 열기/닫기 토글 버튼 -->
    <span class="minifyme" data-action="minifyMenu">
        <i class="fa fa-arrow-circle-left hit"></i>
    </span>
    <!--// 사이드 메뉴 열기/닫기 토글 버튼 -->
</aside>
