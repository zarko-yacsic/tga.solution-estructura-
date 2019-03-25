<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_eva extends CI_Model{
	function __construct(){
		parent::__construct();
	}
	function lista_respuesta($dbData,$idER){
        $query = $dbData->query("SELECT idRespuestaDato, idRespuestaEstructura, valor, texto, orden
                                     FROM data_respuesta_datos
                                    WHERE idRespuestaEstructura = $idER
                                      AND estado                = 1
                                 ORDER BY orden ASC, texto ASC;");
        if ($query->num_rows() > 0){
            $row = $query->row();
            ?>
            <div class="listaRespeuesta listaRespeuesta-x">
                <div class="valor">Valor</div>
                <div class="texto">Texto</div>
                <div class="orden">Orden</div>
            </div>
            <?php
            for ($i=1;$i<=$query->num_rows();$i++) {
                ?>
                <div class="listaRespeuesta">
                    <div class="valor" onclick="respuesta(<?php echo $row->idRespuestaDato;?>,<?php echo $idER;?>);"><?php echo $row->valor;?></div>
                    <div class="texto" onclick="respuesta(<?php echo $row->idRespuestaDato;?>,<?php echo $idER;?>);"><?php echo $row->texto;?></div>
                    <div class="orden" onclick="respuesta(<?php echo $row->idRespuestaDato;?>,<?php echo $idER;?>);"><?php echo $row->orden;?></div>
                    <div class="equivalente">Equivalente</div>
                </div>
                <?php
                $row = $query->next_row();
            }
        }
	}
    function lista_estructura_respuesta($dbData,$idRespuestaEstructura){
        $query = $dbData->query("SELECT nombre
                                   FROM data_respuesta_estructura
                                  WHERE idRespuestaEstructura = $idRespuestaEstructura
                                    AND estado = 1;");
        if ($query->num_rows() > 0){
            $row = $query->row();
            ?>
            <h4><?php echo $row->nombre;?></h4>
            <?php
        }
        $query = $dbData->query("SELECT idRespuestaDato, texto, valor
                                   FROM data_respuesta_datos
                                  WHERE idRespuestaEstructura = $idRespuestaEstructura
                                    AND estado                = 1
                               ORDER BY orden ASC;");
        if ($query->num_rows() > 0){
            $row = $query->row();
            for ($i=1;$i<=$query->num_rows();$i++) {
                ?>
                <div class="arboleda">
                    <div class="arbol arbol-1"><?php echo $row->valor;?></div>
                    <div class="arbol arbol-2"><?php echo $row->texto;?></div>
                </div>
                <?php
                $row = $query->next_row();
            }
        }
    }
}












