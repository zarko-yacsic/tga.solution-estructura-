
<form id="miFormNewPregunta" action="<?php print(base_url());?>productos/disenio_preguntas/code_pregunta" method="post">
	<input type="hidden" value="<?php echo $idTipoC;?>" name="idTipoC">
	<input type="hidden" value="<?php echo $idCategoria;?>" name="idCategoria">

	<div class="tga-box tga-box-texto tga-w100p">
	    <p class="tga-input-pp">Texto pregunta</p>
	    <input type="text" name="pregunta" class="form-control form-control-sm" value="" placeholder="Texto">
	</div>

	<div class="tga-box tga-box-check tga-box-check-left tga-w100p">
	    <div class="form-check">
	      <input class="form-check-input" type="radio" name="tipo" id="exampleRadios1" value="1">
	      <label class="form-check-label" for="exampleRadios1">
	        Normal
	      </label>
	    </div>
	    <div class="form-check">
	      <input class="form-check-input" type="radio" name="tipo" id="exampleRadios3" value="2">
	      <label class="form-check-label" for="exampleRadios3">
	        Padre
	      </label>
	    </div>
	</div>

	<button type="button" class="btn btn-success btn-sm" onclick="clicFormNP();">Crear</button>
</form>

<script type="text/javascript">
$(document).ready(function() {
    var options = {
        target:        '#oculto',
        beforeSubmit:  showRequest,
        success:       showResponse,
        timeout:       240000
    };
    $('#miFormNewPregunta').ajaxForm(options);
});
function clicFormNP(){
    loaderTgaSolutions(1);
    $("#miFormNewPregunta").submit();
}
</script>
