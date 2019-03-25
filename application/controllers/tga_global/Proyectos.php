<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proyectos extends CI_Controller {
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
        $data["hoja"] = "home";
        $this->load->view('header',$data);

        $this->load->view('tga_global/proyectos/index',$data);
        $this->load->view('post_body');
    }
    public function cargaProyectos(){
        $idInmobiliaria = $this->input->post('idInmobiliaria');
        $query              = $this->db->query("SELECT idProyecto,proyecto
                                                  FROM tga_proyectos
                                                 WHERE idEmpresa = ".$idInmobiliaria);
        $proyectos          = $query->result();
        foreach ($proyectos as $key) {
            ?>
            <li class="tga-listas"onclick="muestraProyecto(<?php echo $key->idProyecto;?>);"><?php echo $key->proyecto;?></li>
            <?php
        }
    }
    public function muestraProyectos(){
        $idInmobiliaria     = trim($this->input->post('idInmobiliaria'));
        $idProyecto         = trim($this->input->post('idProyecto'));

        if (!is_numeric($idInmobiliaria+$idProyecto) && $idInmobiliaria<1 && $idProyecto<1 ) {
            $this->Tgasolutions->mensaje(3,"ERROR","Seleccione inmobiliaria y proyectos");
            exit;
        }

        $query = $this->db->query("SELECT proyecto, idRegion, idComuna, direccion,
                                          url, latitud, longitud, imagen, estado,
                                          tipoConstruccion, idEmpresa, financiamiento,
                                          idZona
                                      FROM tga_proyectos
                                     WHERE idEmpresa = $idInmobiliaria
                                       AND idProyecto = $idProyecto;");
        if ($query->num_rows() > 0){
            $row = $query->row();
            ?>
            <script type="text/javascript">
            $('#nombreProyecto').val('<?php print($row->proyecto);?>');
            $('#regiones').val('<?php print($row->idRegion);?>');
            $('#comunas').val('<?php print($row->idComuna);?>');
            $('#zonas').val('<?php print($row->idZona);?>');
            $('#direccion').val('<?php print($row->direccion);?>');
            $('#latitud').val('<?php print($row->latitud);?>');
            $('#longitud').val('<?php print($row->longitud);?>');
            $('#estado').val('<?php print($row->estado);?>');
            $('#url').val('<?php print($row->url);?>');
            $('#imagen').val('<?php print($row->imagen);?>');
            $("aside.tga-lado3.lado-c .proyectoMapa").html('<iframe src="https://maps.google.com/maps?q=<?php print($row->latitud);?>,<?php print($row->longitud);?>&ie=UTF8&output=embed" width="100%" height="200" frameborder="0"></iframe>');
            $("aside.tga-lado3.lado-c .images").html('<img id="imagenProy" src="<?php print($row->imagen);?>">');
            $("#tipoConstruccion<?php echo $row->tipoConstruccion;?>").prop("checked", true);
            $('#tipoFinanciamiento<?php echo $row->financiamiento;?>').prop("checked", true);
            </script>
            <?php
        }

    }
    public function guardaProyectos(){
        $idInmobiliaria                 = trim($this->input->post('idInmobiliaria'));
        $idProyecto                     = trim($this->input->post('idProyecto'));
        $nombreProyecto                 = trim($this->input->post('nombreProyecto'));
        $zonas                          = trim($this->input->post('zonas'));
        $regiones                       = trim($this->input->post('regiones'));
        $comunas                        = trim($this->input->post('comunas'));
        $direccion                      = trim($this->input->post('direccion'));
        $url                            = trim($this->input->post('url'));
        $imagen                         = trim($this->input->post('imagen'));
        $latitud                        = trim($this->input->post('latitud'));
        $longitud                       = trim($this->input->post('longitud'));
        $estado                         = trim($this->input->post('estado'));
        $tipoConstruccion               = trim($this->input->post('tipoConstruccion'));
        $tipoFinanciamiento             = trim($this->input->post('tipoFinanciamiento'));
        $proyectoAction                 = trim($this->input->post('proyectoAction'));



        if (!is_numeric($idInmobiliaria) || !is_numeric($idProyecto) || $idInmobiliaria<=0) {exit;}
        $cadena = "-_1234567890 abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ";

        if (strlen($nombreProyecto)==0 || $this->Tgasolutions->valida_texto($nombreProyecto,$cadena)[0] == 0) {
            $this->Tgasolutions->mensaje(3,"ERROR","falta nombre o formato incorrecto");
            exit;
        }
        if (!is_numeric($zonas) || $zonas == 0) {
            $this->Tgasolutions->mensaje(3,"ERROR","zona incorrecta o formato incorrecto");
            exit;
        }
        if (!is_numeric($regiones) || $regiones == 0) {
            $this->Tgasolutions->mensaje(3,"ERROR","falta region o formato incorrecto");
            exit;
        }
        if (!is_numeric($comunas) || $comunas == 0) {
            $this->Tgasolutions->mensaje(3,"ERROR","falta comuna o formato incorrecto");
            exit;
        }
        if (strlen($direccion)==0 || $this->Tgasolutions->valida_texto($direccion,$cadena)[0] == 0) {
            $this->Tgasolutions->mensaje(3,"ERROR","falta direccion o formato incorrecto");
            exit;
        }
        if (strlen($url)==0) {
            $this->Tgasolutions->mensaje(3,"ERROR","falta url o formato incorrecto");
            exit;
        }
        if (strlen($imagen)==0) {
            $this->Tgasolutions->mensaje(3,"ERROR","falta imagen o formato incorrecto");
            exit;
        }
        if (strlen($latitud) == 0) {
            $this->Tgasolutions->mensaje(3,"ERROR","falta latitud o formato incorrecto");
            exit;
        }
        if (strlen($longitud) == 0) {
            $this->Tgasolutions->mensaje(3,"ERROR","falta longitud o formato incorrecto");
            exit;
        }

        if (!is_numeric($tipoConstruccion) || $tipoConstruccion < 1 || $tipoConstruccion > 3) {
            $this->Tgasolutions->mensaje(3,"ERROR","tipo Construccion incorrecto");
            exit;
        }

        if (!is_numeric($tipoFinanciamiento) || $tipoFinanciamiento< 1 || $tipoFinanciamiento>2) {
            $this->Tgasolutions->mensaje(3,"ERROR","tipo Financiamiento incorrecto");
            exit;
        }
        if (!is_numeric($estado) || $estado < 0 || $estado > 1) {
            $this->Tgasolutions->mensaje(3,"ERROR","estado incorrecto o formato incorrecto");
            exit;
        }

        if ($proyectoAction==1) {
            # EDITAR
            $query = $this->db->query("SELECT 1
                                         FROM tga_proyectos
                                        WHERE proyecto = '$nombreProyecto'
                                          AND idEmpresa   = $idInmobiliaria
                                          AND idProyecto != $idProyecto;");
            if ($query->num_rows() > 0){
                $row = $query->row();
                $this->Tgasolutions->mensaje(3,"NOMBRE PROYECTO","El nombre del proyecto ya existe");
                exit;
            }

            $sql = "UPDATE  tga_proyectos
                       SET proyecto             = '$nombreProyecto'
                         , idRegion             = '$regiones'
                         , idComuna             = '$comunas'
                         , direccion            = '$direccion'
                         , url                  = '$url'
                         , latitud              = '$latitud'
                         , longitud             = '$longitud'
                         , imagen               = '$imagen'
                         , estado               =  $estado
                         , tipoConstruccion     =  $tipoConstruccion
                         , idEmpresa            =  $idInmobiliaria
                         , financiamiento       =  $tipoFinanciamiento
                         , idZona               =  $zonas
                     WHERE idEmpresa            =  $idInmobiliaria
                       AND idProyecto           =  $idProyecto;";
            if ($this->db->query($sql)===true) {
                $this->Tgasolutions->mensaje(0,"Proyecto Actualizado","El proyecto fue actualizado correctamente");
            }else{
                $this->Tgasolutions->mensaje(3,"ERROR","El proyecto no pudo ser actualizado");
            }
        }else if ($proyectoAction==0) {
            # INSERTAR
            $query = $this->db->query("SELECT 1
                                         FROM tga_proyectos
                                        WHERE proyecto = '$nombreProyecto'
                                          AND idEmpresa   = $idInmobiliaria;");
            if ($query->num_rows() > 0){
                $row = $query->row();
                $this->Tgasolutions->mensaje(3,"NOMBRE PROYECTO","El nombre del proyecto ya existe");
                exit;
            }

            $sql = "INSERT INTO tga_proyectos (proyecto, idRegion, idZona, idComuna, direccion,
                                               url, latitud, longitud, imagen, estado, tipoConstruccion,
                                               idEmpresa, financiamiento)
                                            VALUES ('$nombreProyecto',
                                                    '$regiones',
                                                    $zonas,
                                                    '$comunas',
                                                    '$direccion',
                                                    '$url',
                                                    '$latitud',
                                                    '$longitud',
                                                    '$imagen',
                                                    $estado,
                                                    $tipoConstruccion,
                                                    $idInmobiliaria,
                                                    $tipoFinanciamiento);";
            if ($this->db->query($sql)===true) {
                $this->Tgasolutions->mensaje(0,"Proyecto Creado","El proyecto fue creado correctamente");
            }else{
                $this->Tgasolutions->mensaje(3,"ERROR","El proyecto no pudo ser creado");
            }
        }
        ?>
        <script type="text/javascript">
        tgaSolution.LoaderTga = 0;
        selectInmobiliaria(<?php echo $idInmobiliaria;?>);
        </script>
        <?php
    }
}
