<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Paging{

    public $currPage;
    public $pageSize;
    public $blockPage;
    public $totalPage;
    public $totalCount;

    public function __construct(){
        $this->pageSize = 15;
        $this->blockPage = 10;
    }

    public function html(){

        if($this->totalCount){

            $temp  = ($this->currPage - 1) % $this->blockPage;
            $sPage = $this->currPage - $temp;
            $str = "<ul class='pagination pagination m-0'>";

            if ($this->currPage > 1){
                $str .= "<li><a href='javascript:ui.page(1);'><i class='fa fa-angle-double-left'></i></a></li>";
            } else {
                $str .= "<li><a href='javascript:;'><i class='fa fa-angle-double-left'></i></a></li>";
            }

            if($sPage - $this->blockPage > 0 ){
                $fPage = $sPage - 1;
                $str .= "<li><a href='javascript:ui.page('".$fPage."');'><i class='fa fa-angle-left'></i></a></li>";
            }else{
                $str .= "<li><a href='javascript:;'><i class='fa fa-angle-left'></i></a></li>";
            }

            for($i = $sPage; $i <= $sPage + $this->blockPage -1; $i++) {
                if ($i == $this->currPage){
                    $str .= "<li class='active'><a href='javascript:void(0);'>".$i."</a></li>";
                } else {
                    $str .= "<li><a href='javascript:ui.page(".$i.");'>".$i."</a></li>";
                }

                if($i >= $this->totalPage){
                    break;
                }
            }

            if($sPage + $this->blockPage <= $this->totalPage) {
                $bPage = $sPage + $this->blockPage;
                $str .= "<li><a href='javascript:ui.page(".$bPage.");'><i class='fa fa-angle-right'></i></a></li>";
            }else{
                $str .= "<li><a href='javascript:;'><i class='fa fa-angle-right'></i></a></li>";
            }

            if ($this->currPage < $this->totalPage){
                $str .= "<li><a href='javascript:ui.page(".$this->totalPage.");'><i class='fa fa-angle-double-right'></i></a></li>";
            } else {
                $str .= "<li><a href='javascript:void(0);'><i class='fa fa-angle-double-right'></i></a></li>";
            }

            $str .= '</ul>';

            return $str;
        }
    }
}