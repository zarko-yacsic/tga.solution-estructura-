<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empresas extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->library('user_agent');
        $this->load->database();
        $this->load->helper('cookie');
        session_start();

        $this->load->model('Tgasolutions');
        $this->load->library('pagination');
        # ------------------ -------------------
        $this->Tgasolutions->usuario();
        $this->Tgasolutions->permisos();
    }
    public function index(){
        $this->load->view('pre_body');
        $data["hoja"]   = "home";
        $this->load->view('header',$data);
        $data["sasas"]  = "asasas";
        $this->load->view('tga_global/empresas/index',$data);
        $this->load->view('post_body');
    }
    public function lista(){
        $total        = 0;
        $uri          = 4;
        $porPagina    = 10;
        $mostrarNum   = 2;
        $lista        = (!is_numeric($this->uri->segment(4))) ? 0 : $this->uri->segment(4);
        $idTipo       = trim($this->input->post('var1'));
        $idpais       = trim($this->input->post('var2'));
        $fragmenta1   = preg_split("/[\-]+/",$idTipo);
        $fragmenta2   = preg_split("/[\-]+/",$idpais);

        if ( $fragmenta1[1] != md5($fragmenta1[0].'alfonsito') ) {
            $this->Tgasolutions->mensaje(3,"ERROR","No se encontro el tipo de empresa");
            exit;
        }
        if ( $fragmenta2[1] != md5($fragmenta2[0].'alfonsito') ) {
            $this->Tgasolutions->mensaje(3,"ERROR","No se encontro el pais");
            exit;
        }

        $idTipo      = $fragmenta1[0];
        $idPais      = $fragmenta2[0];

        $query  = $this->db->query("SELECT SQL_CALC_FOUND_ROWS
                                           idEmpresa,
                                           idTipoEmpresa,
                                           empresa,
                                           idPais,
                                           estado
                                      FROM tga_empresas
                                     WHERE idTipoEmpresa = $idTipo
                                       AND idPais = $idPais
                                  ORDER BY estado DESC, empresa ASC
                                     LIMIT $lista,$porPagina;");

        $query2 = $this->db->query("SELECT FOUND_ROWS() AS total;");
        if ($query2->num_rows() > 0){
            $row2   = $query2->row();
            $total  = $row2->total;
        }

        if ($query->num_rows() > 0){
            $row = $query->row();
            ?>
            <ul class="lista">
            <?php
            for ($i=1;$i<=$query->num_rows();$i++) {
                $idEmpresa    = $row->idEmpresa;
                $empresa      = $row->empresa;
                $estado       = $row->estado;

                $filtro = '';
                if ($estado==0) {
                    $filtro = 'inactivo';
                }else if ($estado==2) {
                    $filtro = 'nuevo';
                }
                $valor = $idTipo.'-'.$idPais.'-'.$idEmpresa.'-'.md5($idTipo.'-'.$idPais.'-'.$idEmpresa.'alfonsito');
                ?>
                <li class="num<?php echo $idEmpresa.' '.$filtro?>" onClick="cargamosLaEmpresa('<?php echo $valor;?>');" style="cursor: pointer;"><?php echo $empresa;?></li>
                <?php
                $row = $query->next_row();
            }
            ?>
            </ul>
            <?php
            $this->Tgasolutions->paginacion($total,$uri,$porPagina,$mostrarNum,'changeSelectTipoPais');
        }
    }
    function cargar_empresa(){
        $idTipoEmpresa = trim($this->input->post('var1'));
        $fragmenta     = preg_split("/[\-]+/",$idTipoEmpresa);

        if ( $fragmenta[3] != md5($fragmenta[0].'-'.$fragmenta[1].'-'.$fragmenta[2].'alfonsito') ) {
            $this->Tgasolutions->mensaje(3,"ERROR","No se encontro el tipo de evaluación ni la categoria.");
            exit;
        }

        $idTipoEmpresa = $fragmenta[0];
        $idPais        = $fragmenta[1];
        $idEmpresa     = $fragmenta[2];

        $query  = $this->db->query("SELECT idEmpresa, idTipoEmpresa, idPais, empresa, estado
                                    FROM tga_empresas
                                   WHERE idTipoEmpresa = $idTipoEmpresa
                                     AND idPais        = $idPais
                                     AND idEmpresa     = $idEmpresa;");
        if ($query->num_rows() > 0){
            $row           = $query->row();
            $idEmpresa     = $row->idEmpresa;
            $idTipoEmpresa = $row->idTipoEmpresa;
            $idPais        = $row->idPais;
            $empresa       = $row->empresa;
            $estado        = $row->estado;

            $estado = ($estado == 2) ? 0 : $estado;
            ?>
            <script type="text/javascript">
            $(".tga-contenido .tga-contenido-In .idTipoEmpresa").val("<?php echo $idTipoEmpresa.'-'.md5($idTipoEmpresa.'alfonsito');?>");
            $(".tga-contenido .tga-contenido-In .idPais").val("<?php echo $idPais.'-'.md5($idPais.'alfonsito');?>");
            $(".tga-contenido .tga-contenido-In .idEmpresa").val("<?php echo $idEmpresa.'-'.md5($idEmpresa.'alfonsito');?>");
            $(".tga-contenido .tga-contenido-In .lado-b .mini").html('<?php echo "ID: ".$idEmpresa;?>');
            $(".tga-contenido .tga-contenido-In .lado-b .nombre").val('<?php echo $empresa;?>');
            $(".tga-contenido .tga-contenido-In .lado-b .tipoEmpresa").val('<?php echo $idTipoEmpresa."-".md5($idTipoEmpresa."alfonsito");?>');
            $(".tga-contenido .tga-contenido-In .lado-b .estado").val(<?php echo $estado == 2 ? 0 : $estado;?>);

            $(".tga-contenido-empresas .tga-contenido-In .lado-b .nombre").prop('disabled', false);
            $(".tga-contenido-empresas .tga-contenido-In .btn-empresa").prop('disabled', false);
            $(".tga-contenido-empresas .tga-contenido-In .btn-actualiza").prop('disabled', false);
            $(".tga-contenido-empresas .tga-contenido-In select").prop('disabled', false);
            </script>
            <?php
        }else{
            exit;
        }
    }
    public function nuevo(){
        $data['idTipoEmpresa']  = trim($this->input->post('var1'));
        $data['idPais']         = trim($this->input->post('var2'));
        $this->load->view('tga_global/empresas/nuevo',$data);
    }
    public function nuevo_tipo(){
        $this->load->view('tga_global/empresas/nuevo_tipo');
    }
    public function crear_empresa(){

        $idTipoEmpresa    = trim($this->input->post('idTipoEmpresa'));
        $idPais           = trim($this->input->post('idPais'));
        $nombre           = trim($this->input->post('nombre'));

        $fragmenta     = preg_split("/[\-]+/",$idTipoEmpresa);
        if ( $fragmenta[1] != md5($fragmenta[0].'alfonsito') ) {
            $this->Tgasolutions->mensaje(3,"ERROR","No se encontro el tipo de empresa.");
            exit;
        }
        $idTipoEmpresa = $fragmenta[0];

        $fragmenta = preg_split("/[\-]+/",$idPais);
        if ( $fragmenta[1] != md5($fragmenta[0].'alfonsito') ) {
            $this->Tgasolutions->mensaje(3,"ERROR","No se encontro el pais.");
            exit;
        }
        $idPais    = $fragmenta[0];

        # Validar nombre
        if (strlen($nombre)<2) {
            $this->Tgasolutions->mensaje(3,"nombre empresa","Tiene que ingresar el nombre de la empresa");
            exit;
        }

        $resultado = $this->Tgasolutions->valida_texto($nombre,' abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ');
        if ($resultado[0]==0) {
            $this->Tgasolutions->mensaje(3,"Nombre empresa","Solo se pueden ingresar caracteres de la A-Z y espacios.<br>Caracter invalido:".$resultado[1]);
            exit;
        }

        $nombre = $this->Tgasolutions->mayuscula_todos($nombre);

        # validar si existe la categoria
        $query = $this->db->query("SELECT 1 FROM tga_empresas a WHERE empresa = '$nombre' and idPais = $idPais;");
        if ($query->num_rows() > 0){
            $this->Tgasolutions->mensaje(3,"Nombre empresa","El nombre de la empresa ya existe.");
            exit;
        }

        # Guardar categoria
        $sql = "INSERT INTO tga_empresas (idTipoEmpresa, idPais, empresa, estado)
                                    VALUES ('$idTipoEmpresa', '$idPais', '$nombre', '2');";
        if ( $this->db->query($sql) === true ) {
            $this->Tgasolutions->mensaje(0,"EMPRESA INGRESADA","La nueva empresa fue ingresada.");
            ?>
            <script type="text/javascript">
            $('#tgaSleModal2').modal('toggle');

            tgaSolution.LoaderTga = 0;
            tgaSolution.LoadCualquiera = setTimeout(changeSelectTipoPais(0),200);
            </script>
            <?php
        }else{
            $this->Tgasolutions->mensaje(3,"VUELVA A INTENTAR","La nueva empresa no fue ingresada.");
            exit;
        }
    }
    public function actualizar_empresa(){

        $idEmpresa        = trim($this->input->post('idEmpresa'));
        $idTipoEmpresa    = trim($this->input->post('idTipoEmpresa'));
        $idPais           = trim($this->input->post('idPais'));
        $nombre           = trim($this->input->post('nombre'));
        $idNewTipoEmpresa = trim($this->input->post('idNewTipoEmpresa'));
        $estadoEmpresa    = trim($this->input->post('estadoEmpresa'));

        $fragmenta     = preg_split("/[\-]+/",$idEmpresa);
        if ( $fragmenta[1] != md5($fragmenta[0].'alfonsito') ) {
            $this->Tgasolutions->mensaje(3,"ERROR","No se encontro la empresa.");
            exit;
        }
        $idEmpresa = $fragmenta[0];

        $fragmenta     = preg_split("/[\-]+/",$idTipoEmpresa);
        if ( $fragmenta[1] != md5($fragmenta[0].'alfonsito') ) {
            $this->Tgasolutions->mensaje(3,"ERROR","No se encontro el tipo de empresa.");
            exit;
        }
        $idTipoEmpresa = $fragmenta[0];

        $fragmenta = preg_split("/[\-]+/",$idPais);
        if ( $fragmenta[1] != md5($fragmenta[0].'alfonsito') ) {
            $this->Tgasolutions->mensaje(3,"ERROR","No se encontro el pais.");
            exit;
        }
        $idPais    = $fragmenta[0];

        $fragmenta     = preg_split("/[\-]+/",$idNewTipoEmpresa);
        if ( $fragmenta[1] != md5($fragmenta[0].'alfonsito') ) {
            $this->Tgasolutions->mensaje(3,"ERROR","No se encontro el nuevo tipo de empresa.");
            exit;
        }
        $idNewTipoEmpresa = $fragmenta[0];

        # Validar nombre
        if (strlen($nombre)<2) {
            $this->Tgasolutions->mensaje(3,"nombre empresa","Tiene que ingresar el nombre de la empresa");
            exit;
        }

        $resultado = $this->Tgasolutions->valida_texto($nombre,' abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ');
        if ($resultado[0]==0) {
            $this->Tgasolutions->mensaje(3,"Nombre empresa","Solo se pueden ingresar caracteres de la A-Z y espacios.<br>Caracter invalido:".$resultado[1]);
            exit;
        }
        $nombre = $this->Tgasolutions->mayuscula_todos($nombre);

        # validar si existe la categoria
        $query = $this->db->query("SELECT 1
                                     FROM tga_empresas a
                                    WHERE idEmpresa != $idEmpresa
                                      AND empresa    = '$nombre'
                                      AND idPais     = $idPais;");
        if ($query->num_rows() > 0){
            $this->Tgasolutions->mensaje(3,"Nombre empresa","El nombre de la empresa ya existe.");
            exit;
        }

        # Guardar categoria
        $sql = "UPDATE tga_empresas
                   SET empresa       = '$nombre'
                     , idTipoEmpresa = $idNewTipoEmpresa
                     , estado        = $estadoEmpresa
                 WHERE idEmpresa     = $idEmpresa
                   AND idPais        = $idPais;";
        if ( $this->db->query($sql) === true ) {
            $this->Tgasolutions->mensaje(0,"EMPRESA ACTUALIZADA","La empresa fue actualizada.");
            ?>
            <script type="text/javascript">
                tgaSolution.LoaderTga = 0;
                tgaSolution.LoadCualquiera = setTimeout(changeSelectTipoPais(0),200);
            </script>
            <?php
        }else{
            $this->Tgasolutions->mensaje(3,"VUELVA A INTENTAR","La nueva empresa no fue ingresada.");
            exit;
        }
    }
    public function lista_tipo(){
        $total        = 0;
        $uri          = 4;
        $porPagina    = 5;
        $mostrarNum   = 2;
        $lista        = (!is_numeric($this->uri->segment(4))) ? 0 : $this->uri->segment(4);

        $query  = $this->db->query("SELECT SQL_CALC_FOUND_ROWS
                                           idTipoEmpresa,
                                           tipo
                                      FROM tga_empresas_tipo
                                     WHERE estado = 1
                                  ORDER BY estado DESC, tipo ASC
                                     LIMIT $lista,$porPagina;");

        $query2 = $this->db->query("SELECT FOUND_ROWS() AS total;");
        if ($query2->num_rows() > 0){
            $row2   = $query2->row();
            $total  = $row2->total;
        }

        if ($query->num_rows() > 0){
            $row = $query->row();
            ?>
            <ul class="lista">
            <?php
            for ($i=1;$i<=$query->num_rows();$i++) {
                $idTipoEmpresa  = $row->idTipoEmpresa;
                $tipo           = $row->tipo;
                $valor          = $idTipoEmpresa.'-'.md5($idTipoEmpresa.'alfonsito');
                ?>
                <li class="num<?php echo $idTipoEmpresa; ?>" style="width: 100%; cursor: context-menu;"><?php echo $tipo;?> <span style="float:right; cursor: pointer; margin-right: 10px;" onclick="removeTipo('<?=$valor;?>')">x</span></li>
                <?php
                $row = $query->next_row();
            }
            ?>
            </ul>
            <?php
            $this->Tgasolutions->paginacion($total,$uri,$porPagina,$mostrarNum,'listaTipos');
        }
    }
    public function crear_tipo_empresa(){
        $tipo = trim($this->input->post('tipo'));
        # Validar tipo
        if (strlen($tipo)<2) {
            $this->Tgasolutions->mensaje(3,"Tipo de empresa","Tiene que ingresar el tipo de empresa");
            exit;
        }
        $resultado = $this->Tgasolutions->valida_texto($tipo,' abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ');
        if ($resultado[0]==0) {
            $this->Tgasolutions->mensaje(3,"Tipo de empresa","Solo se pueden ingresar caracteres de la A-Z y espacios.<br>Caracter invalido:".$resultado[1]);
            exit;
        }
        $tipo = $this->Tgasolutions->mayuscula_todos($tipo);
        # validar si existe la empresa
        $query = $this->db->query("SELECT idTipoEmpresa, tipo, estado FROM tga_empresas_tipo a WHERE tipo = '$tipo';");
        if ($query->num_rows() > 0){
            $row = $query->row();
            if($row->estado == 0){
                $sql = "UPDATE tga_empresas_tipo SET estado = 1 WHERE idTipoEmpresa = ".$row->idTipoEmpresa.";";
                if ( $this->db->query($sql) === true ) {
                    $this->Tgasolutions->mensaje(0,"TIPO DE EMPRESA INGRESADO","El nuevo tipo de empresa fue ingresado.");
                    $idTipoEmpresaMd5 = $row->idTipoEmpresa."-".md5($row->idTipoEmpresa.'alfonsito');
                    ?>
                    <script type="text/javascript">
                    $('#miFor_NewTipoEmpresa input[name=tipo]').val('');
                    $('.tga-contenido-empresas .tga-section-filter .filtroIn-idTipo').append('<option value="<?php echo $idTipoEmpresaMd5; ?>"><?php echo $row->tipo; ?></option>');
                    $('#miFor_UpdEmpresa .tipoEmpresa').append('<option value="<?php echo $idTipoEmpresaMd5; ?>"><?php echo $row->tipo; ?></option>');
                    tgaSolution.LoaderTga = 0;
                    tgaSolution.LoadCualquiera = setTimeout(listaTipos(0),200);
                    </script>
                    <?php
                    exit;
                }else{
                    $this->Tgasolutions->mensaje(3,"VUELVA A INTENTAR","El nuevo tipo de empresa no fue ingresado.");
                    exit;
                }
            }else{
                $this->Tgasolutions->mensaje(3,"Tipo de empresa","El tipo de empresa ya existe.");
                exit;
            }
        }
        # Guardar tipo empresa
        $sql = "INSERT INTO tga_empresas_tipo (tipo, estado)
                     VALUES ('$tipo', '1');";
        if ( $this->db->query($sql) === true ) {
            $query = $this->db->query("SELECT idTipoEmpresa FROM tga_empresas_tipo WHERE tipo = '$tipo';");
            if ($query->num_rows() > 0){
                $row = $query->row();
                $idTipoEmpresa = $row->idTipoEmpresa;
            }else{
                $this->Tgasolutions->mensaje(3,"VUELVA A INTENTAR","El nuevo tipo de empresa no fue ingresado.");
                exit;
            }
            $this->Tgasolutions->mensaje(0,"TIPO DE EMPRESA INGRESADO","El nuevo tipo de empresa fue ingresado.");
            $idTipoEmpresaMd5 = $idTipoEmpresa."-".md5($idTipoEmpresa.'alfonsito');
            ?>
            <script type="text/javascript">
            $('#miFor_NewTipoEmpresa input[name=tipo]').val('');
            $('.tga-contenido-empresas .tga-section-filter .filtroIn-idTipo').append('<option value="<?php echo $idTipoEmpresaMd5; ?>"><?php echo $tipo; ?></option>');
            $('#miFor_UpdEmpresa .tipoEmpresa').append('<option value="<?php echo $idTipoEmpresaMd5; ?>"><?php echo $tipo; ?></option>');
            tgaSolution.LoaderTga = 0;
            tgaSolution.LoadCualquiera = setTimeout(listaTipos(0),200);
            </script>
            <?php
        }else{
            $this->Tgasolutions->mensaje(3,"VUELVA A INTENTAR","El nuevo tipo de empresa no fue ingresado.");
            exit;
        }
    }
    public function inhabilita_tipo(){
        $idTipoEmpresaMd5 = trim($this->input->post('removeTipoEmpresa'));
        $fragmenta        = preg_split("/[\-]+/",$idTipoEmpresaMd5);
        if ( $fragmenta[1] != md5($fragmenta[0].'alfonsito') ) {
            $this->Tgasolutions->mensaje(3,"ERROR","No se encontro el tipo de empresa.");
            exit;
        }
        $idTipoEmpresa = $fragmenta[0];
        # validar si existe tipo en uso
        $query = $this->db->query("SELECT 1 FROM tga_empresas a WHERE idTipoEmpresa = '$idTipoEmpresa';");
        if ($query->num_rows() > 0){
            $this->Tgasolutions->mensaje(3,"Tipo de empresa","No puede eliminar el tipo ya que esta en uso.");
            exit;
        }
        $sql = "UPDATE tga_empresas_tipo SET estado = 0 WHERE idTipoEmpresa = $idTipoEmpresa;";
        if ( $this->db->query($sql) === true ) {
            $this->Tgasolutions->mensaje(0,"TIPO DE EMPRESA SE HA ELIMINADO","El tipo de empresa fue eliminado.");
            ?>
            <script type="text/javascript">
            $('.tga-contenido-empresas .tga-section-filter .filtroIn-idTipo option[value=\'<?php echo $idTipoEmpresaMd5; ?>\']').remove();
            $('#miFor_UpdEmpresa .tipoEmpresa option[value=\'<?php echo $idTipoEmpresaMd5; ?>\']').remove();
            tgaSolution.LoaderTga = 0;
            tgaSolution.LoadCualquiera = setTimeout(listaTipos(0),200);
            </script>
            <?php

        }else{
            $this->Tgasolutions->mensaje(3,"VUELVA A INTENTAR","El nuevo tipo de empresa no fue ingresado.");
            exit;
        }
    }
}
