$(document).ready(function(){
	//Usuarios login
	$("button[action='login']").on("click",function(){
		$("#formLogin").validate({
			rules:
			{
				email: {
					required: true,
					email: true,
					minlength: 5,
					maxlength: 191
				},

				password: {
					required: true,
					minlength: 8,
					maxlength: 40
				}
			},
			submitHandler: function(form) {
				$("button[action='login']").attr('disabled', true);
				form.submit();
			}
		});
	});

	//Usuarios register
	$("button[action='register']").on("click",function(){
		$("#formRegister").validate({
			rules:
			{
				name: {
					required: true,
					minlength: 2,
					maxlength: 191
				},

				lastname: {
					required: true,
					minlength: 2,
					maxlength: 191
				},

				email: {
					required: true,
					email: true,
					minlength: 5,
					maxlength: 191,
					remote: {
						url: "/usuarios/email",
						type: "get"
					}
				},

				password: {
					required: true,
					minlength: 8,
					maxlength: 40
				},

				terms: {
					required: true
				}
			},
			messages:
			{
				email: {
					remote: "Este correo ya esta en uso."
				}
			},
			submitHandler: function(form) {
				$("button[action='register']").attr('disabled', true);
				form.submit();
			}
		});
	});

	//Recuperar Contraseña
	$("button[action='recovery']").on("click",function(){
		$("#formRecovery").validate({
			rules:
			{
				email: {
					required: true,
					email: true,
					minlength: 5,
					maxlength: 191
				}
			},
			submitHandler: function(form) {
				$("button[action='recovery']").attr('disabled', true);
				form.submit();
			}
		});
	});

	//Restaurar Contraseña
	$("button[action='reset']").on("click",function(){
		$("#formReset").validate({
			rules:
			{
				email: {
					required: true,
					email: true,
					minlength: 5,
					maxlength: 191
				},

				password: {
					required: true,
					minlength: 8,
					maxlength: 40
				},

				password_confirmation: { 
					equalTo: "#password",
					minlength: 8,
					maxlength: 40
				}
			},
			submitHandler: function(form) {
				$("button[action='reset']").attr('disabled', true);
				form.submit();
			}
		});
	});

	//Perfil
	$("button[action='profile']").on("click",function(){
		$("#formProfile").validate({
			rules:
			{
				name: {
					required: true,
					minlength: 2,
					maxlength: 191
				},

				lastname: {
					required: true,
					minlength: 2,
					maxlength: 191
				},

				phone: {
					required: true,
					minlength: 5,
					maxlength: 15
				},

				password: {
					required: false,
					minlength: 8,
					maxlength: 40
				},

				password_confirmation: { 
					equalTo: "#password",
					minlength: 8,
					maxlength: 40
				}
			},
			submitHandler: function(form) {
				$("button[action='profile']").attr('disabled', true);
				form.submit();
			}
		});
	});

	// Usuarios
	$("button[action='user']").on("click",function(){
		$("#formUser").validate({
			rules:
			{
				name: {
					required: true,
					minlength: 2,
					maxlength: 191
				},

				lastname: {
					required: true,
					minlength: 2,
					maxlength: 191
				},

				email: {
					required: true,
					email: true,
					minlength: 5,
					maxlength: 191,
					remote: {
						url: "/usuarios/email",
						type: "get"
					}
				},

				phone: {
					required: true,
					minlength: 5,
					maxlength: 15
				},

				type: {
					required: true
				},

				state: {
					required: true
				},

				password: {
					required: true,
					minlength: 8,
					maxlength: 40
				},

				password_confirmation: { 
					equalTo: "#password",
					minlength: 8,
					maxlength: 40
				}
			},
			messages:
			{
				email: {
					remote: "Este correo ya esta en uso."
				},

				type: {
					required: 'Seleccione una opción.'
				},

				state: {
					required: 'Seleccione una opción.'
				}
			},
			submitHandler: function(form) {
				$("button[action='user']").attr('disabled', true);
				form.submit();
			}
		});
	});

	// Clientes
	$("button[action='customer']").on("click",function(){
		$("#formCustomer").validate({
			rules:
			{
				name: {
					required: true,
					minlength: 2,
					maxlength: 191
				},

				lastname: {
					required: true,
					minlength: 2,
					maxlength: 191
				},

				email: {
					required: true,
					email: true,
					minlength: 5,
					maxlength: 191,
					remote: {
						url: "/usuarios/email",
						type: "get"
					}
				},

				phone: {
					required: true,
					minlength: 5,
					maxlength: 15
				},

				state: {
					required: true
				},

				password: {
					required: true,
					minlength: 8,
					maxlength: 40
				},

				password_confirmation: { 
					equalTo: "#password",
					minlength: 8,
					maxlength: 40
				}
			},
			messages:
			{
				email: {
					remote: "Este correo ya esta en uso."
				},

				state: {
					required: 'Seleccione una opción.'
				}
			},
			submitHandler: function(form) {
				$("button[action='customer']").attr('disabled', true);
				form.submit();
			}
		});
	});

	// Empresas
	$("button[action='company']").on("click",function(){
		$("#formCompany").validate({
			rules:
			{
				name: {
					required: true,
					minlength: 2,
					maxlength: 191
				},

				social_reason: {
					required: true,
					minlength: 2,
					maxlength: 191
				},

				rfc: {
					required: true,
					minlength: 2,
					maxlength: 191
				},

				address: {
					required: true,
					minlength: 2,
					maxlength: 191
				},

				state: {
					required: true
				},

				customer_id: {
					required: true
				}
			},
			messages:
			{
				state: {
					required: 'Seleccione una opción.'
				},

				customer_id: {
					required: 'Seleccione una opción.'
				}
			},
			submitHandler: function(form) {
				$("button[action='company']").attr('disabled', true);
				form.submit();
			}
		});
	});

	// Statements
	$("button[action='statement']").on("click",function(){
		$("#formStatement").validate({
			rules:
			{
				name: {
					required: true,
					minlength: 2,
					maxlength: 191
				},

				description: {
					required: true,
					minlength: 2,
					maxlength: 6000
				},

				type_id: {
					required: true
				},

				state: {
					required: true
				},

				company_id: {
					required: true
				}
			},
			messages:
			{
				type_id: {
					required: 'Seleccione una opción.'
				},

				state: {
					required: 'Seleccione una opción.'
				},

				company_id: {
					required: 'Seleccione una opción.'
				}
			},
			submitHandler: function(form) {
				$("button[action='statement']").attr('disabled', true);
				form.submit();
			}
		});
	});

	// Resolutions
	$("button[action='resolution']").on("click",function(){
		$("#formResolution").validate({
			rules:
			{
				name: {
					required: true,
					minlength: 2,
					maxlength: 191
				},

				description: {
					required: true,
					minlength: 2,
					maxlength: 6000
				},

				date: {
					required: true,
					date: false,
					time: false
				}
			},
			messages:
			{
				date: {
					required: 'Seleccione una fecha.'
				}
			},
			submitHandler: function(form) {
				$("button[action='resolution']").attr('disabled', true);
				form.submit();
			}
		});
	});

	// Types
	$("button[action='type']").on("click",function(){
		$("#formType").validate({
			rules:
			{
				name: {
					required: true,
					minlength: 2,
					maxlength: 191
				},

				state: {
					required: true
				}
			},
			messages:
			{
				state: {
					required: 'Seleccione una opción.'
				}
			},
			submitHandler: function(form) {
				$("button[action='type']").attr('disabled', true);
				form.submit();
			}
		});
	});

	// Setting
	$("button[action='setting']").on("click",function(){
		$("#formSetting").validate({
			rules:
			{
				header_background_color: {
					required: true,
					minlength: 7,
					maxlength: 7
				},

				header_text_color: {
					required: true,
					minlength: 7,
					maxlength: 7
				},

				menu_background_color: {
					required: true,
					minlength: 7,
					maxlength: 7
				},

				menu_background_color_hover: {
					required: true,
					minlength: 7,
					maxlength: 7
				},

				menu_icon_color: {
					required: true,
					minlength: 7,
					maxlength: 7
				},

				menu_text_color: {
					required: true,
					minlength: 7,
					maxlength: 7
				},

				menu_border_color: {
					required: true,
					minlength: 7,
					maxlength: 7
				},

				header_text: {
					required: false,
					minlength: 2,
					maxlength: 100
				},

				google_drive_client_id: {
					required: true,
					minlength: 2,
					maxlength: 191
				},

				google_drive_client_secret: {
					required: true,
					minlength: 2,
					maxlength: 191
				},

				google_drive_refresh_token: {
					required: true,
					minlength: 2,
					maxlength: 191
				},

				google_drive_folder_id: {
					required: true,
					minlength: 2,
					maxlength: 191
				}
			},
			submitHandler: function(form) {
				$("button[action='setting']").attr('disabled', true);
				form.submit();
			}
		});
	});
});