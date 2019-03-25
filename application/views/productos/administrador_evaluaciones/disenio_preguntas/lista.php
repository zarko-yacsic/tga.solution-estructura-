<ul class="tga-arbol">
    <?php
	$query = $dbData->query("SELECT idPreguntaDisenio
									, tipo
									, idPadre
									, titulo
									, estado
								 FROM data_pregunta_disenio
								WHERE idTipoC       = $idTipoC
								  AND idCategoria   = $idCategoria
								  AND tipo          < 3
							 ORDER BY estado DESC, titulo ASC;");
	if ($query->num_rows() > 0){
	    $row = $query->row();
	    for ($i=1; $i<=$query->num_rows();$i++) {
	    	$estado = ($row->estado==0) ? ' desactivada' : '';
		    if ($row->tipo==1) {
		    	?>
			    <li class="caja tga-seccion normal<?php echo $estado;?>">
			        <div class="codigo">ID-<?php echo $row->idPreguntaDisenio;?></div>
			        <div class="texto" onclick="editar(<?php echo $row->idPreguntaDisenio;?>,'normal');"><?php echo $row->titulo;?></div>
			    </li>
		    	<?php
		    }else{
		    	$idPreguntaDisenio = $row->idPreguntaDisenio;
		    	$clic = ($row->estado==0) ? '' : ' onclick="crearItem('.$row->idPreguntaDisenio.');"';
		    	?>
			    <li class="caja tga-seccion padre<?php echo $estado;?>">
			        <div class="codigo">ID-<?php echo $row->idPreguntaDisenio;?></div>
			        <div class="texto" onclick="editar(<?php echo $row->idPreguntaDisenio;?>,'Padre');"><?php echo $row->titulo;?></div>
			        <div class="padre"<?php echo $clic;?>>+</div>
			    </li>
		    	<?php
				$query2 = $dbData->query("SELECT idPreguntaDisenio, tipo, idPadre, titulo, estado
										    FROM data_pregunta_disenio
										   WHERE idTipoC        = $idTipoC
										     AND idCategoria    = $idCategoria
										     AND tipo           = 3
										     AND idPadre        = $idPreguntaDisenio
										ORDER BY estado DESC, titulo ASC");
				if ($query2->num_rows() > 0){
				    $row2 = $query2->row();
				    for ($j=1; $j<=$query2->num_rows();$j++) {
				    	$estado = ($row2->estado==0) ? ' desactivada' : '';
				    	?>
					    <li class="caja tga-seccion hijo<?php echo $estado;?>">
					        <div class="codigo">ID<?php echo $row2->idPadre;?>-<?php echo $row2->idPreguntaDisenio;?></div>
					        <div class="texto" onclick="editar(<?php echo $row2->idPreguntaDisenio;?>,'item');"><?php echo $row2->titulo;?></div>
					    </li>
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
</ul>

<script type="text/javascript">
function crearItem(valor){
	$("body").loadTgaSol({modal:1,
				   modalTamanio:3,
	                modalTitulo:"Nuevo item",
	                        url: 'productos/disenio_preguntas/nuevo_item',
			             valor1: $(".estructura_respuestas-1 .tga-box-1 select").val(),
			             valor2: $(".estructura_respuestas-1 .tga-box-2 select").val(),
			             valor3: valor
	});
}

function editar(valor,titulo){
	$("body").loadTgaSol({modal:1,
	                modalTitulo:"Ficha " + titulo,
	                        url: 'productos/disenio_preguntas/editar',
			             valor1: $(".estructura_respuestas-1 .tga-box-1 select").val(),
			             valor2: $(".estructura_respuestas-1 .tga-box-2 select").val(),
			             valor3: valor
	});
}
</script>


