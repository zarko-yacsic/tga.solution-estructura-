<h2>BDD</h2>

<article class="tga-section-filter">
	<ul>
		<li>
			<div class="tga-cuadro tga-w150 tga-noclear tga-left tga-mR20 tga-mB10 selectTipoC">
				<p class="tga-pp">Tipo de cuestionario</p>

				<select class="custom-select custom-select-sm mb-3" onchange="admin_carga_empresa();">
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
			<div class="tga-cuadro tga-w150 tga-noclear tga-left tga-mR30 tga-mB10 selectPais">
				<p class="tga-pp">País</p>
				<select class="custom-select custom-select-sm mb-3" onchange="admin_carga_empresa();">
					<option value="0">Seleccione</option>
					<?php
					$query = $this->db->query("SELECT idPais, pais
											   FROM tga_pais
											  WHERE estado = 1
										   ORDER BY pais ASC;");
					if ($query->num_rows() > 0){
					    $row = $query->row();
					    for ($i=1;$i<=$query->num_rows();$i++) {
					    	?>
					    	<option value="<?php print($row->idPais);?>"><?php print($row->pais);?></option>
					    	<?php
					    	$row = $query->next_row();
					    }
					}else{
					    exit;
					}
					?>
				</select>
			</div>
			<div class="tga-cuadro tga-w250 tga-noclear tga-left tga-mR20 tga-mB10 selectEmpresa">
				<p class="tga-pp">Inmobiliaria</p>
				<select class="custom-select custom-select-sm mb-3" onchange="admin_carga_proyecto();">
					<option value="0">Seleccione</option>
				</select>
			</div>
			<div class="tga-cuadro tga-w250 tga-noclear tga-left tga-mR20 tga-mB10 selectProyecto">
				<p class="tga-pp">Proyecto</p>
				<select class="custom-select custom-select-sm mb-3" onchange="carga_evaluacion();">
					<option value="0">Seleccione</option>
				</select>
			</div>
		</li>
		<li>
			<div class="tga-cuadro tga-w600 tga-noclear tga-left tga-mR20 tga-mB10 selectEvaluacion">
				<p class="tga-pp">Evaluación</p>
				<select class="custom-select custom-select-sm mb-3" onchange="admin_seleccionar_evaluacion();">
					<option value="0">Seleccione</option>
				</select>
			</div>
		</li>
	</ul>
</article>

<article class="bdd-subir">
	<aside class="lado lado-a">
		<form id="formSubirArchivo">
			<div class="input-group mb-3 bddSubida">
				<div class="input-group-prepend">
					<span class="input-group-text" id="inputGroupFileAddon01" onclick="subirArchivo();">Subir</span>
				</div>
				<div class="custom-file">
					<input type="file" class="custom-file-input" name="archivo" id="upload_xls" onchange="seleccionarExcel(this);" aria-describedby="inputGroupFileAddon01">
					<label class="custom-file-label archivoTxt archivoTxt-1" for="upload_xls">Cargar archivo</label>
				</div>
			</div>
			<input type="hidden" name="hf_idTipoCuestionario" id="hf_idTipoCuestionario" value="3">
			<input type="hidden" name="hf_idPais" id="hf_idPais" value="30">
			<input type="hidden" name="hf_idInmobiliaria" id="hf_idInmobiliaria" value="1">
			<input type="hidden" name="hf_idProyecto" id="hf_idProyecto" value="25">
			<input type="hidden" name="hf_idEvaluacion" id="hf_idEvaluacion" value="1">
		</form>
	</aside>
	<aside class="lado lado-b" id="btn_mostrar">
		<button type="button" class="btn btn-info btn-sm bddVer">Ver</button>
	</aside>
</article>

<article id="contenidoSubIn" class="contenidoSubIn bddTabla">
</article>


<style type="text/css">
	.ul_duplicados { list-style: none; margin: 20px 0px 20px 0px; padding: 0px 0px 0px 40px;}
	.ul_duplicados > li { font-size: 16px;}
	.p_duplicados { font-size: 14px;}
	.p_encontrados { margin-bottom: 15px !important;}
	#btn_mostrar { width: 100px; margin: 3px 0px 0px 20px !important;}
</style>


<script type="text/javascript">

	$(document).ready(function(){
	    var options1 = {
	        target : '',
	        url : 'Bdd/subir_excel',
	        type : 'post',
	        dataType : 'json',
	        beforeSubmit : function(){},
	        success : function(data){
	        	var json = JSON.stringify(data);
				json = eval('(' + json + ')');
	        	var id_evaluacion = json.id_evaluacion;
	        	var pag_limit = json.pag_limit;
	        	if(json.status == 'SUCCESS'){
	        		loaderTgaSolutions(0);
	        		reestablecerFormulario();
	        		mensajesTgaSolutions(3, json.titulo, json.mensaje);
	        		/* listarBDD(id_evaluacion); */
	        	}
	        	else{
	        		var m_status = json.status;
	        		var m_titulo = json.titulo;
	        		var m_mensaje = json.mensaje;
	        		if(m_status.indexOf('ERROR_') != -1){
	        			if(m_status == 'ERROR_DUPLICADOS'){
	        				var m_cod_duplicados = json.cod_duplicados;
	        				m_mensaje += '<ul class="ul_duplicados">';
	        				for (x = 0; x < m_cod_duplicados.length; x++){
	        					m_mensaje += '<li><strong>' + m_cod_duplicados[x]['codigo'] + '</strong> ';
	        					m_mensaje += '(Encontrado ' + m_cod_duplicados[x]['existe_veces'] + ' veces)</li>';
	        				}
	        				m_mensaje += '</ul>';
	        				m_mensaje += '<p class="p_duplicados"><strong>IMPORTANTE :</strong> No deben existir códigos duplicados en la BDD. ';
	        				m_mensaje += 'Por favor corregir archivo .xlsx y volver a intentar la subida.</p>';
	        			}
	        		}
	        		reestablecerFormulario();
	        		mensajesTgaSolutions(3, m_titulo, m_mensaje);
	        	}
	    	}
	    };

	    $('#formSubirArchivo').submit(function(){
	        $(this).ajaxSubmit(options1);
	        $('#contenidoSubIn').html('');
	        return false;
	    });

	    $('#btn_mostrar button').click(function(){
	    	var id_evaluacion = $('#hf_idEvaluacion').val();
	    	listarBDD(id_evaluacion);
	    });
	});

	function subirArchivo(){
		if ($('#formSubirArchivo label.archivoTxt-1').html() != 'Cargar archivo'){
			var fileName = $('#formSubirArchivo input#upload_xls').val();
			var fileExt = fileName.substring(parseInt(fileName.length - 5), fileName.length);
			if(fileExt != '.xlsx'){
	    		reestablecerFormulario();
	    		mensajesTgaSolutions(3, 'Administrar evaluaciones', 'Sólo se pueden seleccionar archivos con extensión .xlsx');
			}
			else{
				loaderTgaSolutions(1);
				$('#formSubirArchivo').submit();
			}
		}
	}


	function seleccionarExcel(myUploader){
		var texto = $(myUploader).val();
		$('#formSubirArchivo label.archivoTxt-1').html(texto);
	}


	function reestablecerFormulario(){
		$('#formSubirArchivo label.archivoTxt-1').html('Cargar archivo');
		$('#formSubirArchivo input#upload_xls').removeAttr('value');
	}


	function listarBDD(id_evaluacion){
		loaderTgaSolutions(1);
		$.ajax({
			url: 'Bdd/listar_bdd',
			type: 'GET',
			data: {
				id_evaluacion : id_evaluacion
			},
			success: function(data){
				var json = eval('(' + data + ')');
				var html = '';
				if(json.status == 'SUCCESS'){
					var data_bdd = json.data_bdd; var fecha_tmp;
					if(parseInt(data_bdd.length) != 0){
						html = '<p class="p_encontrados">* Se ha actualizado la BDD con ' + parseInt(data_bdd.length) + ' registros.</p>';
						html += '<table class="tablaResumen" width="1100" cellspacing="0" cellpadding="0" border="0">';
						html += '<thead><tr>';
						html += '<th class="a1">Codigo</th>';
						html += '<th class="a2">Nombre persona</th>';
						html += '<th class="a3">Email</th>';
						html += '<th class="a4">Fecha entrega</th>';
						html += '<th class="a5">Fono</th>';
						html += '<th class="a6">Celular</th>';
						html += '</tr></thead>';
						html += '<tbody>';
						for(i = 0; i < data_bdd.length; i++){
							data_bdd[i]['fonoCasa'] = data_bdd[i]['fonoCasa'] != '' ? data_bdd[i]['fonoCasa'] : '-';
							data_bdd[i]['celular'] = data_bdd[i]['celular'] != '' ? data_bdd[i]['celular'] : '-';
							fecha_tmp = data_bdd[i]['fecha'].split('-');
							data_bdd[i]['fecha'] = fecha_tmp[2] + '-' + fecha_tmp[1] + '-' + fecha_tmp[0];
							html += '<tr id="tr_' + data_bdd[i]['idBDD'] + '">';
							html += '<td class="a1">' + data_bdd[i]['codigo'] + '</td>';
							html += '<td class="a2" nowrap="nowrap">' + data_bdd[i]['propietario'].toUpperCase() + '</td>';
							html += '<td class="a3">' + data_bdd[i]['email'].toLowerCase() + '</td>';
							html += '<td class="a4">' + data_bdd[i]['fecha'] + '</td>';
							html += '<td class="a5">' + data_bdd[i]['fonoCasa'] + '</td>';
							html += '<td class="a6">' + data_bdd[i]['celular'] + '</td>';
							html += '</tr>';
						}
						html += '</tbody>';
						html += '</table>';
					}
				}
				if(json.status == 'ERROR'){
					html += '<p class="p_encontrados">* ' + json.mensaje + '</p>';
				}
				$('#contenidoSubIn').html(html);
				loaderTgaSolutions(0);
			}
		});
	}

function admin_seleccionar_evaluacion(){
	bloquearDesde_evaluaciones();
	if ($(".tga-contenido .selectEvaluacion .custom-select").val()>0) {
		$("body").loadTgaSol({url: 'productos/administrar_evaluaciones/carga_bdd',
		                   valor1: $(".tga-contenido .selectTipoC .custom-select").val(),
		                   valor2: $(".tga-contenido .selectPais .custom-select").val(),
		                   valor3: $(".tga-contenido .selectEmpresa .custom-select").val(),
		                   valor4: $(".tga-contenido .selectProyecto .custom-select").val(),
		                   valor5: $(".tga-contenido .selectEvaluacion .custom-select").val()});
	}
}

tgaSolution_admin_eva.donde = 2;
bloquearTodo();
</script>
