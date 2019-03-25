<article class="listaCont listaCont-new">
	<form id="miFor_NewEmpresa" action="<?php print(base_url());?>tga_global/empresas/crear_empresa" method="post">
		<input name="idTipoEmpresa" value="<?=$idTipoEmpresa;?>" type="hidden">
		<input name="idPais" value="<?=$idPais;?>" type="hidden">
		<p class="titulo-form">Nombre empresa</p>
		<input class="form-control form-control-sm" name="nombre" value="" maxlength="50" type="text" placeholder="Nombre empresa">
		<p class="ayuda-form">Solo caracteres de la A-Z y espacios</p>
		<hr>
		<button type="button" class="btn btn-success btn-sm" onclick="clicFormNewEmpresa();">Crear</button>
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
    $('#miFor_NewEmpresa').ajaxForm(options);
});
function clicFormNewEmpresa(){
    loaderTgaSolutions(1);
    $("#miFor_NewEmpresa").submit();
}
</script>
