<h3>P-4: Agrupar categorias</h3>

<?php
$idUser = $_SESSION['idUser'];
$idTipoCuestionario = $_SESSION['id_tipo_cuestionario'];
$idPais = $_SESSION['id_pais'];
$idInmobiliaria = $_SESSION['id_inmobiliaria'];
$idProyecto = $_SESSION['id_proyecto'];
$idEvaluacion = $_SESSION['id_evaluacion'];
$idCategoria = 0;
?>
<div class="drag_container">
	<form id="formEnviarIdEvaluacion">
		<input type="hidden" name="id_evaluacion" id="id_evaluacion" value="<?php echo $idEvaluacion;?>">
		<input type="hidden" name="id_categoria" id="id_categoria" value="0">
	</form>
	<aside class="categoria-p4 lado lado-a">
		<div class="cuadro cuadroCh cHr slt15" id="next_step">
			<button type="button" class="btn btn-info btn-sm categ_list_btn">Lista de categorias</button>
			<button type="button" class="btn btn-danger btn-sm next_btn" onclick="contenidoSubInMenu(5);">Siguiente paso</button>
		</div>
		<p id="preguntasUncat">Preguntas sin categoría: <span>0</span> / <span>0</span></p>
		<article class="parte parte-a">
			<div class="drag_column" ondrop="drop(event, this)" ondragover="allowDrop(event)" id="preguntasListado"></div>
		</article>
	</aside>
	<aside class="categoria-p4 lado lado-b">
		<form id="formGuardarPreguntasCategorias" action="<?php print(base_url());?>productos/administrar_evaluaciones_p4/guardar_preguntas_categorias" method="post">
			<div class="categoriaP4 cuadro cuadroM cuadroLeft slt15">
				<p class="pp">Categoria</p>
				<select class="custom-select custom-select-sm mb-3" name="sel_id_categoria" id="sel_id_categoria" onchange="cargarCategoria(this.value);">
						<option value="0">Seleccione</option>
						<?php
						$query = $dbData->query("SELECT a.idCategoria
							  						  , a.siglaMin
							  						  , IF(b.categoria IS NULL
							  						  , a.categoria,b.categoria) AS categoria
												   FROM data_categoria a
											  LEFT JOIN data_categoria_pais b ON b.idTipoC = a.idTipoC AND b.idCategoria = a.idCategoria AND b.idPais = $idPais
												  WHERE a.estado     = 1
													AND a.idTipoC    = $idTipoC;");
						if ($query->num_rows() > 0){
						    $row = $query->row();
						    for ($i=1;$i<=$query->num_rows();$i++) {
						    	?>
						    	<option value="<?php print($row->idCategoria);?>"><?php echo "(".$row->siglaMin.") ".$row->categoria;?></option>
						    	<?php
						    	$row = $query->next_row();
						    }
						}
						?>
				</select>
				<input type="hidden" name="hf_id_evaluacion" id="hf_id_evaluacion" value="<?php echo $idEvaluacion;?>">
			</div>
			<button type="button" class="btn btn-primary btn-sm" onclick="guardarPreguntasCategorias();">Guardar</button>
			<article class="parte parte-b">
				<div class="drag_column" id="preguntasCategorias"></div>
			</article>
		</form>
	</aside>
</div>

<style type="text/css">
	#next_step { width: 280px;}
	#next_step > button.categ_list_btn { float: left; margin-right: 5px;}
	#next_step > button.next_btn { float: left; display: none;}
	.drag_container { margin-bottom: 20px !important;}
	.drag_container { overflow: auto; margin-bottom: 20px;}
	.drag_container .drag_column { width: 100%; height: 100%; padding: 20px; overflow: auto;}
	.drag_container .drag_column .draggable_item { margin: 0px; padding-bottom: 10px;}
	.drag_container .drag_column .draggable_item > .draggable_inner {
		padding: 16px;
		border: 1px solid #DDDDDD;
		background-color: #FCFCFC;
		cursor: all-scroll;
	}
	.drag_container .drag_column .draggable_item > .draggable_inner .preguntaTxt {
		height: 38px;
	    overflow: auto;
	    font-size: 12px;
	    text-align: justify;
	    padding-right: 10px;
	}
</style>

<script type="text/javascript">
function cargarCajaLista(ruta,contenedor){
	$('#next_step .next_btn').css('display', 'none');
	$("body").loadTgaSol({url: 'productos/administrar_evaluaciones_p4/'+ruta,
					  salida: contenedor,
			  idInmobiliaria: $(".selectEmpresa .custom-select").val(),
	              idProyecto: $(".selectProyecto .custom-select").val(),
	                  valor1: $(".selectTipoC .custom-select").val(),
	                  valor2: $(".selectPais .custom-select").val(),
	                  valor3: $(".selectEvaluacion .custom-select").val(),
	                  valor4: $("#sel_id_categoria").val()});
}

function cargarCategoria(idCategoria){
	if (idCategoria>0) {
		$('#preguntasCategorias').attr({'ondrop' : 'drop(event, this);', 'ondragover' : 'allowDrop(event);'});
		$('#preguntasCategorias').parent().css('background-color', '#eeeeee');
		cargarCajaLista('lista_b','preguntasCategorias');
	}else{
		$('#preguntasCategorias').removeAttr('ondrop ondragover').html('');
		$('#preguntasCategorias').parent().css('background-color', '#ffecec');
		$('#preguntasCategorias').parent().css('background-color', '#ffecec');
	}
}


$(document).ready(function() {
    var options = {
        target:        '#oculto',
        beforeSubmit:  showRequest,
        success:       showResponse,
        timeout:       240000
    };
    $('#formGuardarPreguntasCategorias').ajaxForm(options);
});
function guardarPreguntasCategorias(){
	if ($("#sel_id_categoria").val()>0) {
	    loaderTgaSolutions(1);
	    $("#formGuardarPreguntasCategorias").submit();
	}

}

// --------------------------------------------

function listarPreguntas(myContainer){
    loaderTgaSolutions(1);
    console.log(myContainer);
    $('#formEnviarIdEvaluacion').ajaxSubmit({
        target : '',
        url : 'Administrar_evaluaciones_p4/listar_preguntas',
        type : 'post',
        dataType : 'json',
        beforeSubmit : function(){},
        success : function(data){
			loaderTgaSolutions(0);
        	var json = JSON.stringify(data);
			json = eval('(' + json + ')');
        	var evaluacion = json.data_evaluacion;
        	$('#' + myContainer).html('');
        	if(json.status == 'SUCCESS'){
        		if(evaluacion.length > 0){
					var preguntas = '';
	        		for(f = 0; f < evaluacion.length; f++){
	        			preguntas += preguntaDD(
							parseInt(f + 1), evaluacion[f]['id_preguntaE'], evaluacion[f]['nomCampo'], evaluacion[f]['pregunta'],
	        				evaluacion[f]['numpregunta'], evaluacion[f]['numSubPregunta'], myContainer);
	        		}
        		}
        		$('#' + myContainer).html(preguntas);
        	}
        	if(json.status == 'ERROR'){
        		console.log('ERROR MySQL : ' + json.mensaje);
        	}
    	}
	});
    return false;
}



function preguntaDD(n, id_estructura, nom_campo, pregunta, num_pregunta, num_subpregunta, contenedor){
	var tipoFila; var subpregunta = 0;
	if(parseInt(num_subpregunta) > 0){ subpregunta = 1;}
	if(contenedor == 'preguntasListado'){ tipoFila = 'PREG';}
	if(contenedor == 'preguntasCategorias'){ tipoFila = 'PCAT';}
	var html = '<div draggable="true" ondragstart="drag(event)" class="draggable_item" id="drag_' + tipoFila + '_' + id_estructura + '">';
	html += '<div class="draggable_inner" id="draggable_inner_' + tipoFila + '_' + id_estructura + '">';
	html += '<div class="preguntaTxt" id="pregTxt_' + tipoFila + '_' + id_estructura+ '"><strong>' + nom_campo + '</strong> : ' + pregunta + '</div></div>';
	html += '<input type="hidden" name="id_estructura_' + tipoFila + '[]" id="id_estructura_' + tipoFila + '_' + id_estructura + '" value="' + id_estructura + '" data-tipo_hf="id_estructura">';
	html += '<input type="hidden" name="num_pregunta_' + tipoFila + '[]" id="num_pregunta_' + tipoFila + '_' + id_estructura + '" value="' + num_pregunta + '" data-tipo_hf="num_pregunta">';
	html += '<input type="hidden" name="is_subpregunta_' + tipoFila + '[]" id="is_subpregunta_' + tipoFila + '_' + id_estructura + '" value="' + subpregunta + '" data-tipo_hf="subpregunta">';
	html += '</div>';
	return html;
}


function contadorPreguntasSinCategoria(){
	$('#next_step .next_btn').css('display', 'none');
	$.ajax({
		url: 'Administrar_evaluaciones_p4/preguntas_sin_categoria',
		type: 'GET',
		data: {
			id_evaluacion : <?php echo $idEvaluacion;?>
		},
		success: function(data){
		var json = eval('(' + data + ')');
			if(json.status == 'SUCCESS'){
				var total_preg = parseInt(json.total_preguntas);
				var total_preg_sc = parseInt(json.total_preguntas_sc);
				if(total_preg_sc == 0){
					$('#next_step .next_btn').css('display', 'block');
				}
				$('#preguntasUncat span:nth-of-type(1)').html(total_preg_sc);
				$('#preguntasUncat span:nth-of-type(2)').html(total_preg);
			}
		}
	});
}


/* D & D... */
function allowDrop(ev){
	ev.preventDefault();
}


function drag(ev){
	ev.dataTransfer.setData('text/plain', ev.target.id);
}


function drop(ev, el){
  	ev.preventDefault();
  	var data = ev.dataTransfer.getData('text');
  	var target_id = ev.target.id;
  	var match = {
  		'list_preg'	: target_id.indexOf('preguntasListado'),
  		'list_pcat'	: target_id.indexOf('preguntasCategorias'),
  		'drag_preg'	: target_id.indexOf('drag_PREG_'),
  		'drag_pcat'	: target_id.indexOf('drag_PCAT_'),
  		'inn_preg'	: target_id.indexOf('draggable_inner_PREG_'),
  		'inn_pcat'	: target_id.indexOf('draggable_inner_PCAT_'),
  		'text_preg'	: target_id.indexOf('pregTxt_PREG_'),
  		'text_pcat'	: target_id.indexOf('pregTxt_PCAT_'),
  		'idE_preg'	: target_id.indexOf('id_estructura_PREG'),
  		'idE_pcat'	: target_id.indexOf('id_estructura_PCAT')
  	};
	if(target_id != ''){
		if(match['list_preg'] != -1 || match['list_pcat'] != -1){
			$('#' + target_id).append(document.getElementById(data));
		}
		else if(match['inn_preg'] != -1 || match['inn_pcat'] != -1){
			$('#' + target_id).parent().after(document.getElementById(data));
		}
		else if(match['text_preg'] != -1 || match['text_pcat'] != -1){
			$('#' + target_id).parent().parent().after(document.getElementById(data));
		}
		else{
			$('#' + target_id).after(document.getElementById(data));
		}
		var idDragInner, idTextPreg, idHfEstructura,idHfPregunta, idHfSubpregunta;
		if(match['list_preg'] != -1 || match['drag_preg'] != -1 || match['inn_preg'] != -1 || match['text_preg'] != -1 || match['idE_preg'] != -1){
			$('#preguntasListado').find('.draggable_item').each(function(){
				idDragInner = $('#' + $(this).attr('id') + ' .draggable_inner');
				idTextPreg = $('#' + $(this).attr('id') + ' #' + idDragInner.attr('id') + ' .preguntaTxt');
				idHfEstructura = $('#' + $(this).attr('id') + ' input[data-tipo_hf="id_estructura"]');
				idHfPregunta = $('#' + $(this).attr('id') + ' input[data-tipo_hf="num_pregunta"]');
				idHfSubpregunta = $('#' + $(this).attr('id') + ' input[data-tipo_hf="subpregunta"]');
				renameItem(this, 'drag_PCAT_', 'drag_PREG_');
				renameItem(idDragInner, 'draggable_inner_PCAT_', 'draggable_inner_PREG_');
				renameItem(idTextPreg, 'pregTxt_PCAT_', 'pregTxt_PREG_');
				renameItem(idHfEstructura, 'id_estructura_PCAT', 'id_estructura_PREG', true);
				renameItem(idHfPregunta, 'num_pregunta_PCAT', 'num_pregunta_PREG', true);
				renameItem(idHfSubpregunta, 'is_subpregunta_PCAT', 'is_subpregunta_PREG', true);
			});
		}
		if(match['list_pcat'] != -1 || match['drag_pcat'] != -1 || match['inn_pcat'] != -1 || match['text_pcat'] != -1 || match['idE_pcat'] != -1){
			$('#preguntasCategorias').find('.draggable_item').each(function(){
				idDragInner = $('#' + $(this).attr('id') + ' .draggable_inner');
				idTextPreg = $('#' + $(this).attr('id') + ' #' + idDragInner.attr('id') + ' .preguntaTxt');
				idHfEstructura = $('#' + $(this).attr('id') + ' input[data-tipo_hf="id_estructura"]');
				idHfPregunta = $('#' + $(this).attr('id') + ' input[data-tipo_hf="num_pregunta"]');
				idHfSubpregunta = $('#' + $(this).attr('id') + ' input[data-tipo_hf="subpregunta"]');
				renameItem(this, 'drag_PREG_', 'drag_PCAT_');
				renameItem(idDragInner, 'draggable_inner_PREG_', 'draggable_inner_PCAT_');
				renameItem(idTextPreg, 'pregTxt_PREG_', 'pregTxt_PCAT_');
				renameItem(idHfEstructura, 'id_estructura_PREG', 'id_estructura_PCAT', true);
				renameItem(idHfPregunta, 'num_pregunta_PREG', 'num_pregunta_PCAT', true);
				renameItem(idHfSubpregunta, 'is_subpregunta_PREG', 'is_subpregunta_PCAT', true);
			});
		}
	}
}


function renameItem(myObj, str_search, str_replace, input_array = false){
	var id = $(myObj).attr('id');
	var id_new = id.replace(str_search, str_replace);
	$(myObj).attr('id', id_new);
	if(input_array == true){
		var n = id_new.split('_');
		var name_new = n[0] + '_' + n[1] + '_' + n[2] + '[]';
		$(myObj).attr('name', name_new);
	}
}


function randomNumber(from, to){
	return Math.floor((Math.random() * to) + from);
}

$(document).ready(function(){
	setTimeout("cargarCajaLista('lista_a','preguntasListado');",500);
});

</script>



