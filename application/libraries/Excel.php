<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."/third_party/PHPExcel.php";

/**
 * 엑셀 제어를 위한 클래스
 */
class Excel extends PHPExcel {
    private $oPHPExcel;
    public $header;
    public $rows;

    /**
     * 생성자
     */
    public function __construct() {
        parent::__construct();

        $this->oPHPExcel = new PHPExcel();
    }

    /**
     * 엑셀파일 출력
     */
    public function download($fileName='')
    {
        // 헤더와 데이터 통합
        $data = array_merge(array($this->header), $this->rows);

        // 쉬트에 데이터 입력
        $this->oPHPExcel->getActiveSheet()->fromArray($data, NULL, 'A1');

        // 파일의 저장형식이 utf-8일 경우 한글파일 이름은 깨지므로 euc-kr로 변환해준다.
        $fileName = iconv("UTF-8", "EUC-KR", $fileName);

        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=".$fileName.".xlsx");
        header('Cache-Control: max-age=0');

        $oCreateWriter = PHPExcel_IOFactory::createWriter($this->oPHPExcel, 'Excel2007');
        $oCreateWriter->save('php://output');
    }

    /**
     * 배열 데이터에서 key명을 index 값으로 변경
     *
     * Array ( [name] => test ) 구조에서
     * Array ( [0] => test ) 구조로 변경
     */
    public function setColNameToIndex($dataList=[])
    {
        $resultList = [];
        foreach ($dataList as $key => $data) {
            array_push($resultList, array_values($data));
        }
        return $resultList;
    }

    /**
     * 타이틀 배경색 설정
     */
    public function setFillHeader($color='EEEEEE')
    {
        $lastChar = $this->getChar(count($this->header) - 1);
        $this->oPHPExcel->setActiveSheetIndex(0)
            ->getStyle( "A1:${lastChar}1" )
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB($color);
    }

    /**
     * 헤더 고정 설정
     */
    public function setFixHeader($line='2')
    {
        $this->oPHPExcel->getActiveSheet()->freezePane('A'.$line);
    }

    /**
     * 숫자로 영문자 구하기
     */
    public function getChar($num)
    {
        return chr( 65 + $num );
    }
}