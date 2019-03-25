<section class="tga-contenido tga-contenido-empresas">
	<h2>Empresas</h2>
	<article class="tga-section-filter">
		<ul>
			<li>
				<div class="tga-cuadro tga-w200 tga-left tga-noclear tga-mR20">
					<p class="tga-pp">Tipo empresa</p>
					<select class="custom-select custom-select-sm mb-3 filtroIn-idTipo" onchange="changeSelectTipoPais(0);">
						<option value="0">Seleccione</option>
						<?php
						$query = $this->db->query("SELECT idTipoEmpresa, tipo
												     FROM tga_empresas_tipo
												    WHERE estado = 1
											     ORDER BY tipo ASC;");
						if ($query->num_rows() > 0){
					    	$row = $query->row();
					    	for ($i=1;$i<=$query->num_rows();$i++) {
					    		?>
					    		<option value="<?php print($row->idTipoEmpresa.'-'.md5($row->idTipoEmpresa.'alfonsito'));?>"><?php print($row->tipo);?></option>
					    		<?php
					    		$row = $query->next_row();
					    	}
						}else{
					    	exit;
						}
						?>
					</select>
				</div>
				<div class="tga-cuadro tga-w200 tga-left tga-noclear">
					<p class="tga-pp">País</p>
					<select class="custom-select custom-select-sm mb-3 filtroIn-idPais" onchange="changeSelectTipoPais(0);">
						<option value="0">Seleccione</option>
						<?php
						$query = $this->db->query("SELECT idPais, pais
												     FROM tga_pais
												    WHERE estado = 1
											     ORDER BY pais ASC;");
						if ($query->num_rows() > 0){
					    	$row = $query->row();
					    	for ($i=1;$i<=$query->num_rows();$i++) {
					    		?>
					    		<option value="<?php print($row->idPais.'-'.md5($row->idPais.'alfonsito'));?>"><?php print($row->pais);?></option>
					    		<?php
					    		$row = $query->next_row();
					    	}
						}else{
					    	exit;
						}
						?>
					</select>
				</div>
			</li>
			<li class="btn-finale">
				<button type="button" class="btn btn-dark btn-sm btn-tipo" onclick="clicBotonNewTipoEmpresa();">Crear tipo empresa</button>
			</li>
		</ul>
	</article>

	<article class="tga-contenido-In tga-cIn850 listaCont listaCont-x3">
		<div class="tga-cuadro tga-mB20">
			<button type="button" class="btn btn-dark btn-sm tga-right btn-empresa" onclick="clicBotonNewEmpresa();">Nueva empresa</button>
		</div>
		<aside id="listaEmpresasIn" class="lado lado-a">
		</aside>
		<aside class="tga-lado2 lado-b">
			<form id="miFor_UpdEmpresa" action="<?php print(base_url()); ?>tga_global/empresas/actualizar_empresa" method="post">
			<p class="tga-titulo-form">Nombre empresa</p>
			<input name="idTipoEmpresa" value="" class="idTipoEmpresa" type="hidden">
			<input name="idPais" value="" class="idPais" type="hidden">
			<input name="idEmpresa" value="" class="idEmpresa" type="hidden">
			<input class="form-control form-control-sm nombre" name="nombre" value="" maxlength="50" type="text" placeholder="Nombre empresa">
			<p class="tga-ayuda-form">Solo caracteres de la A-Z y espacios</p>
			<div class="tga-cuadro tga-w200 tga-left tga-noclear tga-mR40">
				<p class="tga-titulo-form">Tipo empresa</p>
				<select class="custom-select custom-select-sm mb-3 tipoEmpresa" name = "idNewTipoEmpresa">
					<option value="0">Seleccione</option>
					<?php
						$query = $this->db->query("SELECT idTipoEmpresa, tipo
												     FROM tga_empresas_tipo
												    WHERE estado = 1
											     ORDER BY tipo ASC;");
						if ($query->num_rows() > 0){
					    	$row = $query->row();
					    	for ($i=1;$i<=$query->num_rows();$i++) {
					    		?>
					    		<option value="<?php print($row->idTipoEmpresa.'-'.md5($row->idTipoEmpresa.'alfonsito'));?>"><?php print($row->tipo);?></option>
					    		<?php
					    		$row = $query->next_row();
					    	}
						}else{
					    	exit;
						}
					?>
				</select>
			</div>
			<div class="tga-cuadro tga-w200 tga-left tga-noclear tga-mB20">
				<p class="tga-titulo-form">Estado</p>
				<select class="custom-select custom-select-sm mb-3 estado" name = "estadoEmpresa">
					<option value="0">No activo</option>
					<option value="1">Activo</option>
				</select>
			</div>
			<div class="tga-cuadro">
				<button type="button" class="btn btn-primary btn-sm btn-actualiza" onclick="clicBotonUpdEmpresa();">Actualizar</button>
			</div>
			</form>
		</aside>
	</article>
</section>

<script type="text/javascript">
$(document).ready(function() {
    var options = {
        target:        '#oculto',
        beforeSubmit:  showRequest,
        success:       showResponse,
        timeout:       240000
    };
    $('#miFor_UpdEmpresa').ajaxForm(options);
});
function clicBotonUpdEmpresa(){
    loaderTgaSolutions(1);
    $("#miFor_UpdEmpresa").submit();
}
$("head").append('<link rel="stylesheet" href="/css/tga_global/empresas.css" crossorigin="anonymous">');
function blockSelInput(){
	$(".tga-contenido-empresas .tga-contenido-In .lado-b .nombre").prop('disabled', true);
    $(".tga-contenido-empresas .tga-contenido-In .btn-empresa").prop('disabled', true);
    $(".tga-contenido-empresas .tga-contenido-In .btn-actualiza").prop('disabled', true);
	$(".tga-contenido-empresas .tga-contenido-In input[name=empresas]").val('');
	$(".tga-contenido-empresas .tga-contenido-In select").prop('disabled', true);

	$(".tga-contenido-empresas .tga-contenido-In select").val(0);
	$(".tga-contenido-empresas .tga-contenido-In .lado-b .nombre").val("");
}
blockSelInput();
function changeSelectTipoPais(val){
	$(".tga-contenido-empresas #listaEmpresasIn").html("");
	blockSelInput();
	if ($(".filtroIn-idTipo").val() != 0 && $(".filtroIn-idPais").val() != 0) {
		$(".tga-contenido-empresas .tga-contenido-In .btn-empresa").prop('disabled', false);
		$(".section-filter .btn-finale .btn").prop('disabled', false);
		$("body").loadTgaSol({url: 'tga_global/empresas/lista/' + val,
						   salida: "listaEmpresasIn",
						   valor1: $(".filtroIn-idTipo").val(),
						   valor2: $(".filtroIn-idPais").val()});
	}
}
function cargamosLaEmpresa(val){
	blockSelInput();
	if ($(".filtroIn-idTipo").val() !=0 && $(".filtroIn-idPais").val() != 0) {
		$("body").loadTgaSol({url: 'tga_global/empresas/cargar_empresa',
						   valor1: val});
	}
}
function clicBotonNewEmpresa(){
	if ($(".filtroIn-idTipo").val() != 0 && $(".filtroIn-idPais").val() != 0) {
		$("body").loadTgaSol({modal:1,
	                   modalTamanio:0,
	                    modalTitulo:"Nueva Empresa",
	                            url: 'tga_global/empresas/nuevo',
						     valor1: $(".filtroIn-idTipo").val(),
						     valor2: $(".filtroIn-idPais").val()});
	}
}
function clicBotonNewTipoEmpresa(){
	$("body").loadTgaSol({modal:1,
                   modalTamanio:0,
                    modalTitulo:"Nuevo Tipo de Empresa",
                            url: 'tga_global/empresas/nuevo_tipo'});
}
</script>
