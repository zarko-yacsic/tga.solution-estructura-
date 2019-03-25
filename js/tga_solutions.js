/*
 _                     _____       _       _   _
| |                   /  ___|     | |     | | (_)
| |_ __ _  __ _ ______\ `--.  ___ | |_   _| |_ _  ___  _ __  ___
| __/ _` |/ _` |______|`--. \/ _ \| | | | | __| |/ _ \| '_ \/ __|
| || (_| | (_| |      /\__/ / (_) | | |_| | |_| | (_) | | | \__ \
 \__\__, |\__,_|      \____/ \___/|_|\__,_|\__|_|\___/|_| |_|___/
     __/ |
    |___/
*/
var tgaSolution = {};

tgaSolution.LoaderTga           = 0;
tgaSolution.LoaderTgaTime       = "vacio";
tgaSolution.LoaderTgaTime2      = "vacio";
tgaSolution.LoaderTgaTime3      = "vacio";
tgaSolution.Load                = "vacio";
tgaSolution.LoadLoaderTime      = "vacio";
tgaSolution.LoadCualquiera      = "vacio";
tgaSolution.LoadForm            = 0;
tgaSolution.LoadLoader          = 0;
tgaSolution.modal               = 0;
tgaSolution.modalTitulo         = "";
tgaSolution.modalTamanio        = 0;
tgaSolution.LoadDiv             = "";
tgaSolution.url                 = "";
tgaSolution.data                = "";
// --------------------------------------

function cargar(){
	$( ".tgaPreCarga .images" ).animate({
		width: "280px",
		opacity: 0.6
	},300, function() {
		$( ".tgaPreCarga" ).animate({
			opacity: 0
		},200, function() {
			$(".tgaPreCarga").remove();
		});
	});
}

// --------------------------------------
function loaderTgaSolutionRetifica(){
	if ( $(".cargadorLoader").css("display") == "table" && (tgaSolution.LoadLoader===1 || tgaSolution.LoadForm===1) ) {
		if (tgaSolution.LoadLoaderTime!="vacio") {
			clearTimeout(tgaSolution.LoadLoaderTime);
		}
		tgaSolution.LoadLoaderTime = setTimeout("loaderTgaSolutionRetifica();",1500);
	}else if ( $(".cargadorLoader").css("display") == "table" && (tgaSolution.LoadLoader===0 || tgaSolution.LoadForm===0) ) {
		loaderTgaSolutions(0);
	}
}

function loaderTgaSolutions(val){
	if (val === 1 && $(".cargadorLoader").css("opacity")>0 && $(".cargadorLoader").css("display")!="none") {val = 2;}
	if (val === 1 && $(".cargadorLoader").css("opacity")===0 && $(".cargadorLoader").css("display")=="none") {tgaSolution.LoaderTga = 0;}
	if (val === 0 && $(".cargadorLoader").css("opacity")===0 && $(".cargadorLoader").css("display")=="none") {val = 2;}

	if (val === 1 && tgaSolution.LoaderTga === 0) {

		if (tgaSolution.LoadLoaderTime!="vacio") {
			clearTimeout(tgaSolution.LoadLoaderTime);
		}
		tgaSolution.LoadLoaderTime = setTimeout("loaderTgaSolutionRetifica();",1500);

		tgaSolution.LoaderTga = 1;
		$(".cargadorLoader").css("opacity",0);
		$(".cargadorLoader").css("display","table");

		$( ".cargadorLoader-2" ).animate({
			opacity: 0.4
		}, 320, function() {
			$( ".cargadorLoader-1" ).animate({ "opacity": 1 }, 200 );
		});
	}else if (val === 0 && tgaSolution.LoaderTga === 1) {
		$( ".cargadorLoader" ).animate({
			opacity: 0
		}, 420, function() {
			$(".cargadorLoader").css("display","none");
			tgaSolution.LoaderTga         = 0;
		});
	}else if (val!==2){
		if (tgaSolution.LoaderTgaTime!="vacio") {
			clearTimeout(tgaSolution.LoaderTgaTime);
		}
		tgaSolution.LoaderTgaTime = setTimeout("loaderTgaSolutions(0)",100);
	}
}
// -------------------------------------- load
function loadTgaSolLoad(){
	if ( tgaSolution.modal===1 ) {
		var tamanio = "modal-dialog";
		if (tgaSolution.modalTamanio === 1) {
			tamanio = "modal-dialog modal-lg";
		}else if(tgaSolution.modalTamanio === 2) {
			tamanio = "modal-dialog modal-xl";
		}else if(tgaSolution.modalTamanio === 3) {
			tamanio = "modal-dialog modal-sm";
		}
		$("#tgaSleModal2 .modal-dialog").attr('class', tamanio);
		$("#tgaSleModal2 .modal-header h5").html(tgaSolution.modalTitulo);
		$("#tgaSleModal2 .modal-content .modal-body").html(tgaSolution.data);
		$('#tgaSleModal2').modal('show');
		loaderTgaSolutions(0);
	}else{
		loaderTgaSolutions(0);
		$("#" + tgaSolution.LoadDiv).html(tgaSolution.data);
		$("#" + tgaSolution.LoadDiv).animate({ "opacity": 1 },300 );

	}
}

$.fn.loadTgaSol = function(options){
	var opts = $.extend({}, $.fn.loadTgaSol.defaults, options);
	this.each(function(){

		if (tgaSolution.LoaderTga === 0) {

			$("#" + opts.salida).css("opacity",0);

			parent.parent.loaderTgaSolutions(1);
			tgaSolution.LoadLoader = 1;
			tgaSolution.Load = $.post( tgaSolution.url + opts.url , {  idUser:              opts.idUser,
															   idInmobiliaria:              opts.idInmobiliaria,
																   idProyecto:              opts.idProyecto,
																    proyectos:              opts.proyectos,
																       var1  :              opts.valor1,
																       var2  :              opts.valor2,
																       var3  :              opts.valor3,
																       var4  :              opts.valor4,
																       var5  :              opts.valor5,
																       var6  :              opts.valor6,
																       var7  :              opts.valor7,
																       var8  :              opts.valor8,
																       var9  :              opts.valor9,
																       var10 :              opts.valor10,
																       var11 :              opts.valor11,
																       var12 :              opts.valor12,
																       var13 :              opts.valor13,
																       var14 :              opts.valor14,
																       var15 :              opts.valor15,
																       var16 :              opts.valor16,
																       var17 :              opts.valor17,
																       var18 :              opts.valor18,
																       var19 :              opts.valor19,
																       var20 :              opts.valor20,
																       var21 :              opts.valor21,
																       var22 :              opts.valor22,
																       var23 :              opts.valor23,
																       var24 :              opts.valor24,
																       var25 :              opts.valor25,
																       var26 :              opts.valor26,
																       var27 :              opts.valor27,
																       var28 :              opts.valor28,
																       var29 :              opts.valor29},function() {
			}).done(function(data) {

				tgaSolution.data = data;
				if (tgaSolution.LoaderTgaTime2!="vacio") {
					clearTimeout(tgaSolution.LoaderTgaTime2);
				}
				tgaSolution.LoadDiv        = opts.salida;
				tgaSolution.modal          = opts.modal;
				tgaSolution.modalTitulo    = opts.modalTitulo;
				tgaSolution.modalTamanio   = opts.modalTamanio
				tgaSolution.LoaderTgaTime2 = setTimeout("loadTgaSolLoad();",300);

			}).fail(function() {
				parent.parent.loaderTgaSolutions(0);
				alert("error");
			}).always(function() {
				tgaSolution.LoadLoader = 0;
			});
		}
		// -------------
	});
};

$.fn.loadTgaSol.defaults = {
    url:              "home/demo",
    salida:           "oculto",
    modal:            0,
    modalTitulo:      "Ejemplo salida modal",
    modalTamanio:     0,
    idUser:           null,
    idInmobiliaria:   null,
    idProyecto:       null,
    proyectos:        null,
    valor1:           null,
    valor2:           null,
    valor3:           null,
    valor4:           null,
    valor5:           null,
    valor6:           null,
    valor7:           null,
    valor8:           null,
    valor9:           null,
    valor10:          null,
    valor11:          null,
    valor12:          null,
    valor13:          null,
    valor14:          null,
    valor15:          null,
    valor16:          null,
    valor17:          null,
    valor18:          null,
    valor19:          null,
    valor20:          null,
    valor21:          null,
    valor22:          null,
    valor23:          null,
    valor24:          null,
    valor25:          null,
    valor26:          null,
    valor27:          null,
    valor28:          null,
    valor29:          null
};

// --------------------------------------
function showRequest(formData, jqForm, options) {
	tgaSolution.LoadForm = 1;
    var queryString      = $.param(formData);
    return true;
}

function showResponse(responseText, statusText, xhr, $form)  {
	tgaSolution.LoadForm = 0;
	if (tgaSolution.LoaderTgaTime3!="vacio") {
		clearTimeout(tgaSolution.LoaderTgaTime3);
	}
	tgaSolution.LoaderTgaTime3 = setTimeout("loaderTgaSolutions(0)",450);
}

$( document ).ajaxError(function() {
	if (tgaSolution.LoadForm===1) {
		tgaSolution.LoadForm = 0;
		mensajesTgaSolutions(3,"ERROR AL CARGAR","Lo sentimos ocurrio un error, Puede volver a realizar la operación y si el problema persiste contacte con soporte.");
		loaderTgaSolutions(0);
	}
});

// -------------------------------------- mensajes modal
function mensajesTgaSolutions(icon,titulo,texto){
	var icono = new Array("marca-ok.png","bidon-de-basura.png","correo-abierto.png","peligro.png","dispositivo-de-guardar-disquete.png");

	$(".tgaSleModalC, #tgaSleModal").animate({
		opacity: 0
	},200, function() {
		$("#tgaSleModal").css("display","table");
		$(".tgaSleModalC").css("display","table");
		$("#tgaSleModal").css("marginTop","-120px");

		$("#tgaSleModal h5").html(titulo);
		$("#tgaSleModal .icono").html('<img src="/images/' + icono[icon] + '">');
		$("#tgaSleModal .texto").html(texto);

		$("#tgaSleModal").attr('class', 'tga-msn tga-msn-'+icon);

		$(".tgaSleModalC").animate({
			opacity: 0.6
		}, 400, function() {
			$( "#tgaSleModal" ).animate({
				"opacity": 1,
				"marginTop": "0px"
			},200);
		});
		// ---------------------
	});
}

function mensajesTgaSolutions_salir(){
	$(".tgaSleModalC, #tgaSleModal").animate({
		opacity: 0
	}, 200, function() {
		$("#tgaSleModal, .tgaSleModalC").css("display","none");
	});
}



