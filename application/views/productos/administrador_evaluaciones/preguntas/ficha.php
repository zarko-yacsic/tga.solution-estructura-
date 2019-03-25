<?php
$query = $dbData->query("SELECT idPregunta, titulo, tipo, codigo, idPadre, estado, idPreguntaDisenio
						   FROM data_pregunta
						  WHERE idTipoC       = $idTipoC
						    AND idCategoria   = $idCategoria
						    AND idPregunta    = $idPregunta;");
if ($query->num_rows() > 0){
    $row = $query->row();
    $titulo              = $row->titulo;
    $tipo                = $row->tipo;
    $codigo              = $row->codigo;
    $idPadre             = $row->idPadre;
    $estado              = $row->estado;
    $idPreguntaDisenio   = $row->idPreguntaDisenio;
}else{
	exit;
}

$query = $dbData->query("SELECT idRespuestaEstructura
						   FROM data_pregunta_disenio
						  WHERE idPreguntaDisenio = $idPreguntaDisenio;");
if ($query->num_rows() > 0){
    $row = $query->row();
    $idRespuestaEstructura = $row->idRespuestaEstructura;
}else{
	$idRespuestaEstructura = 0;
}




?>
<article class="tga-sides sides50-50 w-798 tga-contenido-In preguntas preguntas-ficha">
	<form id="miForm3" action="<?php print(base_url());?>productos/preguntas/editar_ficha" method="post">
	    <aside class="side-a">
			<input type="hidden" name="idTipoC" value="<?php echo $idTipoC;?>">
			<input type="hidden" name="idCategoria" value="<?php echo $idCategoria;?>">
			<input type="hidden" name="idPregunta" value="<?php echo $idPregunta;?>">
			<input type="hidden" name="tipo" value="<?php echo $tipo;?>">
			<input type="hidden" name="idPadre" value="<?php echo $idPadre;?>">

				<div class="tga-box tga-box-1 tga-box-texto">
				    <p class="tga-input-pp">Titulo texto</p>
				    <input type="text" name="pregunta"  class="form-control form-control-sm" value="<?php echo $titulo;?>" placeholder="Texto">
				    <p class="tga-input-ayuda">Solo caracteres de la A-Z y espacios</p>
				</div>

				<div class="tga-box tga-box-2 tga-box-select tga-w150">
				    <p class="tga-input-pp">Estado pregunta</p>
				    <select name="estado" class="custom-select custom-select-sm mb-3">
				        <option value="0">No activo</option>
				        <option value="1">Activo</option>
				    </select>
				</div>

				<hr class="tga-clear">
				<button type="button" class="btn btn-primary btn-sm" onclick="clicForm3();">Actualizar</button>

	    </aside>
	    <aside class="side-b">
			<div class="tga-box tga-box-3 tga-box-select">
			    <p class="tga-input-pp">Pregunta base</p>
			    <select class="custom-select custom-select-sm mb-3" name="idPreguntaDisenio" onchange="$('.preguntas-ficha .side-a .tga-box-2 select').prop('disabled', false);">
			        <option value="0">Seleccione</option>
			        <?php
			        switch ($tipo) {
			        	case 1:
						$query = $dbData->query("SELECT idPreguntaDisenio, titulo
												   FROM data_pregunta_disenio
												  WHERE idCategoria    = $idCategoria
													AND idTipoC        = $idTipoC
													AND estado         = 1
													AND tipo           = 1
													AND idPadre        = 0
											   ORDER BY titulo ASC;");
			        	break;
			        	case 2:
						$query = $dbData->query("SELECT idPreguntaDisenio, titulo
												   FROM data_pregunta_disenio
												  WHERE idCategoria    = $idCategoria
													AND idTipoC        = $idTipoC
													AND estado         = 1
													AND tipo           = 2
													AND idPadre        = 0
											   ORDER BY titulo ASC;");


			        	break;
			        	case 3:

						$query = $dbData->query("SELECT idPreguntaDisenio
												   FROM data_pregunta
												  WHERE idTipoC          = $idTipoC
													AND idCategoria      = $idCategoria
													AND tipo             = 2
													AND idPregunta       = (SELECT idPadre
																			  FROM data_pregunta
																			 WHERE idTipoC          = $idTipoC
																			   AND idCategoria      = $idCategoria
																			   AND idPregunta       = $idPregunta
																			   AND tipo             = 3);");
						if ($query->num_rows() > 0){
							$row = $query->row();
							$idPreguntaDisenioIN = $row->idPreguntaDisenio;
						}

						$query = $dbData->query("SELECT idPreguntaDisenio, titulo
												   FROM data_pregunta_disenio
												  WHERE idCategoria    = $idCategoria
													AND idTipoC        = $idTipoC
													AND estado         = 1
													AND tipo           = 3
													AND idPadre        = $idPreguntaDisenioIN
											   ORDER BY titulo ASC;");
			        	break;
			        }


					if ($query->num_rows() > 0){
						$row = $query->row();
						for ($i=1;$i<=$query->num_rows();$i++) {
							?>
							<option value="<?php echo $row->idPreguntaDisenio;?>"><?php echo $row->titulo;?></option>
							<?php
							$row = $query->next_row();
						}
					}
			        ?>
			    </select>
			</div>
			<hr class="tga-clear">
			<article class="ejemploArbol">
				<?php $this->Admin_eva->lista_estructura_respuesta($dbData,$idRespuestaEstructura);?>
			</article>
	    </aside>
	</form>
</article>

<script type="text/javascript">
$(".preguntas-ficha .side-a .tga-box-2 select").val(<?php echo $estado;?>);
$(".preguntas-ficha .side-b .tga-box-3 select").val(<?php echo $idPreguntaDisenio;?>);
if ($(".preguntas-ficha .side-b .tga-box-3 select").val()==0) {
	$(".preguntas-ficha .side-a .tga-box-2 select").val(0);
	$('.preguntas-ficha .side-a .tga-box-2 select').prop('disabled', true);
}

$(document).ready(function() {
    var options = {
        target:        '#oculto',
        beforeSubmit:  showRequest,
        success:       showResponse,
        timeout:       240000
    };
    $('#miForm3').ajaxForm(options);
});
function clicForm3(){
    loaderTgaSolutions(1);
    $("#miForm3").submit();
}
function actualizarListaRespuestas(val){

}
</script>
