
<style type="text/css">
    #frmNewAccount .selectpicker {
        padding-left: 12px;
        padding-top: 6px;
        padding-bottom: 6px;
    }
    ul.dropdown-menu.selectpicker {
        padding-left: 0 !important;
    }
</style>

<script src="<?=GD_ASSETS_PATH?>/js/plugin/datatables/jquery.dataTables.min.js"></script>
<script src="<?=GD_ASSETS_PATH?>/js/plugin/datatables/dataTables.colVis.min.js"></script>
<script src="<?=GD_ASSETS_PATH?>/js/plugin/datatables/dataTables.tableTools.min.js"></script>
<script src="<?=GD_ASSETS_PATH?>/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?=GD_ASSETS_PATH?>/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>

<script type="text/javascript">
    /**
     * Class: 관리자 계정
     */
    var account = {
        /**
         * 아이디 중복 체크여부
         */
        isUniqueIDStatus: false,

        /**
         * 신규계정 추가
         */
        add: function () {
            var $frm = $(document.frmNewAccount);

            if (confirm("저장 하시겠습니까?")) {
                $.ajax({
                    url: "/configAdmin/accountAddDo",
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
                            alert("등록 되었습니다");
                            window.location.href = "/configadmin/account?<?=$page['depth']?>";
                        } else {
                            alert(data.errMsg);
                            return false;
                        }
                    }
                });
            }
        },

        /**
         * 계정 수정
         */
        loadAccountForUpdate: function (seq) {
            var $frm = $(document.frmUpdateAccount);

            $.ajax({
                url: "/configAdmin/getAdminDetail",
                type: "POST",
                dataType: "json",
                data: {seq: seq},
                beforeSend: function () {
                    // write code here before submit
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log("code: " + xhr.status);
                },
                success: function(data, textStatus, xhr) {
                    $("[name=seq]", $frm).val(data.seq);        // 계정 고유 시퀀스
                    $("[name=adminId]", $frm).val(data.adminId);// 아이디
                    $("[name=adminId_disp]", $frm).val(data.adminId);// 아이디
                    $("[name=name]", $frm).val(data.name);      // 이름
                    $("[name=email]", $frm).val(data.email);    // 이메일
                    $("[name=phone]", $frm).val(data.phone);    // 폰번호
                    $("[name=grSeq]", $frm).val(data.grSeq);    // 관리자그룹 고유 시퀀스
                    $("[name=status]", $frm).val(data.status);  // 계정 상태
                    $("[name=memo]", $frm).html(data.memo);     // 메모
                }
            });
        },

        /**
         * 계정 정보 수정
         */
        update: function () {
            var $frm = $(document.frmUpdateAccount);

            // 계정 정보 저장
            if (confirm("저장 하시겠습니까?")) {
                $.ajax({
                    url: "/configAdmin/accountUpdateDo",
                    type: "POST",
                    dataType: "json",
                    data: $frm.serialize(),
                    beforeSend: function () {
                        // write code here before submit
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log("code: " + xhr.status);
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
         * 계정 삭제
         */
        remove: function () {
            var $frm = $(document.frmUpdateAccount);

            if (confirm("삭제 하시겠습니까?")) {
                $.ajax({
                    url: "/configAdmin/accountRemoveDo",
                    type: "POST",
                    dataType: "json",
                    data: $frm.serialize(),
                    beforeSend: function () {
                        // write code here before submit
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log("code: " + xhr.status);
                    },
                    success: function(data, textStatus, xhr) {
                        alert(data.errMsg);

                        if (data.status == true) {
                            util.move('account?<?=$page['depth']?>');
                        } else {
                            return false;
                        }
                    }
                });
            }
        },

        /**
         * 아이디 중복 체크
         */
        isUniqueAdminId: function () {
            var $frm = $(document.frmNewAccount);
            var $oAdminId = $("[name=adminId]", $frm);
            var $msgArea = $("#errMsgAdminId");

            // 조건부 에러 메세지 설정
            if ($oAdminId.val().length == 0) {
                this.isUniqueIDStatus = false;
                $msgArea.html("<span>영문 및 숫자로 4자 이상 20자 이하로 등록 해주세요</span>");
                return false;
            }

            if ($oAdminId.val().length < 4) {
                this.isUniqueIDStatus = false;
                $msgArea.html("<span class='text-danger'>ID는 최소 4자 이상이어야 합니다</span>");
                return false;
            }

            $.ajax({
                url: "/configAdmin/isUniqueAdminId",
                type: "POST",
                dataType: "json",
                data: {adminId: $oAdminId.val()},
                beforeSend: function () {
                    // write code here before submit
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log("code : " + xhr.status);
                },
                success: function(data, textStatus, xhr) {
                    if (data.status == true) {
                        account.isUniqueIDStatus = true;
                        classStatus = "text-success";
                    } else {
                        account.isUniqueIDStatus = false;
                        classStatus = "text-danger";
                    }
                    $msgArea.html("<span class='"+classStatus+"'>"+data.errMsg+"</span>");
                }
            });
        }
    }

    /**
     * 페이지 로딩시 실행 이벤트
     */
    $(function () {
        /* Data table 초기화 */
        var responsiveHelper_dt_basic = undefined;
        var responsiveHelper_datatable_fixed_column = undefined;
        var responsiveHelper_datatable_col_reorder = undefined;
        var responsiveHelper_datatable_tabletools = undefined;

        var breakpointDefinition = {
            tablet : 1024,
            phone : 480
        };

        $('#dt_admin').dataTable({
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
                "t"+
                "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
            "autoWidth" : true,
            "oLanguage": {
                "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
            },
            "pageLength": 25,
            "preDrawCallback" : function() {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_dt_basic) {
                    responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_admin'), breakpointDefinition);
                }
            },
            "rowCallback" : function(nRow) {
                responsiveHelper_dt_basic.createExpandIcon(nRow);
            },
            "drawCallback" : function(oSettings) {
                responsiveHelper_dt_basic.respond();
            }
        });

        // Data table에 버튼영역 추가
        $("#dt_admin_wrapper").prepend(
            '<div class="p-10 p-b-0 text-right">'+
            '    <a href="#modal-addaccount" class="btn btn-primary" data-toggle="modal">신규계정등록</a>'+
            '    <input type="button" class="btn btn-default" value="목록보기" onclick="util.move(window.location.href)">'+
            '</div>'+
            '<hr class="simple">'
        );
        $("#dt_admin_wrapper").addClass('bg-silver-lighter-more');

        // 셀렉트 박스 플러그인 설정
        $(".selectpicker").selectpicker('refresh');


        // 유효성체크 플러그인 설정
        var errorClass = 'invalid';
        var errorElement = 'em';

        // 신규 계정등록 유효성 체크
        var $newAccountForm = $("#frmNewAccount").validate({
            errorClass      : errorClass,
            errorElement    : errorElement,
            highlight: function(element) {
                $(element).parent().removeClass('state-success').addClass("state-error");
                $(element).removeClass('valid');
            },
            unhighlight: function(element) {
                $(element).parent().removeClass("state-error").addClass('state-success');
                $(element).addClass('valid');
            },
            // Rules for form validation
            rules : {
                adminId : {
                    required : true
                },
                adminPw : {
                    required : true,
                    minlength : 4,
                    maxlength : 20
                },
                adminPwConfirm : {
                    required : true,
                    minlength : 4,
                    maxlength : 20,
                    equalTo : '#adminPw'
                },
                name : {
                    required : true
                },
                grSeq : {
                    required : true
                },
                status : {
                    required : true
                }
            },

            // Messages for form validation
            messages : {
                adminId : {
                    required : ''
                },
                adminPw : {
                    required : '비밀번호를 입력해주세요',
                    minlength : $.validator.format('비밀번호는 {0}자 이상이어야 합니다.'),
                    maxlength : $.validator.format('비밀번호는 {0}자 이하여야 합니다.')
                },
                adminPwConfirm : {
                    required : '비밀번호를 한번 더 입력해주세요',
                    equalTo : '비밀번호가 일치하지 않습니다. 정확히 입력해주세요',
                    minlength : $.validator.format('비밀번호는 {0}자 이상이어야 합니다.'),
                    maxlength : $.validator.format('비밀번호는 {0}자 이하여야 합니다.')
                },
                name : {
                    required : '이름을 입력해주세요'
                },
                grSeq : {
                    required : '관리자권한 그룹을 선택해주세요'
                },
                status : {
                    required : '계정상태를 선택해주세요'
                }
            },

            // 유효성 체크 후 실행 이벤트
            submitHandler: function(form) {
                account.add();
            },

            // Do not change code below
            errorPlacement : function(error, element) {
                error.insertAfter(element.parent());
            }
        });

        // 계정 정보 수정 유효성 체크
        var $updateAccountForm = $("#frmUpdateAccount").validate({
            errorClass      : errorClass,
            errorElement    : errorElement,
            highlight: function(element) {
                $(element).parent().removeClass('state-success').addClass("state-error");
                $(element).removeClass('valid');
            },
            unhighlight: function(element) {
                $(element).parent().removeClass("state-error").addClass('state-success');
                $(element).addClass('valid');
            },
            // Rules for form validation
            rules : {
                adminPw : {
                    minlength : 4,
                    maxlength : 20
                },
                name : {
                    required : true
                },
                grSeq : {
                    required : true
                },
                status : {
                    required : true
                }
            },

            // Messages for form validation
            messages : {
                adminPw : {
                    minlength : $.validator.format('비밀번호는 {0}자 이상이어야 합니다.'),
                    maxlength : $.validator.format('비밀번호는 {0}자 이하여야 합니다.')
                },
                name : {
                    required : '이름을 입력해주세요'
                },
                grSeq : {
                    required : '관리자권한 그룹을 선택해주세요'
                },
                status : {
                    required : '계정상태를 선택해주세요'
                }
            },

            // 유효성 체크 후 실행 이벤트
            submitHandler: function(form) {
                account.update();
            },

            // Do not change code below
            errorPlacement : function(error, element) {
                error.insertAfter(element.parent());
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
                        <h2>관리자 계정 목록</h2>
                    </header>

                    <!-- widget div-->
                    <div>
                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->
                        </div>
                        <!-- end widget edit box -->

                        <!-- widget content -->
                        <div class="widget-body no-padding">

                            <table id="dt_admin" class="table table-striped table-bordered table-hover" width="100%">
                                <thead>
                                    <tr>
                                        <th data-hide="phone,tablet">아이디</th>
                                        <th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> 이름</th>
                                        <th data-hide="phone,tablet" data-class="expand"><i class="fa fa-fw fa-group text-muted hidden-md hidden-sm hidden-xs"></i> 관리권한</th>
                                        <th data-hide="phone,tablet"><i class="fa fa-fw fa-phone text-muted hidden-md hidden-sm hidden-xs"></i> 전화번호</th>
                                        <th data-hide="phone,tablet"><i class="fa fa-fw fa-envelope-o text-muted hidden-md hidden-sm hidden-xs"></i> 이메일</th>
                                        <th data-hide="phone,tablet"><i class="fa fa-fw fa-calendar hidden-md hidden-sm hidden-xs"></i> 등록일자</th>
                                        <th>상태</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?
                                    foreach ($adminList as $key => $item) {
                                        # 계정 상태별 CSS 설정
                                        switch ($item->status) {
                                            // 활동중
                                            case 1: $classStatus = "text-primary"; break;
                                            // 사용정지
                                            case 2: $classStatus = "text-warning"; break;
                                            // 퇴사
                                            case 3: $classStatus = "text-danger"; break;
                                            default: $classStatus = ""; break;
                                        }
                                    ?>
                                    <tr>
                                        <td><?=$item->adminId?></td>
                                        <td><a href="#modal-updateaccount" onclick="account.loadAccountForUpdate('<?=$item->seq?>')" data-toggle="modal"><?=$item->name?></a></td>
                                        <td><?=$codeToHtml->getGroupToText($item->grSeq)?></td>
                                        <td><?=$item->phone?></td>
                                        <td><?=$item->email?></td>
                                        <td><?=$item->regDate?></td>
                                        <td class="<?=$classStatus?>"><?=$codeToHtml->getCodeToText('accountstate', $item->status)?></td>
                                    </tr>
                                    <?
                                    }
                                    ?>
                                </tbody>
                            </table>

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

<!-- Modal: 관리자계정 등록 -->
<div class="modal fade" id="modal-addaccount">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">관리자계정 등록</h4>
            </div>
            <form id="frmNewAccount" name="frmNewAccount" class="smart-form" novalidate="novalidate">
                <fieldset>
                    <div class="row">
                        <section class="col col-6">
                            <label class="label">아이디</label>
                            <label class="input">
                                <i class="icon-append fa fa-user"></i>
                                <input type="text" name="adminId" maxlength="20" class="not-ko" onkeyup="util.notko($(this));account.isUniqueAdminId()">
                            </label>
                        </section>
                        <section class="col col-6 p-t-30">
                            <div>
                                <label id="errMsgAdminId" class="label"><span>영문 및 숫자로 4자 이상 20자 이하로 등록 해주세요</span></label>
                            </div>
                        </section>

                    </div>
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
                    <div class="row">
                        <section class="col col-6">
                            <label class="label">이름</label>
                            <label class="input">
                                <i class="icon-append fa fa-user"></i>
                                <input type="text" name="name">
                            </label>
                        </section>
                        <section class="col col-6">
                            <label class="label">이메일</label>
                            <label class="input">
                                <i class="icon-append fa fa-envelope-o"></i>
                                <input type="email" name="email">
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-6">
                            <label class="label">전화번호</label>
                            <label class="input"> <i class="icon-prepend fa fa-phone"></i>
                                <input type="tel" name="phone" placeholder="전화번호" data-mask="(999) 9999-9999">
                            </label>
                        </section>
                        <section class="col col-6">
                            <label class="label">관리자권한 그룹</label>
                            <label class="label">
                                <select name="grSeq" class="form-control">
                                    <option value="">선택해주세요</option>
                                    <?=$codeToHtml->getGroupListSelectBox()?>
                                </select>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-6">
                            <label class="label">계정상태</label>
                            <label class="label">
                                <select name="status" class="form-control">
                                    <option value="">선택해주세요</option>
                                    <?=$codeToHtml->getCodeListSelectBox('accountstate')?>
                                </select>
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
                    <button type="submit" class="btn btn-primary">등록</button>
                </footer>
            </form>
        </div>
    </div>
</div>
<!--// Modal: 관리자계정 등록 -->

<!-- Modal: 관리자계정 정보 수정 -->
<div class="modal fade" id="modal-updateaccount">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">관리자계정 정보 수정</h4>
            </div>
            <form id="frmUpdateAccount" name="frmUpdateAccount" class="smart-form" novalidate="novalidate">
                <input type="hidden" name="seq" value="">
                <input type="hidden" name="adminId" value="">

                <fieldset>
                    <div class="row">
                        <section class="col col-6">
                            <label class="label">아이디</label>
                            <label class="input">
                                <i class="icon-append fa fa-user"></i>
                                <input type="text" name="adminId_disp" maxlength="20" class="not-ko" disabled="disabled">
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-6">
                            <label class="label">비밀번호</label>
                            <label class="input">
                                <i class="icon-append fa fa-lock"></i>
                                <input type="password" name="adminPw" maxlength="20" value="">
                            </label>
                        </section>
                        <section class="col col-6 hide">
                            <label class="label">비밀번호 확인</label>
                            <label class="input">
                                <i class="icon-append fa fa-lock"></i>
                                <input type="password" name="adminPwConfirm" maxlength="20">
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-10">
                            <div class="txt-color-red">비밀번호 항목은 비밀번호 수정시에만 입력해주세요</div>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-6">
                            <label class="label">이름</label>
                            <label class="input">
                                <i class="icon-append fa fa-user"></i>
                                <input type="text" name="name">
                            </label>
                        </section>
                        <section class="col col-6">
                            <label class="label">이메일</label>
                            <label class="input">
                                <i class="icon-append fa fa-envelope-o"></i>
                                <input type="email" name="email">
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-6">
                            <label class="label">전화번호</label>
                            <label class="input"> <i class="icon-prepend fa fa-phone"></i>
                                <input type="tel" name="phone" placeholder="전화번호" data-mask="(999) 9999-9999">
                            </label>
                        </section>
                        <section class="col col-6">
                            <label class="label">관리자권한 그룹</label>
                            <label class="label">
                                <select name="grSeq" class="form-control">
                                    <option value="">선택해주세요</option>
                                    <?=$codeToHtml->getGroupListSelectBox()?>
                                </select>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-6">
                            <label class="label">계정상태</label>
                            <label class="label">
                                <select name="status" class="form-control">
                                    <option value="">선택해주세요</option>
                                    <?=$codeToHtml->getCodeListSelectBox('accountstate')?>
                                </select>
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
                    <button type="submit" class="btn btn-primary">정보수정</button>
                    <button type="button" onclick="account.remove()" class="btn btn-danger">계정삭제</button>
                </footer>
            </form>
        </div>
    </div>
</div>
<!--// Modal: 관리자계정 정보 수정 -->
