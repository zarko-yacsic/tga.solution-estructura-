<h3>P-6: Conectar preguntas</h3>
<div class="cuadro cuadroCh cHr slt15">
	<button type="button" class="btn btn-danger btn-sm tga-displayNone">Siguiente paso</button>
</div>

<article class="filtro-p6">
	<div class="caja caja-A">
		<ul>
			<li>
				<input type="radio" value="0" name="filtro-p6">
				<label>Todo</label>
			</li>
			<li>
				<input type="radio" value="1" name="filtro-p6">
				<label>No conectadas</label>
			</li>
			<li>
				<input type="radio" value="2" name="filtro-p6">
				<label>Conectadas</label>
			</li>
			<li>
				<input type="radio" value="3" name="filtro-p6">
				<label>Preguntas</label>
			</li>
			<li>
				<input type="radio" value="4" name="filtro-p6">
				<label>Item</label>
			</li>
		</ul>
	</div>
	<div class="caja caja-B">
		<button type="button" class="btn btn-warning btn-sm">Procesar</button>
	</div>
</article>

<article class="p-6 estructuraQuest">
	<p>Lista de preguntas</p>
	<?php
	$query = $dbData->query("SELECT a.idCategoria
								    , IF(c.idCategoria IS NULL,b.categoria,c.categoria) as categoria
								 FROM data_estructura a
							LEFT JOIN data_categoria b ON b.idCategoria = a.idCategoria
							LEFT JOIN data_categoria_pais c ON c.idCategoria = a.idCategoria
														   AND c.idTipoC     = $idTipoC
														   AND c.idPais      = $idPais
						        WHERE a.idEvaluacion  =  $idEvaluacion
						          AND a.estado > 0
							 GROUP BY a.idCategoria
							 ORDER BY a.idEstructura ASC;");
	if ($query->num_rows() > 0){
	    $row = $query->row();

	    # Categoria
	    for ($i=1;$i<=$query->num_rows();$i++) {
		    $idCategoria      = $row->idCategoria;
		    $categoria        = $row->categoria;

		    ?>
			<div class="categoria">
				<div class="caja">
					<?php echo $categoria;?>
				</div>
			</div>
			<div class="salto-1"></div>

			<?php
		    # Preguntas
			$query2 = $dbData->query("SELECT idEstructura
										   , nomCampo
										   , num
										   , numPregunta
										   , numSubPregunta
										   , pregunta
										FROM data_estructura
									   WHERE idEvaluacion     = $idEvaluacion
										 AND idPadre          = 0
										 AND numSubPregunta   < 2
										 AND idCategoria      = $idCategoria
									ORDER BY idEstructura ASC;");
			if ($query2->num_rows() > 0){
			    $row2 = $query2->row();
			    for ($x=1;$x<=$query2->num_rows();$x++) {
				    $idEstructura       = $row2->idEstructura;
				    $nomCampo           = $row2->nomCampo;
				    $num                = $row2->num;
				    $numPregunta        = $row2->numPregunta;
				    $numSubPregunta     = $row2->numSubPregunta;
				    $pregunta           = $row2->pregunta;
				    if ($numSubPregunta==0) {
				    	?>
						<div class="pregunta preg">
							<div class="linea"><div class="lineaIn"></div></div>
							<div class="caja caja-1" onclick="remover(<?php echo $idEstructura;?>);" title="<?php echo $pregunta;?>">
								<?php echo $pregunta;?>
							</div>
							<div class="caja caja-2">--</div>
							<div class="caja caja-3" onclick="clicBuscar(<?php echo $numPregunta.','.$numSubPregunta.','.$idCategoria.','.$num.','.$idEstructura; ?>);">
								Buscar
							</div>
						</div>
						<div class="salto-2"></div>
				    	<?php
				    }else{
				    	?>
						<div class="pregunta preg-item active">
							<div class="linea"><div class="lineaIn"></div></div>
							<div class="caja caja-1" title="<?php echo $pregunta;?>">
								<?php echo $pregunta;?>
							</div>
							<div class="caja caja-2 caja-sola"></div>
							<div class="caja caja-3 caja-sola"></div>
						</div>
						<div class="salto-3"></div>
				    	<?php
						$query3 = $dbData->query("SELECT idEstructura
														, nomCampo
														, num
														, numPregunta
														, numSubPregunta
														, pregunta
														, idCategoria
														, item
													 FROM data_estructura
													WHERE idEvaluacion    = $idEvaluacion
													  AND numPregunta     = $numPregunta
													  AND idCategoria     = $idCategoria
											     GROUP BY pregunta, item
												 ORDER BY idEstructura ASC;");

						if ($query3->num_rows() > 0){
							$total = $query3->num_rows();
						    $row3  = $query3->row();
						    for ($y=1;$y<=$query3->num_rows();$y++) {
						    	$item               = $row3->item;
						    	$numSubPregunta     = $row3->numSubPregunta;
						    	$idEstructura       = $row3->idEstructura;

						    	if ($total>$y) {
							    	?>
									<div class="pregunta item">
										<div class="linePre"></div>
										<div class="linea"><div class="lineaIn"></div></div>
										<div class="caja caja-1" onclick="remover(<?php echo $idEstructura;?>);" title="<?php echo $item;?>">
											<?php echo $item;?>
										</div>
										<div class="caja caja-2">--</div>
										<div class="caja caja-3" onclick="clicBuscar(<?php echo $numPregunta.','.$numSubPregunta.','.$idCategoria.','.$num.','.$idEstructura; ?>);">
											Buscar
										</div>
									</div>
									<div class="salto-3"></div>
							    	<?php
						    	}else{
							    	?>
									<div class="pregunta item active">
										<div class="linePre"></div>
										<div class="linea fin"><div class="lineaIn"></div></div>
										<div class="caja caja-1" onclick="remover(<?php echo $idEstructura;?>);" title="<?php echo $item;?>">
											<?php echo $item;?>
										</div>
										<div class="caja caja-2">C1-2_1</div>
										<div class="caja caja-3" onclick="clicBuscar(<?php echo $numPregunta.','.$numSubPregunta.','.$idCategoria.','.$num.','.$idEstructura; ?>);">
											Buscar
										</div>
									</div>
									<div class="salto-1"></div>
							    	<?php
						    	}
						    	$row3 = $query3->next_row();
						    }
						}
				    	# ---------------------------------------
				    }
				    $row2 = $query2->next_row();
			    }
			}
		    $row = $query->next_row();
	    }
	}
	?>

	<div class="categoria categoria-final">
		<div class="caja"></div>
	</div>
</article>
<script type="text/javascript">
function clicBuscar(numPregunta,numSubPregunta,idCategoria,num,idEstructura){
	$("body").loadTgaSol({url: 'productos/Administrar_evaluaciones_p6/caja',
		          modalTitulo:"Vincular preguntas",
						modal:1,
				 modalTamanio:1,
			  idInmobiliaria: $(".selectEmpresa .custom-select").val(),
	              idProyecto: $(".selectProyecto .custom-select").val(),
	                  valor1: $(".selectTipoC .custom-select").val(),
	                  valor2: $(".selectPais .custom-select").val(),
	                  valor3: $(".selectEvaluacion .custom-select").val(),
	                  valor4: numPregunta,
	                  valor5: numSubPregunta,
	                  valor6: idCategoria,
	                  valor7: num,
	                  valor8: idEstructura});
}

function remover(idEstructura){
	$("body").loadTgaSol({modal:1});
}

</script>


