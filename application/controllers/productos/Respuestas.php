<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Respuestas extends CI_Controller {
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


    public function subir_excel(){
        if(isset($_SESSION['idUser'])){
            $idTipoCuestionario = $_POST['hf_idTipoCuestionario'];
            $idPais = $_POST['hf_idPais'];
            $idInmobiliaria = $_POST['hf_idInmobiliaria'];
            $idProyecto = $_POST['hf_idProyecto'];
            $idEvaluacion = $_POST['hf_idEvaluacion'];
            $fileName = $_FILES['archivo']['name'];
            $fileSize = $_FILES['archivo']['size'];
            $target_dir = 'excel';
            $target_file = $_SERVER['DOCUMENT_ROOT'] . '/' . $target_dir . '/' . basename($fileName);
            $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $maxSize_bytes = 5242880; // 5 mb tamaño maximo subida...
            $archivo_xlsx = '';

            if($fileSize > $maxSize_bytes){
                $mensaje = 'El tamaño del archivo Excel seleccionado no debe ser mayor a 5MB.';
                $status = 'ERROR_FILESIZE';
            }
            else{
                if(move_uploaded_file($_FILES['archivo']['tmp_name'], $target_file)){
                    $idUser = $_SESSION['idUser'];
                    $archivo_xlsx = $idTipoCuestionario . '_' . $idPais . '_' . $idInmobiliaria . '_' . $idProyecto . '_' . $idEvaluacion . '_' . $idUser . '_respuestas.xlsx';
                    $target_file_new = $_SERVER['DOCUMENT_ROOT'] . '/' . $target_dir . '/' . $archivo_xlsx;
                    if(rename($target_file, $target_file_new)){
                        $_SESSION['id_tipo_cuestionario'] = $idTipoCuestionario;
                        $_SESSION['id_pais'] = $idPais;
                        $_SESSION['id_inmobiliaria'] = $idInmobiliaria;
                        $_SESSION['id_proyecto'] = $idProyecto;
                        $_SESSION['id_evaluacion'] = $idEvaluacion;
                        $_SESSION['xlsx_uploads'] = $target_dir;
                        $_SESSION['xlsx_archivo'] = $archivo_xlsx;

                        // Get all data from .xlsx...
                        $columna_inicio = 1;
                        $totalColumnas = 0;
                        $data          = array();
                        $pregunta      = array();
                        $pregunta1     = array();
                        $pregunta2     = array();
                        $a             = 0;
                        $b             = 0;
                        $Reader = new SpreadsheetReader($target_dir . '/' . $archivo_xlsx);
                        
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
                            for ($i = $columna_inicio; $i <= $ancho; $i++){
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

                        for ($i = $columna_inicio; $i <= $ancho; $i++){
                            $viene = 0;
                            if ($pregunta1[$i] < $ancho){ $viene = 1;}
                            $valor              = $data[2][$i];
                            $valor              = $this->limpiar_carcteres($valor);
                            $valor              = utf8_decode($valor);
                            $subPreguntaNom[$i] = $valor;
                            $valor = $i + $viene;
                            if(!isset($pregunta1[$valor]) || empty($pregunta1[$valor])){
                                $pregunta1[$valor] = '';
                            }
                            if (($pregunta1[$i] == 1 && $pregunta1[$valor] == 1) || $i == $ancho){
                                $d = 0;
                                $c++;
                                $tipoP[$i]      = 1;
                                $preguntaX[$i]  = 'P' . $c;
                                $preguntaN[$i]  = $c;
                                $preguntaSN[$i] = 0;
                                $valor              = $data[1][$i];
                                $valor              = $this->limpiar_carcteres($valor);
                                $valor              = utf8_decode($valor);
                                $nomP               = $valor;
                                $preguntaNom[$i]    = $nomP;
                            }
                            else if ($pregunta1[$i] == 1){
                                $d = 0;
                                $c++;
                                $d++;
                                $tipoP[$i]       = 2;
                                $preguntaX[$i]   = 'P' . $c . '_' . $d;
                                $preguntaN[$i]   = $c;
                                $preguntaSN[$i]  = $d;
                                $valor           = $data[1][$i];
                                $valor           = $this->limpiar_carcteres($valor);
                                $valor           = utf8_decode($valor);
                                $nomP            = $valor;
                                $preguntaNom[$i] = $nomP;
                            }
                            else{
                                $d++;
                                $tipoP[$i]       = 2;
                                $preguntaX[$i]   = 'P' . $c . '_' . $d;
                                $preguntaN[$i]   = $c;
                                $preguntaSN[$i]  = $d;
                                $preguntaNom[$i] = $this->limpiar_carcteres($nomP);
                            }
                        }
                        
                        $e = 0;
                        $nomP = '';
                        for ($i = 1; $i < $columna_inicio; $i++){
                            $e++;
                            $tipoP[$i]      = 0;
                            $preguntaX[$i]  = 'M' . $e;
                            $preguntaN[$i]  = 0;
                            $preguntaSN[$i] = 0;
                            $valor          = $data[1][$i];

                            if ($valor == ''){
                                $preguntaNom[$i] = $nomP . ' ' . utf8_decode($this->limpiar_carcteres($data[2][$i]));
                            }
                            else{
                                $valor           = str_replace("'", "´", $valor);
                                $valor           = str_replace('"', "´", $valor);
                                $valor           = str_replace('Ñ', "N", $valor);
                                $valor           = str_replace('ñ', "n", $valor);
                                $valor           = str_replace('á', "a", $valor);
                                $valor           = str_replace('é', "e", $valor);
                                $valor           = str_replace('í', "i", $valor);
                                $valor           = str_replace('ó', "o", $valor);
                                $valor           = str_replace('ú', "u", $valor);
                                $valor           = utf8_decode($valor);
                                $nomP            = $valor;
                                $preguntaNom[$i] = $nomP;
                                $r = $i + 1;
                                if ($data[1][$r] == ''){
                                    $preguntaNom[$i] = $nomP . ' ' . utf8_decode($this->limpiar_carcteres($data[2][$i]));
                                }
                            }
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
                            $valor              = utf8_decode($valor);
                            $subPreguntaNom[$i] = $valor;
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

                        // Reindexar array...
                        $data_tmp = $data;
                        $data = array_values($data_tmp);
                        
                        // Imprimir array (Test)...
                        echo '<pre>';
                        print_r($data);
                        echo '</pre><br>';

                        $mensaje = 'Se ha subido correctamente el archivo .xlsx seleccionado.';
                        $status = 'SUCCESS';
                    }
                }
                else{
                    $mensaje = 'Error al subir archivo Excel seleccionado.';
                    $status = 'ERROR_UPLOAD';
                }
            }
            
            $data = array(
                'status' => $status,
                'titulo' => 'Administrar evaluaciones',
                'mensaje' => $mensaje,
                'archivo_xlsx' => $archivo_xlsx,
                'upload_dir' => $target_dir
            );

            // Salida JSON...
            $output = json_encode($data);
            echo $output;
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