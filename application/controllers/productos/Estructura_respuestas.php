<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Estructura_respuestas extends CI_Controller {
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
        $this->load->model('Admin_eva');
        $this->Tgasolutions->usuario();
        $this->Tgasolutions->permisos();
    }
    public function index(){

    }
    public function carga_lista(){
        $data["dbData"] = $this->load->database('evaluaciones', TRUE);
        $data["idTipoC"]        = trim($this->input->post('var1'));
        $data["idCategoria"]    = trim($this->input->post('var2'));
        $data["tipoR"]          = trim($this->input->post('var3'));
        if (!is_numeric($data["idTipoC"])) {exit;}
        if (!is_numeric($data["idCategoria"])) {exit;}
        if (!is_numeric($data["tipoR"])) {exit;}

        $this->load->view('productos/administrador_evaluaciones/estructura_respuestas/lista',$data);
    }
    public function nuevo_tipo_respuesta(){
        $data["dbData"]         = $this->load->database('evaluaciones', TRUE);
        $data["idTipoC"]        = trim($this->input->post('var1'));
        $data["idCategoria"]    = trim($this->input->post('var2'));
        $data["tipoR"]          = trim($this->input->post('var3'));
        if (!is_numeric($data["idTipoC"])) {exit;}
        if (!is_numeric($data["idCategoria"])) {exit;}
        if (!is_numeric($data["tipoR"])) {exit;}

        $this->load->view('productos/administrador_evaluaciones/estructura_respuestas/nuevo_tipo',$data);
    }
    public function crear_tipo_respuesta(){
        $idTipoC        = trim($this->input->post('idTipoC'));
        $idCategoria    = trim($this->input->post('idCategoria'));
        $tipoR          = trim($this->input->post('tipoR'));
        $nombre         = trim($this->input->post('nombre'));
        if (!is_numeric($idTipoC)) {exit;}
        if (!is_numeric($idCategoria)) {exit;}

        if (strlen($nombre)<3) {
            $this->Tgasolutions->mensaje(3,"Titulo","Tiene que ingresar un titulo al nuevo tipo de respuesta");
            exit;
        }
        if (!is_numeric($tipoR)) {exit;}
        $validar = $this->Tgasolutions->valida_texto($nombre,"-_¡!¿?=.,[]()1234567890 abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ");
        if ($validar[0]==0) {
            $this->Tgasolutions->mensaje(3,"Titulo","El titulo solo acepta los siguientes caracteres: a-Z 0-9 .,-[] ()¡!¿?=<br>Caracter invalido [".$validar[1]."]");
            exit;
        }


        if ($tipoR<0) {
            $this->Tgasolutions->mensaje(3,"Tipo de respuesta","Tiene que seleccionar un tipo de respuesta");
            exit;
        }

        $dbData = $this->load->database('evaluaciones', TRUE);
        $query = $dbData->query("SELECT 1
                                   FROM data_respuesta_estructura
                                  WHERE nombre        = '$nombre';");
        if ($query->num_rows() > 0){
            $this->Tgasolutions->mensaje(3,"Titulo ya existe","Lo sentimos el titulo ingresado ya existe.");
            exit;
        }

        $sql = "INSERT INTO data_respuesta_estructura (idTipoC
                                                     , idCategoria
                                                     , tipoRespuesta
                                                     , nombre)
                                               VALUES ($idTipoC
                                                     , $idCategoria
                                                     , $tipoR
                                                     , '$nombre');";
        if ($dbData->query($sql)===true) {
            $this->Tgasolutions->mensaje(0,"Tipo de respuesta creada","El tipo de respuesta fue creada correctamente");
            ?>
            <script type="text/javascript">
            $('#tgaSleModal2').modal('toggle');
            tgaSolution.LoaderTga = 0;
            seleccionTipoRespuesta(<?php echo $tipoR;?>);
            </script>
            <?php
            exit;
        }else{
            $this->Tgasolutions->mensaje(3,"ERROR","Ocurrio un problema no se pudo guardar");
            exit;
        }
    }
    public function estructura_respuesta(){
        $data["dbData"] = $this->load->database('evaluaciones', TRUE);
        $data["idTipoC"]        = trim($this->input->post('var1'));
        $data["idCategoria"]    = trim($this->input->post('var2'));
        $data["tipoR"]          = trim($this->input->post('var3'));
        $data["idER"]           = trim($this->input->post('var4'));
        if (!is_numeric($data["idTipoC"])) {exit;}
        if (!is_numeric($data["idCategoria"])) {exit;}
        if (!is_numeric($data["tipoR"])) {exit;}
        if (!is_numeric($data["idER"])) {exit;}

        $idTipoC         = $data["idTipoC"];
        $idCategoria     = $data["idCategoria"];
        $tipoR           = $data["tipoR"];
        $idER            = $data["idER"];
        $dbData          = $data["dbData"];

        $query = $dbData->query("SELECT idRespuestaEstructura, nombre, estado, tipoRespuesta, `global`
                                   FROM data_respuesta_estructura
                                  WHERE idTipoC                = $idTipoC
                                    AND (idCategoria           = $idCategoria OR `global`=1)
                                    AND idRespuestaEstructura  = $idER;");
        if ($query->num_rows() > 0){
            $row = $query->row();
            $data['nombre']          = $row->nombre;
            $data['estado']          = $row->estado;
            $data['global']          = $row->global;
            $data['idTipoR']         = $row->tipoRespuesta;

            if ($data['estado']!= 1) {$data['estado'] = 0;}
        }else{
            exit;
        }

        $this->load->view('productos/administrador_evaluaciones/estructura_respuestas/estructura_respuesta',$data);
    }
    public function editar_estructura_respuesta(){
        $dbData = $this->load->database('evaluaciones', TRUE);
        $idTipoC         = trim($this->input->post('idTipoC'));
        $idCategoria     = trim($this->input->post('idCategoria'));
        $idTipoR         = trim($this->input->post('idTipoR'));
        $idER            = trim($this->input->post('idER'));
        $nombre          = trim($this->input->post('nombre'));
        $estado          = trim($this->input->post('estado'));
        $global          = trim($this->input->post('global'));
        if (!is_numeric($idTipoC)) {exit;}
        if (!is_numeric($idCategoria)) {exit;}
        if (!is_numeric($idTipoR)) {exit;}
        if (!is_numeric($idER)) {exit;}
        if (!is_numeric($estado)) {exit;}

        # Comprobar nombre
        if (strlen($nombre)<3) {
            $this->Tgasolutions->mensaje(3,"Titulo","Tiene que ingresar un titulo del tipo de respuesta");
            exit;
        }

        $globalIn  = (!is_numeric($global)) ? ', global = 0' : ', global = 1';

        $query = $dbData->query("SELECT 1
                                   FROM data_respuesta_tipo
                                  WHERE idTipoR = $idTipoR;");
        if ($query->num_rows() == 0){
            $this->Tgasolutions->mensaje(3,"Tipo de respuesta","tiene que seleccionar un tipo de respuesta.");
            exit;
        }

        $query = $dbData->query("SELECT 1
                                   FROM data_respuesta_estructura
                                  WHERE idTipoC                = $idTipoC
                                    AND idCategoria            = $idCategoria
                                    AND idRespuestaEstructura != $idER
                                    AND nombre                 = '$nombre';");
        if ($query->num_rows() > 0){
            $this->Tgasolutions->mensaje(3,"Titulo ya existe","Lo sentimos el titulo ingresado ya existe.");
            exit;
        }
        if ($estado!=1) {$estado = 0;}

        $sql = "UPDATE data_respuesta_estructura
                   SET nombre        = '$nombre'
                     , estado        = $estado
                     , tipoRespuesta = $idTipoR
                     $globalIn
                 WHERE idTipoC                = $idTipoC
                   AND idCategoria            = $idCategoria
                   AND idRespuestaEstructura  = $idER;";

        if ($dbData->query($sql)===true) {
            $this->Tgasolutions->mensaje(0,"Tipo de respuesta actualizada","El tipo de respuesta fue actualizada correctamente");
            ?>
            <script type="text/javascript">
            tgaSolution.LoaderTga = 0;
            seleccionTipoRespuesta(<?php echo $idTipoR;?>);
            </script>
            <?php
            exit;
        }else{
            $this->Tgasolutions->mensaje(3,"ERROR","Ocurrio un problema no se pudo actualizadar");
            exit;
        }

    }
    public function nueva_respuesta(){
        $data["dbData"] = $this->load->database('evaluaciones', TRUE);
        $data["idTipoC"]        = trim($this->input->post('var1'));
        $data["idCategoria"]    = trim($this->input->post('var2'));
        $data["tipoR"]          = trim($this->input->post('var3'));
        $data["idER"]           = trim($this->input->post('var4'));
        if (!is_numeric($data["idTipoC"])) {exit;}
        if (!is_numeric($data["idCategoria"])) {exit;}
        if (!is_numeric($data["tipoR"])) {exit;}
        if (!is_numeric($data["idER"])) {exit;}

        $this->load->view('productos/administrador_evaluaciones/estructura_respuestas/nueva_respuesta',$data);
    }
    public function crear_nueva_respuesta(){
        $dbData = $this->load->database('evaluaciones', TRUE);
        $idTipoC         = trim($this->input->post('idTipoC'));
        $idCategoria     = trim($this->input->post('idCategoria'));
        $tipoR           = trim($this->input->post('tipoR'));
        $idER            = trim($this->input->post('idER'));
        $nombre          = trim($this->input->post('nombre'));
        $valor           = trim($this->input->post('valor'));
        if (!is_numeric($idTipoC)) {exit;}
        if (!is_numeric($idCategoria)) {exit;}
        if (!is_numeric($tipoR)) {exit;}
        if (!is_numeric($idER)) {exit;}

        if (strlen($nombre)<1) {
            $this->Tgasolutions->mensaje(3,"Texto","Tiene que ingresar un texto de la respuesta");
            exit;
        }
        $validar = $this->Tgasolutions->valida_texto($nombre,"-_¡!¿?=.,%/[]()1234567890 abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ");
        if ($validar[0]==0) {
            $this->Tgasolutions->mensaje(3,"Text","El texto solo acepta los siguientes caracteres: a-Z 0-9 .,-[] ()¡!¿?=<br>Caracter invalido [".$validar[1]."]");
            exit;
        }

        if (strlen($valor)<1) {
            $this->Tgasolutions->mensaje(3,"Valor","Tiene que ingresar un valor de la respuesta");
            exit;
        }
        if (!is_numeric($valor)) {
            $this->Tgasolutions->mensaje(3,"Valor","El valor solo acepta numeros");
            exit;
        }
        $validar = $this->Tgasolutions->valida_texto($valor,"1234567890");
        if ($validar[0]==0) {
            $this->Tgasolutions->mensaje(3,"Valor","El valor solo acepta los siguientes caracteres: a-Z 0-9 .,-[] ()¡!¿?=<br>Caracter invalido [".$validar[1]."]");
            exit;
        }

        $query = $dbData->query("SELECT 1
                                   FROM data_respuesta_datos
                                  WHERE texto                 = '$nombre'
                                    AND idRespuestaEstructura = $idER
                                    AND estado = 1;");
        if ($query->num_rows() > 0){
            $this->Tgasolutions->mensaje(3,"Texto ya existe","Lo sentimos el texto ingresado ya existe.");
            exit;
        }

        $query = $dbData->query("SELECT MAX(orden) AS orden
                                   FROM data_respuesta_datos
                                  WHERE idRespuestaEstructura = $idER
                                    AND estado = 1;");
        if ($query->num_rows() > 0){
            $row               = $query->row();
            $orden = ($row->orden!= "") ? ($row->orden + 1): 1;
        }

        # ver si esta creada
        $action = 1;
        $query = $dbData->query("SELECT idRespuestaDato
                                   FROM data_respuesta_datos
                                  WHERE texto                 = '$nombre'
                                    AND idRespuestaEstructura = $idER
                                    AND estado = 0;");
        if ($query->num_rows() > 0){
            $row               = $query->row();
            $idRespuestaDato   = $row->idRespuestaDato;
            $action            = 0;
        }

        if ($action==1) {
            $sql = "INSERT INTO data_respuesta_datos (idRespuestaEstructura, idTipoC, idCategoria, texto,valor,orden)
                                              VALUES ($idER, $idTipoC, $idCategoria, '$nombre',$valor,$orden);";
        }else{
            $sql = "UPDATE data_respuesta_datos
                       SET valor    = $valor
                         , estado   = 1
                         , orden    = $orden
                    WHERE idRespuestaDato        = $idRespuestaDato
                      AND idRespuestaEstructura  = $idER;";
        }

        if ($dbData->query($sql)===true) {
            $this->Tgasolutions->mensaje(0,"La acción fue realizada","La acción fue realizada correctamente");
            ?>
            <script type="text/javascript">
            $('#tgaSleModal2').modal('toggle');
            tgaSolution.LoaderTga = 0;
            listaRespuesta(<?php echo $idER;?>);
            </script>
            <?php
            exit;
        }else{
            $this->Tgasolutions->mensaje(3,"ERROR","Ocurrio un problema no se pudo realizar la acción");
            exit;
        }

    }
    public function carga_lista_respuestas(){
        $dbData = $this->load->database('evaluaciones', TRUE);
        $idER   = trim($this->input->post('var1'));
        if (!is_numeric($idER)) {exit;}

        $this->Admin_eva->lista_respuesta($dbData,$idER);
    }
    public function respuesta(){
        $data['dbData'] = $this->load->database('evaluaciones', TRUE);
        $data['idR']    = trim($this->input->post('var1'));
        $data['idER']   = trim($this->input->post('var2'));
        if (!is_numeric($data['idR'])) {exit;}
        if (!is_numeric($data['idER'])) {exit;}

        $this->load->view('productos/administrador_evaluaciones/estructura_respuestas/respuesta',$data);
    }
    public function editar_respuesta(){
        $dbData   = $this->load->database('evaluaciones', TRUE);
        $idR      = trim($this->input->post('idR'));
        $idER     = trim($this->input->post('idER'));
        $nombre   = trim($this->input->post('nombre'));
        $valor    = trim($this->input->post('valor'));
        $orden    = trim($this->input->post('orden'));
        if (!is_numeric($idR)) {exit;}
        if (!is_numeric($idER)) {exit;}
        if (!is_numeric($valor)) {exit;}
        if (!is_numeric($orden)) {exit;}

        $query = $dbData->query("SELECT 1
                                   FROM data_respuesta_datos
                                  WHERE texto                   = '$nombre'
                                    AND idRespuestaEstructura   = $idER
                                    AND idRespuestaDato        != $idR;");
        if ($query->num_rows() > 0){
            $this->Tgasolutions->mensaje(3,"Texto ya existe","Lo sentimos el texto ingresado ya existe.");
            exit;
        }

        if (strlen($nombre)<1) {
            $this->Tgasolutions->mensaje(3,"Texto","Tiene que ingresar un texto de la respuesta");
            exit;
        }
        $validar = $this->Tgasolutions->valida_texto($nombre,"-_¡!¿?=.,%/[]()1234567890 abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ");
        if ($validar[0]==0) {
            $this->Tgasolutions->mensaje(3,"Text","El texto solo acepta los siguientes caracteres: a-Z 0-9 .,-[] ()¡!¿?=<br>Caracter invalido [".$validar[1]."]");
            exit;
        }

        $sql = "UPDATE data_respuesta_datos
                   SET texto   = '$nombre'
                     , valor   = $valor
                     , orden   = $orden
                 WHERE idRespuestaEstructura   = $idER
                   AND idRespuestaDato         = $idR;";
        if ($dbData->query($sql)===true) {
            $this->Tgasolutions->mensaje(0,"Respuesta creada","La respuesta fue actualizada correctamente");
            ?>
            <script type="text/javascript">
            $('#tgaSleModal2').modal('toggle');
            tgaSolution.LoaderTga = 0;
            listaRespuesta(<?php echo $idER;?>);
            </script>
            <?php
            exit;
        }else{
            $this->Tgasolutions->mensaje(3,"ERROR","Ocurrio un problema no se pudo actaulizar");
            exit;
        }

    }
    public function delete_respuesta(){
        $dbData   = $this->load->database('evaluaciones', TRUE);
        $idR      = trim($this->input->post('var1'));
        $idER     = trim($this->input->post('var2'));

        $sql = "UPDATE data_respuesta_datos
                   SET estado = 0
                 WHERE idRespuestaEstructura   = $idER
                   AND idRespuestaDato         = $idR;";
        if ($dbData->query($sql)===true) {
            ?>
            <script type="text/javascript">
            $('#tgaSleModal2').modal('toggle');
            setTimeout("listaRespuesta(<?php echo $idER;?>);",600);
            </script>
            <?php
            exit;
        }else{
            $this->Tgasolutions->mensaje(3,"ERROR","Ocurrio un problema no se pudo eliminar");
            exit;
        }


    }
    public function categoria(){
        $dbData    = $this->load->database('evaluaciones', TRUE);
        $idTipoC   = trim($this->input->post('var1'));
        if (!is_numeric($idTipoC)) {exit;}

        $opciones = '<option value="0">Seleccione</option>';
        $query = $dbData->query("SELECT idCategoria, categoria
                                   FROM data_categoria
                                  WHERE idTipoC = $idTipoC
                                    AND estado  = 1
                               ORDER BY categoria ASC;");
        if ($query->num_rows() > 0){
            $row = $query->row();
            for ($i=1;$i<=$query->num_rows();$i++) {
                $opciones = $opciones.'<option value="'.$row->idCategoria.'">'.$row->categoria.'</option>';
                $row = $query->next_row();
            }
            ?>
            <script type="text/javascript">
            $(".estructura_respuestas-1 .tga-box-2 select").html('<?php echo $opciones;?>');
            $(".estructura_respuestas-1 .tga-box-2 select").prop('disabled', false);
            </script>
            <?php
        }
    }
    public function equivalente_respuesta(){
        echo "equivalente_respuesta";
    }
}
