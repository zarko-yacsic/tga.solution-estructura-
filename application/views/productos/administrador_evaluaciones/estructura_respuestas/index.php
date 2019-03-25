
<h2>Estructura de respuestas</h2>
<article class="tga-filtro estructura_respuestas-1">
	<div class="tga-box tga-box-1 tga-box-select tga-w250 tga-left tga-mR20">
	    <p class="tga-input-pp">Tipo de custionario</p>
	    <select class="custom-select custom-select-sm mb-3" onchange="cargaCateogria();">
	        <option value="0">Seleccionar</option>
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
	<div class="tga-box tga-box-2 tga-box-select tga-w250 tga-left tga-mR20">
	    <p class="tga-input-pp">Categoria</p>
	    <select class="custom-select custom-select-sm mb-3" onchange="cargaEstRespuestas();">
	        <option value="0">Seleccionar</option>
	    </select>
	</div>
	<div class="tga-box tga-box-3 tga-box-select tga-w150 tga-left tga-mR40">
	    <p class="tga-input-pp">Tipo de respuesta</p>
	    <select class="custom-select custom-select-sm mb-3" onchange="seleccionTipoRespuesta();">
	        <option value="0">Mostrar todo</option>
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
	<button type="button" class="btn btn-success btn-sm btn-mas tga-left" onclick="nuevoTipoRespuesta();">+</button>
</article>

<section id="tga-contenidoIn-ee" class="tga-contenidoIn">
	<article class="tga-sides sides3-lista">
	    <aside class="side-a">
	    	<ul class="tga-lista lista-1"></ul>
	    </aside>
	    <aside class="side-b"></aside>
	    <aside class="side-c"></aside>
	</article>
</section>

<script type="text/javascript">

function nuevoTipoRespuesta(val){
	$("body").loadTgaSol({url: 'productos/estructura_respuestas/nuevo_tipo_respuesta',
						modal:1,
				 modalTamanio:3,
		          modalTitulo:"Nuevo tipo de respuesta",
					   valor1: $(".estructura_respuestas-1 .tga-box-1 select").val(),
					   valor2: $(".estructura_respuestas-1 .tga-box-2 select").val(),
					   valor3: $(".estructura_respuestas-1 .tga-box-3 select").val()});
}

function cargaCateogria(){
	$(".estructura_respuestas-1 .tga-box-2 select").prop('disabled', true);
	$(".estructura_respuestas-1 .tga-box-3 select").prop('disabled', true);
	$(".estructura_respuestas-1 .btn-mas").prop('disabled', true);
	$(".tga-sides.sides3-lista .side-a ul").html("");
	$(".tga-sides.sides3-lista .side-b").html("");
	$(".tga-sides.sides3-lista .side-c").html("");

	if ($(".estructura_respuestas-1 .tga-box-1 select").val()>0) {
		$("body").loadTgaSol({url: 'productos/Estructura_respuestas/categoria',
			               valor1: $(".estructura_respuestas-1 .tga-box-1 select").val()
		});
	}
}

function cargaEstRespuestas(){
	$(".estructura_respuestas-1 .tga-box-3 select").prop('disabled', true);
	$(".estructura_respuestas-1 .btn-mas").prop('disabled', true);
	$(".tga-sides.sides3-lista .side-a ul").html("");
	$(".tga-sides.sides3-lista .side-b").html("");
	$(".tga-sides.sides3-lista .side-c").html("");
	if ($(".estructura_respuestas-1 .tga-box-2 select").val()>0) {
		$(".estructura_respuestas-1 .tga-box-3 select").prop('disabled', false);
		seleccionTipoRespuesta();
	}
}

function seleccionTipoRespuesta(){
	$(".estructura_respuestas-1 .btn-mas").prop('disabled', true);
	$(".tga-sides.sides3-lista .side-a ul").html("");
	$(".tga-sides.sides3-lista .side-b").html("");
	$(".tga-sides.sides3-lista .side-c").html("");
	$(".estructura_respuestas-1 .btn-mas").prop('disabled', false);
	$("body").loadTgaSol({url: 'productos/estructura_respuestas/carga_lista',
					   salida: "tga-contenidoIn-ee .side-a ul",
					   valor1: $(".estructura_respuestas-1 .tga-box-1 select").val(),
					   valor2: $(".estructura_respuestas-1 .tga-box-2 select").val(),
					   valor3: $(".estructura_respuestas-1 .tga-box-3 select").val()});
}

// es en las listas
function estructuraRespuesta(val){
	$("tga-contenidoIn-ee .side-b").html("");
	if (val>0) {
		var idTipoR = $(".estructura_respuestas-1 .custom-select").val();
		$("body").loadTgaSol({url: 'productos/estructura_respuestas/estructura_respuesta',
						   salida: "tga-contenidoIn-ee .side-b",
						   valor1: $(".estructura_respuestas-1 .tga-box-1 select").val(),
						   valor2: $(".estructura_respuestas-1 .tga-box-2 select").val(),
						   valor3: $(".estructura_respuestas-1 .tga-box-3 select").val(),
						   valor4: val});
	}
}

$(".estructura_respuestas-1 .tga-box-1 select").val(0);
$(".estructura_respuestas-1 .tga-box-2 select").prop('disabled', true);
$(".estructura_respuestas-1 .tga-box-3 select").prop('disabled', true);
$(".estructura_respuestas-1 .btn-mas").prop('disabled', true);

</script>




