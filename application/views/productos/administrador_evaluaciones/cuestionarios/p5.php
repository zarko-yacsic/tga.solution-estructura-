<h3>P-5: Estructura del cuestionario</h3>
<div class="cuadro cuadroCh cHr slt15">
	<button type="button" class="btn btn-danger btn-sm tga-displayNone" onclick="contenidoSubInMenu(6);">Siguiente paso</button>
</div>

<article class="p-5 p-5-1">
</article>
<button type="button" class="btn btn-warning btn-sm" onclick="buscarRepetidos1();">Buscar repetidos en preguntas</button>
<hr>

<script type="text/javascript">
function buscarRepetidos1(){
	$("body").loadTgaSol({url: 'productos/Administrar_evaluaciones_p5/lista_repetidos_a',
	                   salida: "contenidoSubIn .p-5.p-5-1",
			  idInmobiliaria: $(".selectEmpresa .custom-select").val(),
	              idProyecto: $(".selectProyecto .custom-select").val(),
	                  valor1: $(".selectTipoC .custom-select").val(),
	                  valor2: $(".selectPais .custom-select").val(),
	                  valor3: $(".selectEvaluacion .custom-select").val(),
	                  valor4: $("#sel_id_categoria").val()});

}
</script>
