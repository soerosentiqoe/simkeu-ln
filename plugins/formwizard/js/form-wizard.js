var FormWizard = function () {


    return {
        //main function to initiate the module
        init: function () {
            if (!jQuery().bootstrapWizard) {
                return;
            }

            /*function format(state) {
                if (!state.id) return state.text; // optgroup
                return "<img class='flag' src='../../assets/global/img/flags/" + state.id.toLowerCase() + ".png'/>&nbsp;&nbsp;" + state.text;
            }*/

            $("#kdsatker").select2({
                placeholder: "Select",
                allowClear: true,
                //formatResult: format,
                //formatSelection: format,
                escapeMarkup: function (m) {
                    return m;
                }
            });
			
			$("#kdkppn").select2({
                placeholder: "Select",
                allowClear: true,
                //formatResult: format,
                //formatSelection: format,
                escapeMarkup: function (m) {
                    return m;
                }
            });
			
			$("#kdnip").select2({
                placeholder: "Select",
                allowClear: true,
                //formatResult: format,
                //formatSelection: format,
                escapeMarkup: function (m) {
                    return m;
                }
            });
			
			$("#kdlevel").select2({
                placeholder: "Select",
                allowClear: true,
                //formatResult: format,
                //formatSelection: format,
                escapeMarkup: function (m) {
                    return m;
                }
            });

            var form = $('#form-ruh');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                rules: {
					//data kppn
					kdkppn: {
						required: true
					},
					
					//data satker
					kdsatker: {
						required: true
					},
					nodipa: {
						required: true
					},
					tgdipa: {
						required: true
					},
					
					//data user
					nama: {
						required: true
					},
					
					kdnip: {
						required: true
					},
					
					nip: {
						required: true,
						maxlength: 18
					},
					
					nip2: {
						required: true
					},
					
					jabatan: {
						required: true
					},
					
					email: {
						required: true,
						email: true
					},
					
					telp: {
						required: true
					},
					
					alamat: {
						required: true
					},
					
					kdlevel: {
						required: true
					},
					
					//data login
					username: {
						required: true,
						minlength: 6,
						remote: {  // value of 'username' field is sent by default
							type: 'get',
							url: 'cek-username'
						}
					},
					
					password: {
						required: true,
						minlength: 8
					},
					
					password1: {
						required: true,
						equalTo: "#password"
					},
					
					//upload
					upload3: {
						required: true,
						extension: "png",
						filesize: 1048576
					},
					
					upload1: {
						required: true,
						extension: "pdf",
						filesize: 1048576
					},
					
					upload2: {
						required: true,
						extension: "pdf",
						filesize: 1048576
					},
					
					//disclaimer
					'agree[]': {
						required: true,
						minlength: 1
					}
                },
				
				messages: { // custom messages for radio buttons and checkboxes
                    'agree[]': {
                        required: "Silahkan beri checklist persetujuan tanggung jawab terlebih dahulu",
                        minlength: jQuery.validator.format("Silahkan beri checklist persetujuan tanggung jawab terlebih dahulu")
                    },
					
					username:{
						remote: "Username telah terdaftar"
					},
					
					upload3:{
						required: "Lakukan upload file terlebih dahulu untuk melanjutkan",
						extension: "Tipe file ekstensi tidak sesuai .png",
						filesize: "Ukuran file melebihi 1 MB"
					},
					
					upload2:{
						required: "Lakukan upload file terlebih dahulu untuk melanjutkan",
						extension: "Tipe file ekstensi tidak sesuai .pdf",
						filesize: "Ukuran file melebihi 1 MB"
					},
					
					upload1:{
						required: "Lakukan upload file terlebih dahulu untuk melanjutkan",
						extension: "Tipe file ekstensi tidak sesuai .pdf",
						filesize: "Ukuran file melebihi 1 MB"
					}
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    if (element.attr("name") == "agree[]") { // for uniform radio buttons, insert the after the given container
                        error.insertAfter("#form_agree_error");
                    } else {
                        error.insertAfter(element); // for other inputs, just perform default behavior
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit   
                    success.hide();
                    error.show();
                    Metronic.scrollTo(error, -200);
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    if (label.attr("for") == "agree[]") { // for checkboxes and radio buttons, no need to show OK icon
                        label
							.addClass('valid')
                            .closest('.form-group').removeClass('has-error').addClass('has-success');
                        label.remove(); // remove error label here
                    } else { // display success icon for other inputs
                        label
                            .addClass('valid') // mark the current input as valid and display OK icon
                        .closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                    }
                },

                submitHandler: function (form) {
					var data=jQuery('#form-ruh').serialize();
					jQuery.ajax({
						url:'registrasi',
						method:'POST',
						data:data,
						success:function(result){
							if(result=='success'){
								alertify.log('Data berhasil disimpan!');
								window.location.href='login';
							}
							else{
								alertify.log('Data tidak masuk database');
							}
						},
						error:function(result){
							alertify.log(result);
						}
					});
                    //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
                }
            });

            var handleTitle = function(tab, navigation, index) {
                var total = navigation.find('li').length;
                var current = index + 1;
                // set wizard title
                $('.step-title', $('#form_wizard_1')).text('Step ' + (index + 1) + ' of ' + total);
                // set done steps
                jQuery('li', $('#form_wizard_1')).removeClass("done");
                var li_list = navigation.find('li');
                for (var i = 0; i < index; i++) {
                    jQuery(li_list[i]).addClass("done");
                }

                if (current == 1) {
                    $('#form_wizard_1').find('.button-previous').hide();
                } else {
                    $('#form_wizard_1').find('.button-previous').show();
                }

                if (current >= total) {
                    $('#form_wizard_1').find('.button-next').hide();
                    $('#form_wizard_1').find('.button-submit').show();
                } else {
                    $('#form_wizard_1').find('.button-next').show();
                    $('#form_wizard_1').find('.button-submit').hide();
                }
                Metronic.scrollTo($('.page-title'));
            }

            // default form wizard
            $('#form_wizard_1').bootstrapWizard({
                'nextSelector': '.button-next',
                'previousSelector': '.button-previous',
                onTabClick: function (tab, navigation, index, clickedIndex) {
                    return false;
                    /*
                    success.hide();
                    error.hide();
                    if (form.valid() == false) {
                        return false;
                    }
                    handleTitle(tab, navigation, clickedIndex);
                    */
                },
                onNext: function (tab, navigation, index) {
                    success.hide();
                    error.hide();

                    if (form.valid() == false) {
                        return false;
                    }

                    handleTitle(tab, navigation, index);
                },
                onPrevious: function (tab, navigation, index) {
                    success.hide();
                    error.hide();

                    handleTitle(tab, navigation, index);
                },
                onTabShow: function (tab, navigation, index) {
                    var total = navigation.find('li').length;
                    var current = index + 1;
                    var $percent = (current / total) * 100;
                    $('#form_wizard_1').find('.progress-bar').css({
                        width: $percent + '%'
                    });
                }
            });

            $('#form_wizard_1').find('.button-previous').hide();
            $('#form_wizard_1 .button-submit').click(function () {
                if (form.valid() == true) {
                    var data=jQuery('#form-ruh').serialize();
						jQuery.ajax({
							url:'registrasi',
							method:'POST',
							data:data,
							success:function(result){
								if(result=='success'){
									alertify.log('Data berhasil disimpan!');
									window.location.href='login';
								}
								else{
									alertify.log('Data tidak masuk database');
								}
							},
							error:function(result){
								alertify.log(result);
							}
					});
                }
				
            }).hide();

            //apply validation on select2 dropdown value change, this only needed for chosen dropdown integration.
            $('#kdsatker', form).change(function () {
                form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });
			
			$('#kdkppn', form).change(function () {
                form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });
			$('#kdnip', form).change(function () {
                form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });
			
			$('#kdlevel', form).change(function () {
                form.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });
        }

    };

}();