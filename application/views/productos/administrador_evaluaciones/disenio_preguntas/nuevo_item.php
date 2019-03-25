
<form id="miFormNewItem" action="<?php print(base_url());?>productos/disenio_preguntas/code_item" method="post">
	<input type="hidden" value="<?php echo $idTipoC;?>" name="idTipoC">
	<input type="hidden" value="<?php echo $idCategoria;?>" name="idCategoria">
	<input type="hidden" value="<?php echo $idPreguntaDisenio;?>" name="idPreguntaDisenio">

	<div class="tga-box tga-box-texto tga-w100p">
	    <p class="tga-input-pp">Texto del item</p>
	    <input type="text" name="item" class="form-control form-control-sm" value="" placeholder="Texto">
	</div>

	<button type="button" class="btn btn-success btn-sm" onclick="clicFormNI();">Crear</button>
</form>

<script type="text/javascript">
$(document).ready(function() {
    var options = {
        target:        '#oculto',
        beforeSubmit:  showRequest,
        success:       showResponse,
        timeout:       240000
    };
    $('#miFormNewItem').ajaxForm(options);
});
function clicFormNI(){
    loaderTgaSolutions(1);
    $("#miFormNewItem").submit();
}
</script>
