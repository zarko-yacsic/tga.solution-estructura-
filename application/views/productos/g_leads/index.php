<section class="tga-contenido tga-contenido-empresas">
	<h2>G-Leads</h2>
	<article class="tga-section-filter">
		<ul>
			<li>
				<div class="tga-cuadro tga-w300 tga-left tga-noclear tga-mR20">
					<p class="tga-pp">Ver:</p>
					<select class="custom-select custom-select-sm mb-3" onchange="cargarGLeadsVista(this.value);">
						<option value="0">Seleccione</option>
						<option value="1">Inmobiliarias</option>
						<option value="2">Proyectos</option>
						<option value="3">Portales</option>
						<option value="4">G-Quest</option>
						<option value="5">G-Cotizador</option>
						<option value="6">G-Contact</option>
					</select>
				</div>
			</li>
		</ul>
	</article>

	<article id="tga-contenido-In" class="tga-contenido-In">
	</article>

</section>
<script type="text/javascript">
function cargarGLeadsVista(val){
	$("#tga-contenido-In").html("");
	if (val!=0 && val>0) {
		$("body").loadTgaSol({url: 'productos/g_leads/v' + val,
                           salida: "tga-contenido-In"
                              });
	}
}
</script>
