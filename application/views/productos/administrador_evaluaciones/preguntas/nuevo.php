<article class="tga-contenido-In preguntas preguntas-nuevo">
	<form id="miForm1" action="<?php print(base_url());?>productos/preguntas/crear_pregunta" method="post">
		<input type="hidden" name="idTipoC" value="<?php echo $idTipoC;?>">
		<input type="hidden" name="idCategoria" value="<?php echo $idCategoria;?>">
		<div class="tga-box tga-box-texto">
		    <p class="tga-input-pp">Titulo Pregunta</p>
		    <input type="text" name="pregunta"  class="form-control form-control-sm" value="" placeholder="Texto">
		    <p class="tga-input-ayuda">Solo caracteres de la A-Z y espacios</p>
		</div>

		<div class="tga-box tga-box-check tga-box-check-left">
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

		<hr class="tga-clear">
		<button type="button" class="btn btn-success btn-sm" onclick="clicForm1();">Crear</button>
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
    $('#miForm1').ajaxForm(options);
});
function clicForm1(){
    loaderTgaSolutions(1);
    $("#miForm1").submit();
}
</script>
