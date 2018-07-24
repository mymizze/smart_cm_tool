
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
                                    <form name="frmSearch">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-4">
                                                <select name="" class="form-control">
                                                    <option value="">닉네임</option>
                                                    <option value="">아이디</option>
                                                    <option value="">계좌</option>
                                                    <option value="">예금주</option>
                                                    <option value="">권한</option>
                                                    <option value="">이메일</option>
                                                    <option value="">전화번호</option>
                                                    <option value="">핸드폰번호</option>
                                                    <option value="">포인트</option>
                                                    <option value="">가입일시</option>
                                                    <option value="">최근접속일시</option>
                                                    <option value="">IP</option>
                                                    <option value="">추천인</option>
                                                </select>
                                            </div>
                                            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-6">
                                                <div class="input-group">
                                                    <input class="form-control" id="appendbutton" type="text">
                                                    <div class="input-group-btn">
                                                        <button class="btn btn-default" type="button">검색</button>
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
                                <article class="col-xs-12 col-sm-7 col-md-7 col-lg-6">
                                    <ul class="inline-type">
                                        <li class="">전체: <span>2,846</span></li>
                                        <li class="">정상: <span>1,554</span></li>
                                        <li class="txt-color-darkyellow">차단: <span>1,554</span></li>
                                        <li class="txt-color-darkred">탈퇴: <span>1,229</span></li>
                                        <li class="">임시: <span>8</span></li>
                                    </ul>
                                </article>
                                <article class="col-xs-12 col-sm-7 col-md-7 col-lg-6">
                                    <ul class="inline-type text-right">
                                        <li class="">전체회원보유머니: <span>20,171,351</span></li>
                                        <li class="">정상: <span>2,856,829</span></li>
                                    </ul>
                                </article>
                            </div>

                            <!-- 회원 목록 -->
                            <div class="table-responsive m-t-10">
                                <form name="frmMember" class="smart-form">
                                    <table class="table table-bordered">
                                        <colgroup>
                                            <col width="30">
                                            <col width="140">
                                            <col width="90">
                                            <col width="90">
                                            <col width="100">
                                            <col width="*">
                                            <col width="*">
                                            <col width="*">
                                            <col width="*">
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <td><label class="checkbox"><input type="checkbox" name="checkbox"><i></i></label></td>
                                                <th class="txt">닉네임</th>
                                                <th class="txt">가입일</th>
                                                <th class="txt">최근접속일</th>
                                                <th class="txt">추천인</th>
                                                <th class="txt">이달수익</th>
                                                <th class="txt">누적수익</th>
                                                <th class="txt">캐쉬</th>
                                                <th class="txt">포인트</th>
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
                                                <td><label class="checkbox"><input type="checkbox" name="checkbox"><i></i></label></td>
                                                <td class="txt <?=$fontColor?>"><b class="badge <?=$badgeColor?> bounceIn animated p-3" style="font-size: 3px; margin-top: -3px"> <?=$item->level?> </b> <?=$item->nickname?></td>
                                                <td class="txt"><?=$util->switchTodayDateToTime($item->regDate)?></td>
                                                <td class="txt"><?=$item->lastLogin?></td>
                                                <td class="txt"><?=$item->affiliatedId?></td>
                                                <td class="txt"><span class="">0</span></td>
                                                <td class="txt"><span class="">0</span></td>
                                                <td class="txt"><span class="">0</span></td>
                                                <td class="txt"><span class="">0</span></td>
                                            </tr>
                                            <?
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </form>

                                <div class="row m-t-10">
                                    <article class="col-xs-12 col-sm-5 col-md-5 col-lg-6">
                                        <button type="button" class="btn btn-success">선택수정</button>
                                        <button type="button" class="btn btn-warning">회원차단</button>
                                        <button type="button" class="btn btn-danger">회원탈퇴</button>
                                        <button type="button" class="btn btn-default">엑셀(전체)</button>
                                        <button type="button" class="btn btn-default">엑셀(정상)</button>
                                    </article>

                                    <article class="col-xs-12 col-sm-5 col-md-5 col-lg-6 text-right">
                                        <ul class="pagination pagination m-0">
                                            <li>
                                                <a href="javascript:void(0);"><i class="fa fa-angle-double-left"></i></a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);"><i class="fa fa-angle-left"></i></a>
                                            </li>
                                            <li class="active">
                                                <a href="javascript:void(0);">1</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">2</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">3</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">4</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">5</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">6</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">7</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">8</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);">9</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);"><i class="fa fa-angle-right"></i></a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);"><i class="fa fa-angle-double-right"></i></a>
                                            </li>
                                        </ul>
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
