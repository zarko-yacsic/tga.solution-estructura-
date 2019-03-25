
<section class="tga-contenido tga-contenido-proyectos">
	<h2>Proyectos</h2>

	<aside id="cargaProyecto" class="tga-lado3 lado-a">
		<div class="tga-cuadro tga-w250 tga-clear tga-mB20">
			<p class="tga-pp">Seleccione empresa</p>
		    <select id="idInmobiliaria" class="custom-select custom-select-sm mb-3" name="idInmobiliaria" onchange="selectInmobiliaria(this.value)">
		    	<option value="0" selected="selected">Seleccione empresa</option>
		    	<?php
		        $query  = $this->db->query("SELECT idEmpresa,empresa
		                                      FROM tga_empresas
		                                     WHERE estado = 1;");
		    	foreach ($query->result() as $key) {
		    		?>
		    		<option value="<?php echo $key->idEmpresa;?>"><?php echo $key->empresa;?></option>
		    		<?php
		    	}
		    	?>
		    </select>
		</div>
	    <script type="text/javascript">
	    function selectInmobiliaria(idInmobiliaria){
	    	$("#cargaProyecto ul").html("");
	    	limpiarProyecto();
	    	if (idInmobiliaria!=0) {
		    	$("body").loadTgaSol({url: 'tga_global/Proyectos/cargaProyectos',
		                           salida: "cargaProyecto ul",
		                   idInmobiliaria: idInmobiliaria
		                              });
	    	}else{
	    		$("#idInmobiliaria2").val(0);
	    	}
	    }
	    </script>

		<ul class="tga-listas"></ul>
	</aside>
	<aside class="tga-lado3 lado-b">
		<button type="button" class="btn btn-dark btn-sm btnNew tga-mB20" onclick="$('#idInmobiliaria2').val($('#idInmobiliaria').val());$('#idProyecto').val(0);$('#proyectoAction').val(0);limpiarProyecto();">Nueva</button>
		<hr class="tga-hr1">
		<div class="tga-cuadro tga-m tga-w350">
	    	<form name="formProyecto" id="formProyecto" action="<?php echo base_url();?>tga_global/Proyectos/guardaProyectos" method="POST">
		    	<input type="hidden" name="idInmobiliaria" value="" id="idInmobiliaria2">
		    	<input type="hidden" name="idProyecto" value="" id="idProyecto">
		    	<input type="hidden" name="proyectoAction" value="" id="proyectoAction">
		    	<p class="tga-pp">Proyecto</p>
		    	<input type="text" class="form-control form-control-sm tga-mB10" name="nombreProyecto" id="nombreProyecto" placeholder="nombreProyecto">
		    	<p class="tga-pp">Zona</p>
		    	<select id="zonas" class="custom-select custom-select-sm mb-3 tga-mB10 tga-w200" name="zonas">
					<option value="0" selected="selected">Selecciones Zona</option>
					<option value="1">Zona 1</option>
					<option value="2">Zona 2</option>
					<option value="3">Zona 3</option>
				</select>
		    	<p class="tga-pp">Región</p>
		    	<select id="regiones" class="custom-select custom-select-sm mb-3 tga-mB10 tga-w300" name="regiones" id="regiones">
					<option value="0" selected="selected">Selecciones Region</option>
					<option value="1">1 Tarapaca</option>
					<option value="2">2 Antofagasta</option>
					<option value="3">3 Atacama</option>
					<option value="4">4 Coquimbo</option>
					<option value="5">5 Valparaiso</option>
					<option value="6">6 O'Higgins</option>
					<option value="7">7 Maule</option>
					<option value="8">8 Bio - Bio</option>
					<option value="9">9 Araucania</option>
					<option value="10">10 Los Lagos</option>
					<option value="11">11 Aisen</option>
					<option value="12">12 Magallanes Y Antartica</option>
					<option value="13">13 Metropolitana</option>
					<option value="14">14 Los Rios</option>
					<option value="15">15 Arica y Parinacota</option>
				</select>
		    	<p class="tga-pp">Comuna</p>
				<select id="comunas" class="custom-select custom-select-sm mb-3 tga-mB10 tga-w300" name="comunas" id="comunas">
					<option value="0" selected="selected">Selecciones Comuna</option>
					<option value="345">Comuna 1</option>
					<option value="1">Comuna 2</option>
					<option value="4">Comuna 3</option>
					<option value="123">Comuna 4</option>
					<option value="122">Comuna 5</option>
					<option value="6">Comuna 6</option>
					<option value="7">Comuna 7</option>
				</select>
		    	<p class="tga-pp">Dirección</p>
		    	<input type="text" class="form-control form-control-sm tga-mB10" name="direccion" placeholder="direccion" id="direccion">
		    	<p class="tga-pp">Url del proyecto</p>
		    	<input type="text" class="form-control form-control-sm tga-mB10" name="url" placeholder="url" id="url">
		    	<p class="tga-pp">Imagen del proyecto</p>
		    	<input type="text" class="form-control form-control-sm tga-mB20" name="imagen" placeholder="imagen" id="imagen">
		    	<p class="tga-pp">Latitud</p>
		    	<input type="text" class="form-control form-control-sm tga-mB10 tga-w200" name="latitud" placeholder="latitud" id="latitud">
		    	<p class="tga-pp">Longitud</p>
		    	<input type="text" class="form-control form-control-sm tga-mB20 tga-w200" name="longitud" placeholder="longitud" id="longitud">
		    	<div class="tga-cuadro tga-mB20">
			    	<p class="tga-pp">tipo de construcción</p>
			    	<input type="radio" name="tipoConstruccion" value="1" class="tipoConstruccion" id="tipoConstruccion1">
			    	<label>Depto</label>
			    	<input type="radio" name="tipoConstruccion" value="2" class="tipoConstruccion" id="tipoConstruccion2">
			    	<label>Casa</label>
			    	<input type="radio" name="tipoConstruccion" value="3" class="tipoConstruccion" id="tipoConstruccion3">
			    	<label>Oficina</label>
		    	</div>

		    	<div class="tga-cuadro tga-mB20">
			    	<p class="tga-pp">Financiamiento</p>
			    	<input type="radio" name="tipoFinanciamiento" value="1" class="tipoFinanciamiento" id="tipoFinanciamiento1">
			    	<label>Con subsidio</label>
			    	<input type="radio" name="tipoFinanciamiento" value="2" class="tipoFinanciamiento" id="tipoFinanciamiento2">
			    	<label>Sin subsidio</label>
		    	</div>

		    	<p class="tga-pp">Estado</p>
		    	<select name="estado" class="custom-select custom-select-sm mb-3 tga-mB10 tga-w200" id="estado">
		    		<option value="x">Seleccionar estado</option>
		    		<option value="1">Activo</option>
		    		<option value="0">Desactivo</option>
		    	</select>
		    	<hr class="proyectoHr">
		    	<button type="button" class="btn btn-primary btn-sm" onclick="clicForm();" id="btnProyectos">Insertar</button>
	    	</form>
		</div>
		<script type="text/javascript">
		$(document).ready(function() {
		    var options = {
		        target:        '#oculto',
		        beforeSubmit:  showRequest,
		        success:       showResponse,
		        timeout:       240000
		    };
		    $('#formProyecto').ajaxForm(options);
		});
		function clicForm(){
			if ($("#idInmobiliaria2").val()>'0') {
			    loaderTgaSolutions(1);
			    $("#formProyecto").submit();
			}
		}
		</script>
	</aside>
	<aside class="tga-lado3 lado-c tga-w400">
		<div class="tga-cuadro tga-clear tga-mB20 proyectoMapa"></div>
		<div class="images"></img></div>
	</aside>

</section>
<script type="text/javascript" src="../js/tga_proyectos.js"></script>
<script type="text/javascript">
limpiarProyecto();
$("#idInmobiliaria2").val(0);
$("head").append('<link rel="stylesheet" href="/css/tga_global/proyecto.css" crossorigin="anonymous">');
$("aside.tga-lado3.lado-a .custom-select").val(0);
</script>
