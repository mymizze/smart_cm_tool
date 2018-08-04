
<script type="text/javascript">
    /**
     * 회원목록 기능
     */
    var member = {
        /**
         * 키워드 검색
         */
        searchKeyword: function () {
            var $frm = $(document.frmSearch);

            $("input[name=status]", $frm).val('');
            $("input[name=accountType]", $frm).val('');

            $frm.attr("action", "");
            $frm.submit();
        },

        /**
         * 상태별 검색
         */
        searchStatus: function (status) {
            var $frm = $(document.frmSearch);

            $("input[name=searchKeyword]", $frm).val('');
            $("input[name=accountType]", $frm).val('');
            $("input[name=status]", $frm).val(status);

            $frm.attr("action", "");
            $frm.submit();
        },

        /**
         * 계정종류별 검색
         */
        searchAccountType: function (type) {
            var $frm = $(document.frmSearch);

            $("input[name=searchKeyword]", $frm).val('');
            $("input[name=status]", $frm).val('');
            $("input[name=accountType]", $frm).val(type);

            $frm.attr("action", "");
            $frm.submit();
        },

        /**
         * Excel 다운로드
         */
        excelDown: function () {
            var $frm = $(document.frmSearch);

            $frm.attr("action", "/member/exceldown");
            $frm.submit();
        }
    }

    /**
     * 페이지 로딩시 실행 이벤트
     */
    $(function () {
        // 커서 첫 포커스
        $(document.frmSearch).find('input[name=searchKeyword]').focus();

        // 검색 키워드 엔터 이벤트
        $("[name=searchKeyword]").on('keypress', function(event) {
            if (event.keyCode == 13) {
                member.searchKeyword();
            }
        });
    })
</script>

<div id="content">
    <?php include_once(APPPATH."views/_inc/title.php");?>

    <!-- widget grid -->
    <section id="widget-grid" class="">
        <!-- row -->
        <div class="row">
            <!-- NEW WIDGET START -->
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <!-- Widget ID (each widget will need unique ID)-->
                <div class="jarviswidget jarviswidget-color-white" id="wid-id-0" data-widget-editbutton="false" data-widget-colorbutton="false">
                    <header>
                        <span class="widget-icon glyphicon glyphicon-align-justify"> <i class="fa fa-table"></i> </span>
                        <h2>회원 목록</h2>
                    </header>

                    <!-- widget div-->
                    <div>
                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->
                        </div>
                        <!-- end widget edit box -->

                        <!-- widget content -->
                        <div class="widget-body">

                            <!-- 검색 및 회원등록 -->
                            <div class="row">
                                <article class="col-xs-12 col-sm-7 col-md-7 col-lg-8">
                                    <form name="frmSearch" method="post">
                                        <input type="hidden" name="currPage" value="<?=$search['currPage']?>">
                                        <input type="hidden" name="status" value="<?=$search['status']?>">
                                        <input type="hidden" name="accountType" value="<?=$search['accountType']?>">

                                        <div class="row">
                                            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-4">
                                                <select name="searchType" class="form-control">
                                                    <option value="nickname" <?=$util->compare($search['searchType'],'nickname','selected')?>>닉네임</option>
                                                    <option value="userId" <?=$util->compare($search['searchType'],'userId','selected')?>>아이디</option>
                                                    <option value="bankNumber" <?=$util->compare($search['searchType'],'bankNumber','selected')?>>계좌</option>
                                                    <option value="bankDepositName" <?=$util->compare($search['searchType'],'bankDepositName','selected')?>>예금주</option>
                                                    <option value="level" <?=$util->compare($search['searchType'],'level','selected')?>>레벨</option>
                                                    <option value="email" <?=$util->compare($search['searchType'],'email','selected')?>>이메일</option>
                                                    <option value="phone" <?=$util->compare($search['searchType'],'phone','selected')?>>핸드폰번호</option>
                                                    <option value="point" <?=$util->compare($search['searchType'],'point','selected')?>>포인트</option>
                                                    <option value="regDate" <?=$util->compare($search['searchType'],'regDate','selected')?>>가입일시</option>
                                                    <option value="lastLogin" <?=$util->compare($search['searchType'],'lastLogin','selected')?>>최근접속일시</option>
                                                    <option value="ip" <?=$util->compare($search['searchType'],'ip','selected')?>>IP</option>
                                                    <option value="affiliatedId" <?=$util->compare($search['searchType'],'affiliatedId','selected')?>>추천인</option>
                                                </select>
                                            </div>
                                            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-6">
                                                <div class="input-group">
                                                    <input type="text" name="searchKeyword" class="form-control" value="<?=$search['searchKeyword']?>">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-default" type="button" onclick="member.searchKeyword()">검색</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </article>
                                <article class="col-xs-12 col-sm-5 col-md-5 col-lg-4 text-right">
                                    <button type="button" class="btn btn-default" onclick="member.excelDown()">엑셀다운</button>
                                    <button type="button" class="btn btn-primary" onclick="util.move('/member/write?<?=$page['depth']?>')">회원등록</button>
                                    <button type="button" class="btn btn-default" onclick="util.move('<?=$page['currentUrl']?>')">목록</button>
                                </article>
                            </div>
                            <!--// 검색 및 회원등록 -->

                            <div class="row m-t-10">
                                <article class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
                                    <ul class="inline-type">
                                        <li><a href="javascript:member.searchStatus('')" class="txt-color-grayDark">전체: <span><?=number_format($count->total)?></span></a></li>
                                        <li><a href="javascript:member.searchStatus(1)" class="txt-color-grayDark">활동중: <span><?=number_format($count->active)?></span></a></li>
                                        <li><a href="javascript:member.searchStatus(2)" class="txt-color-blue">일시정지: <span><?=number_format($count->pending)?></span></a></li>
                                        <li><a href="javascript:member.searchStatus(3)" class="txt-color-darkyellow">사용제한: <span><?=number_format($count->block)?></span></a></li>
                                        <li><a href="javascript:member.searchStatus(4)" class="txt-color-darkred">탈퇴: <span><?=number_format($count->withdraw)?></span></a></li>
                                        <li><a href="javascript:member.searchAccountType(2)" class="txt-color-grayDark">임시: <span><?=number_format($count->temp)?></span></a></li>
                                    </ul>
                                </article>
                                <article class="col-xs-12 col-sm-7 col-md-7 col-lg-5">
                                    <ul class="inline-type text-right">
                                        <li class=""><b>전체회원캐쉬:</b> <span><?=number_format($count->cashTotal)?></span></li>
                                        <li class=""><b>활동회원캐쉬:</b> <span><?=number_format($count->cashActive)?></span></li>
                                    </ul>
                                </article>
                            </div>

                            <!-- 회원 목록 -->
                            <div class="table-responsive m-t-10">
                                <form name="frmMember" class="smart-form">
                                    <table class="table table-bordered tbl-bgcolor-lightgray-even">
                                        <colgroup>
                                            <col width="140">
                                            <col width="90">
                                            <col width="90">
                                            <col width="100">
                                            <col width="*">
                                            <col width="*">
                                            <col width="*">
                                            <col width="*">
                                            <col width="80">
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th class="txt p-t-15 p-b-15">닉네임</th>
                                                <th class="txt p-t-15 p-b-15">가입일</th>
                                                <th class="txt p-t-15 p-b-15">최근접속일</th>
                                                <th class="txt p-t-15 p-b-15">추천인</th>
                                                <th class="txt p-t-15 p-b-15">이달수익</th>
                                                <th class="txt p-t-15 p-b-15">누적수익</th>
                                                <th class="txt p-t-15 p-b-15">캐쉬</th>
                                                <th class="txt p-t-15 p-b-15">포인트</th>
                                                <th class="txt p-t-15 p-b-15">배팅내역</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?
                                            foreach ($memberList as $key => $item) {
                                                switch ($item->blacklistType) {
                                                    case 2:
                                                        $fontColor = "txt-color-darkyellow";
                                                        $badgeColor = "bg-color-yellow";
                                                        break;
                                                    case 3:
                                                        $fontColor = "txt-color-darkred";
                                                        $badgeColor = "bg-color-red";
                                                        break;

                                                    default:
                                                        $fontColor = "txt-color-grayDark";
                                                        $badgeColor = "";
                                                        break;
                                                }
                                            ?>
                                            <tr>
                                                <td class="txt"><a href="#modal-viewaccount" data-toggle="modal" class="<?=$fontColor?>"><b class="badge <?=$badgeColor?> bounceIn animated p-3 f-s-10" style="margin-top: -3px"> <?=$item->level?> </b> <?=$item->nickname?></a></td>
                                                <td class="txt"><?=$util->switchTodayDateToTime($item->regDate)?></td>
                                                <td class="txt"><?=$util->switchTodayDateToTime($item->lastLogin)?></td>
                                                <td class="txt"><?=$item->affiliatedId?></td>
                                                <td class="txt"><span class=""><?=number_format($item->profitMonth)?></span></td>
                                                <td class="txt"><span class=""><?=number_format($item->profitCumulative)?></span></td>
                                                <td class="txt"><span class=""><?=number_format($item->cash)?></span></td>
                                                <td class="txt"><span class=""><?=number_format($item->point)?></span></td>
                                                <td class="txt"><a href="javascript:;" class="btn btn-default btn-padding-default">보기</a></td>
                                            </tr>
                                            <?
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </form>

                                <div class="row m-t-10">
                                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                                        <?=$pagingHTML?>
                                    </article>
                                </div>
                            </div>
                            <!--// 회원 목록 -->
                        </div>
                        <!-- end widget content -->
                    </div>
                    <!-- end widget div -->
                </div>
                <!-- end widget -->
            </article>
            <!-- WIDGET END -->
        </div>
        <!-- end row -->

        <!-- row -->
        <div class="row">
            <!-- a blank row to get started -->
            <div class="col-sm-12">
                <!-- your contents here -->
            </div>
        </div>
        <!-- end row -->
    </section>
    <!-- end widget grid -->
</div>

<!-- Modal: 회원 상세정보 -->
<div class="modal fade" id="modal-viewaccount">
    <div class="modal-dialog" style="width:700px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">회원 상세정보</h4>
            </div>
            <div class="modal-body">
                <form id="frmNewAccount" name="frmNewAccount" class="form-horizontal form-bordered">
                    <table class="table table-bordered popup-table">
                        <colgroup>
                            <col width="120">
                            <col width="230">
                            <col width="120">
                            <col width="230">
                        </colgroup>
                        <tbody>
                            <tr>
                                <td class="title">아이디</td>
                                <td><input type="text" name="userId" value="" maxlength="20" class="form-control"></td>
                                <td class="title">닉네임</td>
                                <td><input type="text" name="nickname" value="" maxlength="6" class="form-control"></td>
                            </tr>
                            <tr>
                                <td class="title">비밀번호</td>
                                <td><input type="password" name="userPw" value="" maxlength="20" class="form-control"></td>
                                <td class="title">출금비밀번호</td>
                                <td><input type="password" name="exchangePw" value="" maxlength="20" class="form-control"></td>
                            </tr>
                            <tr>
                                <td class="title">추천인</td>
                                <td><input type="text" name="affiliatedId" value="" maxlength="20" class="form-control"></td>
                                <td class="title">레벨</td>
                                <td>
                                    <select name="level" class="form-control">
                                        <option value="0">승인대기</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="title">계정종류</td>
                                <td>
                                    <select name="status" class="form-control">
                                        <option value="">선택해주세요</option>
                                        <?=$codeToHtml->getCodeListSelectBox('accountType')?>
                                    </select>
                                </td>
                                <td class="title">계정상태</td>
                                <td>
                                    <select name="status" class="form-control">
                                        <option value="">선택해주세요</option>
                                        <?=$codeToHtml->getCodeListSelectBox('useraccountstatus')?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="title">블랙리스트</td>
                                <td>
                                    <select name="status" class="form-control">
                                        <option value="">선택해주세요</option>
                                        <?=$codeToHtml->getCodeListSelectBox('blacklist')?>
                                    </select>
                                </td>
                                <td class="title"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td class="title">하위레벨</td>
                                <td colspan="3">
                                    <div class="min-height">
                                        <ul class="inline-type">
                                            <li><b>인원:</b> <span>0</span>명</li>
                                            <li><b>총지급:</b> <span>0</span>원</li>
                                            <li><b>총배팅:</b> <span>0</span>원</li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="title">소속</td>
                                <td colspan="3">
                                    <table class="table table-bordered m-0">
                                        <thead>
                                            <tr>
                                                <th class="title bg-color-magnesium">대본</th>
                                                <th class="title bg-color-silver">부본</th>
                                                <th class="title bg-color-mercury">총판</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td class="title">가입일</td>
                                <td>
                                    <div class="min-height">2018-07-10 12:00:00</div>
                                </td>
                                <td class="title">최근접속</td>
                                <td><span>2018-07-10 12:00:00</span></td>
                            </tr>
                            <tr>
                                <td class="title">단폴더배팅</td>
                                <td>
                                    <input type="checkbox" id="bet_1">
                                    <label for="bet_1">단폴불가</label>

                                    <input type="checkbox" id="bet_2">
                                    <label for="bet_2">투폴불가</label>

                                    <input type="checkbox" id="bet_3">
                                    <label for="bet_3">투폴하락</label>
                                </td>
                                <td class="title">게시판작성권한</td>
                                <td>
                                    <select name="" class="form-control">
                                        <option value="1">가능</option>
                                        <option value="2">불가능</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="title">총배팅횟수</td>
                                <td>
                                    <div class="min-height">0</div>
                                </td>
                                <td class="title">새글 및 댓글</td>
                                <td>0 / 0</td>
                            </tr>
                            <tr>
                                <td class="title">최초가입IP</td>
                                <td>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-7 p-t-6">
                                            <span>111.222.333.44</span>
                                        </div>
                                        <div class="col-sm-12 col-md-4 text-right">
                                            <button type="button" class="btn btn-default" onclick="">접속로그</button>
                                        </div>
                                    </div>
                                </td>
                                <td class="title">접속IP</td>
                                <td>
                                    <div>
                                        <span>222.222.222.222</span>
                                        <span>(대한민국 <img class="flag flag-kr" src="<?=GD_IMAGE_BASE_PATH?>/blank.gif" alt="Korea, Republic of">)</span>
                                    </div>
                                    <div>
                                        <span>로그인 횟수 100회</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="title">미니게임제한</td>
                                <td colspan="3">

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<!--// Modal: 회원 상세정보 -->