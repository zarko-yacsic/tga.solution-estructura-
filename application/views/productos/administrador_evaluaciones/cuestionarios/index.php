<h2>Cuestionarios</h2>

<article class="tga-section-filter">
	<ul>
		<li>
			<div class="tga-cuadro tga-w150 tga-noclear tga-left tga-mR20 tga-mB10 selectTipoC">
				<p class="tga-pp">Tipo de custionario</p>
				<select class="custom-select custom-select-sm mb-3" onchange="admin_carga_empresa();">
					<option value="0">Seleccione</option>
					<?php
					$query = $dbData->query("SELECT idTipoC, tipo
												 FROM data_tipo_cuestionario
												WHERE estado = 1
											 ORDER BY tipo ASC;");
					if ($query->num_rows() > 0){
					    $row = $query->row();

					    for ($i=1;$i<=$query->num_rows();$i++) {
					    	?>
					    	<option value="<?php print($row->idTipoC);?>"><?php print($row->tipo);?></option>
					    	<?php
					    	$row = $query->next_row();
					    }
					}else{
					    exit;
					}
					?>
				</select>
			</div>
			<div class="tga-cuadro tga-w150 tga-noclear tga-left tga-mR30 tga-mB10 selectPais">
				<p class="tga-pp">País</p>
				<select class="custom-select custom-select-sm mb-3" onchange="admin_carga_empresa();">
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
					    	<option value="<?php print($row->idPais);?>"><?php print($row->pais);?></option>
					    	<?php
					    	$row = $query->next_row();
					    }
					}else{
					    exit;
					}
					?>
				</select>
			</div>
			<div class="tga-cuadro tga-w250 tga-noclear tga-left tga-mR20 tga-mB10 selectEmpresa">
				<p class="tga-pp">Inmobiliaria</p>
				<select class="custom-select custom-select-sm mb-3" onchange="admin_carga_proyecto();">
					<option value="0">Seleccione</option>
				</select>
			</div>
			<div class="tga-cuadro tga-w250 tga-noclear tga-left tga-mR20 tga-mB10 selectProyecto">
				<p class="tga-pp">Proyecto</p>
				<select class="custom-select custom-select-sm mb-3" onchange="carga_evaluacion();">
					<option value="0">Seleccione</option>
				</select>
			</div>
		</li>
		<li>
			<div class="tga-cuadro tga-w600 tga-noclear tga-left tga-mR20 tga-mB10 selectEvaluacion">
				<p class="tga-pp">Evaluación</p>
				<select class="custom-select custom-select-sm mb-3" onchange="admin_seleccionar_evaluacion();">
					<option value="0">Seleccione</option>
				</select>
			</div>
		</li>
	</ul>
</article>

<article class="section-pasos">
	<ul>
		<li id="sPas-1" class="normal" onclick="contenidoSubInMenu(1);">
			P-1: <span>Subir archivo</span>
		</li>
		<li id="sPas-2" class="normal" onclick="contenidoSubInMenu(2);">
			P-2: <span>Selección preguntas</span>
		</li>
		<li id="sPas-3" class="normal" onclick="contenidoSubInMenu(3);">
			P-3: <span>Proceso</span>
		</li>
		<li id="sPas-4" class="normal" onclick="contenidoSubInMenu(4);">
			P-4: <span>Agrupar categorias</span>
		</li>
		<li id="sPas-5" class="normal" onclick="contenidoSubInMenu(5);">
			P-5: <span>Estructura del cuestionario</span>
		</li>
		<li id="sPas-6" class="normal" onclick="contenidoSubInMenu(6);">
			P-6: <span>Conectar preguntas</span>
		</li>
		<li id="sPas-7" class="normal" onclick="contenidoSubInMenu(7);">
			P-7: <span>Comparar cuestionario</span>
		</li>
		<li id="sPas-8" class="normal" onclick="contenidoSubInMenu(8);">
			P-8: <span>Encuesta Ok</span>
		</li>
	</ul>
	<p class="estadoMini">Estado: []</p>
</article>

<article id="contenidoSubIn" class="contenidoSubIn">

</article>

<script type="text/javascript">
function contenidoSubInMenu(id){
	$(".tga-contenido .section-pasos ul .normal").attr('class', 'normal');
	$(".tga-contenido .section-pasos ul #sPas-"+id).attr('class', 'normal active');
	$("body").loadTgaSol({url:   'productos/Administrar_evaluaciones/p' + id,
					   salida:   "contenidoSubIn",
			   idInmobiliaria:	 $(".tga-contenido .selectEmpresa .custom-select").val(),
				   idProyecto:   $(".tga-contenido .selectProyecto .custom-select").val(),
					   valor1:   $(".tga-contenido .selectTipoC .custom-select").val(),
					   valor2:   $(".tga-contenido .selectPais .custom-select").val(),
					   valor3:   $(".tga-contenido .selectEvaluacion .custom-select").val()
						  });
}

function admin_seleccionar_evaluacion(){
	bloquearDesde_evaluaciones();
	if ($(".tga-contenido .selectEvaluacion .custom-select").val()>0) {
		$("body").loadTgaSol({url: 'productos/administrar_evaluaciones/carga_cuestionario',
		                   valor1: $(".tga-contenido .selectTipoC .custom-select").val(),
		                   valor2: $(".tga-contenido .selectPais .custom-select").val(),
		                   valor3: $(".tga-contenido .selectEmpresa .custom-select").val(),
		                   valor4: $(".tga-contenido .selectProyecto .custom-select").val(),
		                   valor5: $(".tga-contenido .selectEvaluacion .custom-select").val()});
	}
}

tgaSolution_admin_eva.donde = 1;
bloquearTodo();
</script>
