
<?php
$porPagina  = 10;
$mostrarNum = 2;
$uri        = 4;

if ($opcion > 0 && $opcion < 4) {
	$filtro = " = $opcion ";
}else{
	$filtro = " > 0 ";
}

$query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS idUser, nombre, estado
							 FROM tga_user
							WHERE tipoUser $filtro
						 ORDER BY estado DESC, nombre ASC
							LIMIT $lista,$porPagina");

$total = 0;
$query2 = $this->db->query("SELECT FOUND_ROWS() AS total;");
if ($query2->num_rows() > 0){
	$row2  = $query2->row();
	$total = $row2->total;
}

if ($query->num_rows() > 0){
	$row = $query->row();
	?>
	<ul class="list-group list-group-flush">
		<?php
		for ($i=1;$i<=$query->num_rows();$i++) {
			?>
			<li id="listUser-<?php echo $row->idUser;?>" class="list-group-item normal estado-<?php echo $row->estado;?>" onClick="tgaSolutionIN.id=<?php echo $row->idUser;?>;menuAdminCuentaF();"><?php echo $row->nombre;?></li>
			<?php
			$row = $query->next_row();
		}
		?>
	</ul>
	<?php

}

# <li class="list-group-item list-group-item-primary">Cras justo odio</li>
$this->Tgasolutions->paginacion($total,$uri,$porPagina,$mostrarNum,'seleccionarLista');
?>

