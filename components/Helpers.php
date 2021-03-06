<?php

namespace app\components;

use Yii;

use app\models\Alternatif;
use app\models\Kriteria;
use app\models\Spk;
use app\models\Penilaian;

use kartik\mpdf\Pdf;
/**
 * Class Helpers
 */
class Helpers extends \yii\base\Component
{

    /**
     * Generate View to PDF
     * @param view content
     * @param string dest
     * @param string filename
     * @param array options
     * @return pdf
     */
    public static function generatePdf($content, $dest, $filename = false, $options = [])
    {
        $params = [
            'filename' => $filename,
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4, 
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => $dest,
            'content' => $content,
            'cssFile' => '@webroot/css/pdf.css',
            'options' => ['title' => 'Sistem Pendukung Keputusan'],
            'methods' => [ 
                'SetHeader'=>['Sistem Pendukung Keputusan'], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ];

        $params = array_replace($params, $options);
        $pdf = new Pdf($params);
        return $pdf->render();
        exit();
    }

    /**
     * Generate Excel
     * @param string filename
     * @param array data
     * @param array style
     * @return excel
     */
    public static function generateExcel($filename, $data = [], $styles = [])
    {
        foreach ($data as $key => $dat) {
            $column = count($data[$key]['titles']);
            $row = count($data[$key]['data']) + 1;
            $all_cell = 'A1:' . chr($column + 64) . $row;
            $header_cell = 'A1:' . chr($column + 64) . '1';
            $content_cell = 'A2:' . chr($column + 64) . $row;

            // foreach ($styles as $old_key => $value) {
            //     $arr_key = explode('-', $old_key);

            //     if (strtolower($arr_key[0]) === 'col' && strtolower($arr_key[2]) === 'content') {
            //         $new_key = $arr_key[1] . '2:' . $arr_key[1] . $row;
            //         $styles[$new_key] = $value;
            //         unset($styles[$old_key]);
            //     }

            // }

            $data[$key]['styles'] = [
                $all_cell => [
                    'borders' => [
                        'allborders' => [
                            'style' => \PHPExcel_Style_Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    ],
                ],
                $header_cell => [
                    'fill' => [
                        'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => [
                            'argb' => 'd3d3d3',
                        ],
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 12,
                    ],
                ],
                $content_cell => [
                    'font' => [
                        'size' => 11,
                    ],
                ],
            ];
            $data[$key]['on beforeRender'] = function ($event) use ($column) {
                for ($i = 65; $i < ($column + 65); $i++) {
                    $sheet = $event->sender->getSheet()->getColumnDimension(chr($i));
                    $sheet->setAutoSize(true);
                }
            };
            $data[$key]['styles'] = array_replace($data[$key]['styles'], $styles);
        }

        $file = \Yii::createObject([
            'class' => 'codemix\excelexport\ExcelFile',
            'sheets' => $data,
        ]);

        $file->send($filename);
        exit();
    }

    /**
     * Convert date to date format Indonesia
     * @param string date
     * @return string result
     */
    public static function dateIndonesia($date) 
    {
        $result = '';
        if(!empty($date) && $date !== '0000-00-00') {
            $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"); 
            $tahun = substr($date, 0, 4);
            $bulan = substr($date, 5, 2);
            $tgl   = substr($date, 8, 2);
         
            $result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
            
        }
        return $result;
    }

    /**
     * Convert datetime to datetime format Indonesia
     * @param string date
     * @return string result
     */
    public static function dateTimeIndonesia($date) 
    {
        $result = '';
        if(!empty($date) && $date !== '0000-00-00 00:00:00') {
            $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"); 
            $tahun = substr($date, 0, 4);
            $bulan = substr($date, 5, 2);
            $tgl   = substr($date, 8, 2);
         
            $result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun.' - '.substr($date, 11, 19);
            
        }
        return $result;
    }

    /**
     * Get nama SPK berdasarkan id spk
     * @param int id spk
     * @return string nama spk
     */
    public static function getNamaSpkByIdSpk($id_spk)
    {
        $spk = Spk::findOne($id_spk);

        if (!empty($spk)) {
            return $spk->nama_spk;
        }

        return '';
    }

    /**
     * Get jenis bobot berdasarkan id spk
     * @param int id spk
     * @return string nama spk
     */
    public static function getJenisBobotByIdSpk($id_spk)
    {
        $spk = Spk::findOne($id_spk);

        if (!empty($spk)) {
            return $spk->jenis_bobot;
        }

        return null;
    }

    /**
     * Get nama alternatif berdasarkan id alternatif
     * @param int id
     * @return string nama alternatif
     */
    public static function getNamaAlternatifByIdAlternatif($id_alternatif)
    {
        $alternatif = Alternatif::findOne($id_alternatif);
        
        if (!empty($alternatif)) {
            return $alternatif->nama_alternatif;
        }

        return '';
    }

    /**
     * Get nama alternatif berdasarkan id penilaian
     * @param int id
     * @return string nama alternatif
     */
    public static function getNamaAlternatifByIdPenilaian($id_penilaian)
    {
        $penilaian = Penilaian::findOne($id_penilaian);

        if (!empty($penilaian)) {
            $id_alternatif = $penilaian->id_alternatif;
            $alternatif = Alternatif::findOne($id_alternatif);

            if (!empty($alternatif)) {
                return $alternatif->nama_alternatif;
            }
        }

        return '';
    }

    /**
     * Get tipe kriteria berdasarkan id tipe (0/1)
     * @param int id type
     * @return string
     */
    public static function getTypeKriteria($id_type)
    {
        if ($id_type == Kriteria::COST) {
            return 'COST';
        } else if ($id_type == Kriteria::BENEFIT) {
            return 'BENEFIT';
        }

        return '';
    }

    /**
     * cek apakah semua bobot kriteria sudah diset atau belum (masih 0) berdasarkan id spk
     * sudah = false, belum = true
     * @param int id spk
     * @return boolean
     */
    public static function cekBobotKosong($id_spk)
    {
        $arr_bobot = [];
        $kriteria = Kriteria::find()->select('bobot')->where(['id_spk' => $id_spk])->asArray()->all();
        
        foreach ($kriteria as $key => $kri) {
            $arr_bobot[] = $kri['bobot'];
        }

        if (in_array(0, $arr_bobot)) {
            return true;
        }

        return false;
    }

    /**
     * cek apakah semua kriteria sudah diisi di table penilaian berdasarkan id spk
     * sesuai semua = true, tidak sesuai = false
     * @param int id spk
     * @param int jumlah kriteria
     * @return boolean
     */
    public static function cekKriteriaSesuaiPenilaian($id_spk, $jml_kriteria)
    {
        $penilaian = Penilaian::find()->where(['id_spk' => $id_spk])->all();
        $kriteria = Kriteria::find()->where(['id_spk' => $id_spk])->all();
        $arr_jml_nilai = [];

        foreach ($penilaian as $key => $pen) {
            $arr_jml_nilai[] = count(json_decode($pen->penilaian, true));
        }

        foreach ($arr_jml_nilai as $jml) {
            if ($jml_kriteria != $jml) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Convert nilai crips to nama crips
     * @param double nilai_crips
     * @param int id_kriteria
     * @return string nama_crips
     */
    public static function nilaiToCrips($nilai_crips, $id_kriteria)
    {
        $crips = json_decode(Kriteria::findOne($id_kriteria)->crips, true);
        
        if ($crips) {
            $nama_crips = array_search($nilai_crips, $crips);
            if ($nama_crips) {
                return $nama_crips;   
            } 
        }

        return $nilai_crips;
    }

    /**
     * get crips berdasarkan id kriteria
     * @param int id_kriteria
     * @return array crips
     */
    public static function getCrips($id_kriteria)
    {
        $crips = json_decode(Kriteria::findOne($id_kriteria)->crips, true);

        return !empty($crips) ? array_flip($crips) : null;
    }

    /**
     * get jenis bobot berdasarkan id spk
     * @param int id_spk
     * @return int jenis_bobot
     */
    public static function getJenisBobot($id_spk)
    {
        $spk = Spk::findOne($id_spk);
        
        return !empty($spk) ? $spk->jenis_bobot : null;
    }

}