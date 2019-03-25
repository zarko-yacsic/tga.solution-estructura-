<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Administrar_evaluaciones_cat extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('html');
        $this->load->library('user_agent');
        $this->load->database();
        $this->load->helper('cookie');
        session_start();

        $this->load->model('Tgasolutions');
        # ------------------ -------------------
        $this->Tgasolutions->usuario();
        $this->Tgasolutions->permisos();

        $this->load->library('pagination');
    }
    public function index(){
    }
    public function nuevo(){
        $data["dbData"] = $second_DB = $this->load->database('evaluaciones', TRUE);
        $this->load->view('productos/administrador_evaluaciones/categorias/nueva',$data);
    }
    public function crear(){
        $categoria    = trim($this->input->post('categoria'));
        $sigla        = trim($this->input->post('sigla'));
        $idTipo       = trim($this->input->post('idTipo'));

        $fragmenta    = preg_split("/[\-]+/",$idTipo);
        if ( $fragmenta[1] != md5($fragmenta[0].'alfonsito') ) {
            $this->Tgasolutions->mensaje(3,"ERROR","Vuelva a intentar");
            exit;
        }
        $idTipo = $fragmenta[0];

        # Validar nombre
        if (strlen($categoria)<2) {
            $this->Tgasolutions->mensaje(3,"Titulo categoria","Tiene que ingresar el titulo de la categoria");
            exit;
        }

        $resultado = $this->Tgasolutions->valida_texto($categoria,' abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ');
        if ($resultado[0]==0) {
            $this->Tgasolutions->mensaje(3,"Titulo categoria","Solo se pueden ingresar caracteres de la A-Z y espacios.<br>Caracter invalido:".$resultado[1]);
            exit;
        }

        # Validar sigla
        if (strlen($sigla)<2 || strlen($sigla)>4) {
            $this->Tgasolutions->mensaje(3,"Sigla","La sigla tiene que tener minimo 2 caracteres y maximo 4 caracteres.");
            exit;
        }

        $resultado = $this->Tgasolutions->valida_texto($sigla,'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
        if ($resultado[0]==0) {
            $this->Tgasolutions->mensaje(3,"Sigla","En la sigla solo se pueden escribir caracteres de la a-Z, 0-9 y sin espacios.<br>Caracter invalido:".$resultado[1]);
            exit;
        }

        $categoria   = $this->Tgasolutions->mayuscula_todos($categoria);
        $sigla       = $this->Tgasolutions->mayuscula_todos($sigla);
        $siglaMin    = $this->Tgasolutions->minuscula_todos($sigla);

        $dbData      = $this->load->database('evaluaciones', TRUE);

        # validar si existe la categoria
        $query = $dbData->query("SELECT 1 FROM data_categoria a WHERE categoria = '$categoria';");
        if ($query->num_rows() > 0){
            $this->Tgasolutions->mensaje(3,"Titulo categoria","El titulo de la categoria ya existe.");
            exit;
        }

        # validar si existe la sigla
        $query = $dbData->query("SELECT 1 FROM data_categoria a WHERE sigla = '$sigla';");
        if ($query->num_rows() > 0){
            $this->Tgasolutions->mensaje(3,"Sigla","La sigla ya existe.");
            exit;
        }


        # Guardar categoria
        $sql = "INSERT INTO data_categoria (categoria, sigla, siglaMin, idTipoC)
                                    VALUES ('$categoria', '$sigla', '$siglaMin', $idTipo);";
        if ( $dbData->query($sql) === true ) {
            $this->Tgasolutions->mensaje(0,"CATEGORIA INGRESADA","La nueva categoria fue ingresada.");
            ?>
            <script type="text/javascript">
            $('#tgaSleModal2').modal('toggle');

            if (tgaSolution.LoadCualquiera !== "vacio") {
                clearTimeout(tgaSolution.LoadCualquiera);
            }

            tgaSolution.LoaderTga = 0;
            tgaSolution.LoadCualquiera = setTimeout(changeSelectCategoria(0),200);
            </script>
            <?php
        }else{
            $this->Tgasolutions->mensaje(3,"VUELVA A INTENTAR","La nueva categoria no fue ingresada.");
            exit;
        }
    }
    public function lista(){
        $total        = 0;
        $uri          = 4;
        $porPagina    = 20;
        $mostrarNum   = 2;
        $lista        = (!is_numeric($this->uri->segment(4))) ? 0 : $this->uri->segment(4);
        $idTipo       = trim($this->input->post('var1'));
        $fragmenta    = preg_split("/[\-]+/",$idTipo);

        if ( $fragmenta[1] != md5($fragmenta[0].'alfonsito') ) {
            $this->Tgasolutions->mensaje(3,"ERROR","No se encontro el tipo de evaluación");
            exit;
        }
        $idTipo      = $fragmenta[0];
        $dbData      = $this->load->database('evaluaciones', TRUE);

        $query  = $dbData->query("SELECT SQL_CALC_FOUND_ROWS
                                         idCategoria,
                                         categoria,
                                         estado
                                    FROM data_categoria
                                   WHERE idTipoC = $idTipo
                                ORDER BY estado DESC, categoria ASC
                                   LIMIT $lista,$porPagina;");

        $query2 = $dbData->query("SELECT FOUND_ROWS() AS total;");
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
                $idCategoria  = $row->idCategoria;
                $categoria    = $row->categoria;
                $estado       = $row->estado;

                $filtro = '';
                if ($estado==0) {
                    $filtro = 'inactivo';
                }else if ($estado==2) {
                    $filtro = 'nuevo';
                }
                $valor = $idTipo.'-'.$idCategoria.'-'.md5($idTipo.'-'.$idCategoria.'alfonsito');
                ?>
                <li class="num<?php echo $idCategoria.' '.$filtro?>" onClick="cargamosLaCategoria('<?php echo $valor;?>');"><?php echo $categoria;?></li>
                <?php
                $row = $query->next_row();
            }
            ?>
            </ul>
            <?php
            $this->Tgasolutions->paginacion($total,$uri,$porPagina,$mostrarNum,'changeSelectCategoria');
        }
    }
    function cargar_categoria(){
        $idTipoC       = trim($this->input->post('var1'));
        $fragmenta    = preg_split("/[\-]+/",$idTipoC);

        if ( $fragmenta[2] != md5($fragmenta[0].'-'.$fragmenta[1].'alfonsito') ) {
            $this->Tgasolutions->mensaje(3,"ERROR","No se encontro el tipo de evaluación ni la categoria.");
            exit;
        }
        $idTipoC           = $fragmenta[0];
        $idCategoria       = $fragmenta[1];
        $dbData            = $this->load->database('evaluaciones', TRUE);

        $query  = $dbData->query("SELECT categoria, sigla, siglaMin, estado
                                    FROM data_categoria
                                   WHERE idCategoria   = $idCategoria
                                     AND idTipoC       = $idTipoC;");
        if ($query->num_rows() > 0){
            $row           = $query->row();
            $categoria     = $row->categoria;
            $sigla         = $row->sigla;
            $siglaMin      = $row->siglaMin;
            $estado        = $row->estado;

            $estado = ($estado == 2) ? 0 : $estado;
            ?>
            <script type="text/javascript">
            $("#contenidoSubIn .lado .categoria").val("<?php echo $idCategoria.'-'.md5($idCategoria.'alfonsito');?>");
            $("#contenidoSubIn .lado .idTipoC").val("<?php echo $idTipoC.'-'.md5($idTipoC.'alfonsito');?>");
            $("#contenidoSubIn .lado.lado-b .mini").html('<?php echo "ID: ".$idCategoria;?>');
            $("#contenidoSubIn .lado.lado-b .nombre").val('<?php echo $categoria;?>');
            $("#contenidoSubIn .lado.lado-b .sigla").val('<?php echo $sigla;?>');
            $("#contenidoSubIn .lado.lado-b .estado").val(<?php echo $estado;?>);

            $(".contenido .listaCont .lado input").prop('disabled',  false);
            $(".contenido .listaCont .lado select").prop('disabled', false);
            $(".contenido .listaCont .lado .btn").prop('disabled',  false);
            $(".section-filter .btn-finale .btn").prop('disabled',   false);
            </script>
            <?php
        }else{
            exit;
        }

        $query  = $dbData->query("SELECT idPais, idTipoC, idCategoria, categoria
                                    FROM data_categoria_pais
                                   WHERE estado        = 1
                                     AND idTipoC       = $idTipoC
                                     AND idCategoria   = $idCategoria;");
        if ($query->num_rows() > 0){
            $row = $query->row();
            for ($i=1;$i<=$query->num_rows();$i++) {
                $idPais        = $row->idPais;
                $categoria     = $row->categoria;
                ?>
                <script type="text/javascript">
                $("#contenidoSubIn .lado.lado-c .pais_<?php echo $idPais;?>").val('<?php echo $categoria;?>');
                </script>
                <?php
                $row = $query->next_row();
            }
        }else{
            exit;
        }

    }
    function editar_categoria(){
        $idCategoria       = trim($this->input->post('idCategoria'));
        $idTipoC           = trim($this->input->post('idTipoC'));
        $categoria         = trim($this->input->post('categoria'));
        $sigla             = trim($this->input->post('sigla'));
        $estado            = trim($this->input->post('estado'));

        $fragmenta    = preg_split("/[\-]+/",$idCategoria);
        if ( $fragmenta[1] != md5($fragmenta[0].'alfonsito') ) {
            $this->Tgasolutions->mensaje(3,"ERROR","El idCategoria no corresponde, Vuelva a intentar");
            exit;
        }
        $idCategoria  = $fragmenta[0];

        $fragmenta    = preg_split("/[\-]+/",$idTipoC);
        if ( $fragmenta[1] != md5($fragmenta[0].'alfonsito') ) {
            $this->Tgasolutions->mensaje(3,"ERROR","El idTipoC no corresponde, Vuelva a intentar");
            exit;
        }
        $idTipoC = $fragmenta[0];

        # Validar nombre
        if (strlen($categoria)<2) {
            $this->Tgasolutions->mensaje(3,"Titulo categoria","Tiene que ingresar el titulo de la categoria");
            exit;
        }

        $resultado = $this->Tgasolutions->valida_texto($categoria,' abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ');
        if ($resultado[0]==0) {
            $this->Tgasolutions->mensaje(3,"Titulo categoria","Solo se pueden ingresar caracteres de la A-Z y espacios.<br>Caracter invalido:".$resultado[1]);
            exit;
        }

        # Validar sigla
        if (strlen($sigla)<2 || strlen($sigla)>4) {
            $this->Tgasolutions->mensaje(3,"Sigla","La sigla tiene que tener minimo 2 caracteres y maximo 4 caracteres.");
            exit;
        }

        $resultado = $this->Tgasolutions->valida_texto($sigla,'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
        if ($resultado[0]==0) {
            $this->Tgasolutions->mensaje(3,"Sigla","En la sigla solo se pueden escribir caracteres de la a-Z, 0-9 y sin espacios.<br>Caracter invalido:".$resultado[1]);
            exit;
        }

        $categoria   = $this->Tgasolutions->mayuscula_todos($categoria);
        $sigla       = $this->Tgasolutions->mayuscula_todos($sigla);
        $siglaMin    = $this->Tgasolutions->minuscula_todos($sigla);

        $dbData      = $this->load->database('evaluaciones', TRUE);

        # buscar si existe el nombre de la categoria
        $query  = $dbData->query("SELECT 1
                                    FROM data_categoria
                                   WHERE idCategoria != $idCategoria
                                     AND idTipoC      = $idTipoC
                                     AND categoria    = '$categoria';");
        if ($query->num_rows() > 0){
            $this->Tgasolutions->mensaje(3,"Titulo categoria","El titulo de la categoria ya fue utilizado.");
            exit;
        }

        # buscar si existe la sigla en la categoria
        $query  = $dbData->query("SELECT 1
                                    FROM data_categoria
                                   WHERE idCategoria != $idCategoria
                                     AND idTipoC      = $idTipoC
                                     AND sigla        = '$sigla';");
        if ($query->num_rows() > 0){
            $this->Tgasolutions->mensaje(3,"Sigla","La sigla de la categoria ya fue utilizada.");
            exit;
        }

        if (!is_numeric($estado)) {
            $this->Tgasolutions->mensaje(3,"Estado","El estado de la categoria esta mal ingresado.");
            exit;
        }
        $estado = ($estado!=1) ? 0 : 1;

        # editar categoria
        $sql = "UPDATE data_categoria
                   SET categoria     = '$categoria',
                       sigla         = '$sigla',
                       siglaMin      = '$siglaMin',
                       estado        = $estado
                 WHERE idTipoC       = $idTipoC
                   AND idCategoria   = $idCategoria;";

        if ( $dbData->query($sql) === true ) {
            $this->Tgasolutions->mensaje(0,"CATEGORIA ACTUALIZADA","La categoria fue actualizada.");

            $filtro = ($estado==1) ? "num$idCategoria " : "num$idCategoria inactivo";
            ?>
            <script type="text/javascript">
            $("#listaCategoriaIn ul.lista li.num<?php echo $idCategoria;?>").html('<?php echo $categoria;?>');
            $("#listaCategoriaIn ul.lista li.num<?php echo $idCategoria;?>").attr('class', '<?php echo $filtro;?>');
            </script>
            <?php
        }else{
            $this->Tgasolutions->mensaje(3,"VUELVA A INTENTAR","La categoria no fue actualizada.");
            exit;
        }

    }
    function text_categoria(){
        $idCategoria       = trim($this->input->post('idCategoria'));
        $idTipoC           = trim($this->input->post('idTipoC'));
        $dbData            = $this->load->database('evaluaciones', TRUE);

        $fragmenta         = preg_split("/[\-]+/",$idCategoria);
        if ( $fragmenta[1] != md5($fragmenta[0].'alfonsito') ) {
            $this->Tgasolutions->mensaje(3,"ERROR","El idCategoria no corresponde, Vuelva a intentar");
            exit;
        }
        $idCategoria       = $fragmenta[0];

        $fragmenta         = preg_split("/[\-]+/",$idTipoC);
        if ( $fragmenta[1] != md5($fragmenta[0].'alfonsito') ) {
            $this->Tgasolutions->mensaje(3,"ERROR","El idTipoC no corresponde, Vuelva a intentar");
            exit;
        }
        $idTipoC          = $fragmenta[0];

        $query = $this->db->query("SELECT idPais, pais
                                     FROM tga_pais
                                    WHERE estado  = 1
                                 ORDER BY pais ASC;");
        if ($query->num_rows() > 0){
            $row = $query->row();
            for ($i=1;$i<=$query->num_rows();$i++) {
                $categoria = trim($this->input->post('pais_'.$row->idPais));
                $idPais      = $row->idPais;
                $pais        = $row->pais;

                $estado  = 0;
                $action  = 1;
                #buscar
                $query2 = $dbData->query("SELECT 1
                                             FROM data_categoria_pais
                                            WHERE idPais        = $idPais
                                              AND idTipoC       = $idTipoC
                                              AND idCategoria   = $idCategoria;");
                if ($query2->num_rows() > 0){
                    $action = 0;
                }

                if (strlen($categoria)>0) {
                    # Validar
                    $resultado = $this->Tgasolutions->valida_texto($categoria,' abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ');
                    if ($resultado[0]==0) {
                        $this->Tgasolutions->mensaje(3,"Titulo categoria $pais","Solo se pueden ingresar caracteres de la A-Z y espacios.<br>Caracter invalido:".$resultado[1]);
                        exit;
                    }
                    $categoria = $this->Tgasolutions->mayuscula_todos($categoria);
                }

                $estado = ($categoria=="") ? 0 : 1;
                #Accion
                if ($action==0) {
                    # actualizar
                    $sql = "UPDATE data_categoria_pais
                               SET categoria     = '$categoria',
                                   estado        = $estado
                             WHERE idPais        = $idPais
                               AND idTipoC       = $idTipoC
                               AND idCategoria   = $idCategoria;";

                    if ( $dbData->query($sql) !== true ) {
                        $this->Tgasolutions->mensaje(3,"VUELVA A INTENTAR","La categoria no fue actualizada.");
                        exit;
                    }
                }else if ($estado==1) {
                    # insertar
                    $sql = "INSERT INTO data_categoria_pais (categoria, estado, idPais,  idTipoC, idCategoria)
                                                     VALUES ('$categoria', 1,  $idPais, $idTipoC, $idCategoria);";

                    if ( $dbData->query($sql) !== true ) {
                        $this->Tgasolutions->mensaje(3,"VUELVA A INTENTAR","La categoria no fue insertada.");
                        exit;
                    }
                }
                $row = $query->next_row();
            }
            $this->Tgasolutions->mensaje(0,"GUARDADO","Titulo de categoria por paises guardado.");
        }
    }
}
