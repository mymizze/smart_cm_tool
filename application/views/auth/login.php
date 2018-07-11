<script>
    // Smart Admin Bootstrap 실행 함수
    runAllForms();

    /**
     * 페이지 로딩시 실행 이벤트
     */
    $(function() {
        // Validation
        $("#login-form").validate({
            // Rules for form validation
            rules : {
                userid : {
                    required : true,
                    minlength : 4,
                    maxlength : 20
                },
                password : {
                    required : true,
                    minlength : 4,
                    maxlength : 20
                }
            },

            // Messages for form validation
            messages : {
                userid : {
                    required : '아이디를 입력해 주세요'
                },
                password : {
                    required : '비밀번호를 입력해 주세요'
                }
            },

            // Do not change code below
            errorPlacement : function(error, element) {
                error.insertAfter(element.parent());
            }
        });
    });
</script>

<header id="header">
    <div id="logo-group">
        <span id="logo"> <img src="<?=GD_IMAGE_BASE_PATH?>/logo.png" alt="SmartAdmin"> </span>
    </div>

    <span id="extr-page-header-space"> <span class="hidden-mobile hiddex-xs">계정이 필요하신가요?</span> <a href="register.html" class="btn btn-danger">회원가입</a> </span>
</header>

<div id="main" role="main">
    <!-- MAIN CONTENT -->
    <div id="content" class="container">

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">
                <h1 class="txt-color-red login-header-big">SmartAdmin</h1>
                <div class="hero">

                    <div class="pull-left login-desc-box-l">
                        <h4 class="paragraph-header">It's Okay to be Smart. Experience the simplicity of SmartAdmin, everywhere you go!</h4>
                        <div class="login-app-icons">
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm">Frontend Template</a>
                            <a href="javascript:void(0);" class="btn btn-danger btn-sm">Find out more</a>
                        </div>
                    </div>

                    <img src="<?=GD_IMAGE_BASE_PATH?>/demo/iphoneview.png" class="pull-right display-image" alt="" style="width:210px">

                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <h5 class="about-heading">About SmartAdmin - Are you up to date?</h5>
                        <p>
                            Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa.
                        </p>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <h5 class="about-heading">Not just your average template!</h5>
                        <p>
                            Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi voluptatem accusantium!
                        </p>
                    </div>
                </div>

            </div>
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
                <div class="well no-padding">
                    <form action="index.html" id="login-form" class="smart-form client-form">
                        <header>
                            로그인
                        </header>

                        <fieldset>

                            <section>
                                <label class="label">아이디</label>
                                <label class="input"> <i class="icon-append fa fa-user"></i>
                                    <input type="text" name="userid" maxlength="20">
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> 아이디를 입력해주세요</b>
                                </label>
                            </section>

                            <section>
                                <label class="label">비밀번호</label>
                                <label class="input"> <i class="icon-append fa fa-lock"></i>
                                    <input type="password" name="password" maxlength="20">
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> 비밀번호를 입력해주세요</b>
                                </label>
                                <div class="note">
                                    <a href="forgotpassword.html">비밀번호를 분실하셨나요?</a>
                                </div>
                            </section>

                            <section>
                                <label class="checkbox">
                                    <input type="checkbox" name="remember" checked="">
                                    <i></i>아이디 저장
                                </label>
                            </section>
                        </fieldset>
                        <footer>
                            <button type="submit" class="btn btn-primary">
                                로그인
                            </button>
                        </footer>
                    </form>

                </div>

                <h5 class="text-center"> - Or sign in using -</h5>

                <ul class="list-inline text-center">
                    <li>
                        <a href="javascript:void(0);" class="btn btn-primary btn-circle"><i class="fa fa-facebook"></i></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="btn btn-info btn-circle"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="btn btn-warning btn-circle"><i class="fa fa-linkedin"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
