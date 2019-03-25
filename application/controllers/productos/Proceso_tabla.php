<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proceso_tabla extends CI_Controller {
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
        $this->load->model('Admin_eva');
        $this->Tgasolutions->usuario();
        $this->Tgasolutions->permisos();
    }
    private function _exite_campo($tabla,$idTipoC,$dbData,$row){

    }
    private function _exite_tabla($tabla,$idTipoC,$dbData,$row){

        $query2 = $dbData->query("SHOW TABLES LIKE 'data_preguntas'");
        if ($query2->num_rows() == 0){

        }


    }
    private function _resumen($idTipoC,$dbData){

    }
    public function index(){
        $dbData         = $this->load->database('evaluaciones', TRUE);
        $idTipoC        = trim($this->input->post('var1'));
        if (!is_numeric($idTipoC)) {exit;}

        $query = $dbData->query("SELECT a.idPregunta
                                      , a.idTipoC
                                      , a.idCategoria
                                      , a.codigo
                                      , a.idPreguntaDisenio
                                      , d.tipoRespuesta
                                      , d.tipe
                                      , d.largo
                                      , d.indexado
                                      , a.codigoTabla
                                      , e.siglaMin
                                   FROM data_pregunta a
                              LEFT JOIN data_pregunta_disenio b     ON b.idPreguntaDisenio     = a.idPreguntaDisenio
                                                                   AND b.idTipoC               = a.idTipoC
                                                                   AND b.idCategoria           = a.idCategoria
                                                                   AND b.estado                = 1
                              LEFT JOIN data_respuesta_estructura c ON c.idRespuestaEstructura = b.idRespuestaEstructura
                                                                   AND c.idTipoC               = a.idTipoC
                                                                   AND c.estado                = 1
                                                                   AND b.idCategoria           = a.idCategoria
                              LEFT JOIN data_respuesta_tipo d       ON d.idTipoR               = c.tipoRespuesta
                                                                   AND d.estado                = 1
                              LEFT JOIN data_categoria e            ON e.idCategoria = a.idCategoria
                                  WHERE a.estado       = 1
                                    AND a.estadoTabla  = 0
                                    AND a.idTipoC      = 1
                                    AND a.tipo        != 2
                                        LIMIT 10;");

        if ($query->num_rows() > 0){
            $row = $query->row();
            for ($i=1;$i<=$query->num_rows();$i++) {
                #$row->idCategoria

                $sigla        = $row->siglaMin;
                $codigoTabla  = $row->codigoTabla;
                $tabla        = "tabla_".$sigla.$idTipoC."_".$codigoTabla;

                $this->_exite_tabla($tabla,$idTipoC,$dbData,$row);


            }
        }

    }
}
