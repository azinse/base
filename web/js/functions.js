//SCRIPT DATEPICKER
function initdatepicker() {
    $(".js-datepicker").prop('readonly', true);
    $(".js-timepicker").prop('readonly', true);
    $(".js-datetimepicker").prop('readonly', true);
    $(".js-datepicker, .js-timepicker, .js-datetimepicker").css({"cursor": "inherit", "background-color": "#fff"});
    //Datepicker
    $('.js-datepicker').datetimepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+100", // last hundred years
        showWeek: true,
        firstDay: 1,
        dateFormat: 'dd-mm-yy',
        showButtonPanel: true,
        showTime: false,
        showTimePicker: false,
        showHour: false,
        showMinute: false,
        showSecond: false,
        showMillisec: false,
        showMicrosec: false,
        showTimezone: false
    }).keyup(function (e) {
        if (e.keyCode == 8 || e.keyCode == 46) {
            $.datepicker._clearDate(this);
        }
    });
    $('.js-datetimepicker').datetimepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+100", // last hundred years
        showWeek: true,
        firstDay: 1,
        dateFormat: 'dd-mm-yy',
        showButtonPanel: true,
        showTimePicker: false,
        timeFormat: 'H:mm:ss',
        showSecond: false,
        showMillisec: false,
        showMicrosec: false,
        showTimezone: false
    }).keyup(function (e) {
        if (e.keyCode == 8 || e.keyCode == 46) {
            $.datepicker._clearDate(this);
        }
    });
    $('.js-timepicker').timepicker({
        showTimePicker: true,
        timeFormat: 'H:mm:ss',
        showSecond: true,
        showMillisec: false,
        showMicrosec: false,
        showTimezone: false,
    });
}
$(document).ready(function () {
    initdatepicker();
});
$(document).ajaxComplete(function (event, request, settings) {
    initdatepicker();
});

//SCRIPT AFFICHAGE MODAL 
//Affichage modal
$(document).ready(function () {
    //Menu 
    $('#sidebar .sub-menu > a, #sidebar .sub > li > a').each(function(){
        if(window.location.pathname == $(this).attr('href')){
            if($(this).closest('.sub').length){
                $(this).closest('li').addClass("active");
            }
            $('#sidebar .sub-menu').each(function(){
                $(this).removeClass("active");
            });
            $(this).closest('.sub-menu').addClass("active");
        }
    });
    //Définir les couleur d'entête des dialog
    $(".modal-header").css({'backgroundColor': $(".navbar-inner").css('backgroundColor'), 'color': $(".navbar-inner").css('color')});
});

//Changement de theme
$(document).on('click', 'span.colors > span', function (event) {
    var theme = $(this).prop("class").replace('color', 'style');
    $.ajax({
        type: "POST",
        url: Routing.generate("send_param"),
        data: {theme: theme},
        dataType: "json",
    }).done(function (json) {
//Définir les couleur d'entête des dialog
        $(".modal-header").css({'backgroundColor': $(".navbar-inner").css('backgroundColor'), 'color': $(".navbar-inner").css('color')});
    });
});
// Jquery draggable
$('.modal').draggable({
    handle: ".modal-header,.modal-footer"
});
/*$('.modal-dialog').resizable({
 alsoResize: ".modal-body,.modal",
 minHeight: 150,
 maxWidth: 680
 });*/

$('.modal').on('show.bs.modal', function () {
    $(this).css({"top": "4%", "max-height": $(window).height() - 100, "max-width": "680px", "left": "10%", "right": "10%", "margin": "0 auto"});
    $(this).find('.modal-body').css({
        "max-height": $(window).height() - 200,
        'overflow-y': 'auto',
    });
    $(this).find('.modal-footer').css({
        "padding": '5px',
    });
});
//SCRIPT ENVOIE EMAIL
$(document).on('click', '#btn-mail-cc-div', function (event) {
    $("#mail-cc-div").toggle();
});
$(document).on('click', '.btn-email', function (event) {
    event.preventDefault();
    var sendTo = $(this).find('.btn-email-param').text();
    if (sendTo !== "") {
        $("#email-form").find("input.tags_input").importTags('');
        $("#email-form").find("input.tags_input").addTag(sendTo);
    }
    var exts = ".gif,.jpg,.jpeg,.png,.bmp,.tif,.txt,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.odt,.ods,.odp,.csv";
    //Nombre de fichiers autorisés
    var maxFiles = 5;
    //Taille maximal de fichier
    var maxFileSize = max_file_size;
    //Url d'action du formulaire
    var action = $("#email-form").prop('action');
    //Configuration
    var dropzoneOptions = {
        url: action,
        autoProcessQueue: false,
        uploadMultiple: true,
        parallelUploads: 10,
        maxFiles: maxFiles,
        maxFilesize: maxFileSize,
        acceptedFiles: '"' + exts + '"',
        addRemoveLinks: true,
        dictDefaultMessage: dictDefaultMessage,
        dictFallbackMessage: dictFallbackMessage,
        dictFallbackText: "",
        dictInvalidFileType: dictInvalidFileType,
        dictFileTooBig: dictFileTooBig,
        dictCancelUpload: dictCancelUpload,
        dictRemoveFile: dictRemoveFile,
        dictMaxFilesExceeded: dictMaxFilesExceeded,
        init: function () {
            dzClosure = this; // Makes sure that 'this' is understood inside the functions below.
            localError = 0;
            // for Dropzone to process the queue (instead of default form behavior):
            document.getElementById("submit-email").addEventListener("click", function (e) {
                if($("#mail-to").val()){
                    // Make sure that the form isn't actually being sent.
                    e.preventDefault();
                    e.stopPropagation();
                    //                CKupdate();
                    if (dzClosure.getUploadingFiles().length === 0 && dzClosure.getQueuedFiles().length === 0) {
                        $.ajax({
                            type: "POST",
                            url: action,
                            data: {from: $("#mail-from").val(), to: $("#mail-to").val(), cc: $("#mail-cc").val(), cci: $("#mail-cci").val(), message: $("#mail-message").val(), objet: $("#mail-objet").val(), copie: $("#mail-copie").val()},
                            dataType: "json",
                        }).done(function (json) {
                            if (json !== "error") {
                                $("#alert-success-email").show().delay(10000).fadeOut();
                                $("#alert-success-email").show(function(){
                                    $(this).attr('tabindex',-1).focus();
                                }).delay(10000).fadeOut();
                                $("#submit-email").hide();
                                $("#email-reload").show();
                            } else {
                                $("#alert-error-email").show(function(){
                                    $(this).attr('tabindex',-1).focus();
                                }).delay(10000).fadeOut();
                            }
                        });
                    }
                    dzClosure.processQueue();
                }else{
                    $("#mail-to").closest(".controls").find(".tagsinput").find('input').css({"border":"1px solid red"});
                    $("#mail-to").closest(".controls").find(".tagsinput").find('input').delay(10000)
                    .queue(function (next) { 
                      $(this).css("border", "none");
                      next(); 
                    });
                }
            });
            //send all the form data along with the files:
            this.on("sendingmultiple", function (data, xhr, formData) {
                if($("#mail-to").val()){
                    formData.append("from", jQuery("#mail-from").val());
                    formData.append("to", jQuery("#mail-to").val());
                    formData.append("cc", jQuery("#mail-cc").val());
                    formData.append("cci", jQuery("#mail-cci").val());
                    formData.append("message", jQuery("#mail-message").val());
                    formData.append("objet", jQuery("#mail-objet").val());
                    formData.append("copie", jQuery("#mail-copie").val());
                }else{
                    $("#mail-to").closest(".controls").find(".tagsinput").find('input').css({"border":"1px solid red"});
                    $("#mail-to").closest(".controls").find(".tagsinput").find('input').delay(10000)
                    .queue(function (next) { 
                      $(this).css("border", "none");
                      next(); 
                    });
                }
            });
            this.on("successmultiple", function (file, response) {

            });
            this.on("errormultiple", function (file, response) {
                $("#alert-error-email").show().delay(10000).fadeOut();
                localError = 1;
            });
            this.on("completemultiple", function (file) {
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0 && localError !== 1) {
                    var _this = this;
                    $("#alert-success-email").show().delay(10000).fadeOut();
                    //_this.removeAllFiles();
                    setTimeout(function () {
                        _this.removeAllFiles();
                    }, 10000);
                    localError = 0;
                    //$('head').append('<style>.link-icon:before{background-repeat:no-repeat !important;}</style>');
                }
            });
        }
    };

    //On affiche le modal
    $("#btn-email-modal").trigger("click");
    $("div#upload-email").addClass("dropzone");
    var myDropzone = new Dropzone("div#upload-email", dropzoneOptions);


    if (sendTo !== "") {
        var obj = new Array();
        obj.push(sendTo);
        $("#email-form").find("input.tags_input").val(sendTo);
    }

});
//UPLOAD FICHIER
var parent_div = "";
$(document).on('click', '.btn-upload', function (event) {
    $("#btn-upload-modal").trigger("click");
    event.preventDefault();
    parent_div = $(this).closest("#list-files");
    //Liste extension
    var image = ".gif,.jpg,.jpeg,.png,.bmp,.tif";
    var texte = ".txt,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.odt,.ods,.odp,.csv";
    var audio = ".wav,.mp3,.fla,.flac,.ra,.rma,.aif,.aiff,.aa,.aac,.aax,.ac3,.au,.ogg,.avr,.3ga,.flac,.mid,.midi,.m4a,.mp4a,.amz,.mka,.asx,.pcm,.m3u,.wma,.xwma";
    var video = ".avi,.mpg,.mp4,.mkv,.mov,.wmv,.vp6,.264,.vid,.rv,.webm,.swf,.h264,.flv,.mk3d,.gifv,.oggv,.3gp,.m4v,.movie,.divx";
    var archive = ".zip,.zipx,.rar,.tar,.gz,.dmg,.iso";
    //Les données à enregistrer en base, récupérées depuis l'id du bouton 
    var ids = $(this).attr('id').split("_");
    //Remplissage formilaire de upload
    if (ids[0] != 0) {
        $("#file_type").val(ids[0]); //type de document (ex: CV)
    }
    $("#file_table").val(ids[1]); //table concernée
    $("#file_table_id").val(ids[2]); //id de la table concernée
    //Si l'id du bouton contient date, on affiche les champs de date
    if ($(this).attr('id').indexOf("date") > -1) {
        $("#file_date_debut,#file_date_butoir").show();
    }
    /********maximum de fichier et extensions**********/
    var exts = "";
    var extensions = $(this).attr('href').split("_");
    for (i = 1; i < extensions.length; i++) {
        exts = exts + extensions[i] + ',';
    }
//On remplace 
    exts = exts.replace("image", image);
    exts = exts.replace("video", video);
    exts = exts.replace("texte", texte);
    exts = exts.replace("zip", archive);
    exts = exts.replace(",,", ',');
    $("#file_extension").val(exts);
    //Nombre de fichiers autorisés
    var maxFiles = 1;
    if (Number.isInteger(parseInt(extensions[0])) && parseInt(extensions[0]) > 1) {
        maxFiles = parseInt(extensions[0]);
    }
//Taille maximal de fichier
    var maxFileSize = max_file_size;
    //Url d'action du formulaire
    var action = $("#upload-form").prop('action');
    //Configuration
    var dropzoneOptions = {
        url: action,
        autoProcessQueue: false,
        uploadMultiple: true,
        parallelUploads: 10,
        maxFiles: maxFiles,
        maxFilesize: maxFileSize,
        acceptedFiles: '"' + exts + '"',
        addRemoveLinks: true,
        dictDefaultMessage: dictDefaultMessage,
        dictFallbackMessage: dictFallbackMessage,
        dictFallbackText: "",
        dictInvalidFileType: dictInvalidFileType,
        dictFileTooBig: dictFileTooBig,
        dictCancelUpload: dictCancelUpload,
        dictRemoveFile: dictRemoveFile,
        dictMaxFilesExceeded: dictMaxFilesExceeded,
        init: function () {
            dzClosure = this; // Makes sure that 'this' is understood inside the functions below.
            // for Dropzone to process the queue (instead of default form behavior):
            localError = 0;
            document.getElementById("submit-all-files").addEventListener("click", function (e) {
                // Make sure that the form isn't actually being sent.
                e.preventDefault();
                e.stopPropagation();
                dzClosure.processQueue();
            });
            //send all the form data along with the files:
            this.on("sendingmultiple", function (data, xhr, formData) {
                formData.append("table", jQuery("#file_table").val());
                formData.append("table_id", jQuery("#file_table_id").val());
                formData.append("type", jQuery("#file_type").val());
                formData.append("date_debut", jQuery("#file_date_debut").val());
                formData.append("date_butoir", jQuery("#file_date_butoir").val());
                formData.append("extension", jQuery("#file_extension").val());
            });
            this.on("successmultiple", function (file, response) {
                if ($("#file_type").val() !== "logo") {
                    for (i = 0; i < response.length; i++) {
                        parent_div.find("tbody.tbody-files").append('<tr class="item-line"><td><a class="link-icon" href="' + response[i]["url"] + '">' + response[i]["file_name"] + '</a></td><td><a style="margin-right: 4px;" href="' + response[i]["url"] + '" class="btn btn-success download-link"><i class="icon-download-alt"></i></a><a style="margin-right: 4px;" href= "' + response[i]["preview_url"] + '" class="btn btn-primary preview-link"><i class="icon-eye-open "></i></a><a id="' + response[i]["id"] + '" href="' + response[i]["delete_url"] + '" class="btn btn-danger delete-link"><i class="icon-trash "></i></a></td></tr>');
                    }

                } else {
                    $("#logo_img").attr('src', response[0]["url"]);
                    if (window.location.href.indexOf("personne") > -1) {
                        $("#small-avatar").attr('src', response[0]["url"]);
                    }
                }
            });
            this.on("errormultiple", function (file, response) {
                $(".alert-error").show().delay(10000).fadeOut();
                localError = 1;
            });
            this.on("completemultiple", function (file) {
                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0 && localError !== 1) {
                    var _this = this;
                    $(".alert-success").show().delay(10000).fadeOut();
                    setTimeout(function () {
                        _this.removeAllFiles();
                    }, 10000);
                    localError = 0;
                    //$('head').append('<style>.link-icon:before{background-repeat:no-repeat !important;}</style>');
                }
            });
        }
    };
    $("div#my-dropzone").addClass("dropzone");
    var myDropzone = new Dropzone("div#my-dropzone", dropzoneOptions);
});
//SCRIPT SUPPRESSION PAR AJAX 
$(document).on('click', '.delete-link', function (e) {
    e.preventDefault();
    url = $(this).attr('href');
    id = $(this).attr('id');
    line = $(this).closest('.item-line');
    $("#delete-confirm").dialog({
        resizable: false,
        modal: true,
        buttons: {
            continuer: function () {
                $(this).dialog("close");
                $.ajax({
                    type: "DELETE",
                    url: url,
                    data: {id: id},
                    dataType: "json",
                })
                        .done(function (json) {
                            var dialog = $("#dialog-message");
                            dialog.find(".alert-success").show();
                            dialog.find(".alert-error").hide();
                            dialog.dialog({
                                modal: true,
                                buttons: {
                                    Ok: function () {
                                        $(this).dialog("close");
                                        line.remove();
                                    }
                                }
                            });
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            var dialog = $("#dialog-message");
                            dialog.find(".alert-success").hide();
                            dialog.find(".alert-error").show();
                            dialog.dialog({
                                modal: true,
                                buttons: {
                                    Ok: function () {
                                        $(this).dialog("close");
                                    }
                                }
                            });
                        });
            },
            annuler: function () {
                $(this).dialog("close");
            }
        }
    });
});
//SCRIPT PREVISUALISATION FICHIER
$(document).on('click', '.preview-link', function (event) {
    $("#btn-preview-modal").trigger("click");
    event.preventDefault();
    //var file_url = $(this).attr('href');
    var file_url = "http://contact.trefleapp.com/public/trefle_nom_prenom.docx";
    var url = 'http://docs.google.com/viewer?url=';
    url += file_url;
    url += '&amp;embedded=true';
    var iframe = '<iframe src="' + url + '" width="100%" height="600" style="border: none;" id="document-preview"></iframe>';
    $('#document-viewer').html(iframe);
});
//SCRIPT TELECHARGEMENT FICHIER
$(document).on('click', '.download-link', function (event) {
    event.preventDefault();
    window.open(this.href, '_blank');
    return false;
});
//SCRIPT DE CHANGEMENT DU PLACEHOLDER DES SELECT-->
$(".search-field input.default").val(select_options);
$(".chzn-single.chzn-default span").html(select_option);
$(".chzn-select-deselect.chzn-default span").html(select_option);
//SCRIPT DE SOUMISSION FORMULAIRE-->
$(document).on('click', '#btn-save,#btn-save-show', function (event) {
    var form = $(this).closest("form");
    if(form[0].checkValidity()) {
        form.submit();
    }else{form.find(':submit').click()}
    event.preventDefault();
});
//CHARGEMENT DE FORMULAIRE-->
$(document).on('click', '.new-action, .edit-action', function (event) {
    event.preventDefault();
    var href = $(this).prop('href');
    var id = $(this).prop("id");
    //le cas particulier du formulaire d'adresse avec google map
    if (href.indexOf("adresse") >= 0) {
        $('.modal').on('show.bs.modal', function () {
            $(this).css({"top": "4%", "max-height": $(window).height() - 100, "max-width": "850px", "left": "10%", "right": "10%", "margin": "0 auto"});
            $(".map_canvas").css({"max-width": "none"});
            $(".pac-container").css({"z-index": "9999"});
        });
    }
//On charge le formulaire spécifique
    $('#form-modal-content').load(href + ' #content-body', function (result) {
//On change le titre du modal
        $("#form-modal-title").html($(this).find("#content-title").html());
        //on cache les boutons du formulaire chargé
        $(this).find(".form-actions").hide();
        //On enregistre dans le bouton form-modal-param les paramètres supplémentaires du formulaire
        $("#form-modal-param").prop("href", id);
        //On enregistre l'url de soumission du formulaire dans les boutons de soumission du modal
        $("#form-modal").find("#save").prop("href", href);
        $("#form-modal").find("#show").prop("href", href);
        //On enlève le bouton submit si ce n'est pas un formulaire
        if (href.indexOf("edit") < 0 && href.indexOf("new") < 0) {
            $("#form-modal").find("#save").hide();
        }
        //On affiche le modal
        $("#btn-form-modal").trigger("click");
    });
});
//SCRIPT SOUMISSION FORMULAIRE AJAX
$(document).on('click', '.form-modal-submit', function (event) {
    event.preventDefault();
    _this = $(this);
    var params = $("#form-modal-param").attr("href").split('-'); //On rcupère les paramètres
    var btn = $(this).attr("id"); //On récupère l'id du bouton de soumission save|show
    var form = $("#form-modal").find('form'); //On récupère le formulaire
    if(form[0].checkValidity()) {
        var obj = {};
    //on rajoute les paramètres au formulair
    for (i = 0; i < params.length; i++) {

        var key = params[i].split('_')[0];
        var value = params[i].split('_')[1];
        obj[key] = value;
    }
    var data = form.serialize() + '&' + $.param(obj);
    var action = $(this).prop("href");
    $.ajax({
        type: form.attr('method'),
        url: action,
        data: data,
        dataType: "json",
    })
            .done(function (json) {
                if (json.statut === "ok" && json.id !== null) {
                    $("#alert-success-form").show(function(){
                        $(this).attr('tabindex',-1).focus();
                    }).delay(10000).fadeOut();
                    _this.hide();
                    $("#reload").show();
//                    if (btn === "show") {
//                        var url = action.replace('new', json.id + '/show');
//                        url = url.replace('edit', 'show');
//                        setTimeout(function () {
//                            $('#form-modal-content').slideUp('fast').fadeOut(function () {
//                                window.location.href = url;
//                            });
//                        }, 1500);
//                    } else {
//                        setTimeout(function () {
//                            $('#form-modal').slideUp('fast').fadeOut(function () {
//                                location.reload();
//                            });
//                        }, 1500);
//                    }

                } else {
                    $("#alert-error-form").show(function(){
                        $(this).attr('tabindex',-1).focus();
                   }).delay(10000).fadeOut();
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                if (textStatus !== "error") {
                    var rep = $(jqXHR.responseText);
                    rep.find(".form-actions").hide();
                    $('#form-modal-content').html(rep.find('#content-body'));

                } else {
                    $("#alert-error-form").show(function(){
                        $(this).attr('tabindex',-1).focus();
                   }).delay(10000).fadeOut();
                }
            });
    }else{
        console.log("invalid");
        form.find("input, select, textarea").each(function(){
            if($(this).prop('required') && !$(this).val()){
                $(this).focus();
                return false;
            }
        });
        //form.find(':submit').trigger("click");
    }
    
});
//function CKupdate(){
//    for ( instance in CKEDITOR.instances )
//        CKEDITOR.instances[instance].updateElement();
//}

//DATATABLE

//$(document).ready( function () {
function loadDataTable() {
// Setup - ajouter un input de recherche à l'entête de la table'
    $('.liste thead tr#filterrow th').each(function () {
        var title = $('.liste thead th').eq($(this).index()).text();
        if (!$(this).hasClass('no-filtre')) {
            $(this).html('<input type="text" onclick="stopPropagation(event);" placeholder=" ' + title + '" />');
        }
    });
    var extensions = {
        "sFilter": "dataTables_filter visible-phone visible-tablet",
    }
// Used when bJQueryUI is false
    $.extend($.fn.dataTableExt.oStdClasses, extensions);
    var table = $('.liste').DataTable(
            {
                //"sScrollY": "380",//définir la hauteur du tableau
                //"bScrollCollapse": true, //scroll sur le corps du tableau
                "bStateSave": true, //sauvegarde l'état du tableau
                /*"scrollX": true,*/
//        "pagingType": "full_numbers",
                responsive: true,
                bFilter: true,
                orderCellsTop: true,
//        "iDisplayLength": 50,
//        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "∞"]],
                'bLengthChange': false,
                'bPaginate': false,
                autoWidth: false,
                buttons: [
                    {extend: 'print', text: 'Imprimer', autoPrint: true, className: 'btn btn-mini btn-primary'},
                    {extend: 'excel', className: 'btn btn-mini btn-primary'},
                    {extend: 'pdf', className: 'btn btn-mini btn-primary'},
                ],
                "sDom": '<"top"Blfi<"clear">>rt<"bottom"ip<"clear">>',
                language: {
                    "url": dataTableLang
                }
            }
    );
//Permet de fixer l'entête de la table
    new $.fn.dataTable.FixedHeader(table, {
        headerOffset: $("#header.navbar-inverse .navbar-inner").outerHeight(),
        footer: true,
        footerOffset: $("#footer").outerHeight(),
    });
// Permet de filtrer par colonne
    $(".liste thead input").on('keyup change', function () {
        table
                .column($(this).parent().index() + ':visible')
                .search(this.value)
                .draw();
    });
    function stopPropagation(evt) {
        if (evt.stopPropagation !== undefined) {
            evt.stopPropagation();
        } else {
            evt.cancelBubble = true;
        }
    }
}
//});
$.fn.modal.Constructor.prototype.enforceFocus = function () {};
//SCRIPT CREATION ADRESSE AVEC GOOGLE MAP
$(document).ready(function () {
    if (document.getElementById("geocomplete") !== null) {
        OnloadFunction();
    }
    if ($(".liste").length) {
        loadDataTable();
    }
});
$(document).ajaxComplete(function (event, xhr, settings) {
    if (settings.url.indexOf("adresse") >= 0) {
        if (document.getElementById("geocomplete") !== null && ajaxLoadGm === 1) {
            loadGeocomplete();
        }
        if (document.getElementById("geocomplete") !== null && ajaxLoadGm === 0) {
            if (!scriptLoaded(googleMapUrl)) {
                $.when(
                        $.getScript(googleMapUrl),
                        $.getScript(webPath + "js/jquery.geocomplete.js")
                        /*$.Deferred(function (deferred) {
                         $(deferred.resolve);
                         })*/
                        ).done(function () {
                    ajaxLoadGm = 1;
                    loadGeocomplete();
                });
            } else {
                loadGeocomplete();
            }
        }
    }
});
function OnloadFunction() {
    if (!scriptLoaded(googleMapUrl)) {
        $.when(
                $.getScript(googleMapUrl),
                $.getScript(webPath + "js/jquery.geocomplete.js")
                ).done(function () {
            loadGeocomplete();
        });
    } else {
        loadGeocomplete();
    }
}
function loadGeocomplete() {
    var geocomplete = $("#geocomplete");
    geocomplete.geocomplete({
        map: ".map_canvas",
        //details: "form",
        //types: ["geocode", "establishment"],
    }).bind("geocode:result", function (event, result) {
        var arrAddress = result.address_components;
        var form = $("#geocomplete").closest("#content-body").find("form");
        form.find("#adresse_adresse").val(result.formatted_address);
        form.find("#adresse_latitude").val(result.geometry.location.lat());
        form.find("#adresse_longitude").val(result.geometry.location.lng());
        // itération sur le composant géocode
        $.each(arrAddress, function (i, address_component) {
            if (address_component.types[0] == "street_number") {
                form.find("#adresse_numero_rue").val(address_component.long_name);
            }
            if (address_component.types[0] == "route") {
                form.find("#adresse_rue").val(address_component.long_name);
            }
            if (address_component.types[0] == "locality") {
                form.find("#adresse_localite").val(address_component.long_name);
            }
            if (address_component.types[0] == "country") {
                form.find("#adresse_pays").val(address_component.long_name);
                var option = form.find("#adresse_code_pays").find('option[value="' + address_component.short_name + '"]');
                option.prop('selected', true);
                form.find("#adresse_code_pays").closest(".controls").find("#adresse_code_pays_chzn").find("span").html(option.text());
            }
            if (address_component.types[0] == "postal_code") {
                form.find("#adresse_code_postal").val(address_component.long_name);
            }
            if (address_component.types[0] == "administrative_area_level_1") {
                form.find("#adresse_region").val(address_component.long_name);
            }
            if (address_component.types[0] == "administrative_area_level_2") {
                form.find("#adresse_departement").val(address_component.long_name);
            }
        });
    });
    geocomplete.trigger("geocode")
}

$(document).on("click", ".find-location", function () {
    var geocomplete = $(this).closest(".content-map").find("#geocomplete");
    geocomplete.trigger("geocode");
});
//SCRIPT TAB PROFILE
$(document).on("click", ".profile-features", function () {
    $(this).closest("ul").find(".profile-features").each(function () {
        $(this).removeClass("active");
    });
    $(this).addClass("active");
});
//$(function() { 
// for bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line
$('a[data-toggle="tab"]').on('shown', function (e) {
    // Enregistrement de la "tab" active
    localStorage.setItem($(this).prop('href').split('#')[1].split('_')[0], $(this).attr('href'));
});

$('.tab-content').each(function () {
    var id = $(this).prop('id');
    var lastTab = localStorage.getItem(id);
    if (lastTab) {
        $('[href="' + lastTab + '"]').tab('show');
        var a = $('[href="' + lastTab + '"]');
        //a.closest("ul").find("li").each(function(){ $(this).removeClass('active'); $(this).find("a").removeClass('active')});
        a.closest('li').addClass("active");
        a.addClass("active");
        $("div" + lastTab).addClass("active");

    } else {
        $('[href="' + id + '_1"]').tab('show');
        var lastTab = id + '_1';
        var a = $('[href="' + id + '_1"]');
        a.closest('li').addClass("active");
        a.addClass("active");
        $("div" + lastTab).addClass("active");
    }

});

//});
//SCRIPT POUR POSITIONNER DES MARKERS SUR GOOGLE MAP-->
/**
 * 
 * @param {type} container
 * @param {type} locations
 * @returns function loadMap
 */
function showMap(container, locations) {
    if (!scriptLoaded(googleMapUrl)) {
        $.when(
                $.getScript(googleMapUrl),
                $.getScript(webPath + "js/jquery.geocomplete.js")
                ).done(function () {
            loadMap(container, locations);
        });
    } else {
        loadMap(container, locations);
    }
}
/**
 * Tester si un script est déjà chargé
 * @param {string} url l'url du script
 * @returns {Boolean}
 */
function scriptLoaded(url) {
    var len = $('script').filter(function () {
        return ($(this).attr('src') == url);
    }).length;
    if (len === 0) {
        return false;
    } else {
        return true;
    }
}
/**
 * 
 * @param html element container
 * @param container l'élement div où afficher la carte
 * @param array locations
 * @param array icons
 * @returns google map
 */
function loadMap(container, locations) {
    // Dfinition des icons(marker)
    var iconURLPrefix = webPath + 'images/icons/map/';

    var icons = [
        iconURLPrefix + 'red-dot.png',
        iconURLPrefix + 'green-dot.png',
        iconURLPrefix + 'blue-dot.png',
        iconURLPrefix + 'orange-dot.png',
        iconURLPrefix + 'purple-dot.png',
        iconURLPrefix + 'pink-dot.png',
        iconURLPrefix + 'yellow-dot.png'
    ]
    var iconsLength = icons.length;
    var map = new google.maps.Map(container, {
        zoom: 3,
        center: new google.maps.LatLng(-37.92, 151.25),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: false,
        streetViewControl: false,
        panControl: false,
        zoomControlOptions: {
            position: google.maps.ControlPosition.LEFT_BOTTOM
        }
    });

    var infowindow = new google.maps.InfoWindow({
        maxWidth: 160
    });

    var markers = new Array();

    var iconCounter = 0;

    // Add the markers and infowindows to the map
    for (var i = 0; i < locations.length; i++) {
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map,
            icon: icons[iconCounter]
        });

        markers.push(marker);

        google.maps.event.addListener(marker, 'click', (function (marker, i) {
            return function () {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
            }
        })(marker, i));

        iconCounter++;
        // We only have a limited number of possible icon colors, so we may have to restart the counter
        if (iconCounter >= iconsLength) {
            iconCounter = 0;
        }
    }
    autoCenter(map, markers);
}
function autoCenter(map, markers) {
    //  Create a new viewpoint bound
    var bounds = new google.maps.LatLngBounds();
    //  Go through each...
    for (var i = 0; i < markers.length; i++) {
        bounds.extend(markers[i].position);
    }
    //  Fit these bounds to the map
    map.fitBounds(bounds);
}
/*
 <div id="map" style="width: 500px; height: 400px;"></div>
 
 <script>
 // Define your locations: HTML content for the info window, latitude, longitude
 var locations = [
 ['<h4>Bondi Beach</h4>', -33.890542, 151.274856],
 ['<h4>Coogee Beach</h4>', -33.923036, 151.259052],
 ['<h4>Cronulla Beach</h4>', -34.028249, 151.157507],
 ['<h4>Manly Beach</h4>', -33.80010128657071, 151.28747820854187],
 ['<h4>Maroubra Beach</h4>', -33.950198, 151.259302]
 ];
 
 // Setup the different icons and shadows
 var iconURLPrefix = 'http://maps.google.com/mapfiles/ms/icons/';
 
 var icons = [
 iconURLPrefix + 'red-dot.png',
 iconURLPrefix + 'green-dot.png',
 iconURLPrefix + 'blue-dot.png',
 iconURLPrefix + 'orange-dot.png',
 iconURLPrefix + 'purple-dot.png',
 iconURLPrefix + 'pink-dot.png',      
 iconURLPrefix + 'yellow-dot.png'
 ]
 var iconsLength = icons.length;
 
 var map = new google.maps.Map(document.getElementById('map'), {
 zoom: 10,
 center: new google.maps.LatLng(-37.92, 151.25),
 mapTypeId: google.maps.MapTypeId.ROADMAP,
 mapTypeControl: false,
 streetViewControl: false,
 panControl: false,
 zoomControlOptions: {
 position: google.maps.ControlPosition.LEFT_BOTTOM
 }
 });
 
 var infowindow = new google.maps.InfoWindow({
 maxWidth: 160
 });
 
 var markers = new Array();
 
 var iconCounter = 0;
 
 // Add the markers and infowindows to the map
 for (var i = 0; i < locations.length; i++) {  
 var marker = new google.maps.Marker({
 position: new google.maps.LatLng(locations[i][1], locations[i][2]),
 map: map,
 icon: icons[iconCounter]
 });
 
 markers.push(marker);
 
 google.maps.event.addListener(marker, 'click', (function(marker, i) {
 return function() {
 infowindow.setContent(locations[i][0]);
 infowindow.open(map, marker);
 }
 })(marker, i));
 
 iconCounter++;
 // We only have a limited number of possible icon colors, so we may have to restart the counter
 if(iconCounter >= iconsLength) {
 iconCounter = 0;
 }
 }
 
 function autoCenter() {
 //  Create a new viewpoint bound
 var bounds = new google.maps.LatLngBounds();
 //  Go through each...
 for (var i = 0; i < markers.length; i++) {  
 bounds.extend(markers[i].position);
 }
 //  Fit these bounds to the map
 map.fitBounds(bounds);
 }
 autoCenter();
 */
jQuery(document).ready(function () {
    DraggablePortlet.init();
});

//(function ($) {
//
//    $.fn.multiple_emails = function (options) {
//
//        // Default options
//        var defaults = {
//            checkDupEmail: true,
//            theme: "Bootstrap",
//            position: "top"
//        };
//
//        // Merge send options with defaults
//        var settings = $.extend({}, defaults, options);
//
//        var deleteIconHTML = "";
//        if (settings.theme.toLowerCase() == "Bootstrap".toLowerCase())
//        {
//            deleteIconHTML = '<a href="#" class="multiple_emails-close" title=""><i class="icon icon-remove"></i></a>';
//        } else if (settings.theme.toLowerCase() == "SemanticUI".toLowerCase() || settings.theme.toLowerCase() == "Semantic-UI".toLowerCase() || settings.theme.toLowerCase() == "Semantic UI".toLowerCase()) {
//            deleteIconHTML = '<a href="#" class="multiple_emails-close" title="Remove"><i class="remove icon"></i></a>';
//        } else if (settings.theme.toLowerCase() == "Basic".toLowerCase()) {
//            //Default which you should use if you don't use Bootstrap, SemanticUI, or other CSS frameworks
//            deleteIconHTML = '<a href="#" class="multiple_emails-close" title="Remove"><i class="basicdeleteicon">Remove</i></a>';
//        }
//
//        return this.each(function () {
//            //$orig refers to the input HTML node
//            var $orig = $(this);
//            var $list = $('<ul class="multiple_emails-ul" />'); // create html elements - list of email addresses as unordered list
//
//            if ($(this).val() != '' && IsJsonString($(this).val())) {
//                $.each(jQuery.parseJSON($(this).val()), function (index, val) {
//                    $list.append($('<li class="multiple_emails-email"><span class="email_name" data-email="' + val.toLowerCase() + '">' + val + '</span></li>')
//                            .prepend($(deleteIconHTML)
//                                    .click(function (e) {
//                                        $(this).parent().remove();
//                                        refresh_emails();
//                                        e.preventDefault();
//                                    })
//                                    )
//                            );
//                });
//            }
//
//            var $input = $('<input type="text" class="multiple_emails-input text-left" />').on('keyup', function (e) { // input
//                $(this).removeClass('multiple_emails-error');
//                var input_length = $(this).val().length;
//
//                var keynum;
//                if (window.event) { // IE					
//                    keynum = e.keyCode;
//                } else if (e.which) { // Netscape/Firefox/Opera					
//                    keynum = e.which;
//                }
//
//                //if(event.which == 8 && input_length == 0) { $list.find('li').last().remove(); } //Removes last item on backspace with no input
//
//                // Supported key press is tab, enter, space or comma, there is no support for semi-colon since the keyCode differs in various browsers
//                if (keynum == 9 || keynum == 32 || keynum == 188) {
//                    display_email($(this), settings.checkDupEmail);
//                } else if (keynum == 13) {
//                    display_email($(this), settings.checkDupEmail);
//                    //Prevents enter key default
//                    //This is to prevent the form from submitting with  the submit button
//                    //when you press enter in the email textbox
//                    e.preventDefault();
//                }
//
//            }).on('blur', function (event) {
//                if ($(this).val() != '') {
//                    display_email($(this), settings.checkDupEmail);
//                }
//            });
//
//            var $container = $('<div class="multiple_emails-container" />').click(function () {
//                $input.focus();
//            }); // container div
//
//            // insert elements into DOM
//            if (settings.position.toLowerCase() === "top")
//                $container.append($list).append($input).insertAfter($(this));
//            else
//                $container.append($input).append($list).insertBefore($(this));
//
//            /*
//             t is the text input device.
//             Value of the input could be a long line of copy-pasted emails, not just a single email.
//             As such, the string is tokenized, with each token validated individually.
//             
//             If the dupEmailCheck variable is set to true, scans for duplicate emails, and invalidates input if found.
//             Otherwise allows emails to have duplicated values if false.
//             */
//            function display_email(t, dupEmailCheck) {
//
//                //Remove space, comma and semi-colon from beginning and end of string
//                //Does not remove inside the string as the email will need to be tokenized using space, comma and semi-colon
//                var arr = t.val().trim().replace(/^,|,$/g, '').replace(/^;|;$/g, '');
//                //Remove the double quote
//                arr = arr.replace(/"/g, "");
//                //Split the string into an array, with the space, comma, and semi-colon as the separator
//                arr = arr.split(/[\s,;]+/);
//
//                var errorEmails = new Array(); //New array to contain the errors
//
//                var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
//
//                for (var i = 0; i < arr.length; i++) {
//                    //Check if the email is already added, only if dupEmailCheck is set to true
//                    if (dupEmailCheck === true && $orig.val().indexOf(arr[i]) != -1) {
//                        if (arr[i] && arr[i].length > 0) {
//                            new function () {
//                                var existingElement = $list.find('.email_name[data-email=' + arr[i].toLowerCase().replace('.', '\\.').replace('@', '\\@') + ']');
//                                existingElement.css('font-weight', 'bold');
//                                setTimeout(function () {
//                                    existingElement.css('font-weight', '');
//                                }, 1500);
//                            }(); // Use a IIFE function to create a new scope so existingElement won't be overriden
//                        }
//                    } else if (pattern.test(arr[i]) == true) {
//                        $list.append($('<li class="multiple_emails-email"><span class="email_name" data-email="' + arr[i].toLowerCase() + '">' + arr[i] + '</span></li>')
//                                .prepend($(deleteIconHTML)
//                                        .click(function (e) {
//                                            $(this).parent().remove();
//                                            refresh_emails();
//                                            e.preventDefault();
//                                        })
//                                        )
//                                );
//                    } else
//                        errorEmails.push(arr[i]);
//                }
//                // If erroneous emails found, or if duplicate email found
//                if (errorEmails.length > 0)
//                    t.val(errorEmails.join("; ")).addClass('multiple_emails-error');
//                else
//                    t.val("");
//                refresh_emails();
//            }
//
//            function refresh_emails() {
//                var emails = new Array();
//                var container = $orig.siblings('.multiple_emails-container');
//                container.find('.multiple_emails-email span.email_name').each(function () {
//                    emails.push($(this).html());
//                });
//                $orig.val(JSON.stringify(emails)).trigger('change');
//            }
//
//            function IsJsonString(str) {
//                try {
//                    JSON.parse(str);
//                } catch (e) {
//                    return false;
//                }
//                return true;
//            }
//
//            return $(this).hide();
//
//        });
//
//    };
//
//})(jQuery);
//
//$(".tags_email").multiple_emails({
//    position: 'top', // Display the added emails above the input
//    theme: 'bootstrap', // Bootstrap is the default theme
//    checkDupEmail: true // Should check for duplicate emails added
//});

