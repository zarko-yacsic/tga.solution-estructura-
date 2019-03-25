<h2>Evaluaciones</h2>

<article class="tga-section-filter">
	<ul>
		<li>
			<div class="tga-cuadro tga-w150 tga-noclear tga-left tga-mR20 tga-mB10">
				<p class="tga-pp">Tipo de custionario</p>
				<select class="custom-select custom-select-sm mb-3">
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
			<div class="tga-cuadro tga-w150 tga-noclear tga-left tga-mR20 tga-mB10">
				<p class="tga-pp">País</p>
				<select class="custom-select custom-select-sm mb-3">
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
			<div class="tga-cuadro tga-w250 tga-noclear tga-left tga-mR20 tga-mB10">
				<p class="tga-pp">Inmobiliaria</p>
				<select class="custom-select custom-select-sm mb-3">
					<option value="0">Seleccione</option>
				</select>
			</div>
			<div class="tga-cuadro tga-w250 tga-noclear tga-left tga-mR20 tga-mB10">
				<p class="tga-pp">Proyecto</p>
				<select class="custom-select custom-select-sm mb-3">
					<option value="0">Seleccione</option>
				</select>
			</div>
		</li>
		<li class="btn-finale">
			<button type="button" class="btn btn-dark btn-sm">Nueva evaluación</button>
		</li>
	</ul>
</article>

<article id="contenidoSubIn" class="tga-contenido-In evaluacionesTabla">
	<table class="tablaResumen" width="1000" cellspacing="0" cellpadding="0" border="0">
		<thead>
			<tr>
				<th class="a1">Nombre evaluación</th>
				<th class="a2">Fecha incio campo</th>
				<th class="a3">Fecha fin campo</th>
				<th class="a4">Fecha evaluación</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="a1">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</td>
				<td class="a2">10-09-2018</td>
				<td class="a3">12-11-2018</td>
				<td class="a4">15-12-2018</td>
			</tr>
			<tr>
				<td class="a1">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</td>
				<td class="a2">10-09-2018</td>
				<td class="a3">12-11-2018</td>
				<td class="a4">15-12-2018</td>
			</tr>
			<tr>
				<td class="a1">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</td>
				<td class="a2">10-09-2018</td>
				<td class="a3">12-11-2018</td>
				<td class="a4">15-12-2018</td>
			</tr>
			<tr class="desactivado">
				<td class="a1">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</td>
				<td class="a2">10-09-2018</td>
				<td class="a3">12-11-2018</td>
				<td class="a4">15-12-2018</td>
			</tr>
			<tr class="desactivado">
				<td class="a1">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</td>
				<td class="a2">10-09-2018</td>
				<td class="a3">12-11-2018</td>
				<td class="a4">15-12-2018</td>
			</tr>
		</tbody>
	</table>

	<?php
	$total        = 100;
	$uri          = 4;
	$porPagina    = 10;
	$mostrarNum   = 3;
	$this->Tgasolutions->paginacion($total,$uri,$porPagina,$mostrarNum,'seleccionarLista');
	?>
</article>
