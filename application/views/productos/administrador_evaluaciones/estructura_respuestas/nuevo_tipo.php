<article class="tga-contenidoIn">
	<form id="miNewTipe" action="<?php print(base_url());?>productos/estructura_respuestas/crear_tipo_respuesta" method="post">
		<div class="tga-box tga-box-texto tga-w100p">
		    <p class="tga-input-pp">Titulo del tipo de respuesta</p>
		    <input type="hidden" name="idTipoC" value="<?php echo $idTipoC;?>">
		    <input type="hidden" name="idCategoria" value="<?php echo $idCategoria;?>">
		    <input type="hidden" name="tipoR" value="<?php echo $tipoR;?>">
		    <input type="text" name="nombre"  class="form-control form-control-sm" value="" placeholder="Texto">
		    <p class="tga-input-ayuda">Se aceptan a-Z 0-9 .,-[] ()¡!¿?=</p>
		</div>
		<hr>
	    <button type="button" class="btn btn-success btn-sm" onclick="clicFormNew1();">Crear</button>
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
    $('#miNewTipe').ajaxForm(options);
});
function clicFormNew1(){
    loaderTgaSolutions(1);
    $("#miNewTipe").submit();
}
</script>
