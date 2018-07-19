
<script type="text/javascript">
    /**
     * Class: 공통코드 항목
     */
    var code = {
        /**
         * 그룹코드 기본 값
         */
        groupCode: "<?=$defaultGroupCode?>",

        /**
         * 그룹명
         */
        groupName: "<?=$defaultGroupName?>",

        /**
         * 항목별 목록 호출
         */
        getCodeList: function (groupCode) {
            $.ajax({
                url: "/configSystem/getCodeList/"+groupCode,
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
                    var txtGroupCode = "";
                    var txtGroupName = "";

                    // 지정 항목 Html 목록 작성
                    $.each(data, function(index, item) {
                        html +=
                            "<tr data-code-seq='"+item.seq+"' data-old-itemname='"+item.itemName+"' data-old-itemsort='"+item.itemSort+"' data-old-isuse='"+item.isUse+"' data-new-itemsort='"+(index+1)+"'>"+
                            "    <td class='vertical-middle text-right'>"+(index+1)+"</td>"+
                            "    <td class='vertical-middle text-right'>"+item.itemCode+"</td>"+
                            "    <td class='vertical-middle'>"+
                            "       <input type='text' name='itemName' class='form-control' value='"+item.itemName+"'>"+
                            "    </td>"+
                            "    <td class='vertical-middle text-center'>"+
                            "        <input type='checkbox' name='isUse' data-render='switchery' data-theme='blue' "+util.compare(item.isUse,'Y','checked')+" />"+
                            "    </td>"+
                            "    <td class='vertical-middle text-center'>"+
                            "        <input type='checkbox' name='isDel' data-render='switchery' data-theme='red' />"+
                            "    </td>"+
                            "</tr>";

                        txtGroupCode = item.groupCode;
                        txtGroupName = item.groupName;
                    });

                    // 객체내의 기본 그룹코드 값 변경
                    code.groupCode = txtGroupCode;
                    code.groupName = txtGroupName;

                    // Html 입력
                    $("[data-groupcode="+groupCode+"] table tbody").html(html);

                    // 검색어 초기화
                    $("[name=searchWord]", $("#frmSearchCode")).val('');

                    // 그룹 고유코드 텍스트 변경
                    $("#txtGroupCode").text(txtGroupCode);

                    // Switchery 지정 영역 초기화
                    custom.switchery.renderSwitcher('[data-render="switchery"]');
                }
            });
        },

        /**
         * 항목명 검색(필터링)
         */
        search: function (keyword) {
            if (keyword != "") {
                $("tbody td input[name=itemName]:not([value*="+keyword+"])")
                    .closest('tr')
                    .hide();

                $("tbody td input[name=itemName][value*="+keyword+"]")
                    .closest('tr')
                    .show();
            } else {
                $("tbody td input[name=itemName]")
                    .closest('tr')
                    .show();
            }
        },

        /**
         * 항목 신규등록
         */
        add: function () {
            var html = "";

            html +=
                "<tr data-code-seq='new'>"+
                "    <td class='vertical-middle text-right'><span class='text-warning'>New</span></td>"+
                "    <td class='vertical-middle text-right'><span class='text-warning'>New</span></td>"+
                "    <td class='vertical-middle'>"+
                "       <input type='text' name='itemName' class='form-control' value=''>"+
                "    </td>"+
                "    <td class='vertical-middle text-center'>"+
                "        <input type='checkbox' name='isUse' data-render='switchery' data-theme='blue' checked />"+
                "    </td>"+
                "    <td class='vertical-middle text-center'></td>"+
                "</tr>";
            $("[data-groupcode="+this.groupCode+"] table tbody").prepend(html);

            // Switchery 지정 영역 초기화
            custom.switchery.renderSwitcher("[data-groupcode="+this.groupCode+"] table tbody tr[data-code-seq=new] [data-render=switchery]", 0);
        },

        /**
         * 항목 신규등록 및 내용수정 저장
         */
        save: function () {
            var $oItemList = $("[data-groupcode="+this.groupCode+"] table tbody");
            var saveData = {
                new: [],
                edit: []
            };
            var newData = [];
            var editData = [];

            // 신규 등록 대상 데이터 배열에 입력
            $.each($oItemList.find('tr[data-code-seq=new]'), function(index, item) {
                // 사용 유무 값 Y/N 변환
                var isChecked = util.compare($(item).find('[name=isUse]').is(":checked"),true,"Y","N")
                var itemName = $.trim($(item).find('[name=itemName]').val());

                // 항목명이 비었을 경우 저장 제외
                if (itemName == "") { return true; }

                newData.push({
                    groupCode: code.groupCode,
                    itemName: itemName,
                    isUse: isChecked,
                });
            });

            // 수정 대상 데이터 배열에 입력
            $.each($oItemList.find('tr[data-code-seq!=new]'), function(index, item) {
                // 사용 유무 값 Y/N 변환
                var isChecked = util.compare($(item).find('[name=isUse]').is(":checked"),true,"Y","N")
                var itemName = $.trim($(item).find('[name=itemName]').val());

                // 항목명이 비었을 경우 저장 제외
                if (itemName == ""
                    || ($(item).attr('data-old-itemname')==$(item).find('[name=itemName]').val()
                        && $(item).attr('data-old-isuse')==isChecked)
                        && $(item).attr('data-old-itemsort')==$(item).attr('data-new-itemsort') ) {

                    return true;
                }

                editData.push({
                    seq: $(item).attr('data-code-seq'),
                    itemName: itemName,
                    itemSort: $(item).attr('data-new-itemsort'),
                    isUse: isChecked,
                });
            });

            // 저장 및 수정할 데이터 수 체크
            if (newData.length < 1 && editData.length < 1) {
                alert("저장할 데이터가 없습니다");
                return false;
            } else {
                // 신규 데이터 입력
                if (newData.length > 0) {
                    // 신규등록 데이터 입력
                    saveData.new = newData;
                }

                // 수정 데이터 입력
                if (editData.length > 0) {
                    saveData.edit = editData;
                }
            }

            if (confirm("저장 하시겠습니까?")) {
                $.ajax({
                    url: "/configSystem/codeUpdateDo",
                    type: "POST",
                    dataType: "json",
                    data: {saveData: JSON.stringify(saveData)},
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
         * 항목 삭제
         */
        remove: function () {
            var $oItemList = $("[data-groupcode="+this.groupCode+"] table tbody");
            var removeData = [];

            // 삭제 대상 데이터 배열에 입력
            $.each($oItemList.find('tr[data-code-seq!=new] [name=isDel]:checked'), function(index, item) {
                removeData.push($(item).closest('tr').attr('data-code-seq'));
            });

            if (removeData.length < 1) {
                alert("삭제할 대상의 삭제여부 버튼을 활성화 해주세요");
                return false;
            }

            if (confirm("삭제 하시겠습니까?")) {
                $.ajax({
                    url: "/configSystem/codeRemoveDo",
                    type: "POST",
                    dataType: "json",
                    data: {seqs: removeData},
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
    }

    /**
     * Class: 공통코드 그룹
     */
    var group = {
        /**
         * 그룹 신규등록시 고유한 그룹코드 체크여부
         */
        isUniqueIDStatus: false,

        /**
         * 현재 활성화된 탭 순서 값
         */
        currTabOrdinal: util.cookies.get("codeCurrTabIndex"),

        /**
         * 현재 탭 위치값 설정
         */
        setCurrTabOrdinal: function (obj) {
            var index = 0;

            if (obj != null && obj != undefined) {
                index = $(obj).closest('li').index();
            }

            // 탭 위치값 쿠키 설정
            util.cookies.setNoExp([{
                    key: "codeCurrTabIndex",
                    val: index
                }]);
        },

        /**
         * 그룹 정보수정 항목 데이터 로드
         */
        load: function () {
            var $frm = $("#frmEditGroup");

            $('.groupCode', $frm).html(code.groupCode);
            $('input[name=groupName]', $frm).val(code.groupName);
        },

        /**
         * 그룹 신규등록
         */
        add: function () {
            var $frm = $("#frmAddGroup");
            var $frmParsley = $frm.parsley();

            // 유효성 검사
            $frmParsley.validate();

            if (!$frmParsley.isValid()) {
                return false;
            }

            // 고유 그룹 ID 중복체크 유무
            if (group.isUniqueIDStatus == false) {
                alert("그룹 고유코드 중복여부를 다시한번 확인 바랍니다");
                $("[name=groupCode]", $frm).focus();
                return false;
            }

            // 신규 메뉴 추가 실행
            if (confirm("저장 하시겠습니까?")) {
                $.ajax({
                    url: "/configSystem/codeGroupAddDo",
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
         * 그룹 정보수정
         */
        edit: function () {
            var $frm = $("#frmEditGroup");
            var $frmParsley = $frm.parsley();

            // 유효성 검사
            $frmParsley.validate();

            if ($frmParsley.isValid() && confirm("수정 하시겠습니까?")) {
                $.ajax({
                    url: "/configSystem/codeGroupUpdateDo",
                    type: "POST",
                    dataType: "json",
                    data: {groupCode: code.groupCode, groupName: $("[name=groupName]", $frm).val()},
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
            return false;
        },

        /**
         * 그룹 삭제
         */
        remove: function () {
            var labelMenu = $(".nav-tabs > li.active a").text();

            if (labelMenu != "" && code.groupCode != "") {

                if (confirm("메뉴 삭제시 시스템에 영향을 미칠 수 있습니다\n["+labelMenu +"] 그룹을 삭제하시겠습니까?")) {
                    $.ajax({
                        url: "/configSystem/codeGroupRemoveDo",
                        type: "POST",
                        dataType: "json",
                        data: {groupCode: code.groupCode},
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

            } else {
                alert("삭제 가능한 대상이 없습니다");
                return false;
            }
        },

        /**
         * 그룹 고유코드 중복체크
         */
        isUniqueID: function (target, msgArea) {
            var groupCode = $(target).val();

            // 조건부 에러 메세지 설정
            if ($(target).val().length == 0) {
                group.isUniqueIDStatus = false;
                $(msgArea).html('<span>ID를 입력해주세요</span>');
                return false;
            }
            if ($(target).val().length < 4) {
                group.isUniqueIDStatus = false;
                $(msgArea).html('<span>ID는 최소 4자 이상이어야 합니다</span>');
                return false;
            }

            $.ajax({
                url: "/configSystem/isUniqueIdForCodeGroup",
                type: "POST",
                dataType: "json",
                data: {groupCode: groupCode},
                beforeSend: function () {
                    // write code here before submit
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log("code : " + xhr.status);
                },
                success: function(data, textStatus, xhr) {
                    if (data.status == true) {
                        group.isUniqueIDStatus = true;
                        classStatus = "text-success";
                    } else {
                        group.isUniqueIDStatus = false;
                        classStatus = "text-danger";
                    }
                    $(msgArea).html("<span class='"+classStatus+"'>"+data.errMsg+"</span>");
                }
            });
        }
    }

    /**
     * 검색시 대소문자 구분하지 않고 검색하기 위한 사용자 정의
     */
    $.expr[':'].contains = function(a, i, m) {
        return jQuery(a).text().toUpperCase()
            .indexOf(m[3].toUpperCase()) >= 0;
    };

    /**
     * 페이지 로딩시 실행 이벤트
     */
    $(function () {
        // Sortable
        $("div[id^=nav-tab-] table tbody")
            .sortable({
                axis: 'y',
                placeholder: 'sortable-placeholder',
                update: function( event, ui ) {
                    $(this).children().each(function(index) {
                        $(this).attr('data-new-itemsort', index + 1)
                    });
                }
            })
            .disableSelection();

        // 항목별 목록 호출
        code.getCodeList(code.groupCode);

        // Modal 그룹 신규등록 그룹 고유코드 중복체크 초기화
        group.isUniqueID($("#frmAddGroup [name=groupCode]"));

        // Switcher 초기화
        FormSliderSwitcher.init();

        // 보여줄 탭 클릭 트리거
        $(".tab-overflow .nav-tabs > li:eq("+group.currTabOrdinal+") a").trigger('click');

    });
</script>

<div id="content">
    <?php include_once(APPPATH."views/_inc/title.php");?>

    <!-- widget grid -->
    <section id="widget-grid" class="">
        <!-- row -->
        <div class="row">
            <!-- NEW WIDGET START -->
            <article class="col-sm-12 col-md-12 col-lg-12">

                <!-- Widget ID (each widget will need unique ID)-->
                <div class="jarviswidget well" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
                    <header>
                        <span class="widget-icon"> <i class="fa fa-comments"></i> </span>
                        <h2>Default Tabs with border </h2>
                    </header>

                    <!-- widget div-->
                    <div>
                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->
                        </div>
                        <!-- end widget edit box -->

                        <!-- 컨텐츠 -->
                        <div class="widget-body commonCode">
                            <!-- 검색 -->
                            <div class="row">
                                <form id="frmSearchCode" class="smart-form">
                                    <section class="col col-4">
                                        <label class="input">
                                            <div class="input-icon-right">
                                                <i class="fa fa-search"></i>
                                                <input type="text" name="searchWord" class="form-control" placeholder="항목명 검색" onkeyup="code.search($(this).val())">
                                            </div>
                                        </label>
                                    </section>
                                </form>

                                <div class="pull-left" style="margin-top: 7px;">그룹 고유코드: <b><span id="txtGroupCode" class="text-info"><!-- Add DOM via code.getCodeList() --></span></b></div>
                            </div>
                            <!--// 검색 -->

                            <!-- 버튼 -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <button type="button" onclick="code.add()" class="btn btn-warning">항목신규등록</button>
                                    <button type="button" onclick="code.save()" data-ismodify="true" class="btn btn-primary">항목저장</button>
                                    <button type="button" onclick="code.remove()" data-ismodify="true" class="btn btn-danger">항목삭제</button>
                                </div>
                                <div class="col-lg-6 text-right">
                                    <a href="#modal-addgroup" class="btn btn-warning" data-toggle="modal">그룹신규등록</a>
                                    <a href="#modal-editgroup" onclick="group.load()" class="btn btn-primary" data-toggle="modal">그룹정보수정</a>
                                    <button type="button" onclick="group.remove()" data-ismodify="true" class="btn btn-danger">그룹삭제</button>
                                </div>
                            </div>
                            <!--// 버튼 -->

                            <hr class="simple">
                            <ul id="myTab1" class="nav nav-tabs bordered">
                                <?
                                $groupCode = "";

                                foreach ($commonCode->getCodeGroupList() as $key => $item) {
                                    // 현재 활성화중 클래스
                                    $cssCurrent = ($key == 0) ? "active" : "";

                                    // 그룹 고유코드 값
                                    $groupCode = $item->groupCode;
                                ?>
                                <li class="<?=$cssCurrent?>">
                                    <a href="#nav-tab-<?=$key+1?>" data-toggle="tab" onclick="code.getCodeList('<?=$item->groupCode?>');group.setCurrTabOrdinal(this)"><?=$item->groupName?></a>
                                </li>
                                <?
                                }
                                ?>
                            </ul>

                            <!-- 목록 -->
                            <div id="myTabContent1" class="tab-content padding-10">
                                <?
                                foreach ($commonCode->getCodeGroupList() as $key => $item) {
                                    $cssCurrent = ($key == 0) ? "in active" : "";
                                ?>
                                <div class="tab-pane fade <?=$cssCurrent?>" id="nav-tab-<?=$key+1?>" data-groupcode="<?=$item->groupCode?>">

                                    <table class="table table-bordered">
                                        <colgroup>
                                            <col width="100">
                                            <col width="100">
                                            <col width="*">
                                            <col width="15%">
                                            <col width="15%">
                                        </colgroup>
                                        <thead>
                                            <tr>
                                                <th class="text-center">번호</th>
                                                <th class="text-center">항목고유코드</th>
                                                <th class="text-center">항목명</th>
                                                <th class="text-center">사용여부</th>
                                                <th class="text-center">삭제여부</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Get list with ajax via code.getCodeList() -->
                                        </tbody>
                                    </table>

                                </div>
                                <?
                                }
                                ?>
                            </div>

                        </div>
                        <!--// 컨텐츠 -->
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

<!-- Modal: 그룹 신규등록 -->
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
                        <label class="control-label col-md-3">그룹 고유코드</label>
                        <div class="col-md-5">
                            <input class="form-control" type="text" name="groupCode" onkeyup="group.isUniqueID(this, '#errMsgAddGroup')" placeholder="필수항목" data-parsley-required="true" data-parsley-minlength="4" />
                        </div>
                        <div class="col-md-4" style="border:0">
                            <div id="errMsgAddGroup" class="m-t-5 p-t-3"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">그룹명</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="groupName" placeholder="필수항목" data-parsley-required="true" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">항목명</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="itemName" placeholder="필수항목" data-parsley-required="true" />
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
<!--// Modal: 그룹 신규등록 -->

<!-- Modal: 그룹 정보수정 -->
<div class="modal fade" id="modal-editgroup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">그룹 정보수정</h4>
            </div>
            <form id="frmEditGroup" name="frmEditGroup" class="form-horizontal form-bordered" data-parsley-validate="true" onsubmit="return group.edit()">
                <div class="modal-body p-0">
                    <div class="form-group">
                        <label class="control-label col-md-3">그룹 고유코드</label>
                        <div class="col-md-5">
                            <div class="m-t-5 groupCode"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">그룹명</label>
                        <div class="col-md-9">
                            <input class="form-control" type="text" name="groupName" placeholder="필수항목" data-parsley-required="true" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="group.edit()" data-ismodify="true" class="btn btn-primary">저장</button>
                    <a href="javascript:;" class="btn btn-default" data-dismiss="modal">닫기</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!--// Modal: 그룹 정보수정 -->
