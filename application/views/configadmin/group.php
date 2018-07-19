
<script type="text/javascript">
    /**
     * Class: 그룹
     */
    var group = {
        /**
         * 처리: 그룹 정렬 설정 저장
         */
        save: function () {
            var saveData = [];

            // 수정 내역이 없을 경우
            if (this.countUpdate() < 1) {
                alert("수정된 데이터가 없습니다");
                return false;
            }

            // 수정된 데이터만 배열에 담기
            $("#sortable > li").each(function(index, item) {
                if ($(item).attr('data-sort') != $(item).attr('data-new-sort')) {
                    saveData.push({
                        seq: $(item).attr("data-group-seq"),
                        sort: $(item).attr("data-new-sort"),
                    });
                }
            });

            if (confirm("저장 하시겠습니까?")) {
                $.ajax({
                    url: "/configAdmin/groupSortDo",
                    type: "POST",
                    dataType: "json",
                    data: {sort: JSON.stringify(saveData)},
                    beforeSend: function () {
                        // write code here before submit
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log("code : " + xhr.status);
                    },
                    success: function(data, textStatus, xhr) {
                        if (data.status == true) {
                            window.location.reload();
                        } else {
                            alert(data.errMsg);
                            return false;
                        }
                    }
                });
            }
        },

        /**
         * 처리: 신규 그룹 등록
         */
        add: function () {
            var $frm = $("#frmAddGroup");
            var $frmParsley = $frm.parsley();

            // 유효성 검사
            $frmParsley.validate();

            // 신규 그룹 추가 실행
            if ($frmParsley.isValid() && confirm("저장 하시겠습니까?")) {
                $.ajax({
                    url: "/configAdmin/groupAddDo",
                    type: "POST",
                    dataType: "json",
                    data: $frm.serialize(),
                    beforeSend: function () {
                        // write code here before submit
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log("code : " + xhr.status);
                    },
                    success: function(data, textStatus, xhr) {
                        alert(data.errMsg);

                        if (data.status == true) {
                            window.location.reload();
                        } else {
                            return false;
                        }
                    }
                });
            }
        },

        /**
         * 처리: 그룹 삭제
         */
        remove: function () {
            var args = arguments;
            var $obj = $(args[0]);
            var $frm = $obj.closest('form');

            var msgConfirm =
                "그룹 삭제시 그룹내 모든 구성원들의 그룹값이 초기화 되어"+
                "\n페이지 접근권한에 영향을 받게 됩니다"+
                "\n삭제 하시겠습니까?";

            if (confirm(msgConfirm)) {
                $.ajax({
                    url: "/configAdmin/groupRemoveDo",
                    type: "POST",
                    dataType: "json",
                    data: {seq: $("[name=seq]", $frm).val()},
                    beforeSend: function () {
                        // write code here before submit
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log("code : " + xhr.status);
                    },
                    success: function(data, textStatus, xhr) {
                        alert(data.errMsg);

                        if (data.status == true) {
                            window.location.reload();
                        } else {
                            return false;
                        }
                    }
                });
            }
        },

        /**
         * 수정 된 내역 유무 체크
         */
        countUpdate: function () {
            var result = 0;

            $("#sortable > li").each(function(index, item) {
                if ($(item).attr('data-sort') != $(item).attr('data-new-sort')) {
                    result++;
                }
            });
            return result;
        },

        /**
         * 우측 판넬 페이드 화면전환 효과
         */
        fadeAnimRightPanel: function () {
            $('#panel_right').fadeOut('sloow', function() {
                $(this).fadeIn('slow');
            });
        }
    }

    /**
     * Class: 그룹 상세정보
     */
    var detail = {
        /**
         * 검색: 그룹 상세정보 호출
         */
        getDetail: function () {
            var args = arguments;
            var $obj = $(args[0]);
            var $frm = $(document.frmGroupDetail);
            var paneW = ($(window).width() < 992) ? "100%" : "50%";

            // 그룹 구성원 셀렉트박스 설정
            detail.setAdminInGroupList($obj.data('group-seq'));

            $.ajax({
                url: "/configAdmin/getGroupDetail/"+$obj.data('group-seq'),
                type: "POST",
                dataType: "json",
                data: {},
                beforeSend: function () {
                    // write code here before submit
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log("code : " + xhr.status);
                },
                success: function(data, textStatus, xhr) {
                    if (data.seq != undefined) {
                        // 좌,우 판넬 애니메이션 효과
                        if ($("#pane-right").css("display") == "none") {
                            // 메뉴 정렬 좌측 판넬 사이즈 조절
                            $("#pane-left").animate({width: paneW}, 500, function () {
                                // 메뉴 정보수정 우측 판넬 보이기
                                $("#pane-right").fadeIn("slow");
                            });
                        }

                        // 우측 판넬의 각 타이틀에 메뉴명 출력
                        $("[data-panel-head=groupName]").html("- "+data.grName);

                        // 각 항목별 데이터 입력
                        $("[name=seq]", $frm).val(data.seq);
                        $("[name=grName]", $frm).val(data.grName);
                        $("[name=grSummary]", $frm).val(data.grSummary);
                        $("[name=mainMenuSeq]", $frm).val(data.mainMenuSeq);

                        // Switchery 재설정
                        var oIsUse = document.querySelector("[name=isUse]");
                        oIsUse.checked = (data.isUse == "Y") ? true : false;
                        custom.switchery.setStatus(oIsUse);
                    } else {
                        alert("잘못된 그룹 입니다");
                        return false;
                    }
                }
            });


        },

        /**
         * 처리: 그룹 상세정보 수정 저장
         */
        save: function () {
            var $frm = $(document.frmGroupDetail);
            var $frmParsley = $frm.parsley();

            // 메뉴 정렬 수정 내역이 있을 경우
            if (group.countUpdate() > 0) {
                alert("그룹정렬 항목에서 수정된 데이터가 있습니다\n그룹정렬 항목을 먼저 저장 후 진행해주세요");
                return false;
            }

            // 유효성 검사
            $frmParsley.validate();

            // 그룹 상세정보 수정
            if ($frmParsley.isValid() && confirm("저장 하시겠습니까?")) {
                $.ajax({
                    url: "/configAdmin/groupInfoUpdateDo",
                    type: "POST",
                    dataType: "json",
                    data: $frm.serialize(),
                    beforeSend: function () {
                        // write code here before submit
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log("code : " + xhr.status);
                    },
                    success: function(data, textStatus, xhr) {
                        if (data.status == true) {
                            window.location.reload();
                        } else {
                            alert(data.errMsg);
                            return false;
                        }
                    }
                });
            }
            return false;
        },

        /**
         * 설정: 그룹 구성원 셀렉트 박스 설정
         */
        setAdminInGroupList: function (grSeq) {
            $.ajax({
                url: "/configAdmin/getAdminInGroupList",
                type: "POST",
                dataType: "html",
                data: {grSeq: grSeq},
                beforeSend: function () {
                    // write code here before submit
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log("code : " + xhr.status);
                },
                success: function(data, textStatus, xhr) {
                    $("#adSeqs").html(data);

                    // Bootstrap-select 갱신
                    $(".selectpicker").selectpicker('refresh');
                }
            });
        }
    }


    /**
     * Class: 메뉴별 접속 권한
     */
    var access = {
        /**
         * 검색: 메뉴 그룹 목록
         */
        getList: function (grSeq) {
            var $frm = $(document.frmAccess);

            $.ajax({
                url: "/configAdmin/getAccessByMenu/"+grSeq,
                type: "POST",
                dataType: "json",
                data: {},
                beforeSend: function () {
                    // write code here before submit
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log("code : " + xhr.status);
                },
                success: function(data, textStatus, xhr) {
                    var html = "";

                    // 그룹 권한 목록 출력
                    var prevDepth1 = "";

                    $.each(data, function(index, item) {
                        var checkedView = (item.accView == "Y") ? "checked" : "";
                        var checkedModify = (item.accModify == "Y") ? "checked" : "";

                        if (item.depth1 != prevDepth1) {
                            if (index > 0) {
                                html += "</ul>";
                            }

                            html += "<li><div class='pull-left f-w-600 text-primary'>"+item.dep1name+"</div></li>"+
                                    "<ul>";
                        }
                        html +=
                                "<li data-access-seq='"+item.accSeq+"' data-access-view='"+item.accView+"' data-access-modify='"+item.accModify+"'>" +
                                "    <div class='pull-left f-w-600'>"+item.dep2name+"</div>" +
                                "    <div class='pull-right'>" +
                                "        <label>보기</label><input type='checkbox' name='grView' data-render='switchery' data-theme='orange' class='groupSwitch' "+checkedView+" />" +
                                "        <label>수정</label><input type='checkbox' name='grModify' data-render='switchery' data-theme='red' class='groupSwitch' "+checkedModify+" />" +
                                "    </div>" +
                                "</li>";

                        prevDepth1 = item.depth1;
                    });

                    // 지정 영역에 html 입력
                    $("#accessMenuList").html(html);

                    // Switchery 지정 영역 초기화
                    custom.switchery.renderSwitcher('.groupSwitch');
                }
            });
        },

        /**
         * 처리: 그룹 권한 수정 저장
         */
        save: function () {
            var $frm = $(document.frmAccess);
            var saveData = [];

            // 메뉴 정렬 수정 내역이 있을 경우
            if (group.countUpdate() > 0) {
                alert("그룹정렬 항목에서 수정된 데이터가 있습니다\n그룹정렬 항목을 먼저 저장 후 진행해주세요");
                return false;
            }

            // 업데이트 대상 데이터 배열에 입력
            $("ul#accessMenuList > ul li").each(function(index, item) {
                var isView = ($(item).find('[name=grView]').prop("checked")) ? "Y" : "N";
                var isModify = ($(item).find('[name=grModify]').prop("checked")) ? "Y" : "N";


                if ($(item).attr("data-access-view") != isView
                    || $(item).attr("data-access-modify") != isModify) {

                    saveData.push({
                        seq: $(item).attr("data-access-seq"),
                        accView: ($(item).find("[name=grView]").prop("checked")) ? "Y" : "N",
                        accModify: ($(item).find("[name=grModify]").prop("checked")) ? "Y" : "N",
                    });
                }
            });

            if ($(saveData).length < 1) {
                alert("수정된 데이터가 없습니다");
                return false;
            } else {
                if (confirm("저장 하시겠습니까?")) {
                    $.ajax({
                        url: "/configAdmin/accessUpdateDo",
                        type: "POST",
                        dataType: "json",
                        data: {access: JSON.stringify(saveData)},
                        beforeSend: function () {
                            // write code here before submit
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            console.log("code : " + xhr.status);
                        },
                        success: function(data, textStatus, xhr) {
                            alert(data.errMsg);

                            if (data.status == true) {
                                window.location.reload();
                            } else {
                                return false;
                            }
                        }
                    });
                }
            }

        },

        /**
         * 타 그룹 권한 설정 가져오기
         */
        getConfigGroupAccess: function () {
            var $frm = $(document.frmAccess);

            if ($("[name=grSeq]", $frm).val() == "") {
                alert("그룹을 선택해 주세요");
                $("[name=grSeq]", $frm).focus();
                return false;
            }

            this.getList($("[name=grSeq]", $frm).val());
        },

        /**
         * 권한 설정 전체 선택/해제 토글기능
         */
        setSelectAll: function () {
            var $frm = $(document.frmAccess);

            if ($("[type=checkbox]:checked", $frm).length != $("[type=checkbox]", $frm).length) {
                isChecked = false;
            } else {
                isChecked = true;
            }

            $("[type=checkbox]", $frm)
                .prop('checked', isChecked)
                .trigger('click');
        },
    }

    /**
     * 페이지 로딩시 실행 이벤트
     */
    $(function () {
        // Sortable
        $('.sortable')
            .sortable({
                connectWith:    '.sortable',
                cursor:         'move',
                placeholder:    'sortable-placeholder',
                handle:         '.block-title',
                cursorAt:       { left: 150, top: 17 },
                tolerance:      'pointer',
                scroll:         false,
                zIndex:         9999,
                stop: function(e, ui) {
                    // 정렬 순서 변경시 data 속성 sort 값 재설정
                    $("#sortable > li").each(function(index, item) {
                        var sort = index + 1;

                        $(item).attr('data-new-sort', sort);
                    });
                }
            })
            .disableSelection();

        // Switcher
        FormSliderSwitcher.init();

        // Bootstrap-select 갱신
        $(".selectpicker").selectpicker('refresh');
    });

    /**
     * 브라우저 사이즈 조절 이벤트
     */
    // $(window).resize(function(){
    //     // 브라우저 사이즈 변경으로 인한 좌,우 판넬의 레이아웃 균형을 위해 가로 사이즈 재조정
    //     var panelLW = panelRW = ($(window).width() < 992) ? "100%" : "50%";

    //     // 우측 판넬이 없을 경우 좌측 판넬의 사이즈는 브라우저 사이즈 상관없이 100%
    //     panelLW = ($("#pane-right").css('display') == "none") ? "100%" : panelLW;

    //     // 좌,우 판넬 가로 사이즈 적용
    //     $("#pane-left").css("width", panelLW);
    //     $("#pane-right").css("width", panelRW);
    // });
</script>

<div id="content">
    <?php include_once(APPPATH."views/_inc/title.php");?>

    <!-- widget grid -->
    <section id="widget-grid" class="">
        <!-- row -->
        <div class="row">
            <!-- 좌측 판넬 -->
            <article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

                <!-- Widget ID (each widget will need unique ID)-->
                <div class="jarviswidget <?=GD_JARVISWIDGET_COLOR?>" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false">
                    <header>
                        <span class="widget-icon glyphicon glyphicon-align-justify"> <i class="fa fa-comments"></i> </span>
                        <h2>그룹 목록</h2>

                    </header>

                    <!-- widget div-->
                    <div>
                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->
                            <input class="form-control" type="text">
                        </div>
                        <!-- end widget edit box -->

                        <!-- 그룹 목록 -->
                        <div class="widget-body">

                            <form name="frmGroupList" class="form-horizontal form-bordered">
                                <div class="text-right">
                                    <a href="#modal-addgroup" class="btn btn-default" data-toggle="modal">그룹등록</a>
                                    <button type="button" onclick="group.save()" data-ismodify="true" class="btn btn-primary">정렬저장</button>
                                </div>

                                <ul class="groupAccess sortable list-unstyled" id="sortable">
                                    <?
                                    foreach ($groupList as $key => $item) {
                                    ?>
                                    <li onclick="detail.getDetail(this);access.getList($(this).data('group-seq'));group.fadeAnimRightPanel();" data-group-seq="<?=$item['seq']?>" data-sort="<?=$item['sort']?>" data-new-sort="<?=$item['sort']?>">
                                        <div class="block block-title <?=$util->compare($item['isUse'],'N','not-use','bg-silver-lighter')?>"><?=$item['grName']?>: <?=$item['grSummary']?></div>
                                    </li>
                                    <?
                                    }
                                    ?>
                                </ul>
                            </form>

                        </div>
                        <!--// 그룹 목록 -->
                    </div>
                    <!-- end widget div -->
                </div>
                <!-- end widget -->
            </article>
            <!--// 좌측 판넬 -->

            <!-- 우측 판넬 -->
            <article id="panel_right" class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

                <!-- Widget ID (each widget will need unique ID)-->
                <div class="jarviswidget jarviswidget-color-white" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false">
                    <header>
                        <span class="widget-icon glyphicon glyphicon-align-justify"> <i class="fa fa-comments"></i> </span>
                        <h2>그룹 상세정보</h2><h2 data-panel-head="groupName"></h2>

                    </header>

                    <!-- widget div-->
                    <div class="p-0">
                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->
                            <input class="form-control" type="text">
                        </div>
                        <!-- end widget edit box -->

                        <!-- 그룹 상세 정보 -->
                        <div class="widget-body">

                            <form name="frmGroupDetail" class="form-horizontal form-bordered" data-parsley-validate="true">
                                <input type="hidden" name="seq" value="">

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3" for="grName">그룹명</label>
                                    <div class="col-md-8 col-sm-8">
                                        <input class="form-control" type="text" id="grName" name="grName" placeholder="" data-parsley-required="true" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3" for="grSummary">그룹설명</label>
                                    <div class="col-md-8 col-sm-8">
                                        <input class="form-control" type="text" id="grSummary" name="grSummary" placeholder="" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3" for="mainMenuSeq">로그인 후<br>첫페이지</label>
                                    <div class="col-md-8 col-sm-8">
                                        <select id="mainMenuSeq" name="mainMenuSeq" class="form-control">
                                            <option value="" selected>선택 해주세요</option>
                                            <?=$codeToHtml->getSubMenuCodeListSelectBox()?>
                                        </select>
                                        <div class="col-md-12 col-sm-12 p-0 text-primary">
                                            미선택시 관리자 홈으로 기본이동 합니다
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3" for="adSeq">그룹 구성원</label>
                                    <div class="col-md-8 col-sm-8">
                                        <select id="adSeqs" name="adSeqs[]" class="form-control selectpicker" data-size="10" data-live-search="true" data-style="btn-default" multiple>
                                            <!--  Ajax via detail.setAdminInGroupList(그룹시퀀스) -->
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3" for="isUse">사용여부</label>
                                    <div class="col-md-8 col-sm-8">
                                        <div class="col-md-2 col-sm-2 p-l-0">
                                            <input type="checkbox" id="isUse" name="isUse" data-render="switchery" data-theme="blue" />
                                        </div>
                                        <div class="col-md-10 col-sm-10 text-danger">
                                            미사용 설정시 그룹내에 모든 구성원들의 그룹 값이 초기화 됩니다
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group text-center p-10">
                                    <button type="button" onclick="detail.save()" data-ismodify="true" class="btn btn-primary">저장</button>
                                    <button type="button" onclick="group.remove(this)" class="btn btn-danger">삭제</button>
                                </div>
                            </form>

                        </div>
                        <!--// 그룹 상세 정보 -->
                    </div>
                    <!-- end widget div -->
                </div>

                <div class="jarviswidget jarviswidget-color-white" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false">
                    <header>
                        <span class="widget-icon glyphicon glyphicon-align-justify"> <i class="fa fa-comments"></i> </span>
                        <h2>메뉴 접속권한</h2><h2 data-panel-head="groupName"></h2>

                    </header>

                    <!-- widget div-->
                    <div>
                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->
                            <input class="form-control" type="text">
                        </div>
                        <!-- end widget edit box -->

                        <!-- 메뉴 접속권한 -->
                        <div class="widget-body">

                            <form name="frmAccess" class="form-horizontal form-bordered" data-parsley-validate="true">
                                <div class="row">
                                    <div class="col-md-4 p-r-0">
                                        <select name="grSeq" class="form-control">
                                            <option value="">타 그룹 권한 설정 선택</option>
                                            <?=$codeToHtml->getGroupListSelectBox()?>
                                        </select>
                                    </div>
                                    <div class="col-md-2 p-l-2">
                                        <button type="button" onclick="access.getConfigGroupAccess()" class="btn btn-info">가져오기</button>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button type="button" onclick="access.setSelectAll()" class="btn btn-default">전체 선택/해제</button>
                                        <button type="button" onclick="access.save()" data-ismodify="true" class="btn btn-primary">저장</button>
                                    </div>
                                </div>

                                <ul id="accessMenuList" class="li-vertical-list-default">
                                    <!--  Ajax via access.getList() -->
                                </ul>
                            </form>

                        </div>
                        <!--// 메뉴 접속권한 -->
                    </div>
                    <!-- end widget div -->
                </div>
                <!-- end widget -->
            </article>
            <!--// 우측 판넬 -->
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

<!-- Modal: 그룹 추가 -->
<div class="modal fade" id="modal-addgroup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">신규그룹 등록</h4>
            </div>
            <form id="frmAddGroup" name="frmAddGroup" class="form-horizontal form-bordered" data-parsley-validate="true">
                <div class="modal-body p-0">
                    <div class="form-group">
                        <label class="control-label col-md-4">그룹명</label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-8">
                                    <input class="form-control" type="text" id="grNameNew" name="grName" placeholder="" data-parsley-required="true" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">그룹설명</label>
                        <div class="col-md-8">
                            <input class="form-control" type="text" id="grSummaryNew" name="grSummary" placeholder="" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">로그인 후 첫페이지</label>
                        <div class="col-md-8">
                            <select id="mainMenuSeqNew" name="mainMenuSeq" class="form-control">
                                <option value="" selected>선택 해주세요</option>
                                <?=$codeToHtml->getSubMenuCodeListSelectBox()?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">사용여부</label>
                        <div class="col-md-8 col-sm-8">
                            <input type="checkbox" id="isUseNew" name="isUse" data-render="switchery" data-theme="blue" checked="checked" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="group.add()" data-ismodify="true" class="btn btn-primary">저장</button>
                    <a href="javascript:;" class="btn btn-default" data-dismiss="modal">닫기</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!--// Modal: 그룹 추가 -->
