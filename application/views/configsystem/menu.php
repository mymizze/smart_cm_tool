<script src="<?=GD_ASSETS_PATH?>/js/plugin/x-editable/moment.min.js"></script>
<script src="<?=GD_ASSETS_PATH?>/js/plugin/x-editable/jquery.mockjax.min.js"></script>
<script src="<?=GD_ASSETS_PATH?>/js/plugin/x-editable/x-editable.min.js"></script>

<script type="text/javascript">
    /**
     * Class: 메뉴 정렬 기능
     */
    var sort = {
        /**
         * 처리: 신규메뉴 저장
         */
        add: function () {
            var $frm = $("#frmAddMenu");
            var $frmParsley = $frm.parsley();

            // 유효성 검사
            $frmParsley.validate();

            // 신규 메뉴 추가 실행
            if ($frmParsley.isValid() && confirm("저장 하시겠습니까?")) {
                $.ajax({
                    url: "/configSystem/menuAddDo",
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
         * 처리: 메뉴 정렬 설정 저장
         */
        save: function () {
            var saveData = [];

            // 수정 내역이 없을 경우
            if (this.countUpdate() < 1) {
                alert("수정된 데이터가 없습니다");
                return false;
            }

            // Depth1에 Depth2 항목이 없을 경우
            var countDepth2;

            $("#sortable > li").each(function(index, item) {
                countDepth2 = $(item).find('ul li').length;

                if (countDepth2 < 1) {
                    return false;
                }
            });

            if (countDepth2 < 1) {
                alert("메인 메뉴는 최소 1개의 서브메뉴를 보유해야 합니다");
                return false;
            }

            // 업데이트 대상 데이터 배열에 입력
            $("#sortable > li > ul li").each(function(index, item) {
                if ($(item).attr("data-menu-old-depth1") != $(item).attr("data-menu-depth1")
                    || $(item).attr("data-menu-old-depth2") != $(item).attr("data-menu-depth2")) {

                    saveData.push({
                        seq: $(item).attr("data-menu-seq"),
                        depth1: $(item).attr("data-menu-depth1"),
                        depth2: $(item).attr("data-menu-depth2"),
                        dep1name: $(item).attr("data-menu-dep1name"),
                    });
                }
            });

            if (confirm("저장 하시겠습니까?")) {
                $.ajax({
                    url: "/configSystem/menuSortDo",
                    type: "POST",
                    dataType: "json",
                    data: {sort: JSON.stringify(saveData), depth1: "<?=$page['depth1']?>", depth2: "<?=$page['depth2']?>"},
                    beforeSend: function () {
                        // write code here before submit
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log("code : " + xhr.status);
                    },
                    success: function(data, textStatus, xhr) {
                        if (data.status == true) {
                            location.href = data.retUrl;
                            return false;
                        } else {
                            alert(data.errMsg);
                            location.href = data.retUrl;
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

            $("#sortable > li > ul li").each(function(index, item) {
                if ($(item).attr("data-menu-old-depth1") != $(item).attr("data-menu-depth1")
                    || $(item).attr("data-menu-old-depth2") != $(item).attr("data-menu-depth2")) {

                    result++;
                }
            });
            return result;
        },

        /**
         * 우측 판넬 페이드 화면전환 효과
         */
        fadeAnimRightPanel: function () {
            $('#panel_right').fadeOut('fast', function() {
                $(this).fadeIn('fast');
            });
        }
    }

    /**
     * Class: 기본 정보 수정
     */
    var baseInfo = {
        /**
         * 검색: 메뉴 상세 정보 호출
         */
        getDetail: function () {
            var args = arguments;
            var $obj = $(args[0]);
            var $frm = $(document.frmBaseInfo);
            var paneW = ($(window).width() < 992) ? "100%" : "50%";

            // 좌,우 판넬 애니메이션 효과
            if ($("#pane-right").css("display") == "none") {
                // 메뉴 정렬 좌측 판넬 사이즈 조절
                $("#pane-left").animate({width: paneW}, 500, function () {
                    // 메뉴 정보수정 우측 판넬 보이기
                    $("#pane-right").fadeIn("slow");
                });
            }

            // 우측 판넬의 각 타이틀에 메뉴명 출력
            $("[data-panel-head=menuName]").html("- "+$obj.data("menu-dep2name"));

            // 각 항목별 데이터 입력
            $("[name=mnSeq]", $frm).val($obj.data("menu-seq"));
            $("[name=depth1]", $frm).val($obj.data("menu-depth1"));
            $("[name=depth2]", $frm).val($obj.data("menu-depth2"));
            $("[name=dep1name]", $frm).val($obj.data("menu-dep1name"));
            $("[name=dep2name]", $frm).val($obj.data("menu-dep2name"));
            $("[name=summary]", $frm).val($obj.data("menu-summary"));
            $("[name=linkUrl]", $frm).val($obj.data("menu-linkurl"));
            $("[name=linkParams]", $frm).val($obj.data("menu-linkparams"));

            // 연관 페이지 신규 등록 영역 초기화
            $("#relNewList li").remove();

            // 연관 페이지 목록 설정
            this.loadRelativeList($obj.data("menu-seq"));

            // Switchery 재설정
            var oIsUse = document.querySelector("[name=isUse]");
            oIsUse.checked = ($obj.data("menu-isuse") == "Y") ? true : false;
            custom.switchery.setStatus(oIsUse);
        },

        /**
         * 처리: 메뉴 기본정보 수정 저장
         */
        save: function () {
            var $frm = $(document.frmBaseInfo);
            var $frmParsley = $frm.parsley();

            // 메뉴 정렬 수정 내역이 있을 경우
            if (sort.countUpdate() > 0) {
                alert("메뉴정렬 항목에서 수정된 데이터가 있습니다\n메뉴정렬 항목을 먼저 저장 후 진행해주세요");
                return false;
            }

            // 유효성 검사
            $frmParsley.validate();

            // 연관 페이지 링크경로 기존 등록된 대상
            saveDataRelAdded = [];

            $.each($("#relAddedList input[name='linkUrlRel[]']"), function(index, item) {
                saveDataRelAdded.push({
                    seq: $(item).attr('data-seq')
                })
            });

            // 연관 페이지 신규 등록 대상
            saveDataRelNew = [];

            $.each($("#relNewList input[name='linkUrlRelNew[]']"), function(index, item) {
                saveDataRelNew.push({
                    mnSeq: $("[name=mnSeq]", $frm).val(),
                    linkUrl: $(item).val()
                })
            });

            // 메뉴 기본정보 수정
            if ($frmParsley.isValid() && confirm("저장 하시겠습니까?")) {
                $frm.find('input:disabled').prop('disabled', false);

                $.ajax({
                    url: "/configSystem/baseInfoUpdateDo",
                    type: "POST",
                    dataType: "json",
                    data: {
                        saveData: $frm.serialize(),
                        saveDataRelAdded: JSON.stringify(saveDataRelAdded),
                        saveDataRelNew: JSON.stringify(saveDataRelNew)
                    },
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
         * 처리: 메뉴 삭제
         */
        remove: function () {
            var args = arguments;
            var $obj = $(args[0]);
            var $frm = $obj.closest('form');

            if (confirm("삭제 하시겠습니까?")) {
                $.ajax({
                    url: "/configSystem/menuRemoveDo",
                    type: "POST",
                    dataType: "json",
                    data: {mnSeq: $("[name=mnSeq]", $frm).val()},
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
         * 연관 페이지 목록 로드
         */
        loadRelativeList: function (mnSeq) {
            $.ajax({
                url: "/configSystem/getRelativeList",
                type: "POST",
                dataType: "json",
                data: {mnSeq: mnSeq},
                beforeSend: function () {
                    // write code here before submit
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log("code : " + xhr.status);
                },
                success: function(data, textStatus, xhr) {
                    var html = "";

                    // 목록 추가
                    $.each(data, function(index, item) {
                        html +=
                            "<li class='list-vertical'>" +
                            "    <div class='input-group'>" +
                            "        <input class='form-control' type='text' name='linkUrlRel[]' value='"+item.linkUrl+"' data-seq='"+item.seq+"' placeholder='' />" +
                            "        <div class='input-group-btn'>" +
                            "            <button type='button' onclick='baseInfo.removeRelative(this)' class='btn btn-danger m-l-5'>삭제</button>" +
                            "        </div>" +
                            "    </div>" +
                            "</li>";
                    });

                    $("#relAddedList").html(html);
                }
            });
        },

        /**
         * 연관 페이지 항목 추가
         */
        addRelative: function (obj) {
            var html = "";
            var isDup = false;
            var $item = $(obj).closest('ul').find('input[name=linkUrlRelAdd]');

            // 빈 값 체크
            if ($.trim($item.val()).length < 1) {
                alert("항목의 링크 경로를 입력해주세요");
                $item.focus();
                return false;
            }

            // 신규 등록대기 목록에서 동일 값 존재여부 체크
            $.each($("#relNewList input[name='linkUrlRelNew[]']"), function(index, item) {
                if ($.trim($(item).val()) == $.trim($item.val())) {
                    isDup = true;
                    return false;
                }
            });

            // 기존 목록에서 동일 값 존재여부 체크
            $.each($("#relAddedList input[name='linkUrlRel[]']"), function(index, item) {
                if ($.trim($(item).val()) == $.trim($item.val())) {
                    isDup = true;
                    return false;
                }
            });

            // 이미 동일한 경로 항목 추가시 제한
            if (isDup === true) {
                alert("이미 사용중인 경로 입니다");
                $item.focus();
                return false;
            }

            // 항목 추가
            html =
                "<li class='list-vertical'>" +
                "    <div class='input-group'>" +
                "        <div class='p-10 p-l-20 b-1 b-l-rd-3'>" +
                "            <span class='label label-warning m-r-5'>New</span>" +
                "            <span>"+$item.val()+"</span>" +
                "            <input type='hidden' name='linkUrlRelNew[]' value='"+$item.val()+"'>" +
                "        </div>" +
                "        <div class='input-group-btn'>" +
                "            <button type='button' onclick='baseInfo.removeRelative(this)' class='btn btn-danger m-l-5'>삭제</button>" +
                "        </div>" +
                "    </div>" +
                "    <hr />" +
                "</li>";
            $("#relNewList").append(html);

            // 입력 영역 초기화 및 포커스 이동
            $item.val('').focus();
        },

        /**
         * 연관 페이지 항목 삭제
         */
        removeRelative: function (obj) {
            $(obj).closest('li').remove();
        }
    }

    /**
     * Class: 그룹 권한
     */
    var group = {
        /**
         * 검색: 메뉴 그룹 목록
         */
        getList: function (mnSeq) {
            var $frm = $(document.frmGroup);

            $.ajax({
                url: "/configSystem/getGroupListForConfigMenu/"+mnSeq,
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

                    // 각 항목별 데이터 입력
                    $("[name=mnSeq]", $frm).val(mnSeq);

                    // 그룹 권한 목록 출력
                    $.each(data, function(index, item) {
                        var checkedView = (item.accView == "Y") ? "checked" : "";
                        var checkedModify = (item.accModify == "Y") ? "checked" : "";

                        html +=
                            "<li data-group-seq='"+item.seq+"' data-access-view='"+item.accView+"' data-access-modify='"+item.accModify+"'>" +
                            "    <div class='pull-left f-w-600'>"+item.grName+"</div>" +
                            "    <div class='pull-right'>" +
                            "        <label>보기</label><input type='checkbox' name='grView' data-render='switchery' data-theme='orange' class='groupSwitch' "+checkedView+" />" +
                            "        <label>수정</label><input type='checkbox' name='grModify' data-render='switchery' data-theme='red' class='groupSwitch' "+checkedModify+" />" +
                            "    </div>" +
                            "</li>";
                        $("#groupList").html(html);
                    });

                    // Switchery 지정 영역 초기화
                    custom.switchery.renderSwitcher('.groupSwitch');
                }
            });
        },

        /**
         * 처리: 그룹 권한 수정 저장
         */
        save: function () {
            var $frm = $(document.frmGroup);
            var saveData = [];

            // 메뉴 정렬 수정 내역이 있을 경우
            if (sort.countUpdate() > 0) {
                alert("메뉴정렬 항목에서 수정된 데이터가 있습니다\n메뉴정렬 항목을 먼저 저장 후 진행해주세요");
                return false;
            }

            // 업데이트 대상 데이터 배열에 입력
            $("ul#groupList > li").each(function(index, item) {
                var isView = ($(item).find('[name=grView]').prop("checked")) ? "Y" : "N";
                var isModify = ($(item).find('[name=grModify]').prop("checked")) ? "Y" : "N";


                if ($(item).attr("data-access-view") != isView
                    || $(item).attr("data-access-modify") != isModify) {

                    saveData.push({
                        grSeq: $(item).attr("data-group-seq"),
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
                        url: "/configSystem/accessUpdateDo",
                        type: "POST",
                        dataType: "json",
                        data: {mnSeq: $("[name=mnSeq]", $frm).val(), access: JSON.stringify(saveData)},
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
         * 권한 설정 전체 선택/해제 토글기능
         */
        setSelectAll: function () {
            var $frm = $(document.frmGroup);

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
                    // 정렬 순서 변경시 data 속성 depth 값 및 depth1name 재설정
                    $("#sortable > li").each(function(index, depth1) {
                        var resetDepth1 = index + 1;

                        $(depth1).attr('data-menu-depth1', resetDepth1);

                        $(depth1).find('ul > li').each(function(index, depth2) {
                            $(depth2)
                                .attr('data-menu-depth1', resetDepth1)
                                .attr('data-menu-depth2', index + 1)
                                .attr('data-menu-dep1name', $(depth1).data('menu-dep1name'));

                        });
                    });
                }
            })
            .disableSelection();

        // Switcher
        FormSliderSwitcher.init();

        // X-editable 초기화
        $("[data-icon]").editable({
            dataType: 'json',
            params:function(params){
                params.pk = $(this).data('pk');
                return params;
            },
            url: '/configSystem/setMenuIcon',
            title: '메뉴 아이콘 CSS 클래스',
            success: function(response, newValue) {
                var data = eval("("+response+")");

                if (data.status == true) {
                    window.location.reload();
                } else {
                    alert(data.errMsg);
                    return false;
                }
            }
        });
    });

    /*
     * X-Ediable
     */
    (function (e) {
        "use strict";
        var t = function (e) {
            this.init("address", e, t.defaults)
        };
        e.fn.editableutils.inherit(t, e.fn.editabletypes.abstractinput);
        e.extend(t.prototype, {
            render: function () {
                this.$input = this.$tpl.find("input")
            },
            value2html: function (t, n) {
                if (!t) {
                    e(n).empty();
                    return
                }
                var r = e("<div>").text(t.city).html() + ", " + e("<div>").text(t.street).html() +
                    " st., bld. " + e("<div>").text(t.building).html();
                e(n).html(r)
            },
            html2value: function (e) {
                return null
            },
            value2str: function (e) {
                var t = "";
                if (e)
                    for (var n in e)
                        t = t + n + ":" + e[n] + ";";
                return t
            },
            str2value: function (e) {
                return e
            },
            value2input: function (e) {
                if (!e)
                    return;
                this.$input.filter('[name="city"]').val(e.city);
                this.$input.filter('[name="street"]').val(e.street);
                this.$input.filter('[name="building"]').val(e.building)
            },
            input2value: function () {
                return {
                    city: this.$input.filter('[name="city"]').val(),
                    street: this.$input.filter('[name="street"]').val(),
                    building: this.$input.filter('[name="building"]').val()
                }
            },
            activate: function () {
                this.$input.filter('[name="city"]').focus()
            },
            autosubmit: function () {
                this.$input.keydown(function (t) {
                    t.which === 13 && e(this).closest("form").submit()
                })
            }
        });
        t.defaults = e.extend({}, e.fn.editabletypes.abstractinput.defaults, {
            tpl: '<div class="editable-address"><label><span>City: </span><input type="text" name="city" class="input-small"></label></div><div class="editable-address"><label><span>Street: </span><input type="text" name="street" class="input-small"></label></div><div class="editable-address"><label><span>Building: </span><input type="text" name="building" class="input-mini"></label></div>',
            inputclass: ""
        });
        e.fn.editabletypes.address = t
    })(window.jQuery);
</script>

<style type="text/css">
    /*
     * Glyphicons
     *
     * Special styles for displaying the icons and their classes in the docs.
     */

    .bs-glyphicons {
        padding-left: 0;
        padding-bottom: 1px;
        margin-bottom: 20px;
        list-style: none;
        overflow: hidden;
    }
    .bs-glyphicons li {
        float: left;
        width: 25%;
        height: 115px;
        padding: 10px;
        margin: 0 -1px -1px 0;
        font-size: 12px;
        line-height: 1.4;
        text-align: center;
        border: 1px solid #ddd;
    }
    .bs-glyphicons .glyphicon {
        margin-top: 5px;
        margin-bottom: 10px;
        font-size: 24px;
    }
    .bs-glyphicons .glyphicon-class {
        display: block;
        text-align: center;
    }
    .bs-glyphicons li:hover {
        background-color: rgba(86,61,124,.1);
    }

    @media (min-width: 768px) {
        .bs-glyphicons li {
            width: 25%;
        }
    }
</style>

<div id="content">
    <?php include_once(APPPATH."views/_inc/title.php");?>

    <!-- 메뉴 관리 섹션 -->
    <section id="widget-grid" class="">
        <!-- row -->
        <div class="row">
            <!-- 좌측 판넬 -->
            <article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

                <!-- Widget ID (each widget will need unique ID)-->
                <div class="jarviswidget <?=GD_JARVISWIDGET_COLOR?>" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false">
                    <header>
                        <span class="widget-icon fa fa-sort-amount-asc"> <i class="fa fa-comments"></i> </span>
                        <h2>메뉴 생성 및 정렬</h2>

                    </header>

                    <!-- widget div -->
                    <div>
                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->
                            <input class="form-control" type="text">
                        </div>
                        <!-- end widget edit box -->

                        <!-- 메뉴 생성 및 정렬 내용 -->
                        <div class="widget-body">

                            <form name="frmMenuSort" class="form-horizontal form-bordered">
                                <div class="row">
                                    <div class="col-md-4 text-left">
                                        <a href="#modal-icon-fa" class="btn btn-default" data-toggle="modal">아이콘1</a>
                                        <a href="#modal-icon-glyph" class="btn btn-default" data-toggle="modal">아이콘2</a>
                                    </div>
                                    <div class="col-md-4 col-md-offset-4 text-right">
                                        <button type="button" onclick="sort.save()" data-ismodify="true" class="btn btn-primary">정렬저장</button>
                                        <a href="#modal-addmenu" class="btn btn-warning" data-toggle="modal">메뉴등록</a>
                                    </div>
                                </div>

                                <ul class="configMenu sortable list-unstyled" id="sortable">
                                    <?
                                    $prevDepth1 = "";
                                    foreach ($configMenuList as $key => $item) {
                                        if ($item->depth1 != $prevDepth1) {
                                            if ($key > 0) {
                                    ?>
                                            </ul>
                                        </li>
                                    <?
                                            }

                                            $iconClass = ($item->icon == "") ? "fa-file-o" : $item->icon;
                                    ?>
                                    <li data-depth-level="1" data-menu-depth1="<?=$item->depth1?>" data-menu-dep1name="<?=$item->dep1name?>">
                                        <div class="block block-title bg-grey-lighter">
                                            <div class="col-md-8 text-left">
                                                <i class="fa <?=$iconClass?>"></i>
                                                <?=$item->dep1name?>
                                            </div>
                                            <div class="col-md-4 text-right p-0">
                                                <div class="btnConfigMore pull-right text-center">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </div>
                                                <div class="pull-right text-center">
                                                    <span class="editIcon" data-icon data-pk="<?=$item->depth1?>"><?=$item->icon?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <ul class="sortable list-unstyled">
                                    <?
                                        }
                                    ?>
                                            <li onclick="baseInfo.getDetail(this);group.getList($(this).data('menu-seq'));sort.fadeAnimRightPanel();"
                                                data-depth-level="2" data-menu-old-depth1="<?=$item->depth1?>" data-menu-old-depth2="<?=$item->depth2?>"
                                                data-menu-seq="<?=$item->seq?>" data-menu-depth1="<?=$item->depth1?>" data-menu-depth2="<?=$item->depth2?>" data-menu-dep1name="<?=$item->dep1name?>" data-menu-dep2name="<?=$item->dep2name?>" data-menu-summary="<?=$item->summary?>" data-menu-linkurl="<?=$item->linkUrl?>" data-menu-linkparams="<?=$item->linkParams?>" data-menu-isuse="<?=$item->isUse?>">

                                                <div class="block block-title <?=$util->compare($item->isUse,'N','not-use')?>"><?=$item->dep2name?></div>
                                                <ul class="sortable list-unstyled"></ul>
                                            </li>
                                    <?
                                        $prevDepth1 = $item->depth1;
                                    }

                                    if (count($configMenuList) > 0) {
                                    ?>
                                        </ul>
                                    </li>
                                    <?
                                    }
                                    ?>
                                </ul>
                            </form>

                        </div>
                        <!--// 메뉴 생성 및 정렬 내용 -->
                    </div>
                    <!-- end widget div -->
                </div>
                <!-- end widget -->
            </article>
            <!--// 좌측 판넬 -->

            <!-- 우측 판넬 -->
            <article id="panel_right" class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

                <!-- Widget ID (each widget will need unique ID)-->
                <div class="jarviswidget <?=GD_JARVISWIDGET_COLOR?>" id="wid-id-1" data-widget-colorbutton="false" data-widget-editbutton="false">
                    <header>
                        <span class="widget-icon fa fa-list-ol"> <i class="fa fa-comments"></i> </span>
                        <h2>메뉴 상세정보 수정</h2><h2 data-panel-head="menuName"></h2>

                    </header>

                    <!-- widget div-->
                    <div>
                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->
                            <input class="form-control" type="text">
                        </div>
                        <!-- end widget edit box -->

                        <!-- 메뉴 상세정보 수정 -->
                        <div class="widget-body basicInfo">

                            <form name="frmBaseInfo" class="form-horizontal form-bordered" data-parsley-validate="true">
                                <input type="hidden" name="mnSeq" value="">
                                <input type="hidden" name="depth1" value="">
                                <input type="hidden" name="depth2" value="">

                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3" for="dep1name">메인 메뉴명</label>
                                    <div class="col-md-8 col-sm-8">
                                        <input class="form-control" type="text" id="dep1name" name="dep1name" placeholder="" disabled />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3" for="dep2name">서브 메뉴명</label>
                                    <div class="col-md-8 col-sm-8">
                                        <input class="form-control" type="text" id="dep2name" name="dep2name" placeholder="필수항목" data-parsley-required="true" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3" for="summary">요약 설명</label>
                                    <div class="col-md-8 col-sm-8">
                                        <input class="form-control" type="text" id="summary" name="summary" placeholder="선택항목: 페이지 요약 설명에 노출" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3" for="linkUrl">링크 경로</label>
                                    <div class="col-md-8 col-sm-8">
                                        <input class="form-control" type="text" id="linkUrl" name="linkUrl" placeholder="(예시: configAdmin/account)" data-parsley-required="true" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3" for="linkUrl">연관 페이지<br>링크 경로</label>
                                    <div class="col-md-8 col-sm-8 p-r-0">
                                        <!-- 연관 페이지: 신규 추가 입력 -->
                                        <ul id="relNew" class="list-unstyled">
                                            <li>
                                                <div class="input-group">
                                                    <input class="form-control" type="text" name="linkUrlRelAdd" placeholder="(예시: configAdmin/accountWrite)" />
                                                    <div class="input-group-btn">
                                                        <button type="button" onclick="baseInfo.addRelative(this)" class="btn btn-warning m-l-5">항목추가</button>
                                                    </div>
                                                </div>
                                                <hr />
                                            </li>
                                        </ul>
                                        <!--// 연관 페이지: 신규 추가 입력 -->

                                        <!-- 연관 페이지: 신규 추가 대기 목록 -->
                                        <ul id="relNewList" class="list-unstyled">
                                            <!-- Add DOM via baseInfo.addRelative() -->
                                        </ul>
                                        <!--// 연관 페이지: 신규 추가 대기 목록 -->

                                        <!-- 연관 페이지: 목록 -->
                                        <ul id="relAddedList" class="list-unstyled">
                                            <!-- Add DOM via baseInfo.loadRelativeList() -->
                                        </ul>
                                        <!--// 연관 페이지: 목록 -->
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3" for="linkUrl">링크 파라미터</label>
                                    <div class="col-md-8 col-sm-8">
                                        <input class="form-control" type="text" id="linkParams" name="linkParams" placeholder="(예시: &amp;key1=val1&amp;key2=val2)" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3" for="isUse">사용여부</label>
                                    <div class="col-md-8 col-sm-8">
                                        <input type="checkbox" id="isUse" name="isUse" data-render="switchery" data-theme="blue" />
                                    </div>
                                </div>
                                <div class="form-group text-center p-10">
                                    <button type="button" onclick="baseInfo.save()" data-ismodify="true" class="btn btn-primary">저장</button>
                                    <button type="button" onclick="baseInfo.remove(this)" data-ismodify="true" class="btn btn-danger">삭제</button>
                                </div>
                            </form>

                        </div>
                        <!--// 메뉴 상세정보 수정 -->
                    </div>
                    <!-- end widget div -->
                </div>
                <!-- end widget -->

                <!-- Widget ID (each widget will need unique ID)-->
                <div class="jarviswidget <?=GD_JARVISWIDGET_COLOR?>" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false">
                    <header>
                        <span class="widget-icon fa fa-group"> <i class="fa fa-comments"></i> </span>
                        <h2>그룹 권한 설정</h2><h2 data-panel-head="menuName"></h2>

                    </header>

                    <!-- widget div-->
                    <div>
                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->
                            <input class="form-control" type="text">
                        </div>
                        <!-- end widget edit box -->

                        <!-- 그룹 권한 설정 -->
                        <div class="widget-body">

                            <form name="frmGroup" class="form-horizontal form-bordered" data-parsley-validate="true">
                                <input type="hidden" name="mnSeq" value="">

                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button type="button" onclick="group.setSelectAll()" class="btn btn-success">전체 선택/해제</button>
                                        <button type="button" onclick="group.save()" data-ismodify="true" class="btn btn-primary">저장</button>
                                    </div>
                                </div>

                                <ul id="groupList" class="li-vertical-list-default">
                                    <!--  Ajax via group.getList() -->
                                </ul>
                            </form>

                        </div>
                        <!--// 그룹 권한 설정 -->
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
    <!--// 메뉴 관리 섹션 -->
</div>

<!-- Modal: 메뉴 추가 -->
<div class="modal fade" id="modal-addmenu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">신규메뉴 등록</h4>
            </div>
            <form id="frmAddMenu" name="frmAddMenu" class="form-horizontal form-bordered" data-parsley-validate="true">
                <div class="modal-body p-t-0 p-b-0">
                    <div class="form-group">
                        <label class="control-label col-md-4">메인 메뉴명</label>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <select id="depth1New" name="depth1" class="form-control" data-toggle="customInput" data-custominput-base="custom" data-custominput-targetid="depth1nameNew" data-parsley-required="true">
                                        <option value="" selected>선택 해주세요</option>
                                        <option value="custom">신규 메뉴(직접입력)</option>
                                        <?=$codeToHtml->getMainMenuCodeListSelectBox()?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <input id="depth1nameNew" name="dep1name" maxlength="20" class="form-control hide" placeholder="필수항목" data-parsley-required="true" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">서브 메뉴명</label>
                        <div class="col-md-8">
                            <input class="form-control" type="text" id="dep2nameNew" name="dep2name" placeholder="필수항목" data-parsley-required="true" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">요약 설명</label>
                        <div class="col-md-8">
                            <input class="form-control" type="text" id="summaryNew" name="summary" placeholder="선택항목: 페이지 요약 설명에 노출" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">링크 경로</label>
                        <div class="col-md-8">
                            <input class="form-control" type="text" id="linkUrlNew" name="linkUrl" placeholder="필수항목" data-parsley-required="true" />
                            <span class="text-primary">절대경로 (예시: configSystem/site)</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">링크 파라미터</label>
                        <div class="col-md-8">
                            <input class="form-control" type="text" id="linkParamsNew" name="linkParams" placeholder="" />
                            <span class="text-primary">(예시: &amp;key1=val1&amp;key2=val2)</span>
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
                    <button type="button" onclick="sort.add()" data-ismodify="true" class="btn btn-primary">저장</button>
                    <a href="javascript:;" class="btn btn-default" data-dismiss="modal">닫기</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: 아이콘1 팁 -->
<div class="modal fade" id="modal-icon-fa">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">아이콘 - Font Awesome</h4>
            </div>
            <div class="modal-body">
                <!-- row -->
                <div class="row">

                    <!-- NEW WIDGET START -->
                    <article class="col-sm-12">

                        <!-- Widget ID (each widget will need unique ID)-->
                        <div class="jarviswidget jarviswidget-color-blue" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-sortable="false">
                            <header>
                                <span class="widget-icon"> <i class="fa fa-comments"></i> </span>
                                <h2>Font Awesome</h2>

                            </header>

                            <!-- widget div-->
                            <div>

                                <!-- widget edit box -->
                                <div class="jarviswidget-editbox">
                                    <!-- This area used as dropdown edit box -->

                                </div>
                                <!-- end widget edit box -->

                                <!-- widget content -->
                                <div class="widget-body" id="all-icons-demo">


                                        <div class="alert alert-info">
                                            <i class="fa fa-exclamation"></i> Please note: As of the of Font Awesome version 4.0. All icons now require a base class of <code>
                                                fa</code> as well as individual icon classes. For example <code> fa fa-adjust</code>
                                                <br>
                                                <div class="margin-top-5">View the full icon list by going to <a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">//fortawesome.github.io/Font-Awesome/icons/</a></div>
                                        </div>

                                        <div class="alert alert-warning"> <strong>NOTE</strong>: FontAwesome version 4.2 is blurry on some resolutions, therefore we did not upgrade (the included FontAwesome version is 4.1). Hopefully this issue will be resolved in later versions of FontAwesome and will be safer to upgrade</div>

                                        <!--<h2>40 NEW icons with 4.2</h2>

                                        <div class="row">

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-angellist"></i> fa-angellist
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-area-chart"></i> fa-area-chart
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-at"></i> fa-at
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-bell-slash"></i> fa-bell-slash
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-bell-slash-o"></i> fa-bell-slash-o
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-bicycle"></i> fa-bicycle
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-binoculars"></i> fa-binoculars
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-birthday-cake"></i> fa-birthday-cake
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-bus"></i> fa-bus
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-calculator"></i> fa-calculator
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-cc"></i> fa-cc
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-cc-amex"></i> fa-cc-amex
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-cc-discover"></i> fa-cc-discover
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-cc-mastercard"></i> fa-cc-mastercard
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-cc-paypal"></i> fa-cc-paypal
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-cc-stripe"></i> fa-cc-stripe
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-cc-visa"></i> fa-cc-visa
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-copyright"></i> fa-copyright
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-eyedropper"></i> fa-eyedropper
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-futbol-o"></i> fa-futbol-o
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-google-wallet"></i> fa-google-wallet
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-ils"></i> fa-ils
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-ioxhost"></i> fa-ioxhost
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-lastfm"></i> fa-lastfm
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-lastfm-square"></i> fa-lastfm-square
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-line-chart"></i> fa-line-chart
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-meanpath"></i> fa-meanpath
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-newspaper-o"></i> fa-newspaper-o
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-paint-brush"></i> fa-paint-brush
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-paypal"></i> fa-paypal
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-pie-chart"></i> fa-pie-chart
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-plug"></i> fa-plug
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-shekel"></i> fa-shekel <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-sheqel"></i> fa-sheqel <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-slideshare"></i> fa-slideshare
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-soccer-ball-o"></i> fa-soccer-ball-o <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-toggle-off"></i> fa-toggle-off
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-toggle-on"></i> fa-toggle-on
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-trash"></i> fa-trash
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-tty"></i> fa-tty
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-twitch"></i> fa-twitch
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-wifi"></i> fa-wifi
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-yelp"></i> fa-yelp
                                            </div>

                                        </div>-->

                                        <h2>70 new icons was introduced in 4.1</h2>

                                        <div class="row">

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-automobile"></i> fa-automobile <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-bank"></i> fa-bank <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-behance"></i> fa-behance
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-behance-square"></i> fa-behance-square
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-bomb"></i> fa-bomb
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-building"></i> fa-building
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-cab"></i> fa-cab <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-car"></i> fa-car
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-child"></i> fa-child
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-circle-o-notch"></i> fa-circle-o-notch
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-circle-thin"></i> fa-circle-thin
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-codepen"></i> fa-codepen
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-cube"></i> fa-cube
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-cubes"></i> fa-cubes
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-database"></i> fa-database
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-delicious"></i> fa-delicious
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-deviantart"></i> fa-deviantart
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-digg"></i> fa-digg
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-drupal"></i> fa-drupal
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-empire"></i> fa-empire
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-envelope-square"></i> fa-envelope-square
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-fax"></i> fa-fax
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-file-archive-o"></i> fa-file-archive-o
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-file-audio-o"></i> fa-file-audio-o
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-file-code-o"></i> fa-file-code-o
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-file-excel-o"></i> fa-file-excel-o
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-file-image-o"></i> fa-file-image-o
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-file-movie-o"></i> fa-file-movie-o <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-file-pdf-o"></i> fa-file-pdf-o
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-file-photo-o"></i> fa-file-photo-o <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-file-picture-o"></i> fa-file-picture-o <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-file-powerpoint-o"></i> fa-file-powerpoint-o
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-file-sound-o"></i> fa-file-sound-o <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-file-video-o"></i> fa-file-video-o
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-file-word-o"></i> fa-file-word-o
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-file-zip-o"></i> fa-file-zip-o <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-ge"></i> fa-ge <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-git"></i> fa-git
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-git-square"></i> fa-git-square
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-google"></i> fa-google
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-graduation-cap"></i> fa-graduation-cap
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-hacker-news"></i> fa-hacker-news
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-header"></i> fa-header
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-history"></i> fa-history
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-institution"></i> fa-institution <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-joomla"></i> fa-joomla
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-jsfiddle"></i> fa-jsfiddle
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-language"></i> fa-language
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-life-bouy"></i> fa-life-bouy <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-life-ring"></i> fa-life-ring
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-life-saver"></i> fa-life-saver <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-mortar-board"></i> fa-mortar-board <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-openid"></i> fa-openid
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-paper-plane"></i> fa-paper-plane
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-paper-plane-o"></i> fa-paper-plane-o
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-paragraph"></i> fa-paragraph
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-paw"></i> fa-paw
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-pied-piper"></i> fa-pied-piper
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-pied-piper-alt"></i> fa-pied-piper-alt
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-pied-piper-square"></i> fa-pied-piper-square <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-qq"></i> fa-qq
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-ra"></i> fa-ra <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-rebel"></i> fa-rebel
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-recycle"></i> fa-recycle
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-reddit"></i> fa-reddit
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-reddit-square"></i> fa-reddit-square
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-send"></i> fa-send <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-send-o"></i> fa-send-o <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-share-alt"></i> fa-share-alt
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-share-alt-square"></i> fa-share-alt-square
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-slack"></i> fa-slack
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-sliders"></i> fa-sliders
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-soundcloud"></i> fa-soundcloud
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-space-shuttle"></i> fa-space-shuttle
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-spoon"></i> fa-spoon
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-spotify"></i> fa-spotify
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-steam"></i> fa-steam
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-steam-square"></i> fa-steam-square
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-stumbleupon"></i> fa-stumbleupon
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-stumbleupon-circle"></i> fa-stumbleupon-circle
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-support"></i> fa-support <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-taxi"></i> fa-taxi
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-tencent-weibo"></i> fa-tencent-weibo
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-tree"></i> fa-tree
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-university"></i> fa-university
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-vine"></i> fa-vine
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-wechat"></i> fa-wechat <span class="text-muted">(alias)</span>
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-weixin"></i> fa-weixin
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-wordpress"></i> fa-wordpress
                                            </div>

                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-yahoo"></i> fa-yahoo
                                            </div>

                                        </div>

                                        <!-- new sets -->

                                        <h2>Web Application Icons</h2>

                                        <div class="row">
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-adjust"></i> fa-adjust
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-anchor"></i> fa-anchor
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-archive"></i> fa-archive
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-asterisk"></i> fa-asterisk
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-ban"></i> fa-ban
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-bar-chart-o"></i> fa-bar-chart-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-barcode"></i> fa-barcode
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-beer"></i> fa-beer
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-bell"></i> fa-bell
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-bell-o"></i> fa-bell-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-bolt"></i> fa-bolt
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-book"></i> fa-book
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-bookmark"></i> fa-bookmark
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-bookmark-o"></i> fa-bookmark-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-briefcase"></i> fa-briefcase
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-bug"></i> fa-bug
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-building"></i> fa-building
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-bullhorn"></i> fa-bullhorn
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-bullseye"></i> fa-bullseye
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-calendar"></i> fa-calendar
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-calendar-o"></i> fa-calendar-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-camera"></i> fa-camera
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-camera-retro"></i> fa-camera-retro
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-caret-square-o-down"></i> fa-caret-square-o-down
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-caret-square-o-left"></i> fa-caret-square-o-left
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-caret-square-o-right"></i> fa-caret-square-o-right
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-caret-square-o-up"></i> fa-caret-square-o-up
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-certificate"></i> fa-certificate
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-check"></i> fa-check
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-check-circle"></i> fa-check-circle
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-check-circle-o"></i> fa-check-circle-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-check-square"></i> fa-check-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-check-square-o"></i> fa-check-square-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-circle"></i> fa-circle
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-circle-o"></i> fa-circle-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-clock-o"></i> fa-clock-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-cloud"></i> fa-cloud
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-cloud-download"></i> fa-cloud-download
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-cloud-upload"></i> fa-cloud-upload
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-code"></i> fa-code
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-code-fork"></i> fa-code-fork
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-coffee"></i> fa-coffee
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-cog"></i> fa-cog
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-cogs"></i> fa-cogs
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-plus-square-o"></i> fa-plus-square-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-comment"></i> fa-comment
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-comment-o"></i> fa-comment-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-comments"></i> fa-comments
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-comments-o"></i> fa-comments-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-compass"></i> fa-compass
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-credit-card"></i> fa-credit-card
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-crop"></i> fa-crop
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-crosshairs"></i> fa-crosshairs
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-cutlery"></i> fa-cutlery
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-dashboard"></i> fa-dashboard <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-desktop"></i> fa-desktop
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-dot-circle-o"></i> fa-dot-circle-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-download"></i> fa-download
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-edit"></i> fa-edit <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-ellipsis-horizontal"></i> fa-ellipsis-horizontal
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-ellipsis-vertical"></i> fa-ellipsis-vertical
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-envelope"></i> fa-envelope
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-envelope-o"></i> fa-envelope-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-eraser"></i> fa-eraser
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-exchange"></i> fa-exchange
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-exclamation"></i> fa-exclamation
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-exclamation-circle"></i> fa-exclamation-circle
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-exclamation-triangle"></i> fa-exclamation-triangle
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-minus-square-o"></i> fa-minus-square-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-external-link"></i> fa-external-link
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-external-link-square"></i> fa-external-link-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-eye"></i> fa-eye
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-eye-slash"></i> fa-eye-slash
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-female"></i> fa-female
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-fighter-jet"></i> fa-fighter-jet
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-film"></i> fa-film
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-filter"></i> fa-filter
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-fire"></i> fa-fire
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-fire-extinguisher"></i> fa-fire-extinguisher
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-flag"></i> fa-flag
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-flag-checkered"></i> fa-flag-checkered
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-flag-o"></i> fa-flag-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-flash"></i> fa-flash <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-flask"></i> fa-flask
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-folder"></i> fa-folder
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-folder-o"></i> fa-folder-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-folder-open"></i> fa-folder-open
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-folder-open-o"></i> fa-folder-open-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-frown-o"></i> fa-frown-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-gamepad"></i> fa-gamepad
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-gavel"></i> fa-gavel
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-gear"></i> fa-gear <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-gears"></i> fa-gears <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-gift"></i> fa-gift
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-glass"></i> fa-glass
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-globe"></i> fa-globe
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-group"></i> fa-group
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-hdd-o"></i> fa-hdd-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-headphones"></i> fa-headphones
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-heart"></i> fa-heart
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-heart-o"></i> fa-heart-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-home"></i> fa-home
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-inbox"></i> fa-inbox
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-info"></i> fa-info
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-info-circle"></i> fa-info-circle
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-key"></i> fa-key
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-keyboard-o"></i> fa-keyboard-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-laptop"></i> fa-laptop
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-leaf"></i> fa-leaf
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-legal"></i> fa-legal <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-lemon-o"></i> fa-lemon-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-level-down"></i> fa-level-down
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-level-up"></i> fa-level-up
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-lightbulb-o"></i> fa-lightbulb-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-location-arrow"></i> fa-location-arrow
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-lock"></i> fa-lock
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-magic"></i> fa-magic
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-magnet"></i> fa-magnet
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-mail-forward"></i> fa-mail-forward <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-mail-reply"></i> fa-mail-reply <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-mail-reply-all"></i> fa-mail-reply-all
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-male"></i> fa-male
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-map-marker"></i> fa-map-marker
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-meh-o"></i> fa-meh-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-microphone"></i> fa-microphone
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-microphone-slash"></i> fa-microphone-slash
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-minus"></i> fa-minus
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-minus-circle"></i> fa-minus-circle
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-minus-square"></i> fa-minus-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-minus-square-o"></i> fa-minus-square-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-mobile"></i> fa-mobile
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-mobile-phone"></i> fa-mobile-phone <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-money"></i> fa-money
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-moon-o"></i> fa-moon-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-move"></i> fa-move
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-music"></i> fa-music
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-pencil"></i> fa-pencil
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-pencil-square"></i> fa-pencil-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-pencil-square-o"></i> fa-pencil-square-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-phone"></i> fa-phone
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-phone-square"></i> fa-phone-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-picture-o"></i> fa-picture-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-plane"></i> fa-plane
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-plus"></i> fa-plus
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-plus-circle"></i> fa-plus-circle
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-plus-square"></i> fa-plus-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-power-off"></i> fa-power-off
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-print"></i> fa-print
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-puzzle-piece"></i> fa-puzzle-piece
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-qrcode"></i> fa-qrcode
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-question"></i> fa-question
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-question-circle"></i> fa-question-circle
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-quote-left"></i> fa-quote-left
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-quote-right"></i> fa-quote-right
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-random"></i> fa-random
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-refresh"></i> fa-refresh
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-reorder"></i> fa-reorder
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-reply"></i> fa-reply
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-reply-all"></i> fa-reply-all
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-resize-horizontal"></i> fa-resize-horizontal
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-resize-vertical"></i> fa-resize-vertical
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-retweet"></i> fa-retweet
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-road"></i> fa-road
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-rocket"></i> fa-rocket
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-rss"></i> fa-rss
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-rss-square"></i> fa-rss-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-search"></i> fa-search
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-search-minus"></i> fa-search-minus
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-search-plus"></i> fa-search-plus
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-share"></i> fa-share
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-share-square"></i> fa-share-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-share-square-o"></i> fa-share-square-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-shield"></i> fa-shield
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-shopping-cart"></i> fa-shopping-cart
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-sign-in"></i> fa-sign-in
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-sign-out"></i> fa-sign-out
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-signal"></i> fa-signal
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-sitemap"></i> fa-sitemap
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-smile-o"></i> fa-smile-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-sort"></i> fa-sort
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-sort-alpha-asc"></i> fa-sort-alpha-asc
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-sort-alpha-desc"></i> fa-sort-alpha-desc
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-sort-amount-asc"></i> fa-sort-amount-asc
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-sort-amount-desc"></i> fa-sort-amount-desc
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-sort-asc"></i> fa-sort-asc
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-sort-desc"></i> fa-sort-desc
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-sort-down"></i> fa-sort-down <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-sort-numeric-asc"></i> fa-sort-numeric-asc
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-sort-numeric-desc"></i> fa-sort-numeric-desc
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-sort-up"></i> fa-sort-up <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-spinner"></i> fa-spinner
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-square"></i> fa-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-square-o"></i> fa-square-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-star"></i> fa-star
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-star-half"></i> fa-star-half
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-star-half-empty"></i> fa-star-half-empty <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-star-half-full"></i> fa-star-half-full <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-star-half-o"></i> fa-star-half-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-star-o"></i> fa-star-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-subscript"></i> fa-subscript
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-suitcase"></i> fa-suitcase
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-sun-o"></i> fa-sun-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-superscript"></i> fa-superscript
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-tablet"></i> fa-tablet
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-tachometer"></i> fa-tachometer
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-tag"></i> fa-tag
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-tags"></i> fa-tags
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-tasks"></i> fa-tasks
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-terminal"></i> fa-terminal
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-thumb-tack"></i> fa-thumb-tack
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-thumbs-down"></i> fa-thumbs-down
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-thumbs-o-down"></i> fa-thumbs-o-down
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-thumbs-o-up"></i> fa-thumbs-o-up
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-thumbs-up"></i> fa-thumbs-up
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-ticket"></i> fa-ticket
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-times"></i> fa-times
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-times-circle"></i> fa-times-circle
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-times-circle-o"></i> fa-times-circle-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-tint"></i> fa-tint
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-toggle-down"></i> fa-toggle-down <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-toggle-left"></i> fa-toggle-left <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-toggle-right"></i> fa-toggle-right <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-toggle-up"></i> fa-toggle-up <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-trash-o"></i> fa-trash-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-trophy"></i> fa-trophy
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-truck"></i> fa-truck
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-umbrella"></i> fa-umbrella
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-unlock"></i> fa-unlock
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-unlock-alt"></i> fa-unlock-alt
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-unsorted"></i> fa-unsorted <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-upload"></i> fa-upload
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-user"></i> fa-user
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-video-camera"></i> fa-video-camera
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-volume-down"></i> fa-volume-down
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-volume-off"></i> fa-volume-off
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-volume-up"></i> fa-volume-up
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-warning"></i> fa-warning <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-wheelchair"></i> fa-wheelchair
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-wrench"></i> fa-wrench
                                            </div>
                                        </div>

                                        <!-- new sets -->

                                        <h2>Form Control Icons</h2>

                                        <div class="row">
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-check-square"></i> fa-check-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-check-square-o"></i> fa-check-square-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-circle"></i> fa-circle
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-circle-o"></i> fa-circle-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-dot-circle-o"></i> fa-dot-circle-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-minus-square"></i> fa-minus-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-minus-square-o"></i> fa-minus-square-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-square"></i> fa-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-square-o"></i> fa-square-o
                                            </div>
                                        </div>

                                        <!-- new sets -->

                                        <h2>Currency Icons</h2>

                                        <div class="row">
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-bitcoin"></i> fa-bitcoin <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-btc"></i> fa-btc
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-cny"></i> fa-cny <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-dollar"></i> fa-dollar <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-eur"></i> fa-eur
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-euro"></i> fa-euro <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-gbp"></i> fa-gbp
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-inr"></i> fa-inr
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-jpy"></i> fa-jpy
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-krw"></i> fa-krw
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-money"></i> fa-money
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-rmb"></i> fa-rmb <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-rouble"></i> fa-rouble <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-rub"></i> fa-rub
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-ruble"></i> fa-ruble <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-rupee"></i> fa-rupee <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-try"></i> fa-try
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-turkish-lira"></i> fa-turkish-lira <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-usd"></i> fa-usd
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-won"></i> fa-won <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-yen"></i> fa-yen <span class="text-muted">(alias)</span>
                                            </div>
                                        </div>

                                        <!-- new sets -->

                                        <h2>Text Editor Icons</h2>

                                        <div class="row">
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-align-center"></i> fa-align-center
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-align-justify"></i> fa-align-justify
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-align-left"></i> fa-align-left
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-align-right"></i> fa-align-right
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-bold"></i> fa-bold
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-chain"></i> fa-chain <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-chain-broken"></i> fa-chain-broken
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-clipboard"></i> fa-clipboard
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-columns"></i> fa-columns
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-copy"></i> fa-copy <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-cut"></i> fa-cut <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-dedent"></i> fa-dedent <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-eraser"></i> fa-eraser
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-file"></i> fa-file
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-file-o"></i> fa-file-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-file-text"></i> fa-file-text
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-file-text-o"></i> fa-file-text-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-files-o"></i> fa-files-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-floppy-o"></i> fa-floppy-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-font"></i> fa-font
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-indent"></i> fa-indent
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-italic"></i> fa-italic
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-link"></i> fa-link
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-list"></i> fa-list
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-list-alt"></i> fa-list-alt
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-list-ol"></i> fa-list-ol
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-list-ul"></i> fa-list-ul
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-outdent"></i> fa-outdent
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-paperclip"></i> fa-paperclip
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-paste"></i> fa-paste <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-repeat"></i> fa-repeat
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-rotate-left"></i> fa-rotate-left <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-rotate-right"></i> fa-rotate-right <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-save"></i> fa-save <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-scissors"></i> fa-scissors
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-strikethrough"></i> fa-strikethrough
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-table"></i> fa-table
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-text-height"></i> fa-text-height
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-text-width"></i> fa-text-width
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-th"></i> fa-th
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-th-large"></i> fa-th-large
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-th-list"></i> fa-th-list
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-underline"></i> fa-underline
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-undo"></i> fa-undo
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-unlink"></i> fa-unlink <span class="text-muted">(alias)</span>
                                            </div>
                                        </div>

                                        <!-- new sets -->

                                        <h2>Directional Icons</h2>

                                        <div class="row">
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-angle-double-down"></i> fa-angle-double-down
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-angle-double-left"></i> fa-angle-double-left
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-angle-double-right"></i> fa-angle-double-right
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-angle-double-up"></i> fa-angle-double-up
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-angle-down"></i> fa-angle-down
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-angle-left"></i> fa-angle-left
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-angle-right"></i> fa-angle-right
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-angle-up"></i> fa-angle-up
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-arrow-circle-down"></i> fa-arrow-circle-down
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-arrow-circle-left"></i> fa-arrow-circle-left
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-arrow-circle-o-down"></i> fa-arrow-circle-o-down
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-arrow-circle-o-left"></i> fa-arrow-circle-o-left
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-arrow-circle-o-right"></i> fa-arrow-circle-o-right
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-arrow-circle-o-up"></i> fa-arrow-circle-o-up
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-arrow-circle-right"></i> fa-arrow-circle-right
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-arrow-circle-up"></i> fa-arrow-circle-up
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-arrow-down"></i> fa-arrow-down
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-arrow-left"></i> fa-arrow-left
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-arrow-right"></i> fa-arrow-right
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-arrow-up"></i> fa-arrow-up
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-caret-down"></i> fa-caret-down
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-caret-left"></i> fa-caret-left
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-caret-right"></i> fa-caret-right
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-caret-square-o-down"></i> fa-caret-square-o-down
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-caret-square-o-left"></i> fa-caret-square-o-left
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-caret-square-o-right"></i> fa-caret-square-o-right
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-caret-square-o-up"></i> fa-caret-square-o-up
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-caret-up"></i> fa-caret-up
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-chevron-circle-down"></i> fa-chevron-circle-down
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-chevron-circle-left"></i> fa-chevron-circle-left
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-chevron-circle-right"></i> fa-chevron-circle-right
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-chevron-circle-up"></i> fa-chevron-circle-up
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-chevron-down"></i> fa-chevron-down
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-chevron-left"></i> fa-chevron-left
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-chevron-right"></i> fa-chevron-right
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-chevron-up"></i> fa-chevron-up
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-hand-o-down"></i> fa-hand-o-down
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-hand-o-left"></i> fa-hand-o-left
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-hand-o-right"></i> fa-hand-o-right
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-hand-o-up"></i> fa-hand-o-up
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-long-arrow-down"></i> fa-long-arrow-down
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-long-arrow-left"></i> fa-long-arrow-left
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-long-arrow-right"></i> fa-long-arrow-right
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-long-arrow-up"></i> fa-long-arrow-up
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-toggle-down"></i> fa-toggle-down <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-toggle-left"></i> fa-toggle-left <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-toggle-right"></i> fa-toggle-right <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-toggle-up"></i> fa-toggle-up <span class="text-muted">(alias)</span>
                                            </div>
                                        </div>

                                        <!-- new sets -->

                                        <h2>Video Player Icons</h2>

                                        <div class="row">
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-backward"></i> fa-backward
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-eject"></i> fa-eject
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-fast-backward"></i> fa-fast-backward
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-fast-forward"></i> fa-fast-forward
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-forward"></i> fa-forward
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-arrows-alt"></i> fa-arrows-alt
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-pause"></i> fa-pause
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-play"></i> fa-play
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-play-circle"></i> fa-play-circle
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-play-circle-o"></i> fa-play-circle-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-expand"></i> fa-expand
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-compress"></i> fa-compress
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-step-backward"></i> fa-step-backward
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-step-forward"></i> fa-step-forward
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-stop"></i> fa-stop
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-youtube-play"></i> fa-youtube-play
                                            </div>
                                        </div>

                                        <!-- new sets -->

                                        <h2>Brand Icons</h2>

                                        <div class="alert alert-success">
                                            <ul class="margin-bottom-none padding-left-lg">
                                                <li>
                                                    All brand icons are trademarks of their respective owners.
                                                </li>
                                                <li>
                                                    The use of these trademarks does not indicate endorsement of the trademark holder by Font Awesome, nor vice versa.
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-adn"></i> fa-adn
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-android"></i> fa-android
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-apple"></i> fa-apple
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-bitbucket"></i> fa-bitbucket
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-bitbucket-square"></i> fa-bitbucket-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-bitcoin"></i> fa-bitcoin <span class="text-muted">(alias)</span>
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-btc"></i> fa-btc
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-css3"></i> fa-css3
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-dribbble"></i> fa-dribbble
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-dropbox"></i> fa-dropbox
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-facebook"></i> fa-facebook
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-facebook-square"></i> fa-facebook-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-flickr"></i> fa-flickr
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-foursquare"></i> fa-foursquare
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-github"></i> fa-github
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-github-alt"></i> fa-github-alt
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-github-square"></i> fa-github-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-gittip"></i> fa-gittip
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-google-plus"></i> fa-google-plus
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-google-plus-square"></i> fa-google-plus-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-html5"></i> fa-html5
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-instagram"></i> fa-instagram
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-linkedin"></i> fa-linkedin
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-linkedin-square"></i> fa-linkedin-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-linux"></i> fa-linux
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-maxcdn"></i> fa-maxcdn
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-pagelines"></i> fa-pagelines
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-pinterest"></i> fa-pinterest
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-pinterest-square"></i> fa-pinterest-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-renren"></i> fa-renren
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-skype"></i> fa-skype
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-stack-exchange"></i> fa-stack-exchange
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-stack-overflow"></i> fa-stack-overflow
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-trello"></i> fa-trello
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-tumblr"></i> fa-tumblr
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-tumblr-square"></i> fa-tumblr-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-twitter"></i> fa-twitter
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-twitter-square"></i> fa-twitter-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-vimeo-square"></i> fa-vimeo-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-vk"></i> fa-vk
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-weibo"></i> fa-weibo
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-windows"></i> fa-windows
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-xing"></i> fa-xing
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-xing-square"></i> fa-xing-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-youtube"></i> fa-youtube
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-youtube-play"></i> fa-youtube-play
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-youtube-square"></i> fa-youtube-square
                                            </div>
                                        </div>

                                        <!-- new sets -->

                                        <h2>Medical Icons</h2>

                                        <div class="row">
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-ambulance"></i> fa-ambulance
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-h-square"></i> fa-h-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-hospital-o"></i> fa-hospital-o
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-medkit"></i> fa-medkit
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-plus-square"></i> fa-plus-square
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-stethoscope"></i> fa-stethoscope
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-user-md"></i> fa-user-md
                                            </div>
                                            <div class="col-xs-6 col-md-3 col-sm-4 demo-icon-font">
                                                <i class="fa fa-wheelchair"></i> fa-wheelchair
                                            </div>
                                        </div>

                                        <!-- END sets -->
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
            </div>
        </div>
    </div>
</div>

<!-- Modal: 아이콘2 팁 -->
<div class="modal fade" id="modal-icon-glyph">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">아이콘 - Font Awesome</h4>
            </div>
            <div class="modal-body">
                <!-- widget grid -->
                <section id="widget-grid" class="">
                    <!-- row -->
                    <div class="row">
                        <!-- NEW WIDGET START -->
                        <article class="col-sm-12">

                            <!-- Widget ID (each widget will need unique ID)-->
                            <div class="jarviswidget jarviswidget-color-blue" id="wid-id-4" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false">
                                <header>
                                    <h2>Glyph Icons </h2>
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

                                        <ul class="bs-glyphicons">
                                            <li>
                                                <span class="glyphicon glyphicon-adjust"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-adjust</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-align-center"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-align-center</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-align-justify"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-align-justify</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-align-left"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-align-left</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-align-right"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-align-right</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-arrow-down"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-arrow-down</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-arrow-left"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-arrow-left</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-arrow-right"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-arrow-right</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-arrow-up"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-arrow-up</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-asterisk"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-asterisk</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-backward"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-backward</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-ban-circle"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-ban-circle</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-barcode"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-barcode</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-bell"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-bell</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-bold"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-bold</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-book"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-book</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-bookmark"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-bookmark</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-briefcase"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-briefcase</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-bullhorn"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-bullhorn</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-calendar"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-calendar</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-camera"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-camera</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-certificate"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-certificate</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-check"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-check</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-chevron-down"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-chevron-down</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-chevron-left"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-chevron-left</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-chevron-right"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-chevron-right</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-chevron-up"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-chevron-up</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-circle-arrow-down"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-circle-arrow-down</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-circle-arrow-left"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-circle-arrow-left</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-circle-arrow-right"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-circle-arrow-right</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-circle-arrow-up"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-circle-arrow-up</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-cloud"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-cloud</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-cloud-download"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-cloud-download</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-cloud-upload"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-cloud-upload</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-cog"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-cog</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-collapse-down"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-collapse-down</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-collapse-up"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-collapse-up</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-comment"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-comment</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-compressed"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-compressed</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-copyright-mark"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-copyright-mark</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-credit-card"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-credit-card</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-cutlery"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-cutlery</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-dashboard"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-dashboard</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-download"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-download</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-download-alt"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-download-alt</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-earphone"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-earphone</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-edit"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-edit</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-eject"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-eject</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-envelope"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-envelope</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-euro"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-euro</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-exclamation-sign"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-exclamation-sign</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-expand"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-expand</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-export"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-export</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-eye-close"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-eye-close</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-eye-open"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-eye-open</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-facetime-video"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-facetime-video</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-fast-backward"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-fast-backward</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-fast-forward"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-fast-forward</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-file"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-file</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-film"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-film</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-filter"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-filter</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-fire"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-fire</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-flag"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-flag</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-flash"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-flash</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-floppy-disk"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-floppy-disk</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-floppy-open"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-floppy-open</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-floppy-remove"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-floppy-remove</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-floppy-save"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-floppy-save</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-floppy-saved"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-floppy-saved</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-folder-close"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-folder-close</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-folder-open"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-folder-open</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-font"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-font</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-forward"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-forward</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-fullscreen"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-fullscreen</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-gbp"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-gbp</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-gift"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-gift</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-glass"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-glass</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-globe"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-globe</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-hand-down"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-hand-down</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-hand-left"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-hand-left</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-hand-right"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-hand-right</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-hand-up"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-hand-up</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-hd-video"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-hd-video</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-hdd"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-hdd</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-header"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-header</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-headphones"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-headphones</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-heart"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-heart</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-heart-empty"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-heart-empty</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-home"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-home</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-import"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-import</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-inbox"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-inbox</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-indent-left"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-indent-left</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-indent-right"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-indent-right</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-info-sign"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-info-sign</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-italic"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-italic</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-leaf"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-leaf</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-link"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-link</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-list"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-list</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-list-alt"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-list-alt</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-lock"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-lock</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-log-in"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-log-in</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-log-out"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-log-out</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-magnet"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-magnet</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-map-marker"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-map-marker</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-minus"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-minus</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-minus-sign"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-minus-sign</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-move"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-move</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-music"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-music</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-new-window"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-new-window</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-off"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-off</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-ok"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-ok</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-ok-circle"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-ok-circle</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-ok-sign"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-ok-sign</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-open"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-open</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-paperclip"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-paperclip</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-pause"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-pause</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-pencil"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-pencil</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-phone"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-phone</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-phone-alt"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-phone-alt</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-picture"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-picture</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-plane"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-plane</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-play"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-play</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-play-circle"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-play-circle</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-plus"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-plus</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-plus-sign"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-plus-sign</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-print"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-print</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-pushpin"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-pushpin</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-qrcode"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-qrcode</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-question-sign"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-question-sign</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-random"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-random</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-record"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-record</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-refresh"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-refresh</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-registration-mark"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-registration-mark</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-remove"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-remove</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-remove-circle"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-remove-circle</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-remove-sign"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-remove-sign</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-repeat"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-repeat</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-resize-full"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-resize-full</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-resize-horizontal"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-resize-horizontal</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-resize-small"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-resize-small</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-resize-vertical"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-resize-vertical</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-retweet"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-retweet</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-road"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-road</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-save"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-save</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-saved"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-saved</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-screenshot"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-screenshot</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-sd-video"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-sd-video</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-search"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-search</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-send"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-send</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-share"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-share</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-share-alt"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-share-alt</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-shopping-cart"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-shopping-cart</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-signal"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-signal</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-sort"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-sort</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-sort-by-alphabet"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-sort-by-alphabet</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-sort-by-alphabet-alt"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-sort-by-alphabet-alt</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-sort-by-attributes"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-sort-by-attributes</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-sort-by-attributes-alt"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-sort-by-attributes-alt</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-sort-by-order"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-sort-by-order</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-sort-by-order-alt"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-sort-by-order-alt</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-sound-5-1"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-sound-5-1</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-sound-6-1"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-sound-6-1</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-sound-7-1"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-sound-7-1</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-sound-dolby"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-sound-dolby</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-sound-stereo"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-sound-stereo</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-star"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-star</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-star-empty"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-star-empty</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-stats"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-stats</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-step-backward"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-step-backward</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-step-forward"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-step-forward</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-stop"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-stop</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-subtitles"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-subtitles</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-tag"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-tag</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-tags"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-tags</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-tasks"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-tasks</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-text-height"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-text-height</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-text-width"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-text-width</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-th"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-th</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-th-large"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-th-large</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-th-list"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-th-list</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-thumbs-down"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-thumbs-down</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-thumbs-up"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-thumbs-up</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-time"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-time</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-tint"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-tint</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-tower"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-tower</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-transfer"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-transfer</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-trash"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-trash</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-tree-conifer"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-tree-conifer</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-tree-deciduous"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-tree-deciduous</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-unchecked"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-unchecked</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-upload"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-upload</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-usd"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-usd</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-user"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-user</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-volume-down"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-volume-down</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-volume-off"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-volume-off</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-volume-up"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-volume-up</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-warning-sign"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-warning-sign</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-wrench"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-wrench</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-zoom-in"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-zoom-in</span>
                                            </li>
                                            <li>
                                                <span class="glyphicon glyphicon-zoom-out"></span>
                                                <span class="glyphicon-class">.glyphicon .glyphicon-zoom-out</span>
                                            </li>
                                        </ul>

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

                    </div>

                    <!-- end row -->

                </section>
                <!-- end widget grid -->
            </div>
        </div>
    </div>
</div>