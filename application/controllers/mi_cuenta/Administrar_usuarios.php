<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Administrar_usuarios extends CI_Controller {
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
    public function user_new(){
        $this->load->view('mi_cuenta/administrar/user_new');
    }
    public function user_list(){
        $data['opcion']     = $this->input->post('var1');
        $data['lista']      = $this->input->post('var2');

        if (!is_numeric($data['opcion']) || $data['opcion'] > 3) {$data['opcion'] = 0;}
        if (!is_numeric($data['lista']) || $data['lista'] < 0) {$data['lista'] = 0;}

        $this->load->view('mi_cuenta/administrar/user_list',$data);
    }
    public function perfil(){
        echo "perfil";
    }
    public function permisos(){
        echo "permisos";
    }
    public function historia(){
        echo "historia";
    }
    public function user_new_form(){

        $idUser      =    $_SESSION['idUser'];
        $nombre      =    trim($this->input->post('nombre'));
        $email       =    trim($this->input->post('email'));
        $tipo        =    trim($this->input->post('tipo'));
        $estado      =    trim($this->input->post('estado'));

        $datos       = $this->Tgasolutions->buscar_usuario($idUser,0,0);
        $tipoUser    = $datos[5];

        $this->Tgasolutions->poco_permiso($tipoUser,2);

        # Validar el nombre
        if (strlen($nombre)<5) {
            ?>
            <script type="text/javascript">
            mensajesTgaSolutions(3,"Nombre","Tiene que ingresar el nombre y apellido.");
            </script>
            <?php
            exit;
        }

        $resultado = $this->Tgasolutions->valida_texto($nombre,"abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZáéíóúÁÉÍÓÚ");
        if ($resultado[0]==0) {
            ?>
            <script type="text/javascript">
            mensajesTgaSolutions(3,"Nombre","Solo se permiten letras y espacios (a-Z) para escribir el nombre y apellido.<br><br>El carácter no permitido es: <?php echo $resultado[1];?>");
            </script>
            <?php
            exit;
        }

        $datos = $this->Tgasolutions->buscar_usuario(0,$nombre,0);
        if ($datos[0]==1) {
            ?>
            <script type="text/javascript">
            mensajesTgaSolutions(3,"Nombre","El nombre y apellido ya se encuentra en nuestros registros");
            </script>
            <?php
            exit;
        }

        # Validar el nombre
        if (strlen($email)<1) {
            ?>
            <script type="text/javascript">
            mensajesTgaSolutions(3,"Email","Tiene que ingresar el email.");
            </script>
            <?php
            exit;
        }

        if ($this->Tgasolutions->validar_correo($email) === 0) {
            ?>
            <script type="text/javascript">
            mensajesTgaSolutions(3,"Email","Lo sentimos el email esta mal ingresado.");
            </script>
            <?php
            exit;
        }

        $datos = $this->Tgasolutions->buscar_usuario(0,0,$email);
        if ($datos[0]==1) {
            ?>
            <script type="text/javascript">
            mensajesTgaSolutions(3,"Email","El email ya se encuentra en nuestros registros");
            </script>
            <?php
            exit;
        }

        if (!is_numeric($tipo) || $tipo < 1 || $tipo > 3) {
            ?>
            <script type="text/javascript">
            mensajesTgaSolutions(3,"Tipo de usuario","Tiene que seleccionar un tipo de usuario");
            </script>
            <?php
            exit;
        }

        if ($tipo==3) {
            $this->Tgasolutions->poco_permiso($tipoUser,3);
        }
        if ($estado!=1) {$estado = 0;}

        # podemos crear al usuario
        $sql = " INSERT INTO tga_user (nombre, email, tipoUser, estado)
                               VALUES ('$nombre',
                                       '$email',
                                       $tipo,
                                       2);";
        if ($this->db->query($sql) === true) {
            ?>
            <script type="text/javascript">
            $("#seleccionarLista").val(0);
            tgaSolution.LoaderTga = 0;
            seleccionarLista(0);
            $("#tgaSleModal2").modal('toggle');
            mensajesTgaSolutions(0,"USUARIO CREADO","El usuario fue creado con exito.");
            </script>
            <?php
            exit;
        }else{
            ?>
            <script type="text/javascript">
            mensajesTgaSolutions(3,"Lo sentimos","Lo sentimos ocurrio un problema al crear el nuevo usuario, vuelva a intentar.");
            </script>
            <?php
            exit;
        }



    }
}
