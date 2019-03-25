<h3>P-3: Proceso</h3>

<div class="cuadro cuadroCh cHr slt15" id="next_step">
	<button type="button" class="btn btn-danger btn-sm">Siguiente paso</button>
</div>
<div class="cuadro cuadroCh proceso cuadroCh2M" id="procesar">
	<button type="button" class="btn btn-warning btn-sm" onclick="btnProcesar();">Procesar</button>
</div>

<script type="text/javascript">
function btnProcesar(){
	$("body").loadTgaSol({url: 'productos/Administrar_evaluaciones_p3/guardar_preguntas',
	           idInmobiliaria: $(".selectEmpresa .custom-select").val(),
	               idProyecto: $(".selectProyecto .custom-select").val(),
	                   valor1: $(".selectTipoC .custom-select").val(),
	                   valor2: $(".selectPais .custom-select").val(),
	                   valor3: $(".selectEvaluacion .custom-select").val()});
}
</script>

<style type="text/css">
	#next_step{ display: none;}
</style>

<script type="text/javascript">
/*
$(document).ready(function(){
	$('#procesar').css('display', 'block');
	$('#procesar button').attr('onclick', 'enviarForm();');
		var options = {
        target : '',
        url : 'Administrar_evaluaciones_p3/guardar_preguntas',
        type : 'post',
        dataType : 'json',
        beforeSubmit : function(){},
        success : function(data){
        	var json = JSON.stringify(data);
			json = eval('(' + json + ')');
			loaderTgaSolutions(0);
        	if(json.status == 'SUCCESS'){
        		$('#procesar').css('display', 'none');
				$('#procesar button').removeAttr('onclick');
				$('#next_step').css('display', 'block');
				$('#next_step button').attr('onclick', 'contenidoSubInMenu(4);');
        	}
        	if(json.status == 'ERROR'){
        		mensajesTgaSolutions(3, json.titulo, json.mensaje);
        	}
    	}
    };
	$('#formCrearPreguntas').submit(function(){
		$(this).ajaxSubmit(options);
		return false;
	});
});

function enviarForm(){
	loaderTgaSolutions(1);
	$('#formCrearPreguntas').submit();
}
*/
</script>
