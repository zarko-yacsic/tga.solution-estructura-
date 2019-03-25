
<article class="listaCont listaCont-new">
	<form id="miFor_NewCat" action="<?php print(base_url());?>productos/administrar_evaluaciones_cat/crear" method="post">
		<p class="titulo-form">Titulo categoria</p>
		<input name="idTipo" value="" class="idTipo" type="hidden">
		<input class="form-control form-control-sm" name="categoria" value="" maxlength="50" type="text" placeholder="Nombre categoria">
		<p class="ayuda-form">Solo caracteres de la A-Z y espacios</p>
		<p class="titulo-form">Sigla</p>
		<input class="form-control form-control-sm l150" name="sigla" maxlength="4" value="" type="text">
		<p class="ayuda-form">Se pueden escribir 4 caracteres de la a-Z, 0-9 y sin espacios.</p>
		<hr>
		<button type="button" class="btn btn-success btn-sm" onclick="clicFormNewCat();">Crear</button>
	</form>

</article>
<script type="text/javascript">
$(".listaCont-new .idTipo").val($(".filtroIn-idTipo").val());
$(document).ready(function() {
    var options = {
        target:        '#oculto',
        beforeSubmit:  showRequest,
        success:       showResponse,
        timeout:       240000
    };
    $('#miFor_NewCat').ajaxForm(options);
});
function clicFormNewCat(){
    loaderTgaSolutions(1);
    $("#miFor_NewCat").submit();
}
</script>
