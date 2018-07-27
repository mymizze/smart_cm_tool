
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">회원 정보</h4>
            </div>
            <form id="frmNewAccount" name="frmNewAccount" class="smart-form" novalidate="novalidate">
                <fieldset>
                    <div class="row">
                        <section class="col col-6">
                            <label class="label">비밀번호</label>
                            <label class="input">
                                <i class="icon-append fa fa-lock"></i>
                                <input type="password" id="adminPw" name="adminPw" maxlength="20">
                            </label>
                        </section>
                        <section class="col col-6">
                            <label class="label">비밀번호 확인</label>
                            <label class="input">
                                <i class="icon-append fa fa-lock"></i>
                                <input type="password" name="adminPwConfirm" maxlength="20">
                            </label>
                        </section>
                    </div>

                    <section>
                        <label class="label">메모</label>
                        <label class="textarea">
                            <i class="icon-append fa fa-comment"></i>
                            <textarea rows="4" name="memo"></textarea>
                        </label>
                    </section>
                </fieldset>

                <footer>
                    <button type="submit" data-ismodify="true" class="btn btn-primary">등록</button>
                </footer>
            </form>
        </div>
    </div>
</div>
<!--// Modal: 회원 상세정보 -->