<?php

$filtro = ($tipoR==0) ? '' : "AND tipoRespuesta = $tipoR";

$query = $dbData->query("SELECT idRespuestaEstructura, nombre, estado, `global`
                           FROM data_respuesta_estructura
                          WHERE idTipoC       = $idTipoC
                            AND ((`global`    = 1)
                             or (idCategoria = $idCategoria $filtro))
                            ORDER BY estado DESC, `global` DESC, nombre ASC;");
if ($query->num_rows() > 0){
    $row = $query->row();
    for ($i=1;$i<=$query->num_rows();$i++) {
    	$valor = '';
    	if ($row->estado==0) {
    		$valor = ' desactivado';
    	}else if ($row->estado==2) {
    		$valor = ' nuevo';
    	}
        $global = ($row->global==1) ? "<span>(global)</span>" : '';
    	?>
	    <li class="normal<?php echo $valor;?> global" onClick="estructuraRespuesta(<?php echo $row->idRespuestaEstructura;?>);">
	        <div class="texto"><?php echo $row->nombre.$global;?></div>
	    </li>
    	<?php
    	$row = $query->next_row();
    }
}

?>
