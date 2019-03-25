<h2>Diseño de preguntas</h2>
<article class="tga-filtro estructura_respuestas-1">
	<div class="tga-box tga-box-select tga-box-1 tga-w150 tga-left tga-mR30">
	    <p class="tga-input-pp">Tipo de custionario</p>
		<select class="custom-select custom-select-sm mb-3 filtroIn-idTipo" onchange="cargaCategoria();">
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
	<div class="tga-box tga-box-select tga-box-2 tga-w250 tga-left">
	    <p class="tga-input-pp">Categoria</p>
		<select class="custom-select custom-select-sm mb-3 filtroIn-idTipo" onchange="cargarContenido();">
			<option value="0">Seleccione</option>
		</select>
	</div>
	<button type="button" class="btn btn-success btn-sm btn-mas tga-left" onclick="nuevaPregunta();">+</button>
</article>

<section id="tga-contenidoIn-ee" class="tga-contenidoIn">

</section>

<script type="text/javascript">
$(".estructura_respuestas-1 .tga-box-1 select").val(0);

function cargaCategoria(){
	$("#tga-contenidoIn-ee").html("");
	$(".estructura_respuestas-1 .btn-mas").prop('disabled', true);
	$(".estructura_respuestas-1 .tga-box-2 select").prop('disabled', true);
	if ($(".estructura_respuestas-1 .tga-box-1 select").val()>0) {
		$("body").loadTgaSol({url: 'productos/disenio_preguntas/categoria',
			               valor1: $(".estructura_respuestas-1 .tga-box-1 select").val()
		});
	}
}

function cargarContenido(){
	$("#tga-contenidoIn-ee").html("");
	$(".estructura_respuestas-1 .btn-mas").prop('disabled', true);
	if ($(".estructura_respuestas-1 .tga-box-2 select").val()>0) {
		$("body").loadTgaSol({url: 'productos/disenio_preguntas/contenido',
			               salida: "tga-contenidoIn-ee",
			               valor1: $(".estructura_respuestas-1 .tga-box-1 select").val(),
			               valor2: $(".estructura_respuestas-1 .tga-box-2 select").val()
		});
	}
}

function nuevaPregunta(){
	$("body").loadTgaSol({modal:1,
				   modalTamanio:3,
	                modalTitulo:"Nueva pregunta",
	                        url: 'productos/disenio_preguntas/nueva_pregunta',
			             valor1: $(".estructura_respuestas-1 .tga-box-1 select").val(),
			             valor2: $(".estructura_respuestas-1 .tga-box-2 select").val()
	});
}




cargaCategoria(0);
</script>





