<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|   https://codeigniter.com/user_guide/general/hooks.html
|
*/

/**
 * 시스템 작동초기
 * 벤치마크와 후킹클래스들만 로드된 상태로서, 라우팅을 비롯한 어떤 다른 프로세스도 진행되지않은 상태
 */
/*
$hook['pre_system'][] = array(
    'class'    => 'Interceptor',
    'function' => 'preSystem',
    'filename' => 'Interceptor.php',
    'filepath' => 'hooks'
);
*/

/**
 * 컨트롤러가 호출되기 직전
 * 모든 기반클래스(base classes), 라우팅 그리고 보안점검이 완료된 상태
 */
/*
$hook['pre_controller'][] = array(
    'class'    => 'Interceptor',
    'function' => 'preHandle',
    'filename' => 'Interceptor.php',
    'filepath' => 'hooks'
);
*/

/**
 * 컨트롤러가 인스턴스화 된 직후
 * 즉, 사용준비가 완료된 상태. 하지만 인스턴스화 된후 메소드들이 호출되기 직전
 */
$hook['post_controller_constructor'][] = array(
    'class'    => 'Interceptor',
    'function' => 'postHandleConstructor',
    'filename' => 'Interceptor.php',
    'filepath' => 'hooks'
);

/**
 * 컨트롤러가 인스턴스화되고 메소드실행 후
 */
$hook['post_controller'][] = array(
    'class'    => 'Interceptor',
    'function' => 'postHandle',
    'filename' => 'Interceptor.php',
    'filepath' => 'hooks'
);

/**
 * 최종적으로 브라우저에 페이지를 전송할때 사용됨
 * _display() 함수를 재정의 함. 최종적으로 브라우저에 페이지를 전송할때 사용됨. 이로서 당신만의 표시 방법( display methodology)을 사용할 수 있음.
 * 주의 : CI 부모객체(superobject)를 $this->CI =& get_instance() 로 호출하여 사용한 후에 최종데이터 작성은 $this->CI->output->get_output() 함수를 호출하여 할 수 있음
 */
$hook['display_override'][] = array(
    'class'    => 'Layout',
    'function' => 'doLayout',
    'filename' => 'Layout.php',
    'filepath' => 'hooks'
);

/**
 * 출력클래스(output class) 에 있는_display_cache() 함수 대신 당신의 스크립트를 호출할 수 있도록 해줌
 * 이로서 당신만의 캐시 표시 메커니즘(cache display mechanism)을 적용할 수 있음
 */
/*
$hook['cache_override'][] = array(
    'class'    => 'Interceptor',
    'function' => 'cacheOverride',
    'filename' => 'Interceptor.php',
    'filepath' => 'hooks'
);
*/

/**
 * 최종 렌더링 페이지가 브라우저로 보내진후에 호출됨
 */
/*
$hook['post_system'][] = array(
   'class'    => 'Interceptor',
   'function' => 'postSystem',
   'filename' => 'Interceptor.php',
   'filepath' => 'hooks'
);
*/
