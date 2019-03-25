/*
  _              ____        _       _   _
 | |_ __ _  __ _/ ___|  ___ | |_   _| |_(_) ___  _ __
 | __/ _` |/ _` \___ \ / _ \| | | | | __| |/ _ \| '_ \
 | || (_| | (_| |___) | (_) | | |_| | |_| | (_) | | | |
  \__\__, |\__,_|____/ \___/|_|\__,_|\__|_|\___/|_| |_|
     |___/
*/

var tgaSolution_admin_eva = {};

tgaSolution_admin_eva.donde         = 0;
tgaSolution_admin_eva.cargaMenu     = "vacio";

function bloquearTodo(){

	if (tgaSolution_admin_eva.donde===1) {
		$(".tga-contenido .selectEmpresa .custom-select").val(0);
		$(".tga-contenido .selectProyecto .custom-select").val(0);
		$(".tga-contenido .selectEvaluacion .custom-select").val(0);
		$(".tga-contenido .selectEmpresa .custom-select").prop('disabled', true);
		$(".tga-contenido .selectProyecto .custom-select").prop('disabled', true);
		$(".tga-contenido .selectEvaluacion .custom-select").prop('disabled', true);

		$(".section-pasos").css("display","none");
		$("#contenidoSubIn").css("display","none");
	}else if (tgaSolution_admin_eva.donde===2) {
		$(".tga-contenido .selectEmpresa .custom-select").val(0);
		$(".tga-contenido .selectProyecto .custom-select").val(0);
		$(".tga-contenido .selectEvaluacion .custom-select").val(0);
		$(".tga-contenido .selectEmpresa .custom-select").prop('disabled', true);
		$(".tga-contenido .selectProyecto .custom-select").prop('disabled', true);
		$(".tga-contenido .selectEvaluacion .custom-select").prop('disabled', true);

		$(".bddVer").prop('disabled', true);
		$(".bddSubida .custom-file-input").prop('disabled', true);
	}
}

function bloquearDesde_empresa(){
	if (tgaSolution_admin_eva.donde===1) {
		$(".tga-contenido .selectProyecto .custom-select").val(0);
		$(".tga-contenido .selectEvaluacion .custom-select").val(0);
		$(".tga-contenido .selectProyecto .custom-select").prop('disabled', true);
		$(".tga-contenido .selectEvaluacion .custom-select").prop('disabled', true);

		$(".section-pasos").css("display","none");
		$("#contenidoSubIn").css("display","none");

	}else if (tgaSolution_admin_eva.donde===2) {
		$(".tga-contenido .selectProyecto .custom-select").val(0);
		$(".tga-contenido .selectEvaluacion .custom-select").val(0);
		$(".tga-contenido .selectProyecto .custom-select").prop('disabled', true);
		$(".tga-contenido .selectEvaluacion .custom-select").prop('disabled', true);

		$(".bddVer").prop('disabled', true);
		$(".bddSubida .custom-file-input").prop('disabled', true);
	}
}
function bloquearDesde_proyecto(){
	if (tgaSolution_admin_eva.donde===1) {
		$(".tga-contenido .selectEvaluacion .custom-select").val(0);
		$(".tga-contenido .selectEvaluacion .custom-select").prop('disabled', true);

		$(".section-pasos").css("display","none");
		$("#contenidoSubIn").css("display","none");

	}else if (tgaSolution_admin_eva.donde===2) {
		$(".tga-contenido .selectEvaluacion .custom-select").val(0);
		$(".tga-contenido .selectEvaluacion .custom-select").prop('disabled', true);

		$(".bddVer").prop('disabled', true);
		$(".bddSubida .custom-file-input").prop('disabled', true);
	}
}
function bloquearDesde_evaluaciones(){
	if (tgaSolution_admin_eva.donde===1) {
		$(".section-pasos").css("display","none");
		$("#contenidoSubIn").css("display","none");

	}else if (tgaSolution_admin_eva.donde===2) {
		$(".bddVer").prop('disabled', true);
		$(".bddSubida .custom-file-input").prop('disabled', true);
	}
}

// -----------------------------------------------------------------------------

function admin_carga_empresa(){
	bloquearTodo();
	if ($(".tga-contenido .selectTipoC .custom-select").val()>0 &&  $(".tga-contenido .selectPais .custom-select").val()>0) {
		$("body").loadTgaSol({url: 'productos/administrar_evaluaciones/carga_empresa',
		                   valor1: $(".tga-contenido .selectPais .custom-select").val()});
	}
}
function admin_carga_proyecto(){
	bloquearDesde_empresa();
	if ($(".tga-contenido .selectEmpresa .custom-select").val()>0) {
		$("body").loadTgaSol({url: 'productos/administrar_evaluaciones/carga_proyecto',
		                   valor1: $(".tga-contenido .selectEmpresa .custom-select").val()});
	}
}
function carga_evaluacion(){
	bloquearDesde_proyecto();
	if ($(".tga-contenido .selectProyecto .custom-select").val()>0) {
		$("body").loadTgaSol({url: 'productos/administrar_evaluaciones/carga_evaluacion',
		                   valor1: $(".tga-contenido .selectTipoC .custom-select").val(),
		                   valor2: $(".tga-contenido .selectPais .custom-select").val(),
		                   valor3: $(".tga-contenido .selectEmpresa .custom-select").val(),
		                   valor4: $(".tga-contenido .selectProyecto .custom-select").val()});
	}
}





