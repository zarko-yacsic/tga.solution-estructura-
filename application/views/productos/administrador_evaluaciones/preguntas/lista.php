<?php
	$query = $dbData->query("SELECT idPregunta, titulo, tipo, codigo, estado
							   FROM data_pregunta
							  WHERE idTipoC     = $idTipoC
								AND idCategoria = $idCategoria
								AND tipo        < 3
						   ORDER BY estado DESC, codigoNum ASC;");
	if ($query->num_rows() > 0){
	    $row = $query->row();
	    for ($i=1; $i<=$query->num_rows();$i++) {
	    	$idPregunta   = $row->idPregunta;
	    	$titulo       = $row->titulo;
	    	$tipo         = $row->tipo;
	    	$codigo       = $row->codigo;
	    	$estado       = $row->estado;

		    if ($tipo==1) {
		    	?>
				<div class="pregunta pregunta-normal">
					<div class="codigo" onclick="cargarFicha(<?php echo $idPregunta.','.$tipo;?>);"><?php echo $codigo;?></div>
					<div class="texto"><?php echo $titulo;?></div>
				</div>
		    	<?php
		    }else{
		    	?>
				<div class="pregunta pregunta-padre">
					<div class="codigo" onclick="cargarFicha(<?php echo $idPregunta.','.$tipo;?>);"><?php echo $codigo;?></div>
					<div class="texto"><?php echo $titulo;?></div>
					<div class="mas" onclick="crearsubPregunta(<?php echo $idPregunta;?>)">+</div>
				</div>
		    	<?php
				$query2 = $dbData->query("SELECT idPregunta, titulo, tipo, codigo, estado
										    FROM data_pregunta
										   WHERE idTipoC        = $idTipoC
										     AND idCategoria    = $idCategoria
										     AND idPadre        = $idPregunta
										     AND tipo           = 3
										ORDER BY estado DESC, codigoNumSub ASC;");
				if ($query2->num_rows() > 0){
				    $row2 = $query2->row();
				    for ($j=1; $j<=$query2->num_rows();$j++) {

				    	$idPregunta2   = $row2->idPregunta;
				    	$titulo2       = $row2->titulo;
				    	$tipo2         = $row2->tipo;
				    	$codigo2       = $row2->codigo;
				    	$estado2       = $row2->estado;

		    			$clic     = ($estado==1) ? ' onclick="cargarFicha('.$idPregunta2.','.$tipo2.');"' : '';
		    			$inactivo = ($estado==0) ? ' inactivo' : '';
				    	?>
						<div class="pregunta pregunta-hijo<?php echo $inactivo; ?>">
							<div class="codigo"<?php echo $clic;?>><?php echo $codigo2;?></div>
							<div class="texto"><?php echo $titulo2;?></div>
						</div>
				    	<?php
				    	$row2 = $query2->next_row();
				    }
				}
				# -------------
		    }

		    $row = $query->next_row();
	    }
	}
?>
