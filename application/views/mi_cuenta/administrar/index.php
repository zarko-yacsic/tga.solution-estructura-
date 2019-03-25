<section class="contenido contenido-admin" id="contenido">
	<h1>Administrar cuentas</h1>
	<hr class="h1">
	<aside class="lado lado-a">
		<h5>Lista de usuarios</h5>
		<select class="custom-select custom-select-sm mb-3" id="seleccionarLista" onChange="seleccionarLista(0);">
			<option value="0">Mostrar todo</option>
			<option value="1">Vista</option>
			<option value="2">Adminstrador</option>
			<option value="3">Super admin</option>
		</select>

		<article class="listas">
			<?php
	        $data['opcion']     = 0;
	        $data['lista']      = 0;
	        $this->load->view('mi_cuenta/administrar/user_list',$data);
			?>
		</article>
	</aside>
	<aside class="lado lado-b">
		<div class="new-user" onclick="nuevoUsuario();">
			<div class="icone">
				<img src="/images/person_add_black_18dp.png" width="22px">
			</div>
			<div class="texto">Nuevo usuario</div>
		</div>
		<article class="mini-menu">
			<ul>
				<li id="m1" class="normal active" onclick="tgaSolutionIN.menu = 1; menuAdminCuentaF();">Perfil</li>
				<li id="m2" class="normal" onclick="tgaSolutionIN.menu = 2; menuAdminCuentaF();">Permisos</li>
				<li id="m3" class="normal" onclick="tgaSolutionIN.menu = 3; menuAdminCuentaF();">Historial</li>
			</ul>
		</article>
		<article id="contenidoIn" class="contenido-in"></article>
	</aside>
</section>
<script type="text/javascript">
var tgaSolutionIN = {};

tgaSolutionIN.menu    = 1;
tgaSolutionIN.id      = 0;

function menuAdminCuentaF(){
	$(".mini-menu .normal").attr('class', 'normal');
	$(".mini-menu #m" + tgaSolutionIN.menu).attr('class', 'normal active');

	var url = "";
	if (tgaSolutionIN.menu===1) {
		url = "perfil";
	}else if (tgaSolutionIN.menu===2) {
		url = "permisos";
	}else if (tgaSolutionIN.menu===3) {
		url = "historia";
	}

	$("body").loadTgaSol({url: 'mi_cuenta/Administrar_usuarios/'+url,
                       salida: "contenido .lado-b #contenidoIn",
                   	   valor1: tgaSolutionIN.menu,
                   	   valor2: tgaSolutionIN.id});

}

function nuevoUsuario(){
	$("body").loadTgaSol({modal:1,
	                modalTitulo:"Nuevo usuario",
	                        url: 'mi_cuenta/administrar_usuarios/user_new'
	});
}

function seleccionarLista(lista){
	var opcion = $("#seleccionarLista").val();
	if ($.isNumeric(lista)===false) {lista=0;}

	$("body").loadTgaSol({url: 'mi_cuenta/Administrar_usuarios/user_list/'+lista,
                       salida: "contenido .lado-a .listas",
                   	   valor1: opcion,
                   	   valor2: lista});
}
</script>
