<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Administrar_evaluaciones_p3 extends CI_Controller {
	public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->library('user_agent');
        $this->load->library('spreadsheet_reader');
        $this->load->database('evaluaciones');
        $this->load->helper('cookie');
        session_start();
        $this->load->model('Tgasolutions');
        $this->Tgasolutions->usuario();
        $this->Tgasolutions->permisos();
        $this->load->library('pagination');
    }
    public function guardar_preguntas(){
        $idInmobiliaria   = trim($this->input->post('idInmobiliaria'));
        $idProyecto       = trim($this->input->post('idProyecto'));
        $idTipoC          = trim($this->input->post('var1'));
        $idPais           = trim($this->input->post('var2'));
        $idEvaluacion     = trim($this->input->post('var3'));
        $idUser           = $_SESSION['idUser'];
        $archivo_xlsx     = $idTipoC.'_'.$idPais.'_'.$idInmobiliaria.'_'.$idProyecto.'_'.$idEvaluacion.'_'.$idUser.'_cuestionario.xlsx';
        $dbData           = $this->load->database('evaluaciones', TRUE);

        $query = $dbData->query("SELECT numColumna
                                   FROM data_evaluaciones
                                  WHERE idEvaluacion     = $idEvaluacion
                                    AND idInmobiliaria   = $idInmobiliaria
                                    AND idProyecto       = $idProyecto
                                    AND idTipoC          = $idTipoC;");
        if ($query->num_rows() > 0){
            $row = $query->row();
            $numColumna = $row->numColumna;
        }else{
            exit;
        }

        $data          = array();
        $pregunta      = array();
        $pregunta1     = array();
        $pregunta2     = array();
        $a             = 0;
        $b             = 0;
        $Reader = new SpreadsheetReader('excel/' . $archivo_xlsx);
        foreach ($Reader as $Row){
            $a++;
            $b = 0;
            $totalColumnas = count($Row) + 1;
            for ($i = 0; $i < count($Row); $i++){
                $b++;
                $data[$a][$b] = $Row[$i];
            }
        }

        $ancho = $totalColumnas;
        $alto = count($data);
        if((!isset($data[1][$ancho]) || empty($data[1][$ancho])) && (!isset($data[2][$ancho]) || empty($data[2][$ancho]))){
            $ancho = $ancho - 1;
        }

        // Sacamos valores de campos...
        for ($a = 1; $a <= 2; $a++){
            for ($i = $numColumna; $i <= $ancho; $i++){
                if ($data[1][$i] != ''){
                    $pregunta1[$i] = 1;
                }
                else{
                    $pregunta1[$i] = 0;
                }
                if ($data[2][$i] != ''){
                    $pregunta2[$i] = 1;
                }
                else{
                    $pregunta2[$i] = 0;
                }
            }
        }
        $c                  = 0;
        $d                  = 0;
        $nomP               = '';
        $tipoP              = array();
        $preguntaX          = array();
        $preguntaN          = array();
        $preguntaSN         = array();
        $preguntaNom        = array();
        $subPreguntaNom     = array();
        $preguntaNomText    = array();
        $subPreguntaNomText = array();
        $preguntaNum        = array();

        for ($i = $numColumna; $i <= $ancho; $i++){
            $viene = 0;
            if ($pregunta1[$i] < $ancho){ $viene = 1;}
            $valor              = $data[2][$i];
            //$valor              = $this->limpiar_carcteres($valor);
            //$valor              = utf8_decode($valor);
            $subPreguntaNom[$i] = $valor;
            $valor              = $i + $viene;

            if(!isset($pregunta1[$valor]) || empty($pregunta1[$valor])){
                $pregunta1[$valor] = '';
            }
            if (($pregunta1[$i] == 1 && $pregunta1[$valor] == 1) || $i == $ancho){
                $d = 0;
                $c++;
                $tipoP[$i]          = 1;
                $preguntaX[$i]      = 'P' . $c;
                $preguntaN[$i]      = $c;
                $preguntaNum[$i]    = $preguntaN[$i];
                $preguntaSN[$i]     = 0;
                $valor              = $data[1][$i];
                #$valor              = $this->limpiar_carcteres($valor);
                #$valor              = utf8_decode($valor);
                $nomP               = $valor;
                $preguntaNom[$i]    = $nomP;
            }else if ($pregunta1[$i] == 1){
                $d = 0;
                $c++;
                $d++;
                $tipoP[$i]          = 2;
                $preguntaX[$i]      = 'P' . $c . '_' . $d;
                $preguntaN[$i]      = $c;
                $preguntaNum[$i]    = $preguntaN[$i];
                $preguntaSN[$i]     = $d;
                $valor              = $data[1][$i];
                #$valor              = $this->limpiar_carcteres($valor);
                #$valor              = utf8_decode($valor);
                $nomP               = $valor;
                $preguntaNom[$i]    = $nomP;
            }else{
                $d++;
                $tipoP[$i]          = 2;
                $preguntaX[$i]      = 'P' . $c . '_' . $d;
                $preguntaN[$i]      = $c;
                $preguntaNum[$i]    = $preguntaN[$i];
                $preguntaSN[$i]     = $d;
                #$preguntaNom[$i]    = $this->limpiar_carcteres($nomP);
                $preguntaNom[$i]    = $nomP;
            }
        }

        $e     = 0;
        $nomP  = '';

        for ($i = 1; $i < $numColumna; $i++){
            $e++;
            $tipoP[$i]          = 0;
            $preguntaX[$i]      = 'M' . $e;
            $preguntaN[$i]      = 0;
            $preguntaSN[$i]     = 0;
            $valor              = $data[1][$i];


            if ($valor == ''){
                #$preguntaNom[$i] = $nomP . ' ' . utf8_decode($this->limpiar_carcteres($data[2][$i]));
                $preguntaNom[$i] = $nomP . ' ' . $data[2][$i];
            }else{
                /*
                $valor           = str_replace("'", "´", $valor);
                $valor           = str_replace('"', "´", $valor);
                $valor           = str_replace('Ñ', "N", $valor);
                $valor           = str_replace('ñ', "n", $valor);
                $valor           = str_replace('á', "a", $valor);
                $valor           = str_replace('é', "e", $valor);
                $valor           = str_replace('í', "i", $valor);
                $valor           = str_replace('ó', "o", $valor);
                $valor           = str_replace('ú', "u", $valor);
                $valor           = utf8_decode($valor);*/
                $nomP            = $valor;
                $preguntaNom[$i] = $nomP;
                $r = $i + 1;
                if ($data[1][$r] == ''){
                    #$preguntaNom[$i] = $nomP . ' ' . utf8_decode($this->limpiar_carcteres($data[2][$i]));
                    $preguntaNom[$i] = $nomP . ' ' . $data[2][$i];
                }
            }

            /*
            $valor              = $data[2][$i];
            $valor              = str_replace("'", "´", $valor);
            $valor              = str_replace('"', "´", $valor);
            $valor              = str_replace('Ñ', "N", $valor);
            $valor              = str_replace('ñ', "n", $valor);
            $valor              = str_replace('á', "a", $valor);
            $valor              = str_replace('é', "e", $valor);
            $valor              = str_replace('í', "i", $valor);
            $valor              = str_replace('ó', "o", $valor);
            $valor              = str_replace('ú', "u", $valor);
            $valor              = $this->limpiar_carcteres($valor);
            $valor              = utf8_decode($valor);*/
            $subPreguntaNom[$i] = $valor;
        }

        $k = 0;
        for ($i=$numColumna;$i<=count($preguntaN);$i++) {
            if ($i==$numColumna) {
                $k = (int)$preguntaN[$i];
            }
            if ($preguntaN[$i]>$k) {
                $k = (int)$preguntaN[$i];
            }
        }
        for ($i=1;$i<$numColumna;$i++) {
            $preguntaNum[$i] = $k+$i;
        }

        // Info por columnas...
        $preguntaLargo = array();
        for ($a = 3; $a <= $alto; $a++) {
            for ($i=1; $i <= $ancho; $i++) {
                if(!isset($data[$a][$i]) || empty($data[$a][$i])){
                    $data[$a][$i] = '';
                }
                if(!isset($preguntaLargo[$i]) || empty($preguntaLargo[$i])){
                    $preguntaLargo[$i] = strlen(utf8_decode($data[$a][$i]));
                }
                if ((strlen(utf8_decode($data[$a][$i])) > $preguntaLargo[$i])){
                    $preguntaLargo[$i] = strlen(utf8_decode($data[$a][$i]));
                }
            }
        }

        # limpiar tabla
        $sql = "DELETE FROM data_estructura WHERE idEvaluacion = $idEvaluacion;";
        $dbData->query($sql);

        # guardar estructura
        $totalInsert    =    0;
        for ($i=1;$i<=$ancho;$i++){
            $idEstructura =  $i;

            $idPregunta         = 0;
            $nomCampo           = trim($preguntaX[$i]);
            $numpregunta        = trim($preguntaN[$i]);
            $num                = trim($preguntaNum[$i]);
            $numSubPregunta     = trim($preguntaSN[$i]);
            $pregunta           = trim($preguntaNom[$i]);
            $item               = trim($subPreguntaNom[$i]);
            $clavePregunta      = md5(trim($preguntaNom[$i]));
            $claveItem          = md5(trim($preguntaNom[$i].$subPreguntaNom[$i]));

            for ($b=10;$b<=31;$b++) {
                $f = $b.'0000';
                $f = (int)$f;
                for ($c=1;$c<=30;$c++) {
                    $muner = $f + $c;
                    $pregunta   = str_replace("{{ custom.$muner }}","",$pregunta);
                    $item       = str_replace("{{ custom.$muner }}","",$item);
                }
            }

            $item = trim($item);
            $sql = "INSERT INTO data_estructura (idEstructura,
                                                 idEvaluacion,
                                                     nomCampo,
                                                          num,
                                                  numpregunta,
                                               numSubPregunta,
                                                     pregunta,
                                                         item,
                                                  idCategoria,
                                                   idPregunta,
                                                clavePregunta,
                                                    claveItem,
                                                       estado)
                                         VALUES ($idEstructura,
                                                 $idEvaluacion,
                                                   '$nomCampo',
                                                          $num,
                                                  $numpregunta,
                                               $numSubPregunta,
                                                   '$pregunta',
                                                       '$item',
                                                             0,
                                                   $idPregunta,
                                              '$clavePregunta',
                                                  '$claveItem',
                                                             2);";
            if ($dbData->query($sql)===true) {
                $totalInsert++;
            }
        }

        if ($totalInsert == 0) {
            $this->Tgasolutions->mensaje(3,"ERROR","Error al guardar las preguntas de la evaluación.");
        }else{
            $dbData = $this->load->database('evaluaciones', TRUE);
            $sql = " UPDATE data_evaluaciones
                        SET avance = 4
                      WHERE idEvaluacion     = $idEvaluacion
                        AND idInmobiliaria   = $idInmobiliaria
                        AND idProyecto       = $idProyecto
                        AND idTipoC          = $idTipoC;";
            $dbData->query($sql);
            ?>
            <script type="text/javascript">
            $('#procesar').css('display', 'none');
            $('#procesar button').removeAttr('onclick');
            $('#next_step').css('display', 'block');
            $('#next_step button').attr('onclick', 'contenidoSubInMenu(4);');
            </script>
            <?php
        }
    }
    public function limpiar_carcteres($valor){
        $valor = str_replace("'", "´", $valor);
        $valor = str_replace('"', "´", $valor);
        for ($i = 0; $i < 12; $i++){
            $valor = str_replace("´´", "´", $valor);
            $valor = str_replace('  ', " ", $valor);
            $valor = str_replace('  ', " ", $valor);
            $valor = str_replace('__', "_", $valor);
            $valor = str_replace('__', "_", $valor);
            $valor = str_replace('--', "_", $valor);
            $valor = str_replace('Ñ', "N", $valor);
            $valor = str_replace('ñ', "n", $valor);
            $valor = str_replace('á', "a", $valor);
            $valor = str_replace('é', "e", $valor);
            $valor = str_replace('í', "i", $valor);
            $valor = str_replace('ó', "o", $valor);
            $valor = str_replace('ú', "u", $valor);
            $valor = str_replace('¿', '', $valor);
        }
        return $valor;
    }

}
