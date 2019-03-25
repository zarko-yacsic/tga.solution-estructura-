<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Bdd extends CI_Controller {
	public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->library('user_agent');
        $this->load->library('spreadsheet_reader');
        $this->load->database('evaluaciones');
        $this->load->helper('cookie');
        session_start();
        date_default_timezone_set('UTC');
        $this->load->model('Tgasolutions');
        $this->Tgasolutions->usuario();
        $this->Tgasolutions->permisos();
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
                    $archivo_xlsx = $idTipoCuestionario . '_' . $idPais . '_' . $idInmobiliaria . '_' . $idProyecto . '_' . $idEvaluacion . '_' . $idUser . '_bdd.xlsx';
                    $target_file_new = $_SERVER['DOCUMENT_ROOT'] . '/' . $target_dir . '/' . $archivo_xlsx;
                    
                    if(rename($target_file, $target_file_new)){
                        $_SESSION['id_tipo_cuestionario'] = $idTipoCuestionario;
                        $_SESSION['id_pais'] = $idPais;
                        $_SESSION['id_inmobiliaria'] = $idInmobiliaria;
                        $_SESSION['id_proyecto'] = $idProyecto;
                        $_SESSION['id_evaluacion'] = $idEvaluacion;
                        $_SESSION['xlsx_uploads'] = $target_dir;
                        $_SESSION['xlsx_archivo'] = $archivo_xlsx;
                        
                        // Leer .xlsx...
                        $Reader = new SpreadsheetReader($target_dir . '/' . $archivo_xlsx);
                        $totalColumnas = 0;
                        $data = array();
                        $a = 0;
                        $b = 0;
                        
                        foreach ($Reader as $Row){
                            $totalColumnas = count($Row);
                            break;
                        }
                        foreach ($Reader as $Row){
                            $a++;
                            $b = 0;
                            for ($i = 0; $i < $totalColumnas; $i++){
                                $b++;
                                if(!isset($Row[$i])){
                                    $data[$a][$b] = '';
                                }
                                else{
                                    $data[$a][$b] = $Row[$i];
                                }  
                            }
                        }
                        
                        // Eliminar primera fila del array y luego reindexar items...
                        $data_tmp = $data;
                        unset($data_tmp[0]);
                        $data = array_values($data_tmp);

                        // Reindexar sub-items...
                        $subArray_temp = array();
                        for($h = 0; $h < count($data); $h++){
                            $subArray_temp = array_values($data[$h]);
                            $data[$h] = $subArray_temp;
                        }

                        // Buscar si existen codigos repetidos...
                        $cod_duplicados = array();
                        $s = 0;
                        for($r = 0; $r < count($data); $r++){
                            $buscarCod = $data[$r][0];
                            $existe = 0;
                            for($d = 0; $d < count($data); $d++){
                                if($buscarCod == $data[$d][0]){
                                    $existe++;
                                }
                            }
                            if($existe > 1){
                                $cod_duplicados[$s] = array(
                                    'codigo' => $data[$r][0],
                                    'existe_veces' => $existe
                                );
                                $s++;
                            }
                        }

                        // Volver a eliminar primera fila del array y luego reindexar items...
                        $data_tmp = $data;
                        unset($data_tmp[0]);
                        $data = array_values($data_tmp);
                        
                        // Eliminar duplicados en al array y luego reindexar items...
                        $cod_duplicados_tmp = array_map('unserialize', array_unique(array_map('serialize', $cod_duplicados)));
                        $cod_duplicados = array_values($cod_duplicados_tmp);
                        
                        // Borrar cualquier registro que pudiera existir previamente...
                        $query = "DELETE FROM data_bdd WHERE idEvaluacion=" . $idEvaluacion . " AND idInmobiliaria=" . $idInmobiliaria . " AND idProyecto=" . $idProyecto . ";";
                        $result = $this->db->query($query);

                        // Si no hay codigos duplicados en la BDD, entonces insertar datos en la BD y devolver total insertados...
                        if(count($cod_duplicados) == 0){
                            $totalInserts = 0;
                            for($d = 0; $d < count($data); $d++){
                                $fecha_tmp = explode('/', $data[$d][6]);
                                $fecha = $fecha_tmp[0] . '-' . $fecha_tmp[1] . '-' . $fecha_tmp[2];
                                $sql = "INSERT INTO data_bdd (idEvaluacion, idInmobiliaria, idProyecto, codigo, email, fecha, producto, propietario, celular, fonoCasa, programa, direccion) ";
                                $sql .= "VALUES (" . $idEvaluacion . ", " . $idInmobiliaria . ", " . $idProyecto . ", '" . $data[$d][0] . "', '" . strtolower($data[$d][3]) . "', '";
                                $sql .= $fecha . "', '" . $data[$d][1] . "', '" . strtoupper($data[$d][2]) . "', '" . $data[$d][4] . "', '" . $data[$d][5] . "', '" . $data[$d][7] . "', '" . $data[$d][8] . "');";
                                $result = $this->db->query($sql);
                                if($result){
                                    $totalInserts++;
                                }
                            }
                            $mensaje = 'Se ha subido correctamente la BDD seleccionada (' . $totalInserts . ' registros).';
                            $status = 'SUCCESS';
                        }
                        else{
                            $mensaje = 'Se han encontrado los siguientes registros duplicados en la BDD:';
                            $status = 'ERROR_DUPLICADOS';
                        }
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
                'upload_dir' => $target_dir,
                'id_evaluacion' => $idEvaluacion
            );

            // Si el error fuera de tipo 'Codigos duplicados', entonces agregar a la salida json en array con los duplicados encontrados...
            if($status == 'ERROR_DUPLICADOS'){
                $data['cod_duplicados'] = $cod_duplicados;
            }
            
            // Salida JSON...
            $output = json_encode($data);
            echo $output;
        }
    }



    public function listar_bdd(){
        $id_evaluacion = $_GET['id_evaluacion'];
        $query = $this->db->query("SELECT * FROM data_bdd WHERE idEvaluacion=" . $id_evaluacion . ";");
        $result = $query->result_array();
        $data['result'] = $result;
        $this->load->view('productos/administrador_evaluaciones/bdd/listar_bdd', $data);
    }

}