<h3>P-2: Selección preguntas</h3>

<div class="cuadro cuadroCh cHr slt15" id="next_step">
	<button type="button" class="btn btn-primary btn-sm" id="btn_guardar">Seleccionar y guardar</button>
	<button type="button" class="btn btn-danger btn-sm" id="btn_siguiente">Siguiente paso</button>
</div>

<p>Seleccionar la columna de la primera pregunta</p>

<?php
$archivo_xlsx = $idTipoC.'_'.$idPais.'_'.$idInmobiliaria.'_'.$idProyecto.'_'.$idEvaluacion.'_'.$idUser.'_cuestionario.xlsx';
$url_archivo  = 'excel/' . $archivo_xlsx;
$Reader = new SpreadsheetReader($url_archivo);
$data = array();
$pregunta = array();
$pregunta1 = array();
$pregunta2 = array();
$a = 0;
$b = 0;

foreach ($Reader as $Row){
	$a++;
	$b = 0;
	$totalColumnas = count($Row) + 1;
	for($i=0; $i < count($Row); $i++){
		$b++;
		$data[$a][$b] = $Row[$i];
	}
}

$columnas = $totalColumnas;
$filas = count($data);

if (!isset($data[1][$columnas]) || !$data[1][$columnas]){
	$data[1][$columnas] = '';
}
if (!isset($data[2][$columnas]) || !$data[2][$columnas]){
	$data[2][$columnas] = '';
}
if ($data[1][$columnas] == '' && $data[2][$columnas] == ''){
	$columnas = $columnas - 1;
}
$ancho_tabla = $columnas  * 152;
?>
<article class="p-2 bloque bw1100 bh250 bscroll">
	<form id="formGuardarColumnaInicio">
		<input type="hidden" name="hf_idTipoCuestionario" id="hf_idTipoCuestionario" value="3">
		<input type="hidden" name="hf_idPais" id="hf_idPais" value="37">
		<input type="hidden" name="hf_idInmobiliaria" id="hf_idInmobiliaria" value="18">
		<input type="hidden" name="hf_idProyecto" id="hf_idProyecto" value="25">
		<input type="hidden" name="hf_idEvaluacion" id="hf_idEvaluacion" value="19">

		<table class="tablaResumen" width="<?php echo $ancho_tabla;?>px" cellspacing="0" cellpadding="0" border="0">
			<tbody>
				<tr>
					<?php
					$letras = 'X,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,K,Y,Z';
					$letras = preg_split("/[\,]+/", $letras);
					$d = 0;
					$e = 0;
					for ($i = 1; $i <= $columnas; $i++){
						$d++;
						if ($d > 26){ $e++; $d = 1;}
						if ($e == 0) { $col_excel = $letras[$d];}
						else { $col_excel = $letras[$e] . $letras[$d];}
						echo '<td class="numP">P-' . $i . ' (' . $col_excel . ')</td>';
					}
					?>
				</tr>
				<tr>
					<?php
					$d = 0;
					$e = 0;
					for ($i = 1; $i <= $columnas; $i++){
						$d++;
						if ($d > 26){ $e++; $d = 1;}
						if ($e == 0) { $col_excel = $letras[$d];}
						else { $col_excel = $letras[$e] . $letras[$d];}
						echo '<td class="marcar">
								<input type="radio" name="marcar" id="marcar_' . $i . '_' . strtolower($col_excel) . '" value="' . $i . '">
							</td>';
					}
					?>
				</tr>
				<?php
				for ($a = 1; $a <= 2; $a++){
					echo '<tr>';
					for ($b = 1; $b <= $columnas; $b++){
						echo '<td class="pregunta">
								<div class="caja">' . $data[$a][$b] . '</div>
							</td>';
					}
					echo '</tr>';
				}
				?>
			</tbody>
		</table>
	</form>
</article>

<script type="text/javascript">
$("#hf_idTipoCuestionario").val($(".selectTipoC .custom-select").val());
$("#hf_idPais").val($(".selectPais .custom-select").val());
$("#hf_idInmobiliaria").val($(".selectEmpresa .custom-select").val());
$("#hf_idProyecto").val($(".selectProyecto .custom-select").val());
$("#hf_idEvaluacion").val($(".selectEvaluacion .custom-select").val());
</script>


<style type="text/css">
	#next_step { display: none;}
	#next_step > button#btn_guardar { display: none;}
	#next_step > button#btn_siguiente { display: none;}
</style>



<script type="text/javascript">

/* Seleccionar columna de inicio... */
$('input[type="radio"]').click(function(){
	if($(this).is(':checked')){
		var valor = $(this).val();
		reestablecerFormulario();
		if(valor != ''){
	       	$('#next_step').css('display', 'block');
			$('#next_step #btn_guardar').css('display', 'block').attr('onclick', 'enviarForm();');
		}
	}
});


/* Guardar columna de inicio evaluacion... */
$(document).ready(function(){
	var options = {
        target : '',
        url : 'Administrar_evaluaciones_p2/guardar_inicio_evaluacion',
        type : 'post',
        dataType : 'json',
        beforeSubmit : function(){},
        success : function(data){
        	var json = JSON.stringify(data);
			json = eval('(' + json + ')');
			loaderTgaSolutions(0);
        	if(json.status == 'SUCCESS'){
        		$('#next_step').css('display', 'block');
				$('#next_step #btn_guardar').css('display', 'none').removeAttr('onclick');
				$('#next_step #btn_siguiente').css('display', 'block').attr('onclick', 'contenidoSubInMenu(3);');
        	}
        	if(json.status == 'ERROR'){
        		reestablecerFormulario();
        		mensajesTgaSolutions(3, json.titulo, json.mensaje);
        	}
    	}
    };
	$('#formGuardarColumnaInicio').submit(function(){
		$(this).ajaxSubmit(options);
		return false;
	});
});


function enviarForm(){
	loaderTgaSolutions(1);
	$('#formGuardarColumnaInicio').submit();
}


function reestablecerFormulario(){
	$('#next_step #btn_guardar').css('display', 'none').removeAttr('onclick');
	$('#next_step #btn_siguiente').css('display', 'none').removeAttr('onclick');
	$('#next_step').css('display', 'none');
}
</script>
