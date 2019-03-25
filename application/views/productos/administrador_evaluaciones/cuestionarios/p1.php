<h3>P-1: Subir archivo</h3>

<div class="cuadro cuadroCh cHr slt15" id="next_step">
	<button type="button" class="btn btn-danger btn-sm">Siguiente paso</button>
</div>

<div class="cuadro cuadroCh2M cuadroHr slt25">
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
		<input type="hidden" name="hf_idPais" id="hf_idPais" value="37">
		<input type="hidden" name="hf_idInmobiliaria" id="hf_idInmobiliaria" value="18">
		<input type="hidden" name="hf_idProyecto" id="hf_idProyecto" value="25">
		<input type="hidden" name="hf_idEvaluacion" id="hf_idEvaluacion" value="19">
	</form>
</div>

<style type="text/css">
	#next_step { display: none;}
</style>


<script type="text/javascript">
	$("#hf_idTipoCuestionario").val($(".selectTipoC .custom-select").val());
	$("#hf_idPais").val($(".selectPais .custom-select").val());
	$("#hf_idInmobiliaria").val($(".selectEmpresa .custom-select").val());
	$("#hf_idProyecto").val($(".selectProyecto .custom-select").val());
	$("#hf_idEvaluacion").val($(".selectEvaluacion .custom-select").val());

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
		$('#next_step').css('display', 'none');
		$('#next_step button').removeAttr('onclick');
	}

	$(document).ready(function(){
	    var options1 = {
	        target : '',
	        url : 'Administrar_evaluaciones_p1/subir_excel',
	        type : 'post',
	        dataType : 'json',
	        beforeSubmit : function(){},
	        success : function(data){
	        	var json = JSON.stringify(data);
				json = eval('(' + json + ')');
				loaderTgaSolutions(0);
	        	if(json.status == 'SUCCESS'){
	        		$('#next_step button').attr('onclick', 'contenidoSubInMenu(2);');
	        		$('#next_step').css('display', 'block');
	        	}
	        	if(json.status == 'ERROR'){
	        		reestablecerFormulario();
	        		mensajesTgaSolutions(3, json.titulo, json.mensaje);
	        	}
	    	}
	    };
	    $('#formSubirArchivo').submit(function(){
	        $(this).ajaxSubmit(options1);
	        return false;
	    });
	});
</script>


