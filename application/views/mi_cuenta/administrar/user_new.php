
<article class="contenido-modal">
	<form id="miForm2" action="<?php print(base_url());?>mi_cuenta/administrar_usuarios/user_new_form" method="post">
		<div class="input-group input-group-sm mb-3">
			<div class="input-group-prepend">
				<span class="input-group-text" id="inputGroup-sizing-sm">Nombre</span>
			</div>
			<input type="text" class="form-control" name="nombre" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
		</div>
		<div class="input-group input-group-sm mb-3">
			<div class="input-group-prepend">
				<span class="input-group-text" id="inputGroup-sizing-sm">Email</span>
			</div>
		  	<input type="text" class="form-control" name="email" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
		</div>
		<p>Tipo de usuario</p>
		<select class="custom-select custom-select-sm mb-3" name="tipo">
			<option value="0">Tipo usuario</option>
			<option value="1">Vista</option>
			<option value="2">Adminstrador</option>
			<option value="3">Super admin</option>
		</select>
		<p>Estado</p>
		<select class="custom-select custom-select-sm mb-3 hr" name="estado">
			<option value="0">No activo</option>
			<option value="1">Activo</option>
		</select>
		<button type="button" class="btn btn-warning btn-sm" onclick="clicForm2();">Crear nuevo usuario</button>
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
