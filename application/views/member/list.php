
<script type="text/javascript">
    /**
     * 회원목록 기능
     */
    var member = {
        /**
         * 키워드 검색
         */
        search: function () {
            document.frmSearch.submit();
        },

        /**
         * 상태별 검색
         */
        searchStatus: function (status) {
            var $frm = $(document.frmSearch);

            $("input[name=status]", $frm).val(status);
            $frm.submit();
        },

        /**
         * 계정종류별 검색
         */
        searchAccountType: function (type) {
            var $frm = $(document.frmSearch);

            $("input[name=accountType]", $frm).val(type);
            $frm.submit();
        }
    }

    /**
     * 페이지 로딩시 실행 이벤트
     */
    $(function () {
        // 커서 첫 포커스
        $(document.frmSearch).find('input[name=searchKeyword]').focus();
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
                                    <form name="frmSearch" method="post" onsubmit="member.search(); return false;">
                                        <input type="hidden" name="currPage" value="<?=$search['currPage']?>">
                                        <input type="hidden" name="status">
                                        <input type="hidden" name="accountType">

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
                                                        <button class="btn btn-default" type="button" onclick="member.search()">검색</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </article>
                                <article class="col-xs-12 col-sm-5 col-md-5 col-lg-4 text-right">
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
                                    <table class="table table-bordered">
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
                                                <th class="txt">닉네임</th>
                                                <th class="txt">가입일</th>
                                                <th class="txt">최근접속일</th>
                                                <th class="txt">추천인</th>
                                                <th class="txt">이달수익</th>
                                                <th class="txt">누적수익</th>
                                                <th class="txt">캐쉬</th>
                                                <th class="txt">포인트</th>
                                                <th class="txt">배팅내역</th>
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
                                                        $fontColor = "";
                                                        $badgeColor = "";
                                                        break;
                                                }
                                            ?>
                                            <tr>
                                                <td class="txt <?=$fontColor?>"><b class="badge <?=$badgeColor?> bounceIn animated p-3" style="font-size: 3px; margin-top: -3px"> <?=$item->level?> </b> <?=$item->nickname?></td>
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
                                    <article class="col-xs-12 col-sm-5 col-md-4 col-lg-4">
                                        <button type="button" class="btn btn-default">엑셀(전체)</button>
                                        <button type="button" class="btn btn-default">엑셀(활동중)</button>
                                    </article>

                                    <article class="col-xs-12 col-sm-7 col-md-8 col-lg-8 text-right">
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
