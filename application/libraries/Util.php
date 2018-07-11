<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 공통으로 사용 하는 기능형 함수 라이브러리
 */
Class Util
{
#======================================================================================
# 서버 정보 함수
#======================================================================================
    /**
     * 사용자 IP 정보 얻기
     */
    public function getUserIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) { //check ip from share internet
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  //to check ip is pass from proxy
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip=$_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }


#======================================================================================
# 문자 관련 함수
#======================================================================================
    /**
     * get 파라미터 값 받기
     *
     * @param   : Get 파라미터 key명
     * @return  : null or get value
     */
    public function getParam($parameterName)
    {
        return (empty($_GET[$parameterName]))? null : trim($_GET[$parameterName]);
    }

    /**
     * post 파라미터 값 받기
     *
     * @param   : Post 파라미터 key명
     * @return  : null or post value
     */
    public function postParam($parameterName)
    {
        return (empty($_POST[$parameterName]))? null : trim($_POST[$parameterName]);
    }

    /**
     * request 파라미터 값 받기
     *
     * @param   : Get/Post 구분 없는 파라미터 key명
     * @return  : null or REQUEST value
     */
    public function requestParam($parameterName)
    {
        return (empty($_REQUEST[$parameterName]))? null : trim($_REQUEST[$parameterName]);
    }

    /**
     * XML 코드를 json 으로 변환
     *
     * @param   : XML 코드
     * @return  : json
     */
    public function xmlToJson($xml)
    {
        $fileContents = str_replace(array("\n", "\r", "\t"), '', $xml);

        $simpleXml = simplexml_load_string($fileContents);

        $json = json_encode($simpleXml);

        return $json;
    }

    /**
     * URL을 통한 XML 코드를 json 으로 변환
     *
     * @param   : XML 코드
     * @return  : json
     */
    public function xmlToJsonFromUrl($url)
    {
        $fileContents = file_get_contents($url);

        $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);

        $simpleXml = simplexml_load_string($fileContents);

        $json = json_encode($simpleXml);

        return $json;
    }

    /**
     * 빈 값 체크
     *
     * @param   : 체크할 값
     * @return  : boolean
     */
    public function isNullVal($val)
    {
        if(empty($val) || trim($val) == "") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 빈 값 체크 후 값이 비었을 경우 지정 문자로 대체
     *
     * @param   : 체크할 값
     * @param   : 대체할 값
     * @return  : string or number or etc..
     */
    public function isNullToVal($val1, $val2='')
    {
        if(empty($val1) || trim($val1) == "") {
            return $val2;
        } else {
            return $val1;
        }
    }

    /**
     * 입력한 내용을 실행 불가능한 특수문자로 치환 Type1
     *
     * @param   : 태그형 코드
     * @param   : 타입설정 (en/de)
     * @return  : string
     */
    public function htmlTo($tag='', $type='en')
    {
        if (!$this->isNullVal($tag)) {
            switch (strtolower($type)) {
                // encode
                case 'en':
                    $tag = str_replace("&", "&amp;", $tag);
                    $tag = str_replace("<", "&lt;", $tag);
                    $tag = str_replace(">", "&gt;", $tag);
                    $tag = str_replace('"', "&quot;", $tag);
                    $tag = str_replace("'", "''", $tag);
                    $tag = $this->replaceTag($tag);
                    break;

                // decode
                case 'de':
                    $tag = str_replace("&amp;", "&", $tag);
                    $tag = str_replace("&lt;", "<", $tag);
                    $tag = str_replace("&gt;", ">", $tag);
                    $tag = str_replace("&quot;", '"', $tag);
                    $tag = str_replace("''", "'", $tag);
                    break;
            }
        }
        return $tag;
    }

    /**
     * 입력한 내용을 실행 불가능한 특수문자로 치환 Type2
     *
     * @param   : 태그형 코드
     * @param   : 타입설정 (en/de)
     * @return  : string
     */
    public function htmlTo2($tag='', $type='en')
    {
        if (!$this->isNullVal($tag)) {
            switch (strtolower($type)) {
                // encode
                case 'en':
                    $tag = str_replace("'", "", $tag);
                    $tag = str_replace("&", "&amp;", $tag);
                    $tag = str_replace("…", "···", $tag);
                    $tag = str_replace('‥', "··", $tag);
                    $tag = str_replace('//', " ", $tag);
                    break;

                // decode
                case 'de':
                    $tag = str_replace("&amp;", "&", $tag);
                    $tag = str_replace("···", "…", $tag);
                    $tag = str_replace("··", '‥', $tag);
                    break;
            }
        }
        return $tag;
    }

    /**
     * 태그 사용 제한 : 사용 문자 그대로 출력 되도록 치환
     *
     * @param   : 태그 문자
     * @return  : string
     */
    public function replaceTag($tag)
    {
        if(!empty($tag)) {
            $tag = str_replace("<script","&#60;script", $tag);
            $tag = str_replace("</script","&#60;/script", $tag);
            $tag = str_replace("<vb","&#60;vb", $tag);
            $tag = str_replace("</vb","&#60;/vb", $tag);
            $tag = str_replace("<iframe","&#60;iframe", $tag);
            $tag = str_replace("</iframe","&#60;/iframe", $tag);
            $tag = str_replace("<frame","&#60;frame", $tag);
            $tag = str_replace("</frame","&#60;/frame", $tag);
            $tag = str_replace("<html","&#60;html", $tag);
            $tag = str_replace("</html","&#60;/html", $tag);
            $tag = str_replace("<meta","&#60;meta", $tag);
            $tag = str_replace("</meta","&#60;/meta", $tag);
            $tag = str_replace("<link","&#60;link", $tag);
            $tag = str_replace("</link","&#60;/link", $tag);
            $tag = str_replace("<head","&#60;head", $tag);
            $tag = str_replace("</head","&#60;/head", $tag);
            $tag = str_replace("<body","&#60;body", $tag);
            $tag = str_replace("</body","&#60;/body", $tag);
            $tag = str_replace("<form","&#60;form", $tag);
            $tag = str_replace("</form","&#60;/form", $tag);
            $tag = str_replace("<style","&#60;style", $tag);
            $tag = str_replace("</style","&#60;/style", $tag);
            $tag = str_replace("<!","&#60;!", $tag);
            $tag = str_replace("cookie","Cookie", $tag);
            $tag = str_replace("document","Document", $tag);
            $tag = str_replace("<%","&#60;%", $tag);
            $tag = str_replace("<?","&#60;?", $tag);
            $tag = str_replace("wrap","", $tag);
            $tag = str_replace("<=","&#60;", $tag);
        }

        return $tag;
    }

    /**
     * 두개의 값을 비교 후 동일할 경우 지정 값 출력
     *
     * @param   : 비교 문자 1
     * @param   : 비교 문자 2
     * @param   : 동일 조건 성립시 출력 할 값
     * @return  : string
     * @example : compare('aa', 'aa', 'selected') => 'selected'
     *            compare('aa', 'bb', 'selected') => ''
     *            compare('aa', 'bb', 'type1', 'type2') => 'type2'
     */
    public function compare($s1, $s2, $def, $else='')
    {
        $rval = "";

        if(strtolower($s1) == strtolower($s2)) {
            $rval = $def;
        } else {
            $rval = $else;
        }

        return $rval;
    }

    /**
     * 두개의 값을 비교 후 동일 하지 않을 경우 지정 값 출력
     *
     * @param   : 비교 문자 1
     * @param   : 비교 문자 2
     * @param   : 동일 조건 성립시 출력 할 값
     * @return  : string
     * @example : compareNot('aa', 'aa', 'selected') => 'selected'
     *            compareNot('aa', 'bb', 'selected') => ''
     *            compareNot('aa', 'bb', 'type1', 'type2') => 'type2'
     */
    public function compareNot($s1, $s2, $def, $else='')
    {
        $rval = "";

        if(strtolower($s1) != strtolower($s2)) {
            $rval = $def;
        } else {
            $rval = $else;
        }

        return $rval;
    }

    /**
     * 문자열 자르기
     *
     * @return  string
     */
    public function cutStr($str='', $len=10, $lang='utf-8', $tail='..')
    {
        $strLen = mb_strlen($str);

        if ($strLen > $len) {
            $str = iconv_substr($str, 0, $len, $lang);
            $str .= $tail;
        }

        return $str;
    }

    /**
     * 지정 타입별 정규식 체크
     *
     * @param   : email
     * @param   : email@email.com
     * @return  : boolean
     */
    public function isRegExp($type, $str)
    {
        $result = false;

        switch ($type) {
            case 'email':
                $result = preg_match('/^[0-9a-zA-Z]([\-.\w]*[0-9a-zA-Z\-_+])*@([0-9a-zA-Z][\-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,9}$/', $str);
                break;
        }

        return $result;
    }


#======================================================================================
# 숫자 관련 함수
#======================================================================================


#======================================================================================
# 날짜 관련 함수
#======================================================================================
    /**
     * 날짜를 더해서 반환
     *
     * @param   : y,m,d,h,i : 타입(Interval)
     * @param   : 1,-1 : 변경 할 숫자(Number)
     * @param   : yyyy-mm-dd hh:ii:ss : 변경 할 날짜(Date)
     * @return  : date
     */
    public function dateAdd($dateType = 'd', $plusNum = 0, $dateStr = '')
    {
        if ($dateStr == '') {
            //  현재날짜에 넘겨진 인자만큼 더한다.
            return date("Y-m-d H:i:s", mktime(date("h"), date("i"), date("s"), date("m"), date("d") + $days, date("Y")));
        } else {
            $a = explode('-', $dateStr); //년,월,일 구별
            $b = explode(':', $dateStr); //년,월,일 구별

            $y = $a[0];
            $m = $a[1];
            $d = substr($a[2], 0, 2);
            $h = substr($b[0], 11, 13);;
            $i = $b[1];
            $s = $b[2];

            switch (strtoupper($dateType)) {
                // 년도
                case 'Y': return date('Y-m-d H:i:s', mktime($h, $i, $s, $m, $d, $y+$plusNum));
                    break;

                // 월
                case 'M': return date('Y-m-d H:i:s', mktime($h, $i, $s, $m + $plusNum, $d, $y));
                    break;

                // 일
                case 'D': return date('Y-m-d H:i:s', mktime($h, $i, $s, $m, $d + $plusNum, $y));
                    break;

                // 시
                case 'H': return date('Y-m-d H:i:s', mktime($h + $plusNum, $i, $s, $m, $d, $y));
                    break;

                // 분
                case 'I': return date('Y-m-d H:i:s', mktime($h, $i + $plusNum, $s, $m, $d, $y));
                    break;

                // 일
                default: return date('Y-m-d H:i:s', mktime($h, $i, $s, $m, $d + $plusNum, $y));
                    break;
            }
        }
    }


#======================================================================================
# 파일/폴더 처리 관련 함수
#======================================================================================
    /**
     * 유니크한 파일명 설정
     */
    public function getUniqueFileName($filePath)
    {
        // 파일 정보
        $fileInfo = pathinfo($filePath);

        // 실제 저장 경로에 새로 생성한 파일명이 존재하지 않을때까지 반복생성
        $uniqueFileName = "";
        $result = true;
        do {
            $uniqueFileName = date('YmdHis').str_replace('.', '', microtime(true)).".".$fileInfo['extension'];

            if (!file_exists($fileInfo['dirname']."/".$uniqueFileName)) {
                $result = false;
            }
        } while ($result);

        return $uniqueFileName;
    }

#======================================================================================
# 이미지 관련 함수
#======================================================================================


#======================================================================================
# 보안 관련 함수
#======================================================================================
    /**
     * 캐시 미사용 헤더 값 설정
     */
    public function setNoCache()
    {
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('pragma: no-cache');
        header('expires: 0');
    }


#======================================================================================
# 디버깅 관련 함수
#======================================================================================


#======================================================================================
# 기타 함수
#======================================================================================
    /**
     * Javascript 경고창(옵션: URL)
     */
    public function alert($msg='', $url=null, $back=0)
    {
        $html = "
            <meta http-equiv='content-type' content='text/html; charset=utf-8'>
            <script language='javascript'>
                alert('".$msg."');
        ";

        if ($back < 0) {
            $html .= "
                history.go(".$back.");
            ";
        }

        $html .= "
            </script>
        ";
        echo $html;

        if ($url != null && $url != "") {
            $this->goUrl($url);
        }
    }

    /**
     * Javascript 페이지 이동
     */
    public function goUrl($url) {
        $html = "
            <script language='JavaScript'>
                location.href = '".$url."';
            </script>
        ";
        echo $html;
        exit;
    }
}
