<section class="tga-contenido contenido-sub-menu contenido-admin-e">
	<h1>Administrar evaluaciones</h1>
	<hr class="h1">
	<article class="sub-menu">
		<ul>
			<li id="sbm-4" class="subMenu" onclick="contenidoSubMenu(4,3);">A. Evaluaciones</li>
			<li id="sbm-1" class="subMenu" onclick="contenidoSubMenu(1,0);">B. Cuestionarios</li>
			<li id="sbm-2" class="subMenu" onclick="contenidoSubMenu(2,1);">C. BDD</li>
			<li id="sbm-3" class="subMenu" onclick="contenidoSubMenu(3,2);">D. Respuestas</li>
			<li id="sbm-10" class="subMenu" onclick="contenidoSubMenu(10,9);">E. Benchmark</li>

			<li id="sbm-5" class="subMenu" onclick="contenidoSubMenu(5,4);">1 - Categorias</li>
			<li id="sbm-6" class="subMenu" onclick="contenidoSubMenu(6,5);">2 - Est. Respuestas</li>
			<li id="sbm-8" class="subMenu" onclick="contenidoSubMenu(8,7);">3 - Diseño de preguntas</li>
			<li id="sbm-7" class="subMenu" onclick="contenidoSubMenu(7,6);">4 - Preguntas</li>
			<li id="sbm-9" class="subMenu" onclick="contenidoSubMenu(9,8);">5 - Plantillas</li>
		</ul>
	</article>
</section>

<section id="contenido" class="tga-contenido">

</section>

<script src="/js/productos/administrar_evaluaciones.js"></script>
<script type="text/javascript">
function contenidoSubMenu(id,num){
	var subMenu = new Array("cuestionarios","bdd","respuestas","evaluaciones","categorias","estructura_respuestas","preguntas","disenio_preguntas","plantillas","benchmark");
	$(".tga-contenido .sub-menu .subMenu").attr('class', 'subMenu');
	$("#sbm-"+id).attr('class', 'subMenu active');
	$("body").loadTgaSol({url: 'productos/Administrar_evaluaciones/' + subMenu[num] ,salida: "contenido"});
}

$("head").append('<link rel="stylesheet" href="/css/productos/administrador_evaluaciones.css" crossorigin="anonymous">');
</script>
