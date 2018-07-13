<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Global 상수
 */
Class Define
{
    /**
     * 생성자
     */
    public function __construct()
    {
        # 루트 URL
        define('GD_ROOT_URL', 'http://admin.totostandard.com');

        # 루트 경로
        define('GD_ROOT_PATH', '/');

        # 첫 페이지 경로
        define('GD_HOME_PATH', '/');

        # Vendor 경로
        define('GD_ASSETS_PATH', '/assets');

        # 기본 이미지 경로
        define('GD_IMAGE_BASE_PATH', '/assets/img');

        # 위젯 판넬 타이틀바 컬러
        define('GD_JARVISWIDGET_COLOR', 'jarviswidget-color-blueDark');
    }
}

