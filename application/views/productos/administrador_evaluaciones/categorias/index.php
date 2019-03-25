<h2>Categorias</h2>

<article class="section-filter tga-section-filter">
	<ul>
		<li class="btn-finale">
			<div class="cuadro cuadroCh cuadroLeft mR20 slt15">
				<p class="pp">Tipo de custionario</p>
				<select class="custom-select custom-select-sm mb-3 filtroIn-idTipo" onchange="changeSelectCategoria(0);">
					<option value="0">Seleccione</option>
					<?php
					$query = $dbData->query("SELECT idTipoC, tipo
												 FROM data_tipo_cuestionario
												WHERE estado     = 1
											 ORDER BY tipo ASC;");
					if ($query->num_rows() > 0){
					    $row = $query->row();
					    for ($i=1;$i<=$query->num_rows();$i++) {
					    	?>
					    	<option value="<?php print($row->idTipoC.'-'.md5($row->idTipoC.'alfonsito'));?>"><?php print($row->tipo);?></option>
					    	<?php
					    	$row = $query->next_row();
					    }
					}else{
					    exit;
					}
					?>
				</select>
			</div>
			<button type="button" class="btn btn-dark btn-sm" onclick="clicBotonNewCategoria();">Nueva categoria</button>
		</li>
	</ul>
</article>

<hr class="separate">

<article id="contenidoSubIn" class="contenidoSubIn listaCont listaCont-x3">

	<aside id="listaCategoriaIn" class="lado lado-a">
	</aside>
	<aside class="lado lado-b">
		<form id="miFor_editCat" action="<?php print(base_url());?>productos/administrar_evaluaciones_cat/editar_categoria" method="post">
			<p class="mini">ID:123</p>
			<p class="titulo-form">Titulo categoria</p>
			<input name="idCategoria" class="categoria" value="" type="hidden">
			<input name="idTipoC" class="idTipoC" value="" type="hidden">
			<input class="form-control form-control-sm nombre" name="categoria" value="" maxlength="50" type="text" placeholder="Nombre categoria">
			<p class="ayuda-form">Solo caracteres de la A-Z y espacios</p>
			<p class="titulo-form">Sigla</p>
			<input class="form-control form-control-sm l150 sigla" name="sigla" maxlength="4" value="" type="text">
			<p class="ayuda-form">Se pueden escribir 4 caracteres A-Z y 0-9 sin espacios</p>
			<p class="titulo-form">Estado</p>
			<select class="custom-select custom-select-sm mb-3 l150 estado" name="estado">
				<option value="0">No activo</option>
				<option value="1">Activo</option>
			</select>
			<hr>
			<button type="button" class="btn btn-primary btn-sm" onclick="clicFormEditCat();">Actualizar</button>
		</form>
	</aside>
	<aside class="lado lado-c">
		<form id="miFor_textoCat" action="<?php print(base_url());?>productos/administrar_evaluaciones_cat/text_categoria" method="post">
			<p>Texto del nombre por categoria por país</p>
			<input name="idCategoria" class="categoria" value="" type="hidden">
			<input name="idTipoC" class="idTipoC" value="" type="hidden">

			<?php
			$query = $this->db->query("SELECT idPais, pais
										 FROM tga_pais
										WHERE estado  = 1
									 ORDER BY pais ASC;");
			if ($query->num_rows() > 0){
			    $row = $query->row();
				for ($i=1;$i<=$query->num_rows();$i++) {
					?>
					<div class="input-group input-group-sm mb-3">
					  <div class="input-group-prepend">
					    <span class="input-group-text" id="inputGroup-sizing-sm"><?php echo $row->pais;?></span>
					  </div>
					  <input type="text" class="form-control paises pais_<?php echo $row->idPais;?>" name="pais_<?php echo $row->idPais;?>" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
					</div>
					<?php
					$row = $query->next_row();
				}
			}
			?>
			<button type="button" class="btn btn-primary btn-sm" onclick="clicFormtextoCat();">Actualizar</button>
		</form>
	</aside>
</article>

<script type="text/javascript">
function blockSelInput(){
    $("#contenidoSubIn .lado.lado-b .categoria").val("");
    $("#contenidoSubIn .lado.lado-b .idTipoC").val("");
    $("#contenidoSubIn .lado.lado-b .mini").html("ID:");
    $("#contenidoSubIn .lado.lado-b .nombre").val("");
    $("#contenidoSubIn .lado.lado-b .sigla").val("");

	$(".contenido .listaCont .lado input").prop('disabled',  true);
	$(".contenido .listaCont .lado select").prop('disabled', true);
	$(".contenido .listaCont .lado .btn").prop('disabled',  true);
	$(".section-filter .btn-finale .btn").prop('disabled',   true);
	$("#contenidoSubIn .lado.lado-c .paises").val('');
}
blockSelInput();

function clicBotonNewCategoria(){
	if ($(".filtroIn-idTipo").val() != 0) {
		$("body").loadTgaSol({modal:1,
	                   modalTamanio:0,
	                    modalTitulo:"Nueva categoria",
	                            url: 'productos/administrar_evaluaciones_cat/nuevo'});
	}
}

function changeSelectCategoria(val){
	$(".section-filter .btn-finale .btn").prop('disabled', true);
	$("#listaCategoriaIn").html("");
	blockSelInput();
	if ($(".filtroIn-idTipo").val() !=0 ) {
		$(".section-filter .btn-finale .btn").prop('disabled', false);
		$("body").loadTgaSol({url: 'productos/administrar_evaluaciones_cat/lista/' + val,
						   salida: "listaCategoriaIn",
						   valor1: $(".filtroIn-idTipo").val()});
	}
}

function cargamosLaCategoria(val){
	blockSelInput();
	if ($(".filtroIn-idTipo").val() !=0 ) {
		$("body").loadTgaSol({url: 'productos/administrar_evaluaciones_cat/cargar_categoria',
						   valor1: val});
	}
}

$(document).ready(function() {
    var options = {
        target:        '#oculto',
        beforeSubmit:  showRequest,
        success:       showResponse,
        timeout:       240000
    };
    $('#miFor_editCat').ajaxForm(options);
});
function clicFormEditCat(){
    loaderTgaSolutions(1);
    $("#miFor_editCat").submit();
}

$(document).ready(function() {
    var options = {
        target:        '#oculto',
        beforeSubmit:  showRequest,
        success:       showResponse,
        timeout:       240000
    };
    $('#miFor_textoCat').ajaxForm(options);
});
function clicFormtextoCat(){
    loaderTgaSolutions(1);
    $("#miFor_textoCat").submit();
}
</script>
