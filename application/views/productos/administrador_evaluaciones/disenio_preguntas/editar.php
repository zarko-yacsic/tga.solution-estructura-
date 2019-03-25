<?php
$query = $dbData->query("SELECT idPreguntaDisenio
								, tipo
								, idPadre
								, titulo
								, estado
								, idRespuestaEstructura
							 FROM data_pregunta_disenio
							WHERE idTipoC             = $idTipoC
							  AND idCategoria         = $idCategoria
							  AND idPreguntaDisenio   = $idPreguntaDisenio;");
if ($query->num_rows() > 0){
    $row = $query->row();
    $idRespuestaEstructura = $row->idRespuestaEstructura;
    $tipo                  = $row->tipo;
    $idPadre               = $row->idPadre;
    $titulo                = $row->titulo;
    $tipo                  = $row->tipo;
    $estado                = $row->estado;
}
?>

<form id="miFormEditar" action="<?php print(base_url());?>productos/disenio_preguntas/code_editar" method="post">
	<input type="hidden" value="<?php echo $idTipoC;?>" name="idTipoC">
	<input type="hidden" value="<?php echo $idCategoria;?>" name="idCategoria">
	<input type="hidden" value="<?php echo $idPreguntaDisenio;?>" name="idPreguntaDisenio">
	<input type="hidden" value="<?php echo $tipo;?>" name="tipo">

	<div class="tga-box tga-box-texto tga-w100p">
	    <p class="tga-input-pp">Texto</p>
	    <input type="text" name="nombre" class="form-control form-control-sm" value="<?php echo $titulo;?>" placeholder="Nombre">
	</div>

	<div class="tga-box tga-box-editar-1 tga-box-select tga-w100p">
	    <p class="tga-input-pp">Tipo respuesta</p>
	    <select name="idRespuestaEstructura" class="custom-select custom-select-sm mb-3">
	        <option value="0">Seleccione</option>
			<?php
			$query = $dbData->query("SELECT idRespuestaEstructura, nombre, `global`
									   FROM data_respuesta_estructura
									  WHERE estado                  = 1
										AND idTipoC                 = $idTipoC
										AND (idCategoria            = $idCategoria OR `global`=1)
								   ORDER BY nombre ASC;");
			if ($query->num_rows() > 0){
			    $row = $query->row();
			    for ($i=1;$i<=$query->num_rows();$i++) {
			    	$globalIn = ($row->global==1) ? ' *' : '';
				    ?>
				    <option value="<?php echo $row->idRespuestaEstructura;?>"><?php echo $row->nombre.$globalIn;?></option>
				    <?php
			    	$row = $query->next_row();
			    }

			}
			?>
	    </select>
	</div>

	<div class="tga-box tga-box-editar-2 tga-box-select">
	    <p class="tga-input-pp">Estado</p>
	    <select name="estado" class="custom-select custom-select-sm mb-3">
	        <option value="0">No activo</option>
	        <option value="1">Activo</option>
	    </select>
	</div>

	<button type="button" class="btn btn-success btn-sm" onclick="clicFormEdit();">Actualizar</button>
	<hr>
	<article class="ejemploArbol">
		<?php $this->Admin_eva->lista_estructura_respuesta($dbData,$idRespuestaEstructura);?>
	</article>
</form>

<script type="text/javascript">
$(".tga-box-editar-1 select").val(<?php echo $idRespuestaEstructura;?>);
$(".tga-box-editar-2 select").val(<?php echo $estado;?>);
<?php
if ($tipo==2) {
	?>
	$(".tga-box-editar-1 select").prop('disabled', true);
	$(".tga-box-editar-1").remove();
	<?php
}
?>

$(document).ready(function() {
    var options = {
        target:        '#oculto',
        beforeSubmit:  showRequest,
        success:       showResponse,
        timeout:       240000
    };
    $('#miFormEditar').ajaxForm(options);
});
function clicFormEdit(){
    loaderTgaSolutions(1);
    $("#miFormEditar").submit();
}
</script>
