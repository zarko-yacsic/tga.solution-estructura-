<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Administrar_evaluaciones_p5 extends CI_Controller {
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
    public function index(){

    }
    public function lista_repetidos_a(){
        $idInmobiliaria   = trim($this->input->post('idInmobiliaria'));
        $idProyecto       = trim($this->input->post('idProyecto'));
        $idTipoC          = trim($this->input->post('var1'));
        $idPais           = trim($this->input->post('var2'));
        $idEvaluacion     = trim($this->input->post('var3'));
        $idUser           = $_SESSION['idUser'];
        $dbData           = $this->load->database('evaluaciones', TRUE);

        $categoriasID       = array();
        $categoriasNOM      = array();
        $numPreguntaAR      = array();
        $query = $dbData->query("SELECT a.idCategoria, b.categoria
                                   FROM data_estructura a
                              LEFT JOIN data_categoria b ON b.idCategoria = a.idCategoria
                                  WHERE a.idEvaluacion = $idEvaluacion
                               GROUP BY a.idCategoria;");
        if ($query->num_rows() > 0){
            $row = $query->row();
            for ($i=1;$i<=$query->num_rows();$i++) {
                $categoriasID[$i]  = $row->idCategoria;
                $categoriasNOM[$i] = $row->categoria;
                $row               = $query->next_row();
            }
        }

        $idCategoria = 0;
        $pregunta    = '';
        $cero        = 0;
        for ($b=1;$b<=count($categoriasID);$b++) {
            $idCategoria = $categoriasID[$b];
            $categoria   = $categoriasNOM[$b];
            $query = $dbData->query("SELECT COUNT(1) as total, nomCampo, num, numPregunta, pregunta
                                       FROM (SELECT idEstructura, nomCampo, num, numPregunta, pregunta
                                               FROM data_estructura
                                              WHERE idCategoria   = $idCategoria
                                                AND idEvaluacion  = $idEvaluacion
                                                AND idPadre       = 0
                                           GROUP BY num) x GROUP BY pregunta
                                                             HAVING total > 1
                                                                     LIMIT 1;");
            if ($query->num_rows() > 0){
                $row = $query->row();
                $pregunta = $row->pregunta;
                $cero++;
                break;
            }
        }

        if ($cero==0) {
            $query = $dbData->query("SELECT count(1) AS total
                                          , min(idEstructura) as idEstructura
                                          , numPregunta
                                          , idCategoria
                                          , item
                                       FROM data_estructura a
                                      WHERE idEvaluacion     = $idEvaluacion
                                        AND numSubPregunta   > 0
                                        AND idPadre          = 0
                                   GROUP BY numPregunta, item
                                     HAVING total > 1");
            if ($query->num_rows() > 0){
                $row = $query->row();

                for ($i=1;$i<=$query->num_rows();$i++) {
                    $idEstructura   = $row->idEstructura;
                    $numPregunta    = $row->numPregunta;
                    $idCategoria    = $row->idCategoria;
                    $item           = $row->item;

                    $sql = "UPDATE data_estructura
                               SET idPadre         = $idEstructura
                             WHERE idCategoria     = $idCategoria
                               AND idEstructura   != $idEstructura
                               AND numPregunta     = $numPregunta
                               AND item            = '$item';";
                    $dbData->query($sql);

                    $row = $query->next_row();
                }
            }


            $sql = " UPDATE data_evaluaciones
                        SET avance = 6
                      WHERE idEvaluacion     = $idEvaluacion;";
            if ($dbData->query($sql)===true) {
                ?>
                <script type="text/javascript">
                $(".tga-contenido .cuadro .btn").attr('class', 'btn btn-danger btn-sm');
                </script>
                <?php
            }
            exit;
        }

        if ($pregunta!='') {
            ?>
            <p class="p-5p1">Preguntas en conflicto en la misma categoria</p>
            <p class="p-5p2 categoria">Categoria: <?php echo $categoria;?></p>
            <table class="tablaResumen p5-1" width="1000" cellspacing="0" cellpadding="0" border="0">
                <thead>
                    <tr>
                        <th class="info" width="40">Mismo</th>
                        <th class="info" width="40">Distinto</th>
                        <th class="infoP">Pregunta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $md5Item = '';
                    $errores = 0;
                    $idPadre = 0;
                    $query = $dbData->query("SELECT idEstructura, nomCampo, num, numPregunta, pregunta, item
                                               FROM data_estructura
                                              WHERE idCategoria   = $idCategoria
                                                AND idEvaluacion  = $idEvaluacion
                                                AND pregunta      = '$pregunta'
                                           GROUP BY numPregunta
                                           ORDER BY numPregunta ASC;");
                    if ($query->num_rows() > 0){
                        $row = $query->row();

                        for ($i=1;$i<=$query->num_rows();$i++) {
                            $idEstructura      = $row->idEstructura;
                            $nomCampo          = $row->nomCampo;
                            $num               = $row->num;
                            $numPregunta       = $row->numPregunta;
                            $pregunta          = $row->pregunta;

                            $numPreguntaAR[$i] = $row->numPregunta;
                            ?>
                            <tr>
                                <td class="info info-<?php echo $numPregunta;?>">
                                    <input class="radio-1" type="radio" name="p<?php echo $numPregunta;?>" value="1">
                                </td>
                                <td class="info info-<?php echo $numPregunta;?>">
                                    <input class="radio-2" type="radio" name="p<?php echo $numPregunta;?>" value="2">
                                </td>
                                <td class="infoP">
                                    <?php echo $pregunta;?>

                                    <?php
                                    $query2 = $dbData->query("SELECT MD5(GROUP_CONCAT(item)) as md5Item
                                                                   , GROUP_CONCAT(item) AS item
                                                                FROM data_estructura
                                                               WHERE numPregunta    = $numPregunta
                                                                 AND idEvaluacion   = $idEvaluacion;");
                                    if ($query2->num_rows() > 0){
                                        $row2 = $query2->row();
                                        if ($md5Item == '') {
                                            $idPadre = $idEstructura;
                                            $md5Item = $row2->md5Item;
                                        }

                                        if ($md5Item!=$row2->md5Item) {
                                            $errores = 1;
                                            ?>
                                            <script type="text/javascript">
                                                $(".tablaResumen.p5-1 .info.info-<?php echo $numPregunta;?> .radio-1").prop('disabled', true);
                                                $(".tablaResumen.p5-1 .info.info-<?php echo $numPregunta;?> .radio-2").prop("checked", true);
                                            </script>
                                            <?php
                                        }else{
                                            ?>
                                            <script type="text/javascript">
                                                $(".tablaResumen.p5-1 .info.info-<?php echo $numPregunta;?> .radio-2").prop('disabled', true);
                                                $(".tablaResumen.p5-1 .info.info-<?php echo $numPregunta;?> .radio-1").prop("checked", true);
                                            </script>
                                            <?php
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                            $row = $query->next_row();
                        }
                    }
                    ?>
                </tbody>
            </table>
            <?php
            if ($errores==0) {
                $todosLosNum = implode(",",$numPreguntaAR);
                $sql = "UPDATE data_estructura
                           SET idPadre        = $idPadre
                         WHERE idEvaluacion   = $idEvaluacion
                           AND idCategoria    = $idCategoria
                           AND numPregunta      IN($todosLosNum)
                           AND idEstructura  != $idPadre;";
                $dbData->query($sql);
            }
            # ------------------------
        }
        # ------------------------
    }
}
