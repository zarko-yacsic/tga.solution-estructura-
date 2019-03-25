function limpiarProyecto(){
	$("#idProyecto").val(0);
	$("aside.tga-lado3.lado-c .proyectoMapa").html('');
	$("aside.tga-lado3.lado-c .images").html('');

	$('#regiones').val(0);
	$('#comunas').val(0);
	$('#zonas').val(0);
	$('#estado').val(0);

	$('#nombreProyecto').val("");
	$('#direccion').val("");
	$('#url').val("");
	$('#latitud').val("");
	$('#longitud').val("");
	$('#imagen').val("");

	$("aside.tga-lado3.lado-b .tipoConstruccion").prop("checked", false);
	$("aside.tga-lado3.lado-b .tipoFinanciamiento").prop("checked", false);
}
function muestraProyecto(idProyecto){
	$('#btnProyectos').text('Actualizar');
	limpiarProyecto();
	$("#idInmobiliaria2").val($("#idInmobiliaria").val());
	$("#proyectoAction").val(1);

	var idInmobiliaria = $("aside.tga-lado3.lado-a .custom-select").val();
	$("#idProyecto").val(idProyecto);
	$("body").loadTgaSol({url: 'tga_global/Proyectos/muestraProyectos',
           	   idInmobiliaria: idInmobiliaria,
           		   idProyecto: idProyecto});
}
