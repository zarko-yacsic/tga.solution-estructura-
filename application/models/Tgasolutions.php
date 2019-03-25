<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tgasolutions extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	function usuario(){
		if (!isset($_SESSION['idUser'])) {
			?>
			<script type="text/javascript">window.location="<?php print(base_url());?>";</script>
			<?php
		}
	}
    function poco_permiso($tipoUser,$permisoMinimo){
        if ($tipoUser<$permisoMinimo) {
            ?>
            <script type="text/javascript">
            mensajesTgaSolutions(3,"Permiso de usuario","Lo sentimos no tiene el nivel de permiso requerido.");
            </script>
            <?php
            exit;
        }
    }
    function paginacion($total,$uri,$porPagina,$mostrarNum,$funcion){
        ?>
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php
                $config['uri_segment']        = $uri;
                $config['base_url']           = 'vpv/';
                $config['total_rows']         = $total;
                $config['per_page']           = $porPagina;
                $config['num_links']          = $mostrarNum;

                $config['attributes']['rel']  = FALSE;
                $config['use_page_numbers']   = FALSE;
                $config['page_query_string']  = FALSE;

                $config['first_link']       = "<<";
                $config['last_link']        = '>>';

                $config['cur_tag_open']     = '<li class="page-item active" aria-current="page"><span class="page-link">';
                $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
                $config['num_tag_open']     = '<li class="page-item normal">';
                $config['num_tag_close']    = '</li>';



                $this->pagination->initialize($config);
                $pagination = $this->pagination->create_links();

                $pagination = str_replace('" data-ci-',');" data-ci-',$pagination);
                $pagination = str_replace('href="vpv/','onClick="'.$funcion.'(',$pagination);
                $pagination = str_replace('data-ci-pagination-page="','class="page-link pag_v',$pagination);

                echo $pagination;
                ?>
            </ul>
        </nav>
        <?php
    }
    function buscar_usuario($idUser,$nombre,$email){
        if (!is_numeric($idUser)) {exit;}

        $sigue = 1;
        if ($idUser>0) {
            $filtro = "idUSer = $idUser";
        }else if (!is_numeric($email)) {
            $filtro = "email = '$email'";
        }else if (!is_numeric($nombre)) {
            $filtro = "nombre = '$nombre'";
        }else{
            $sigue = 0;
        }

        # salida
        # [0] 0 : No esta en la tabla
        #     1 : Se encontraron datos
        # [1]   : idUser
        # [2]   : email
        # [3]   : nombre
        # [4]   : passcode
        # [5]   : tipoUser
        # [6]   : estado

        if ($sigue===1) {
            $query = $this->db->query("SELECT idUser, nombre, email, passcode, tipoUser, estado
                                         FROM tga_user WHERE $filtro;");

            if ($query->num_rows() > 0){
                $row = $query->row();
                $nombre     = $row->nombre;
                $email      = $row->email;
                $passcode   = $row->passcode;
                $tipoUser   = $row->tipoUser;
                $mail       = $row->email;
                $estado     = $row->estado;
                $idUser     = $row->idUser;

                $salida = array(1,$idUser,$email,$nombre,$passcode,$tipoUser,$estado);
            }else{
                $salida = array(0);
            }
            return $salida;
            # --------- ---------
        }else{
            exit;
        }
    }
	function validar_correo($email){
    	if (preg_match('/^[A-Za-z0-9-_.+%]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/',$email)) {
        	return 1;
        }else{
            return 0;
        }
	}
	function permisos(){

	}
	function rut($r){
		$r  = str_replace(" ", "", $r);
		$r  = str_replace("k", "K", $r);
		$r  = str_replace(".", "", $r);
        $r  = str_replace("-", "", $r);
        if (strlen($r)<8 || strlen($r)>9) {return 0;}
        $dv = substr($r,(strlen($r)-1),1);
        $r  = substr($r,0,(strlen($r)-1));
        $rut= $r;
        if(!is_numeric($r)){return 0;}
        $s=1;
        for($m=0;$r!=0;$r/=10)$s=($s+$r%10*(9-$m++%6))%11;
        $v = chr($s?$s+47:75);
        if ($dv!=$v) {return 0;}
        return 1;
	}
	function sin_tilde($texto){
		$search  = array('Á', 'É', 'Í', 'Ó', 'Ú', 'á', 'é', 'í', 'ó', 'ú');
	    $replace = array('A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u');
	    $subject = $texto;
	    $texto   = str_replace($search, $replace, $subject);
	    return $texto;
	}
    function mayuscula_tilde($texto){
        $search  = array('á', 'é', 'í', 'ó', 'ú');
        $replace = array('Á', 'É', 'Í', 'Ó', 'Ú');
        $subject = $texto;
        $texto   = str_replace($search, $replace, $subject);
        return $texto;
    }
    function minuscula_tilde($texto){
        $search  = array('Á', 'É', 'Í', 'Ó', 'Ú');
        $replace = array('á', 'é', 'í', 'ó', 'ú');
        $subject = $texto;
        $texto   = str_replace($search, $replace, $subject);
        return $texto;
    }
    function minuscula_todos($texto){
        $search  = array('Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ');
        $replace = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
        $subject = $texto;
        $texto   = str_replace($search, $replace, $subject);
        return strtolower($texto);
    }
    function mayuscula_todos($texto){
    	$search  = array('Á', 'É', 'Í', 'Ó', 'Ú');
        $replace = array('A', 'E', 'I', 'O', 'U');
        $subject = $texto;
        $texto = str_replace($search, $replace, $subject);
        $search  = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
        $replace = array('A', 'E', 'I', 'O', 'U', 'Ñ');
        $subject = $texto;
        $texto = str_replace($search, $replace, $subject);
        return strtoupper($texto);
    }
    function primera_mayuscula($texto){
        $uno = mb_substr($texto, 0,1,'UTF-8');
        $dos = mb_substr($texto,1,strlen($texto),'UTF-8');
        $search   = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
        $replace  = array('A', 'E', 'I', 'O', 'U', 'Ñ');
        $replace2 = array('Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ');
        $uno = str_replace($search, $replace, $uno);
        $dos = str_replace($replace2, $search, $dos);
        return strtoupper($uno).strtolower($dos);
    }
    function valida_texto($texto,$cadena){
        //$cadena = abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZáéíóúÁÉÍÓÚ
        $permitidos = "".$cadena."";
        $error=0;
        for ($i=0; $i<strlen($texto); $i++){
            if (strpos($permitidos, substr($texto,$i,1))===false){
            	return array(0,substr($texto,$i,1));
            }
        }
        return array(1);
    }
    function validar_fecha($fecha){
        $anio = substr($fecha, 0, 4);

        $salida = 1;
        if (!is_numeric($anio) || $anio<1901) {
        	$anio = substr($fecha, 6, 4);
        	$salida = 2;
	        if (!is_numeric($anio) || $anio<1901) {
	        	return array(0);
	        }
        }

        if ($salida==1) {
        	$mes = substr($fecha, 5, 2);
        }else if ($salida==2) {
        	$mes = substr($fecha, 3, 2);
        }else{
        	return array(0);
        }

        if ( !is_numeric($mes) || strlen($mes) != 2 || $mes < 1 || $mes > 12 ) {
        	return array(0);
        }

        if ($salida==1) {
        	$dia = substr($fecha, 8, 2);
        }else if ($salida==2) {
        	$dia = substr($fecha, 0, 2);
        }else{
        	return array(0);
        }

        if ( !is_numeric($dia) || strlen($dia) != 2 || $dia < 1 || $dia > 31 ) {
        	return array(0);
        }

        # validar fecha
        if (checkdate($mes,$dia,$anio)!=1) {
        	$salida = 0;
        }
        return array($salida,$anio,$mes,$dia,"$anio-$mes-$dia","$dia-$mes-$anio");
    }
    function historia($idUser,$idMenu,$tipoAccion,$detalle){
        date_default_timezone_set('America/Santiago');
        $time      =  time();
        $fecha     = date ("Y-m-d H:i:s", $time);
        $fechaAnio = date ("Y", $time);
        $fechaMes  = date ("n", $time);

        $sql = "INSERT INTO sis_historia (idUser, idMenu, tipoAccion, fecha, fechaAnio, fechaMes, detalle)
                                  VALUES ($idUser,
                                          $idMenu,
                                          $tipoAccion,
                                          '$fecha',
                                          $fechaAnio,
                                          $fechaMes,
                                          '$detalle');";
        $this->db->query($sql);

    }
    function mensaje($ico,$titulo,$texto){
        ?>
        <script type="text/javascript">
        mensajesTgaSolutions(<?php echo $ico; ?>,"<?php echo $titulo; ?>","<?php echo $texto; ?>");
        </script>
        <?php
    }
}












