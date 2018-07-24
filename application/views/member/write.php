
<script type="text/javascript">
    /**
     * Class: 사용자 계정
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
                    url: "/member/accountAddDo",
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
                            window.location.href = "/member/lists?<?=$page['depth']?>";
                        } else {
                            alert(data.errMsg);
                            return false;
                        }
                    }
                });
            }
        },

        /**
         * 포인트 입력시 세자리 콤마
         */
        pointComma: function (num) {
            var $frm = $(document.frmNewAccount);
            $("input[name=point]", $frm).val(util.comma(util.uncomma(num)));
        },

        /**
         * 아이디 중복 체크
         */
        isUniqueUserId: function () {
            var $frm = $(document.frmNewAccount);
            var $oUserId = $("[name=userId]", $frm);
            var $msgArea = $("#errMsgUserId");

            // 조건부 에러 메세지 설정
            if ($oUserId.val().length == 0) {
                this.isUniqueIDStatus = false;
                $msgArea.html("<span>영문 및 숫자로 4자 이상 20자 이하로 등록 해주세요</span>");
                return false;
            }

            if ($oUserId.val().length < 4) {
                this.isUniqueIDStatus = false;
                $msgArea.html("<span class='text-danger'>ID는 최소 4자 이상이어야 합니다</span>");
                return false;
            }

            $.ajax({
                url: "/member/isUniqueUserId",
                type: "POST",
                dataType: "json",
                data: {userId: $oUserId.val()},
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
                userId : {
                    required : true
                },
                userPw : {
                    required : true,
                    minlength : 4,
                    maxlength : 20
                },
                userPwConfirm : {
                    required : true,
                    minlength : 4,
                    maxlength : 20,
                    equalTo : '#userPw'
                },
                exchangePw : {
                    required : true,
                    minlength : 4,
                    maxlength : 20
                },
                exchangePwConfirm : {
                    required : true,
                    minlength : 4,
                    maxlength : 20,
                    equalTo : '#exchangePw'
                },
                nickname : {
                    required : true
                }
            },

            // Messages for form validation
            messages : {
                userId : {
                    required : ''
                },
                userPw : {
                    required : '비밀번호를 입력해주세요',
                    minlength : $.validator.format('비밀번호는 {0}자 이상이어야 합니다.'),
                    maxlength : $.validator.format('비밀번호는 {0}자 이하여야 합니다.')
                },
                userPwConfirm : {
                    required : '비밀번호를 한번 더 입력해주세요',
                    equalTo : '비밀번호가 일치하지 않습니다. 정확히 입력해주세요',
                    minlength : $.validator.format('비밀번호는 {0}자 이상이어야 합니다.'),
                    maxlength : $.validator.format('비밀번호는 {0}자 이하여야 합니다.')
                },
                exchangePw : {
                    required : '비밀번호를 입력해주세요',
                    minlength : $.validator.format('비밀번호는 {0}자 이상이어야 합니다.'),
                    maxlength : $.validator.format('비밀번호는 {0}자 이하여야 합니다.')
                },
                exchangePwConfirm : {
                    required : '비밀번호를 한번 더 입력해주세요',
                    equalTo : '비밀번호가 일치하지 않습니다. 정확히 입력해주세요',
                    minlength : $.validator.format('비밀번호는 {0}자 이상이어야 합니다.'),
                    maxlength : $.validator.format('비밀번호는 {0}자 이하여야 합니다.')
                },
                nickname : {
                    required : '닉네임을 입력해주세요'
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
                        <h2>신규회원 등록</h2>
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

                            <!-- 신규회원 등록 폼 -->
                            <form id="frmNewAccount" name="frmNewAccount" class="smart-form" novalidate="novalidate">
                                <fieldset>
                                    <div class="row">
                                        <section class="col col-6">
                                            <label class="label">아이디</label>
                                            <label class="input">
                                                <i class="icon-append fa fa-user"></i>
                                                <input type="text" name="userId" maxlength="20" class="not-ko" onkeyup="util.notko($(this));account.isUniqueUserId()">
                                            </label>
                                        </section>
                                        <section class="col col-6 p-t-30">
                                            <div>
                                                <label id="errMsgUserId" class="label"><span>영문 및 숫자로 4자 이상 20자 이하로 등록 해주세요</span></label>
                                            </div>
                                        </section>

                                    </div>
                                    <div class="row">
                                        <section class="col col-6">
                                            <label class="label">비밀번호</label>
                                            <label class="input">
                                                <i class="icon-append fa fa-lock"></i>
                                                <input type="password" id="userPw" name="userPw" maxlength="20">
                                            </label>
                                        </section>
                                        <section class="col col-6">
                                            <label class="label">비밀번호 확인</label>
                                            <label class="input">
                                                <i class="icon-append fa fa-lock"></i>
                                                <input type="password" name="userPwConfirm" maxlength="20">
                                            </label>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <section class="col col-6">
                                            <label class="label">환전 비밀번호</label>
                                            <label class="input">
                                                <i class="icon-append fa fa-lock"></i>
                                                <input type="password" id="exchangePw" name="exchangePw" maxlength="20">
                                            </label>
                                        </section>
                                        <section class="col col-6">
                                            <label class="label">환전 비밀번호 확인</label>
                                            <label class="input">
                                                <i class="icon-append fa fa-lock"></i>
                                                <input type="password" name="exchangePwConfirm" maxlength="20">
                                            </label>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <section class="col col-6">
                                            <label class="label">닉네임</label>
                                            <label class="input">
                                                <i class="icon-append fa fa-user"></i>
                                                <input type="text" name="nickname" maxlength="5">
                                            </label>
                                        </section>
                                        <section class="col col-6">
                                            <label class="label">포인트</label>
                                            <label class="input">
                                                <input type="text" name="point" maxlength="20" class="form-control p-l-10 p-r-10" onblur="account.pointComma(this.value)" value="0" disabled="disabled">
                                            </label>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <section class="col col-6">
                                            <label class="label">이메일</label>
                                            <label class="input">
                                                <i class="icon-append fa fa-envelope-o"></i>
                                                <input type="email" name="email" maxlength="50">
                                            </label>
                                        </section>
                                        <section class="col col-6">
                                            <label class="label">핸드폰번호</label>
                                            <label class="input"> <i class="icon-prepend fa fa-phone"></i>
                                                <input type="tel" name="phone" placeholder="핸드폰번호" data-mask="999-9999-9999">
                                            </label>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <section class="col col-6">
                                            <label class="label">레벨</label>
                                            <label class="input">
                                                <select name="level" class="form-control">
                                                    <option value="">승인대기</option>
                                                </select>
                                            </label>
                                        </section>
                                        <section class="col col-6">
                                            <label class="label">회원타입</label>
                                            <label class="input">
                                                <select name="accountType" class="form-control">
                                                    <?=$codeToHtml->getCodeListSelectBox('accountType')?>
                                                </select>
                                            </label>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <section class="col col-6">
                                            <label class="label">블랙리스트</label>
                                            <label class="input">
                                                <select name="blacklistType" class="form-control">
                                                    <?=$codeToHtml->getCodeListSelectBox('blacklist')?>
                                                </select>
                                            </label>
                                        </section>
                                        <section class="col col-6">
                                            <label class="label">계정상태</label>
                                            <label class="label">
                                                <select name="status" class="form-control">
                                                    <?=$codeToHtml->getCodeListSelectBox('useraccountstatus')?>
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

                                <fieldset>
                                    <div class="row">
                                        <section class="col col-6">
                                            <label class="label">출금 은행명</label>
                                            <label class="input">
                                                <select name="bankName" class="form-control">
                                                    <option value="">선택해주세요</option>
                                                    <?=$codeToHtml->getCodeListSelectBox('bankname')?>
                                                </select>
                                            </label>
                                        </section>
                                        <section class="col col-6">
                                            <label class="label">출금 은행 예금주</label>
                                            <label class="input">
                                                <input type="text" name="bankDepositName" maxlength="20">
                                            </label>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <section class="col col-6">
                                            <label class="label">출금 은행 계좌번호</label>
                                            <label class="input">
                                                <input type="text" name="bankNumber">
                                            </label>
                                        </section>
                                    </div>
                                </fieldset>

                                <fieldset>
                                    <div class="row">
                                        <section class="col col-6">
                                            <label class="label">추천인 아이디</label>
                                            <label class="input">
                                                <input type="text" name="affiliatedId" maxlength="20" class="not-ko">
                                            </label>
                                        </section>
                                    </div>
                                </fieldset>

                                <footer>
                                    <button type="submit" data-ismodify="true" class="btn btn-primary">등록</button>
                                </footer>
                            </form>
                            <!--// 신규회원 등록 폼 -->

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
