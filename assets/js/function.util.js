/**====================================================
 * Page Summary: 기능형 공통 함수
 *====================================================*/

/**
 * trim 합수 쓰기
 *
 * @tutorial:
 *      양쪽 공백제거 : 문자열.trim()
 *      좌측 공백제거 : 문자열.ltrim()
 *      우측 공백제거 : 문자열.rtrim()
 */
String.prototype.trim = function() {
    return this.replace(/(^\s*)|(\s*$)/gi, "");
}

String.prototype.ltrim = function() {
    return this.replace(/^s*/gi, "");
}

String.prototype.rtrim = function() {
    return this.replace(/s*$/gi, "");
}

/**
 * 조사 구분(은/는, 이/가, 을/를)
 *
 * @return: (문자열)을 (문자열)를
 * @tutorial:
 *      str = "핸드폰"; str.josa("을/를");  결과 : 핸드폰을
 *      str = "나"; str.josa("은/는");    결과 : 나는
 */
String.prototype.josa = function(nm) {
    var arrNm = nm.split("/");
    var a = this.substring(this.length - 1, this.length).charCodeAt();
    a = a - 44032;
    var jongsung = a % 28;
    nm = (jongsung) ? arrNm[0] : arrNm[1];
    return this + nm;
}

/**
 * Util 오브젝트 생성
 */
var util = {
    /*=====================================================================
        문자 관련 함수
    =====================================================================*/
        /**
         * 변수값 비교후 같을시 지정한 param3 값으로 리턴
         *
         * @tutorial:
         *      s = "three"
         *      util.compare(s, "3", " selected") result => ""
         *      util.compare(s, "three", " selected") '--> " selected"
         */
        compare: function () {
            var args = arguments;
            var rval = "";

            if (args[0]===args[1]) {
                rval = args[2];
            } else {
                if (args[3] != undefined) {
                    rval = args[3];
                }
            };
            return rval;
        },

        /**
         * 변수값 비교후 같지 않을시 지정한 param3 값으로 리턴
         *
         * @tutorial:
         *      s = "three"
         *      util.compareNot(s, "3", " selected") result => ""
         *      util.compareNot(s, "three", " selected") '--> " selected"
         */
        compareNot: function () {
            var args = arguments;
            var rval = "";

            if (args[0]!==args[1]) {
                rval = args[2];
            } else {
                if (args[3] != undefined) {
                    rval = args[3];
                }
            };
            return rval;
        },

        /**
         * 이벤트 키코드 값 문자로 변경
         *
         * @return: String
         * @tutorial: util.keyCodeToChar()
         */
        keyCodeToChar: function() {
            var result;
            try {
                var keyCode = this.keyCode();
                return String.fromCharCode(event.keyCode);
            }
            catch (e) { return e; }
        },

    /*=====================================================================
        숫자 관련 함수
    =====================================================================*/
        /**
         * 숫자 뒷자리부터 세자리 마다 콤마(,) 찍기
         *
         * @return: String
         * @tutorial: util.comma(123456)
         */
        comma: function(num) {
            var dot = 0;
            var mark = num.match(/[+-]/g);
            var arrDot = num.split(".");

            if (arrDot.length > 1) {
                num = arrDot[0];
                dot = arrDot[1];
            }

            num = num.replace(/(\D)/g, "");

            var reg = /(^[+-]?\d+)(\d{3})/;
            num += "";  //숫자를 문자열로 변환

            while(reg.test(num)){
                num = num.replace(reg, "$1" + "," + "$2");
            }
            if (mark) {
                num = mark + num;
            }
            if (dot > 0) {
                num += "." + dot;
            }
            return num;
        },

    /*=====================================================================
        날짜 관련 함수
    =====================================================================*/
        /**
         * 해당 달의 마지막 날 구하기
         *
         * @return: util.lastDay("2009","11")
         * @tutorial: util.lastDay("2009","11")
         */
        lastDay: function(varYear,varMonth) {
            var arrMonth = new Array(0,31,28,31,30,31,30,31,31,30,31,30,31);
            var year  = parseInt(varYear);
            var month = parseInt(varMonth);

            // 윤년 체크
            var leapYear = function(varYear) {
                var sLeapYear = (((varYear%4 == 0) && (varYear%100 != 0)) || (varYear%400 == 0));
                return sLeapYear;
            }

            if(leapYear(year) == true) {
                arrMonth[2] = 29;
            }
            days = arrMonth[month];
            return days;
        },

        /**
         * 1970년 1월 1일 0초기준 현재 시간까지 지나온 초
         *
         * @tutorial: util.unixTimeStamp() => Result : 1370920439
         */
        unixTimeStamp: function () {
            return Math.floor(new Date().getTime() / 1000);
        },

    /*=====================================================================
        파일/폴더 관련 함수
    =====================================================================*/
        /**
         * cookies class생성(형식 JSON)
         *
         * @tutorial:
         *      [1] 쿠키설정
         *          - util.cookies.set([
         *              { key:"userid", val:"abcd", exp:1 },
         *              { key:"username", val:"kate", exp:1 },
         *              { key:"age", val:"18", exp:1 }
         *          ]);
         *
         *      [2] 쿠키읽기
         *          - util.cookies.get("age")
         *
         *      [3] 쿠키삭제
         *          - util.cookies.del(["userid", "username"])
         */
        cookies: {
            // 쿠키값 읽기
            get: function(key) {
                var arg = key + "=";
                var cookies = unescape(document.cookie);

                if (cookies.length > 0) {
                    offset = cookies.indexOf(arg);

                    if (offset != -1) {
                        offset += arg.length;
                        end = cookies.indexOf(";",offset);

                        if (end == -1) end = cookies.length;
                        return cookies.substring(offset,end);
                    }
                    else return false;
                }
                else return false;
            },

            // 쿠키쓰기(만료일 지정)  만료일(expiredays) : 1 = 1일 , 365 = 365일
            set: function(items) {
                var todayDate = new Date();
                $.each( items, function(idx, item) {
                    todayDate.setDate( todayDate.getDate() + item.exp );
                    document.cookie = item.key + "=" + escape( item.val ) + "; path=/; expires=" + todayDate.toGMTString() + ";";
                })
            },

            // 쿠키쓰기(만료일 없음 : 창닫을시 자동으로 쿠키삭제됨)
            setNoExp: function(items) {
                var todayDate = new Date();
                $.each( items, function(idx, item) {
                    todayDate.setDate(todayDate.getDate());
                    document.cookie = item.key + "=" + escape( item.val ) + "; path=/;";
                })
            },

            // 쿠키삭제(어제 날짜를 쿠키 소멸 날짜로 설정)
            del: function(items) {
                var expireDate = new Date();

                $.each(items, function(idx, item) {
                    expireDate.setDate( expireDate.getDate() - 1 );
                    document.cookie = item + "= " + "; expires=" + expireDate.toGMTString() + "; path=/";
                })
            }
        },
    /*=====================================================================
        이미지 관련 함수
    =====================================================================*/

    /*=====================================================================
        보안 관련 함수
    =====================================================================*/
        /**
         * Base64 암호화
         *
         * @tutorial:
         *      [인코딩] util.base64.encode("abcd")
         *      [디코딩] util.base64.decode(Param)
         * @reference: http://www.webtoolkit.info/
         */
        base64: {
            // private property
            _keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

            // public method for encoding
            encode : function (input) {
                var output = "";
                var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
                var i = 0;

                input = this._utf8_encode(input);

                while (i < input.length) {

                    chr1 = input.charCodeAt(i++);
                    chr2 = input.charCodeAt(i++);
                    chr3 = input.charCodeAt(i++);

                    enc1 = chr1 >> 2;
                    enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                    enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                    enc4 = chr3 & 63;

                    if (isNaN(chr2)) {
                        enc3 = enc4 = 64;
                    } else if (isNaN(chr3)) {
                        enc4 = 64;
                    }

                    output = output +
                    this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
                    this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

                }

                return output;
            },

            // public method for decoding
            decode : function (input) {
                var output = "";
                var chr1, chr2, chr3;
                var enc1, enc2, enc3, enc4;
                var i = 0;

                input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

                while (i < input.length) {

                    enc1 = this._keyStr.indexOf(input.charAt(i++));
                    enc2 = this._keyStr.indexOf(input.charAt(i++));
                    enc3 = this._keyStr.indexOf(input.charAt(i++));
                    enc4 = this._keyStr.indexOf(input.charAt(i++));

                    chr1 = (enc1 << 2) | (enc2 >> 4);
                    chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                    chr3 = ((enc3 & 3) << 6) | enc4;

                    output = output + String.fromCharCode(chr1);

                    if (enc3 != 64) {
                        output = output + String.fromCharCode(chr2);
                    }
                    if (enc4 != 64) {
                        output = output + String.fromCharCode(chr3);
                    }

                }

                output = this._utf8_decode(output);

                return output;

            },

            // private method for UTF-8 encoding
            _utf8_encode : function (string) {
                string = string.replace(/\r\n/g,"\n");
                var utftext = "";

                for (var n = 0; n < string.length; n++) {

                    var c = string.charCodeAt(n);

                    if (c < 128) {
                        utftext += String.fromCharCode(c);
                    }
                    else if((c > 127) && (c < 2048)) {
                        utftext += String.fromCharCode((c >> 6) | 192);
                        utftext += String.fromCharCode((c & 63) | 128);
                    }
                    else {
                        utftext += String.fromCharCode((c >> 12) | 224);
                        utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                        utftext += String.fromCharCode((c & 63) | 128);
                    }

                }

                return utftext;
            },

            // private method for UTF-8 decoding
            _utf8_decode : function (utftext) {
                var string = "";
                var i = 0;
                var c = c1 = c2 = 0;

                while ( i < utftext.length ) {

                    c = utftext.charCodeAt(i);

                    if (c < 128) {
                        string += String.fromCharCode(c);
                        i++;
                    }
                    else if((c > 191) && (c < 224)) {
                        c2 = utftext.charCodeAt(i+1);
                        string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                        i += 2;
                    }
                    else {
                        c2 = utftext.charCodeAt(i+1);
                        c3 = utftext.charCodeAt(i+2);
                        string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                        i += 3;
                    }

                }

                return string;
            }
        },

    /*=====================================================================
        체크용 함수
    =====================================================================*/
        /**
         * 브라우저 체크
         * - jQuery 1.9 이상부터 브라우저 체크 함수를 지원하지 않아서 별도 함수로 사용
         *
         * @tutorial:
         *      util.browser.type()
         *      util.browser.version("Firefox")
         */
        browser: {
            type : function() {
                //Browser Type(IE,Nc)
                var bname = window.navigator.appName;
                var btype;

                //IE
                if (bname.indexOf("Microsoft") > -1) {
                        btype = "MSIE";
                }
                //Netscape(Chrome, Mozilla, FireFox)
                else if (bname.indexOf("Netscape") > -1) {
                        btype = "NC";
                }
                return btype;
            },

            version : function() {
                var args = arguments;
                var ua = window.navigator.userAgent;
                return (ua.indexOf(args[0]) > -1) ? true : false;

                // 리턴을 정규식으로 체크시 인자값에 특수문자가 있을 경우 역슬래쉬를 해줘야함
                // Ex : Firefox/3 체크시 => param = "Firefox\\/3"
                 return eval("/"+args[0]+"/.test(ua)");
            }
        },

        /**
         * 키보드 키코드 체크
         *
         * @tutorial: util.keyCode()
         */
        keyCode: function() {
            var e = event;
            return e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
        },

        /**
         * 엔터키 체크
         *
         * @tutorial:
         *      util.enter() : 파라미터가 없을시 boolean 형으로 체크결과 리턴
         *      util.enter('함수()') : 파라미터에 함수를 문자형으로 입력시 엔터조건 만족시 실행
         */
        enter: function() {
            var args = arguments;

            try {
                var keyCode = this.keyCode();

                if (typeof args[0] == "string" && args[0].trim() != "" && keyCode == 13) { eval(args[0]); return false; }
            }
            catch (e) {
                return e;
            }
            return (keyCode==13) ? true : false;
        },

        /**
         * URI segment
         */
        uri: {
            segment_array: function() {
                var path = location.pathname;

                path = path.substr(1);

                if (path.slice(-1) == '/') {
                    path = path.substr(0 , path.length - 1);
                }

                var seg_arr = path.split('/');

                if (seg_arr[0] == 'index.php') {
                    seg_arr.shift();
                }

                return seg_arr;
            },

            segment: function (n , v) {
                var seg_array = this.segment_array();
                var seg_n = seg_array[n-1];

                if (typeof seg_n == 'undefined') {
                    if (typeof v != 'undefined') {
                        return v;
                    } else {
                        return false;
                    }
                } else {
                    return seg_n;
                }
            }
        },

        /**
         * 한글입력 막기
         */
        notko: function(obj) {
            if (!(event.keyCode >=37 && event.keyCode<=40)) {
                var v = obj.val();
                obj.val(v.replace(/[^a-z0-9]/gi,''));
                return false;
            }
        },

    /*=====================================================================
        일반 기타 함수
    =====================================================================*/
        /**
         * 페이지 이동
         *
         * @tutorial: util.move('/path?key1=val1&key2=val2')
         */
        move: function (url) {
            if (url != undefined && url != "") {
                window.location.href = url;
            }
        },
}
