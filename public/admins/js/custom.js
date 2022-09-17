/*
=========================================
|                                       |
|           Scroll To Top               |
|                                       |
=========================================
*/ 
$('.scrollTop').click(function() {
  $("html, body").animate({scrollTop: 0});
});


$('.navbar .dropdown.notification-dropdown > .dropdown-menu, .navbar .dropdown.message-dropdown > .dropdown-menu ').click(function(e) {
  e.stopPropagation();
});

/*
=========================================
|                                       |
|       Multi-Check checkbox            |
|                                       |
=========================================
*/

function checkall(clickchk, relChkbox) {

  var checker = $('#' + clickchk);
  var multichk = $('.' + relChkbox);


  checker.click(function () {
    multichk.prop('checked', $(this).prop('checked'));
  });    
}


/*
=========================================
|                                       |
|           MultiCheck                  |
|                                       |
=========================================
*/

/*
    This MultiCheck Function is recommanded for datatable
*/

function multiCheck(tb_var) {
  tb_var.on("change", ".chk-parent", function() {
    var e=$(this).closest("table").find("td:first-child .child-chk"), a=$(this).is(":checked");
    $(e).each(function() {
      a?($(this).prop("checked", !0), $(this).closest("tr").addClass("active")): ($(this).prop("checked", !1), $(this).closest("tr").removeClass("active"))
    })
  }),
  tb_var.on("change", "tbody tr .new-control", function() {
    $(this).parents("tr").toggleClass("active")
  })
}

/*
=========================================
|                                       |
|           MultiCheck                  |
|                                       |
=========================================
*/

function checkall(clickchk, relChkbox) {

  var checker = $('#' + clickchk);
  var multichk = $('.' + relChkbox);


  checker.click(function () {
      multichk.prop('checked', $(this).prop('checked'));
  });    
}

/*
=========================================
|                                       |
|               Tooltips                |
|                                       |
=========================================
*/

$('.bs-tooltip').tooltip();

/*
=========================================
|                                       |
|               Popovers                |
|                                       |
=========================================
*/

$('.bs-popover').popover();


/*
================================================
|                                              |
|               Rounded Tooltip                |
|                                              |
================================================
*/

$('.t-dot').tooltip({
  template: '<div class="tooltip status rounded-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
})


/*
================================================
|            IE VERSION Dector                 |
================================================
*/

function GetIEVersion() {
  var sAgent = window.navigator.userAgent;
  var Idx = sAgent.indexOf("MSIE");

  // If IE, return version number.
  if (Idx > 0) 
    return parseInt(sAgent.substring(Idx+ 5, sAgent.indexOf(".", Idx)));

  // If IE 11 then look for Updated user agent string.
  else if (!!navigator.userAgent.match(/Trident\/7\./)) 
    return 11;

  else
    return 0; //It is not IE
}

//////// Scripts ////////
$(document).ready(function() {
  //Validación para introducir solo números
  $('.number, #phone').keypress(function() {
    return event.charCode >= 48 && event.charCode <= 57;
  });
  //Validación para introducir solo letras y espacios
  $('#name, #lastname, .only-letters').keypress(function() {
    return event.charCode >= 65 && event.charCode <= 90 || event.charCode >= 97 && event.charCode <= 122 || event.charCode==32;
  });
  //Validación para solo presionar enter y borrar
  $('.date').keypress(function() {
    return event.charCode == 32 || event.charCode == 127;
  });

  //select2
  if ($('.select2').length) {
    $('.select2').select2({
      language: "es",
      placeholder: "Seleccione",
      tags: true
    });
  }

  //Datatables normal
  if ($('.table-normal').length) {
    $('.table-normal').DataTable({
      "dom": "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>><'table-responsive'tr><'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
      "oLanguage": {
        "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
        "sInfo": "Resultados del _START_ al _END_ de un total de _TOTAL_ registros",
        "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
        "sSearchPlaceholder": "Buscar...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sProcessing":     "Procesando...",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún resultado disponible en esta tabla",
        "sInfoEmpty":      "No hay resultados",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oAria": {
          "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
      },
      "stripeClasses": [],
      "lengthMenu": [10, 20, 50, 100, 200, 500],
      "pageLength": 10
    });
  }

  if ($('.table-export').length) {
    $('.table-export').DataTable({
      "dom": "<'dt--top-section'<'row'<'col-sm-12 col-md-6 d-flex justify-content-md-start justify-content-center'B><'col-sm-12 col-md-6 d-flex justify-content-md-end justify-content-center mt-md-0 mt-3'f>>><'table-responsive'tr><'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
        buttons: {
        buttons: [
        { extend: 'copy', className: 'btn' },
        { extend: 'csv', className: 'btn' },
        { extend: 'excel', className: 'btn' },
        { extend: 'print', className: 'btn' }
        ]
      },
      "oLanguage": {
        "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
        "sInfo": "Resultados del _START_ al _END_ de un total de _TOTAL_ registros",
        "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
        "sSearchPlaceholder": "Buscar...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sProcessing":     "Procesando...",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún resultado disponible en esta tabla",
        "sInfoEmpty":      "No hay resultados",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oAria": {
          "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        },
        "buttons": {
          "copy": "Copiar",
          "print": "Imprimir"
        }
      },
      "stripeClasses": [],
      "lengthMenu": [10, 20, 50, 100, 200, 500],
      "pageLength": 10
    });
  }

  //dropify para input file más personalizado
  if ($('.dropify').length) {
    $('.dropify').dropify({
      messages: {
        default: 'Arrastre y suelte una imagen o da click para seleccionarla',
        replace: 'Arrastre y suelte una imagen o haga click para reemplazar',
        remove: 'Remover',
        error: 'Lo sentimos, el archivo es demasiado grande'
      },
      error: {
        'fileSize': 'El tamaño del archivo es demasiado grande ({{ value }} máximo).',
        'minWidth': 'El ancho de la imagen es demasiado pequeño ({{ value }}}px mínimo).',
        'maxWidth': 'El ancho de la imagen es demasiado grande ({{ value }}}px máximo).',
        'minHeight': 'La altura de la imagen es demasiado pequeña ({{ value }}}px mínimo).',
        'maxHeight': 'La altura de la imagen es demasiado grande ({{ value }}px máximo).',
        'imageFormat': 'El formato de imagen no está permitido (Debe ser {{ value }}).'
      }
    });
  }

  //datepicker material
  if ($('.dateMaterial').length) {
    $('.dateMaterial').bootstrapMaterialDatePicker({
      lang : 'es',
      time: false,
      cancelText: 'Cancelar',
      clearText: 'Limpiar',
      format: 'DD-MM-YYYY',
      maxDate : new Date()
    });
  }

  // flatpickr
  if ($('#flatpickr').length) {
    flatpickr(document.getElementById('flatpickr'), {
      locale: 'es',
      enableTime: false,
      dateFormat: "d-m-Y",
      maxDate : "today"
    });
  }

  //Jquery uploader
  if ($('#drop-area').length) {
    $('#drop-area').dmUploader({
      url: '/admin/casos/'+$('#statement_slug').val()+'/resoluciones/archivos',
      maxFileSize: 20000000,
      allowedTypes: "application/pdf",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      onDragEnter: function(){
        this.addClass('active');
      },
      onDragLeave: function(){
        this.removeClass('active');
      },
      onBeforeUpload: function(){
        $('button[type="submit"]').attr('disabled', true);
        $("#response").text('Subiendo Archivo...');
      },
      onUploadSuccess: function(id, data){
        var obj=data;

        if (obj.status) {
          $("#files").append($('<div>', {
            'class': 'form-group col-lg-3 col-md-3 col-sm-6 col-12',
            'element': id
          }).append($('<div>', {
            'class': 'card'
          }).append($('<div>', {
            'class': 'card-body p-2'
          }).append($('<i>', {
            'class': 'fa fa-2x fa-file mb-2'
          })).append($('<p>', {
            'text': obj.name,
            'class': 'text-truncate mb-0'
          }))).append($('<input>', {
            'type': 'hidden',
            'name': 'files[]',
            'value': obj.name
          })).append($('<button>', {
            'type': 'button',
            'class': 'btn btn-danger btn-absolute-right',
            'file': id,
            'urlFile': '/admins/files/statements/'+obj.name,
            'onclick': 'deleteFileCreate("'+id+'");'
          }).append('<i class="fa fa-trash">'))));

          $('button[type="submit"]').attr('disabled', false);
          $("#response").text('Correcto');
        } else {
          $('button[type="submit"]').attr('disabled', false);
          $("#response").text('Error');
        }
      },
      onUploadError: function(id, xhr, status, message){  
        $('button[type="submit"]').attr('disabled', false);
        $("#response").text('Error');
      },
      onFileSizeError: function(file){
        $('button[type="submit"]').attr('disabled', false);
        $("#response").text('El archivo \'' + file.name + '\' excede el tamaño máximo permitido.');
      }
    });
  }

  if ($('#drop-area2').length) {
    $('#drop-area2').dmUploader({
      url: '/admin/casos/'+$('#statement_slug').val()+'/resoluciones/'+$('#resolution_slug').val()+'/archivos/editar',
      maxFileSize: 20000000,
      allowedTypes: "application/pdf",
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      extraData: function() {
        return {
          "statement_slug": $('#statement_slug').val(),
          "resolution_slug": $('#resolution_slug').val()
        };
      },
      onDragEnter: function(){
        this.addClass('active');
      },
      onDragLeave: function(){
        this.removeClass('active');
      },
      onBeforeUpload: function(){
        $('button[type="submit"]').attr('disabled', true);
        $("#response").text('Subiendo Archivo...');
      },
      onUploadSuccess: function(id, data){
        var obj=data;

        if (obj.status) {
          $("#files").append($('<div>', {
            'class': 'form-group col-lg-3 col-md-3 col-sm-6 col-12',
            'element': id
          }).append($('<div>', {
            'class': 'card'
          }).append($('<div>', {
            'class': 'card-body p-2'
          }).append($('<i>', {
            'class': 'fa fa-2x fa-file mb-2'
          })).append($('<p>', {
            'text': obj.name,
            'class': 'text-truncate mb-0'
          }))).append($('<button>', {
            'type': 'button',
            'class': 'btn btn-danger btn-absolute-right removeFile',
            'file': id,
            'urlFile': obj.url
          }).append('<i class="fa fa-trash">'))));
          $('button[type="submit"]').attr('disabled', false);
          $("#response").text('Correcto');
          Lobibox.notify('success', {
            title: 'Registro exitoso',
            sound: true,
            msg: 'El archivo ha sido agregado exitosamente.'
          });

          // Funcion para eliminar archivos de casos
          $('.removeFile').on('click', function(event) {
            removeFile($(this), event);
          });
        } else {
          $('button[type="submit"]').attr('disabled', false);
          $("#response").text('Error');
          Lobibox.notify('error', {
            title: 'Registro Fallido',
            sound: true,
            msg: 'Ha ocurrido un problema, intentelo nuevamente.'
          });
        }
      },
      onUploadError: function(id, xhr, status, message){  
        $('button[type="submit"]').attr('disabled', false);
        $("#response").text('Error');
        Lobibox.notify('error', {
          title: 'Registro Fallido',
          sound: true,
          msg: 'Ha ocurrido un problema, intentelo nuevamente.'
        });
      },
      onFileSizeError: function(file){
        $('button[type="submit"]').attr('disabled', false);
        $("#response").text('El archivo \'' + file.name + '\' excede el tamaño máximo permitido.');
      }
    });
  }
});

// funcion para cambiar el input hidden al cambiar el switch de estado
$('#stateCheckbox').change(function(event) {
  if ($(this).is(':checked')) {
    $('#stateHidden').val(1);
  } else {
    $('#stateHidden').val(0);
  }
});

//funciones para desactivar y activar
function deactiveUser(slug) {
  $("#deactiveUser").modal();
  $('#formDeactiveUser').attr('action', '/admin/usuarios/' + slug + '/desactivar');
}

function activeUser(slug) {
  $("#activeUser").modal();
  $('#formActiveUser').attr('action', '/admin/usuarios/' + slug + '/activar');
}

function deactiveCustomer(slug) {
  $("#deactiveCustomer").modal();
  $('#formDeactiveCustomer').attr('action', '/admin/clientes/' + slug + '/desactivar');
}

function activeCustomer(slug) {
  $("#activeCustomer").modal();
  $('#formActiveCustomer').attr('action', '/admin/clientes/' + slug + '/activar');
}

function deactiveCompany(slug) {
  $("#deactiveCompany").modal();
  $('#formDeactiveCompany').attr('action', '/admin/empresas/' + slug + '/desactivar');
}

function activeCompany(slug) {
  $("#activeCompany").modal();
  $('#formActiveCompany').attr('action', '/admin/empresas/' + slug + '/activar');
}

function deactiveStatement(slug) {
  $("#deactiveStatement").modal();
  $('#formDeactiveStatement').attr('action', '/admin/casos/' + slug + '/desactivar');
}

function activeStatement(slug) {
  $("#activeStatement").modal();
  $('#formActiveStatement').attr('action', '/admin/casos/' + slug + '/activar');
}

//funciones para preguntar al eliminar
function deleteUser(slug) {
  $("#deleteUser").modal();
  $('#formDeleteUser').attr('action', '/admin/usuarios/' + slug);
}

function deleteCustomer(slug) {
  $("#deleteCustomer").modal();
  $('#formDeleteCustomer').attr('action', '/admin/clientes/' + slug);
}

function deleteCompany(slug) {
  $("#deleteCompany").modal();
  $('#formDeleteCompany').attr('action', '/admin/empresas/' + slug);
}

function deleteStatement(slug) {
  $("#deleteStatement").modal();
  $('#formDeleteStatement').attr('action', '/admin/casos/' + slug);
}

function deleteResolution(statement_slug, resolution_slug) {
  $("#deleteResolution").modal();
  $('#formDeleteResolution').attr('action', '/admin/casos/' + statement_slug + '/resoluciones/' + resolution_slug);
}

// Funcion para eliminar archivos de casos
function deleteFileCreate(file){
  $("div[element="+file+"]").remove();
}

// Funcion para eliminar archivos de casos
$('.removeFile').click(function(event) {
  removeFile($(this), event);
});

function removeFile(element, event) {
  var file=element.attr('file'), statement_slug=$('#statement_slug').val(), resolution_slug=$('#resolution_slug').val(), urlFile=event.currentTarget.attributes[3].value;
  urlFile=urlFile.split('/');
  if (statement_slug!="" && resolution_slug!="") {
    $.ajax({
      url: '/admin/casos/'+statement_slug+'/resoluciones/'+resolution_slug+'/archivos/eliminar',
      type: 'POST',
      dataType: 'json',
      data: {statement_slug: statement_slug, resolution_slug: resolution_slug, url: urlFile[6]},
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    })
    .done(function(obj) {
      if (obj.status) {
        $("div[element='"+file+"']").remove();
        Lobibox.notify('success', {
          title: 'Eliminación Exitosa',
          sound: true,
          msg: 'El archivo ha sido eliminado exitosamente.'
        });
      } else {
        Lobibox.notify('error', {
          title: 'Eliminación Fallida',
          sound: true,
          msg: 'Ha ocurrido un problema, intentelo nuevamente.'
        });
      }
    })
    .fail(function() {
      Lobibox.notify('error', {
        title: 'Error',
        sound: true,
        msg: 'Ha ocurrido un problema, intentelo nuevamente.'
      });
    });
  }
}