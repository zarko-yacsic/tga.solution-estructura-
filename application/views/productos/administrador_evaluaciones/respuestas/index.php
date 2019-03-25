<h2>Respuestas</h2>

<article class="tga-section-filter">
	<ul>
		<li>
			<div class="tga-cuadro tga-w150 tga-noclear tga-left tga-mR20 tga-mB10">
				<p class="tga-pp">Tipo de custionario</p>
				<select class="custom-select custom-select-sm mb-3">
					<option value="0">Seleccione</option>
				</select>
			</div>
			<div class="tga-cuadro tga-w150 tga-noclear tga-left tga-mR30 tga-mB10">
				<p class="tga-pp">País</p>
				<select class="custom-select custom-select-sm mb-3">
					<option value="0">Seleccione</option>
				</select>
			</div>
			<div class="tga-cuadro tga-w250 tga-noclear tga-left tga-mR20 tga-mB10">
				<p class="tga-pp">Inmobiliaria</p>
				<select class="custom-select custom-select-sm mb-3">
					<option value="0">Seleccione</option>
				</select>
			</div>
			<div class="tga-cuadro tga-w250 tga-noclear tga-left tga-mR20 tga-mB10">
				<p class="tga-pp">Proyecto</p>
				<select class="custom-select custom-select-sm mb-3">
					<option value="0">Seleccione</option>
				</select>
			</div>
		</li>
		<li>
			<div class="tga-cuadro tga-w600 tga-noclear tga-left tga-mR20 tga-mB10">
				<p class="tga-pp">Evaluación</p>
				<select class="custom-select custom-select-sm mb-3">
					<option value="0">Seleccione</option>
				</select>
			</div>
			<div class="tga-cuadro tga-w250 tga-noclear tga-left tga-mR20 tga-mB10">
				<p class="tga-pp">Medio de respuestas</p>
				<select class="custom-select custom-select-sm mb-3">
					<option value="0">Seleccione</option>
				</select>
			</div>
		</li>
	</ul>
</article>

<article class="respuestas-subir">
	<aside class="lado lado-a">
		<form id="formSubirArchivo">
			<div class="input-group mb-3">
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
	<aside class="lado lado-b">
		<button type="button" class="btn btn-warning btn-sm">Procesar</button>
	</aside>
</article>

<script type="text/javascript">
	$(document).ready(function(){
	    var options1 = {
	        target : '',
	        url : 'Respuestas/subir_excel',
	        type : 'post',
	        dataType : 'json',
	        beforeSubmit : function(){},
	        success : function(data){
	        	var json = JSON.stringify(data);
				json = eval('(' + json + ')');
	        	if(json.status == 'SUCCESS'){
	        		loaderTgaSolutions(0);
	        		alert('Archivo subido OK');
	        	}
	        	else{
	        		var m_status = json.status;
	        		var m_titulo = json.titulo;
	        		var m_mensaje = json.mensaje;
	        		if(m_status.indexOf('ERROR_') != -1){
	        			reestablecerFormulario();
	        			mensajesTgaSolutions(3, m_titulo, m_mensaje);
	        		}
	        	}
	    	}
	    };
	    $('#formSubirArchivo').submit(function(){
	        $(this).ajaxSubmit(options1);
	        return false;
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

</script>
