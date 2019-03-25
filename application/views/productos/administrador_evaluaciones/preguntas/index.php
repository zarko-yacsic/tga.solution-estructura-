<h2>Preguntas</h2>
<article class="tga-filtro tga-pregunta-4">
	<div class="tga-box tga-box-1 tga-box-select tga-w200 tga-left tga-mR20">
	    <p class="tga-input-pp">Tipo de custionario</p>
	    <select class="custom-select custom-select-sm mb-3" onchange="cargaCategoria();">
	        <option value="0">Seleccione</option>
			<?php
			$query = $dbData->query("SELECT idTipoC, tipo
										 FROM data_tipo_cuestionario
										WHERE estado     = 1
									 ORDER BY tipo ASC;");
			if ($query->num_rows() > 0){
			    $row = $query->row();
			    for ($i=1;$i<=$query->num_rows();$i++) {
			    	?>
			    	<option value="<?php print($row->idTipoC);?>"><?php print($row->tipo);?></option>
			    	<?php
			    	$row = $query->next_row();
			    }
			}else{
			    exit;
			}
			?>
	    </select>
	</div>
	<div class="tga-box tga-box-2 tga-box-select tga-w350 tga-left tga-mR20">
	    <p class="tga-input-pp">Categoria</p>
	    <select class="custom-select custom-select-sm mb-3"  onchange="cargaContenido();">
	        <option value="0">Seleccione</option>
	    </select>
	</div>
	<button type="button" class="btn btn-success btn-sm tga-left tga-mT20" onclick="crearPregunta();">Crear</button>
	<button type="button" class="btn btn-outline-warning btn-sm botonProceso" onclick="procesoTablas();">Procesar tabla (12/1200)</button>
</article>

<article  id="contenidoSubIn" class="tga-contenidoIn tga-contenido-In preguntas">

</article>

<script type="text/javascript">
$(".tga-filtro .tga-box-2 select").prop('disabled', true);
$(".tga-filtro .btn-success").prop('disabled', true);
$(".tga-filtro .botonProceso").prop('disabled', true);

function cargaCategoria(){
	$(".tga-filtro .tga-box-2 select").prop('disabled', true);
	$(".tga-filtro .btn-success").prop('disabled', true);
	$(".tga-filtro .botonProceso").prop('disabled', true);
	$("#contenidoSubIn").html("");
	if ($(".tga-filtro .tga-box-1 select").val()>0) {
		$(".tga-filtro .botonProceso").prop('disabled', false);
		$("body").loadTgaSol({url: 'productos/preguntas/cargaCategoria',
						   valor1: $(".tga-filtro .tga-box-1 select").val()
		});
	}
}

function cargaContenido(){
	$(".tga-filtro .btn-success").prop('disabled', true);
	$("#contenidoSubIn").html("");
	if ($(".tga-filtro .tga-box-2 select").val()>0) {
		$(".tga-filtro .btn-success").prop('disabled', false);
		$("body").loadTgaSol({url: 'productos/preguntas/lista',
			               salida: 'contenidoSubIn',
						   valor1: $(".tga-filtro .tga-box-1 select").val(),
						   valor2: $(".tga-filtro .tga-box-2 select").val()
		});
	}
}

function crearPregunta(){
	$("body").loadTgaSol({url: 'productos/preguntas/nueva_pregunta',
						modal: 1,
				  modalTitulo: "Nueva pregunta",
	                   valor1: $(".tga-filtro .tga-box-1 select").val(),
	                   valor2: $(".tga-filtro .tga-box-2 select").val()});
}

function crearsubPregunta(valor){
	$("body").loadTgaSol({url: 'productos/preguntas/nueva_subpregunta',
						modal: 1,
				  modalTitulo: "Nueva pregunta",
	                   valor1: $(".tga-filtro .tga-box-1 select").val(),
	                   valor2: $(".tga-filtro .tga-box-2 select").val(),
	                   valor3: valor});
}

function cargarFicha(idPregunta,tipo){
	var titulos = new Array();
	titulos[1]    = "Ficha de pregunta";
	titulos[2]    = "Ficha de pregunta";
	titulos[3]    = "Ficha de item";

	$("body").loadTgaSol({url: 'productos/preguntas/ficha',
						modal: 1,
				 modalTamanio: 1,
				  modalTitulo: titulos[tipo],
	                   valor1: $(".tga-filtro .tga-box-1 select").val(),
	                   valor2: $(".tga-filtro .tga-box-2 select").val(),
	                   valor3: idPregunta,
	                   valor4: tipo});
}

function procesoTablas(){
	$("body").loadTgaSol({url: 'productos/proceso_tabla',
					   valor1: $(".tga-filtro .tga-box-1 select").val()
	});
}
</script>



