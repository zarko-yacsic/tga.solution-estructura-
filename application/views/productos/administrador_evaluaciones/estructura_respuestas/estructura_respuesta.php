<article class="tga-contenidoIn e-resp">
	<form id="miRespuesta-1" action="<?php print(base_url());?>productos/estructura_respuestas/editar_estructura_respuesta" method="post">
		<h3>Ficha respuesta</h3>
		    <input type="hidden" name="idTipoC" value="<?php echo $idTipoC;?>">
		    <input type="hidden" name="idCategoria" value="<?php echo $idCategoria;?>">
		    <input type="hidden" name="tipoR" value="<?php echo $tipoR;?>">
		    <input type="hidden" name="idER" value="<?php echo $idER;?>">

		    <div class="guardaCaja">
			    <div class="cajones cajon-1">
					<div class="tga-box tga-box-1 tga-box-texto tga-w100p">
		    			<p class="tga-input-pp">Titulo del tipo de respuesta</p>
			    		<input type="text" name="nombre"  class="form-control form-control-sm" value="<?php echo $nombre;?>" placeholder="Texto">
		   				<p class="tga-input-ayuda">Se aceptan a-Z 0-9 .,-[] ()¡!¿?=</p>
					</div>
			    </div>
			    <div class="cajones cajon-2">
			    	<button type="button" class="btn btn-primary btn-sm" onclick="clicFormNew2();">actualizar</button>
			    </div>
		    </div>

		    <div class="guardaCaja">
			    <div class="cajones cajon-1">
					<div class="tga-box tga-box-2 tga-box-select tga-w100p">
					    <p class="tga-input-pp">Tipo de respuesta</p>
					    <select name="idTipoR" class="custom-select custom-select-sm mb-3" onchange="crearMasRespuestas();">
					        <option value="0">Seleccionar</option>
							<?php
							$query = $dbData->query("SELECT idTipoR, tipoRespuesta
													   FROM data_respuesta_tipo
													  WHERE estado = 1
												   ORDER BY tipoRespuesta ASC;");
							if ($query->num_rows() > 0){
							    $row = $query->row();
							    for ($i=1;$i<=$query->num_rows();$i++) {
							    	?>
							    	<option value="<?php print($row->idTipoR);?>"><?php print($row->tipoRespuesta);?></option>
							    	<?php
							    	$row = $query->next_row();
							    }
							}else{
							    exit;
							}
							?>
					    </select>
					</div>
			    </div>
			    <div class="cajones cajon-2">
			    	<button type="button" class="btn btn-success btn-sm btn-mas" onclick="nuevaRespuesta(<?php echo $idER;?>);">+</button>
			    </div>
		    </div>

			<div class="tga-box tga-box-3 tga-box-check tga-box-check-left">
			    <div class="form-check">
			      <input class="form-check-input" name="global" type="checkbox" value="1" id="defaultCheck1">
			      <label class="form-check-label" for="defaultCheck1">
			        ¿respuesta tipo global?
			      </label>
			    </div>
			</div>

		    <div class="guardaCaja">
			    <div class="cajones cajon-1">
					<div class="tga-box tga-box-4 tga-box-select tga-w100p">
					    <p class="tga-input-pp">Estado</p>
					    <select name="estado" class="custom-select custom-select-sm mb-3">
					        <option value="0">No activo</option>
					        <option value="1">Activo</option>
					    </select>
					</div>
			    </div>
		    </div>
	</form>
</article>
<h4>Respuestas</h4>
<article class="contenidoMini">
	<?php
	$this->Admin_eva->lista_respuesta($dbData,$idER);
	?>
</article>

<script type="text/javascript">
function crearMasRespuestas(){
	$(".e-resp .btn-mas").prop('disabled', true);
	if ($(".e-resp .tga-box-2 .custom-select").val()!=0) {
		$(".e-resp .btn-mas").prop('disabled', false);
	}
}

$(".e-resp .tga-box-2 .custom-select").val(<?php echo $idTipoR;?>);
$(".e-resp .tga-box-4 .custom-select").val(<?php echo $estado;?>);

<?php
if ($global==1) {
	?>
	$(".e-resp .tga-box-3 input").attr('checked',true);
	<?php
}
?>


crearMasRespuestas();
$(document).ready(function() {
    var options = {
        target:        '#oculto',
        beforeSubmit:  showRequest,
        success:       showResponse,
        timeout:       240000
    };
    $('#miRespuesta-1').ajaxForm(options);
});
function clicFormNew2(){
    loaderTgaSolutions(1);
    $("#miRespuesta-1").submit();
}
function nuevaRespuesta(val1){
	$("body").loadTgaSol({url: 'productos/estructura_respuestas/nueva_respuesta',
						modal:1,
				 modalTamanio:3,
		          modalTitulo:"Nueva respuestas",
		          	   valor1: $(".estructura_respuestas-1 .tga-box-1 select").val(),
					   valor2: $(".estructura_respuestas-1 .tga-box-2 select").val(),
					   valor3: $(".estructura_respuestas-1 .tga-box-3 select").val(),
					   valor4:val1});
}
function listaRespuesta(val){
	$("body").loadTgaSol({url: 'productos/estructura_respuestas/carga_lista_respuestas',
					   salida: "tga-contenidoIn-ee .contenidoMini",
					   valor1: val});
}
function respuesta(val1,val2){
	$("body").loadTgaSol({url: 'productos/estructura_respuestas/respuesta',
						modal:1,
				 modalTamanio:3,
		          modalTitulo:"Nueva respuestas",
					   valor1: val1,
					   valor2:val2});
}
</script>
