<?php
$query = $dbData->query("SELECT valor, texto, orden
						   FROM data_respuesta_datos
						  WHERE idRespuestaEstructura = $idER
							AND idRespuestaDato       = $idR;");
if ($query->num_rows() > 0){
    $row = $query->row();
    $valor = $row->valor;
    $texto = $row->texto;
    $orden = $row->orden;
}
?>
<article class="tga-contenidoIn tga-contenidoIn-nwR">
	<form id="miResp-2" action="<?php print(base_url());?>productos/estructura_respuestas/editar_respuesta" method="post">
		<input type="hidden" name="idR" value="<?php echo $idR;?>">
		<input type="hidden" name="idER" value="<?php echo $idER;?>">
		<div class="tga-box tga-box-texto tga-w100p">
		    <p class="tga-input-pp">Texto respuesta</p>
		    <input type="text" name="nombre" maxlength="200"  class="form-control form-control-sm" value="<?php echo $texto;?>" placeholder="Texto">
		    <p class="tga-input-ayuda">Se aceptan a-Z 0-9 .,-[] ()¡!¿?=</p>
		</div>
		<div class="tga-box tga-box-texto">
		    <p class="tga-input-pp">Valor respuesta</p>
		    <input type="text" name="valor" maxlength="10"  class="form-control form-control-sm" value="<?php echo $valor;?>" placeholder="Valor">
		</div>
		<div class="tga-box tga-box-texto">
		    <p class="tga-input-pp">Orden</p>
		    <input type="text" name="orden" maxlength="3"  class="form-control form-control-sm" value="<?php echo $orden;?>" placeholder="Valor">
		</div>
		<hr>
	    <button type="button" class="btn btn-success btn-sm" onclick="clicFormNew4();">Actualizar</button>
	    <button type="button" class="btn btn-danger btn-sm" onclick="deleteRespuesta(1,<?php echo $idR;?>,<?php echo $idER;?>);">Eliminar</button>
	</form>
</article>

<script type="text/javascript">
$(document).ready(function() {
    var options = {
        target:        '#oculto',
        beforeSubmit:  showRequest,
        success:       showResponse,
        timeout:       240000
    };
    $('#miResp-2').ajaxForm(options);
});
function clicFormNew4(){
    loaderTgaSolutions(1);
    $("#miResp-2").submit();
}

function deleteRespuesta(val1,val2,val3){
	var respuesta = new Array();
	respuesta[1]  = '¿Desea eliminar esta respuesta?';
	respuesta[2]  = '¿Realmente desea eliminar esta respuesta?';
	bootbox.confirm({
	    message: respuesta[val1],
	    buttons: {
	        confirm: {
	            label: 'Yes',
	            className: 'btn-success'
	        },
	        cancel: {
	            label: 'No',
	            className: 'btn-danger'
	        }
	    },
	    callback: function (result) {
	    	console.log(result);
	    	if (val1==1) {
	    		deleteRespuesta(2,val2,val3);
	    	}else{
		    	if (result==true) {
		    		$("body").loadTgaSol({url: 'productos/estructura_respuestas/delete_respuesta',
		    			               valor1: val2,
		    			               valor2: val3
		    	});
		    	}
	    	}
	    }
	});
}
</script>
