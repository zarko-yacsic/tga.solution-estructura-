<article class="tga-contenidoIn">
	<form id="miNewresp" action="<?php print(base_url());?>productos/estructura_respuestas/crear_nueva_respuesta" method="post">
		<input type="hidden" name="idTipoC" value="<?php echo $idTipoC;?>">
		<input type="hidden" name="idCategoria" value="<?php echo $idCategoria;?>">
		<input type="hidden" name="tipoR" value="<?php echo $tipoR;?>">
		<input type="hidden" name="idER" value="<?php echo $idER;?>">

		<?php
		if ($tipoR == 5) {
			$texto1   = 'Texto respuesta';
			$texto2   = 'Rango';
		}else{
			$texto1 = 'Texto respuesta';
			$texto2 = 'Valor respuesta';
		}
		?>
		<div class="tga-box tga-box-texto tga-w100p">
		    <p class="tga-input-pp"><?php echo $texto1;?></p>
		    <input type="text" name="nombre" maxlength="200"  class="form-control form-control-sm" value="" placeholder="Texto">
		    <p class="tga-input-ayuda">Se aceptan a-Z 0-9 .,-[] ()¡!¿?=%/</p>
		</div>
		<div class="tga-box tga-box-texto">
		    <p class="tga-input-pp"><?php echo $texto2;?></p>
		    <input type="text" name="valor" maxlength="10"  class="form-control form-control-sm" value="" placeholder="Valor">
		    <p class="tga-input-ayuda">Se aceptan 0-9</p>
		</div>
		<hr>
	    <button type="button" class="btn btn-success btn-sm" onclick="clicFormNew3();">Crear</button>
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
    $('#miNewresp').ajaxForm(options);
});
function clicFormNew3(){
    loaderTgaSolutions(1);
    $("#miNewresp").submit();
}
</script>
