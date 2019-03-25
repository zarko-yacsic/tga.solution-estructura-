<article class="listaCont listaCont-new listaCont listaCont-x3">
	<form id="miFor_NewTipoEmpresa" action="<?php print(base_url());?>tga_global/empresas/crear_tipo_empresa" method="post">
		<p class="titulo-form">Nombre Tipo de Empresa</p>
		<input class="form-control form-control-sm" name="tipo" value="" maxlength="50" type="text" placeholder="Nombre tipo empresa" style="width: 85%;float: left;margin-right: 10px;">
		<button type="button" class="btn btn-success btn-sm" onclick="clicFormNewTipoEmpresa();">Crear</button>
		<p class="ayuda-form">Solo caracteres de la A-Z y espacios</p>
		<hr>
	</form>
	<form id="miFor_RmvTipoEmpresa" action="<?php print(base_url());?>tga_global/empresas/inhabilita_tipo" method="post">
		<input type="hidden" name="removeTipoEmpresa" value="">
	<aside id="listaTiposIn" class="lado lado-a" style="width: 100%; border-right: 0px;">
		<?php
        $total        = 0;
        $uri          = 4;
        $porPagina    = 5;
        $mostrarNum   = 2;
        $lista        = 0;

		$query  = $this->db->query("SELECT SQL_CALC_FOUND_ROWS
                                           idTipoEmpresa,
                                           tipo
                                      FROM tga_empresas_tipo
                                     WHERE estado = 1
                                  ORDER BY estado DESC, tipo ASC
                                     LIMIT $lista,$porPagina;");

        $query2 = $this->db->query("SELECT FOUND_ROWS() AS total;");
        if ($query2->num_rows() > 0){
            $row2   = $query2->row();
            $total  = $row2->total;
        }

        if ($query->num_rows() > 0){
            $row = $query->row();
            ?>
            <ul class="lista">
            <?php
            for ($i=1;$i<=$query->num_rows();$i++) {
                $idTipoEmpresa  = $row->idTipoEmpresa;
                $tipo           = $row->tipo;
                $valor          = $idTipoEmpresa.'-'.md5($idTipoEmpresa.'alfonsito');
                ?>
                <li class="num<?php echo $idTipoEmpresa; ?>" style="width: 100%; cursor: context-menu;"><?php echo $tipo;?> <span style="float:right; cursor: pointer; margin-right: 10px;" onclick="removeTipo('<?=$valor;?>')">x</span></li>
                <?php
                $row = $query->next_row();
            }
            ?>
            </ul>
            <?php
            $this->Tgasolutions->paginacion($total,$uri,$porPagina,$mostrarNum,'listaTipos');
        }
		?>
	</aside>
    </form>
	<div id="ocultoTipoEmpresa"></div>
</article>
<script type="text/javascript">
$(document).ready(function() {
    var options = {
        target:        '#oculto',
        beforeSubmit:  showRequest,
        success:       showResponse,
        timeout:       240000
    };
    $('#miFor_NewTipoEmpresa').ajaxForm(options);
    $('#miFor_RmvTipoEmpresa').ajaxForm(options);
});
function clicFormNewTipoEmpresa(){
    loaderTgaSolutions(1);
    $("#miFor_NewTipoEmpresa").submit();
}
function removeTipo(val){
	$('#miFor_RmvTipoEmpresa input[name=removeTipoEmpresa]').val(val);
	loaderTgaSolutions(1);
	$("#miFor_RmvTipoEmpresa").submit();
}
function listaTipos(val){
	$("body").loadTgaSol({url: 'tga_global/empresas/lista_tipo/' + val,
					   salida: 'listaTiposIn'});
}
</script>
