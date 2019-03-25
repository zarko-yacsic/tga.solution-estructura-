<article class="tga-contenido-In preguntas preguntas-nuevo">
	<form id="miForm2" action="<?php print(base_url());?>productos/preguntas/crear_subpregunta" method="post">
		<input type="hidden" name="idTipoC" value="<?php echo $idTipoC;?>">
		<input type="hidden" name="idCategoria" value="<?php echo $idCategoria;?>">
		<input type="hidden" name="idPregunta" value="<?php echo $idPregunta;?>">
		<div class="tga-box tga-box-texto">
		    <p class="tga-input-pp">Titulo Pregunta</p>
		    <input type="text" name="pregunta"  class="form-control form-control-sm" value="" placeholder="Texto">
		    <p class="tga-input-ayuda">Solo caracteres de la A-Z y espacios</p>
		</div>

		<hr class="tga-clear">
		<button type="button" class="btn btn-success btn-sm" onclick="clicForm2();">Crear</button>
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
    $('#miForm2').ajaxForm(options);
});
function clicForm2(){
    loaderTgaSolutions(1);
    $("#miForm2").submit();
}
</script>
