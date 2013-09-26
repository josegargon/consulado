var $v = jQuery.noConflict();
$v(function() {


	/************ GENERAL ************/
	/* Ajaxing the form submission */
	$v("#theme-options div.right").on("submit", "form.show", function(event){
		event.preventDefault();
		$v('#theme-options form.show #message').html('<img src="' + dirtoinc + '/images/loading.gif" class="loading" />').fadeIn();

		var action = "&action=vz_tho_ajax&page=" + $v(this).attr('id');

		if($v(this).attr('id')=='vz_plugins_extra') {
			$v("#theme-options div.right #n_name").val('');
			$v("#theme-options div.right #n_role").val('');
			$v("#theme-options div.right #a_group").val('');
			$v("#theme-options div.right #a_name").val('');
			$v("#theme-options div.right #a_role").val('');
		}

 		$v.post(ajaxurl, $v(this).serialize() + action , function(response) {
			$v('#theme-options form.show #message').hide().html(response).fadeIn();
		});

	});


	/* Showing first form and activating the first links */
	$v('#theme-options .right form:first-child').addClass('show');
	$v('#theme-options .second-column ul.nav.show li:first-child a').addClass('active');


	/* On clicked a Main Menu link */
	$v("#theme-options .first-column ul.nav li a").click(function(event) {
		event.preventDefault();
		window.location.hash = this.hash;

		$v('#theme-options .first-column ul.nav li a.active').removeClass();
		$v(this).addClass('active');

	 	$v('#theme-options .second-column ul li a.active').removeClass();

	 	$v('#theme-options .second-column ul.nav.show').removeClass('show').addClass('hide');
	 	$v('#theme-options .second-column ul'+this.hash).removeClass('hide').addClass('show').children(":first").children().click().addClass('active');
	});


	/* On clicked an Options Menu link */
	$v("#theme-options .second-column ul.nav li a").click(function(event) {
		event.preventDefault();
		$v('#theme-options .second-column ul li a.active').removeClass();
		$v(this).addClass('active');

		$v('#theme-options .right > form.show').removeClass();
		$v('#theme-options .right > form#' + $v(this).attr('id')).addClass('show');
	});


	/* Generating form jquery select boxes */
	$v('#theme-options div.right .selectbox:not(#a_group)').selectbox();


	/* Toggling below headers */
	$v("#theme-options .right .label.first a.toggle").click(function(event) {
		event.preventDefault();
		$v(this).parent().parent().next().slideToggle('normal');
	});


	/************ ARCHITECTURE ************/
	/* Sorting the architecture */
	$v("#theme-options .sortable").sortable({ cancel: ".disabled" }).disableSelection();


	/* Enabling/Disabling */
	$v("#theme-options .architecture-content li a.status").click(function(event) {
		event.preventDefault();
		var itemvalue 	= $v(this).prev().val().split(':');
		var classes 	= $v(this).attr('class').split(' ');

		if(classes[1]=='disabled') {
			$v(this).removeClass().addClass('status')
					.parent().removeClass('disabled');
			$v(this).prev().val(itemvalue[0] + ':');
		} else {
			$v(this).addClass('disabled')
					.parent().addClass('disabled');
			$v(this).prev().val(itemvalue[0] + ':disabled');
		}

		$v(this).parent().parent().sortable('refresh');
	});


	/* Choosing preset by clicking li */
	$v("#theme-options .right ul.presets li").click(function(event) {
		$v(this).children('input[type="radio"]').attr('checked', true );
	});


	/************ INPUT ELEMENTS ************/
    /* Changing the hidden input next to checkbox */
    $v("#theme-options .hcbox").change(function() {

    	var enValue = 'enabled'; var disValue = '';
    	var namestatus = $v(this).parent().next().attr('name');
    	namestatus = namestatus.split('_');
    	if( namestatus[1] == 'disabled' ) {
    		var enValue = ''; var disValue = 'disabled';
    	}

		if($v(this).is(':checked')) {
	    	$v(this).parent().next().val(enValue);
	    } else {
	    	$v(this).parent().next().val(disValue);
	    }

	});


	/* Focusing and Bluring colorpicker input */
	$v("#theme-options .right .field.colorpicker input")
	.focus(function () {
		input = $v(this);
       	colorPicker = input.next();
       	colorPicker.fadeIn('fast');
		$v.farbtastic(colorPicker, function(a) { input.val(a).css('background', a).change(); }).setColor(input.val());
    })
    .blur(function () {
        $v(this).next().fadeOut('fast');
    });


	/* Chosing transparent colorpicker */
	$v("#theme-options .right #picker").on("click", "div.trans", function(event){
		$v(this).parent().prev().val('transparent').css("background-color", "#ffffff");
	});


	/* Button sample provider */
	$v("#theme-options .right .field.colorpicker input.button_sample").change(function() {
		var att = $v(this).attr('name').split("_"); att = att[2]
		var val = $v(this).val();
		var button = $v(this).parent().parent().nextAll('div.button_sample:first').children('input[type=button]');

		switch(att) {
			case 'font': button.css("color", val); break;
			case 'bg': 	 button.css("background-color", val);  break;
			case 'border':button.css("border-color", val); break;
		}

	});


	/* Slider input */
	$v("#theme-options div.right .slider").each(function() {

        var value = parseInt( $v(this).prev().val() );

        $v(this).empty().slider({
            value: value,
            range: "max",
	        min: 10,
	        max: 24,
	        slide: function( event, ui ) {
	            $v(this).prev().val( ui.value );
	        }
        });

    });


	/* ForceUploading Media by custom Browse Link */
	$v("#theme-options a#browse").click(function(event) {
		event.preventDefault();
		tb_show('Upload Picture', 'media-upload.php?post_id=&type=image&amp;TB_iframe=true');
		var browsing = $v(this);

		window.send_to_editor = function(html){
			image_url = $v(html).attr('href');
			classes	  = $v('img',html).attr('class').match(/wp\-image\-([0-9]+)/);
			if ( classes[1] ) { attachment_id = classes[1]; }

			browsing.prevAll("div.show").html('<img src="'+image_url+'" />')
					.next().children('input[type=text]').val(image_url);
			browsing.next('input[type=hidden]').val(attachment_id);

			tb_remove();
		}
	});


	/* If url changed manually stop tracking attachment id */
	$v("#theme-options input.upload_field").keyup(function() {
		$v(this).parent().nextAll('input[type=hidden]').val($v(this).val());
	});


	/************ WIDGETS ************/
    /* Ajaxing cloneable fields */
	$v("#widgets-right").on("click", "a#clonefield", function(event){
		event.preventDefault();
		field = $v(this).prev().prev();
		field.clone().appendTo( $v(this).prev() );
		$v(this).prev().find('input:last').val('');
	});


	/************ PLUGINS ************/

	// NEWSLETTER
    /* Listing subscribers for the first time */
    if($v('.forms_list').length>0) {
		$v.post(ajaxurl, { 
			action: "vz_tho_ajax", 
			page: "vz_plugins_newsletter_get_subscribers", 
			offset: 0 } , 
			function(response) {
				$v('#theme-options .right ul.subscribers_list').hide().html(response).fadeIn();
			}
		);
	}


	/* Subscribers pagination */
	$v("#theme-options .right #vz_plugins_newsletter .content > a").click(function(event) {
		event.preventDefault();
		var offset = $v('#theme-options .right form.show #offset');
		var total = parseInt( offset.prev().val() );
		var offsetval = parseInt( $v('#theme-options .right form.show #offset').val() );

		if( $v(this).attr('class') == 'prev' ) {
			offsetval--; if(offsetval<0) { return false; }
			offset.val(offsetval);
		} else {
			offsetval++; if( (offsetval*10) > total) { return false; }
			offset.val(offsetval);
		}

		$v.post(ajaxurl, { action: "vz_tho_ajax", page: "vz_plugins_newsletter_get_subscribers", offset: offsetval } , 
			function(response) {
				$v('#theme-options .right ul.subscribers_list').hide().html(response).fadeIn();
			}
		);

	});


	/* Removing subscribers */
	$v("#theme-options .right ul.subscribers_list").on("click", "li a.delete", function(event){
		event.preventDefault();
		the_id = $v(this).attr('id');
		the_sub = $v(this).parent();

		$v.post(ajaxurl, { action: "vz_tho_ajax", page: "vz_plugins_newsletter_remove_subscriber", the_id: the_id });
		the_sub.fadeOut();
	});


	// FORMS
	/* Forms Getting subscribers for the first time */
	if($v('.forms_list').length>0) {
		$v.post(ajaxurl, { 
			action: "vz_tho_ajax", 
			page: "vz_plugins_forms_get_forms", 
			offset: 0 } , 
			function(response) {
				$v('#theme-options .right ul.forms_list').hide().html(response).fadeIn();
			}
		);
	}

	/* +New Field */
	$v("#theme-options .right #vz_plugins_forms a.add-new-element").click(function(event) {
		event.preventDefault();
		$v("#theme-options .right #vz_plugins_forms .elements > :last-child").clone().appendTo('#theme-options .right #vz_plugins_forms .elements');
		$v("#theme-options .right #vz_plugins_forms .elements > :last-child input:text").each(function() { $v(this).val('') });
	});


	/* Removing form */
	$v("#theme-options .right #vz_plugins_forms ul.forms_list").on("click", "li a.edit", function(event){
		event.preventDefault();

		$v("#theme-options .right #vz_plugins_forms .formstatus").html('Editing...');


		the_id = $v(this).attr('id');
		$v('#theme-options .right #vz_plugins_forms #form_id').val(the_id);

		$v.post(ajaxurl, { action: "vz_tho_ajax", page: "vz_plugins_forms_get_form", the_id: the_id },
			function(response) { rdata = response.split("|");
				$v('#theme-options .right #vz_plugins_forms #formname').val(rdata[0]);
				$v('#theme-options .right #vz_plugins_forms #formemail').val(rdata[1]);
				$v('#theme-options .right #vz_plugins_forms .elements').html(rdata[2]);
			}
		);

	});


	/* Forms pagination */
	$v("#theme-options .right #vz_plugins_forms .content > a").click(function(event) {
		event.preventDefault();
		var offset = $v('#theme-options .right form.show #offset');
		var total = parseInt( offset.prev().val() );
		var offsetval = parseInt( $v('#theme-options .right form.show #offset').val() );

		if( $v(this).attr('class') == 'prev' ) {
			offsetval--; if(offsetval<0) { return false; }
			offset.val(offsetval);
		} else {
			offsetval++; if( (offsetval*10) > total) { return false; }
			offset.val(offsetval);
		}

		$v.post(ajaxurl, { action: "vz_tho_ajax", page: "vz_plugins_forms_get_forms", offset: offsetval } , 
			function(response) {
				$v('#theme-options .right ul.forms_list').hide().html(response).fadeIn();
			}
		);

	});


	/* Removing form */
	$v("#theme-options .right ul.forms_list").on("click", "li a.delete", function(event){
		event.preventDefault();
		the_id = $v(this).attr('id');
		the_sub = $v(this).parent();

		$v.post(ajaxurl, { action: "vz_tho_ajax", page: "vz_plugins_forms_remove_form", the_id: the_id });
		the_sub.fadeOut();
	});


	/* If datepicker exists */
	if( $v("#datepicker").length > 0 ) {
		$v('#datepicker').datepicker({ dateFormat: 'dd MM yy' });
	}


	/* If exists timepicker field */
	if( $v("#timepicker_start").length > 0 ) {
		$v('#timepicker_start').timepicker({
			showPeriod: true,
	        showLeadingZero: true,
	        onHourShow: tpStartOnHourShowCallback,
	        onMinuteShow: tpStartOnMinuteShowCallback
	    });
	    $v('#timepicker_end').timepicker({
	    	showPeriod: true,
	        showLeadingZero: true,
	        onHourShow: tpEndOnHourShowCallback,
	        onMinuteShow: tpEndOnMinuteShowCallback
	    });
	}


	/* Switching active plugins button and showing extra plugin settings */
	$v("#theme-options .right #vz_plugins_extra .extra_buttons").on("click", "a", function(event){
		event.preventDefault();
		the_plugin = $v(this).attr('class');
		$v("#theme-options .right #vz_plugins_extra .extra_buttons a#active").removeAttr('id');
		$v(this).attr('id','active');

		$v("#theme-options .right #vz_plugins_extra .extra_content .show").removeClass().addClass('hide');
		$v("#theme-options .right #vz_plugins_extra .extra_content #"+the_plugin).fadeIn('normal').removeClass().addClass('show');
	});


	/* Adding new group */
	$v("#theme-options #vz_plugins_extra .extra_content #user_groups #confirm_add").click(function(event) {
		event.preventDefault();
		var n_name = $v('#theme-options .extra_content #n_name');
		var n_role = $v('#theme-options .extra_content #n_role');

		if(n_name.length>0) {
			$v('#theme-options form.show #message').html('<img src="' + dirtoinc + '/images/loading.gif" class="loading" />').fadeIn();

			$v.post(
				ajaxurl, { action: "vz_tho_ajax", 
						   page: "vz_plugins_extra_add_group", 
						   name: n_name.val(),
						   role: n_role.val(),
						 }, 
				function(response) {
					rdata = response.split('|');
					
					$v('#theme-options form.show #message').hide().html(rdata[0]).fadeIn();

					if(rdata[1] == 'ok') {
						n_name.val(''); n_role.val('');
					}
				}
			);

		}

	});


	/* Getting groups */
	$v.post(
		ajaxurl, { action: "vz_tho_ajax", page: "vz_plugins_extra_get_groups" }, 
		function(response) { 
			$v('#theme-options .extra_content #user_groups #a_group').html(response).selectbox(); 
		}
	);


	/* Setting group name to edit */
	$v("#theme-options .extra_content #user_groups").on("change","#a_group", function(){
		$v('#theme-options .extra_content #user_groups #a_name').val($v(this).val());
	});


	/* Changing a group name */
	$v("#theme-options #vz_plugins_extra .extra_content #user_groups #confirm_edit").click(function(event) {
		event.preventDefault();
		var a_name = $v('#theme-options .extra_content #a_name');
		var a_group = $v('#theme-options .extra_content #a_group');

		if(a_name != a_group) {
			$v('#theme-options form.show #message').html('<img src="' + dirtoinc + '/images/loading.gif" class="loading" />').fadeIn();

			$v.post(
				ajaxurl, { action: "vz_tho_ajax", 
						   page: "vz_plugins_extra_edit_group", 
						   name:  a_name.val(),
						   group: a_group.val(),
						 }, 
				function(response) {					
					$v('#theme-options form.show #message').hide().html(response).fadeIn();
				}
			);

		}

	});


	/* Deleting a group */
	$v("#theme-options #vz_plugins_extra .extra_content #user_groups #confirm_delete").click(function(event) {
		event.preventDefault();
		var a_name = $v('#theme-options .extra_content #a_name');
		var a_group = $v('#theme-options .extra_content #a_group');

		if(a_group.length != 0) {
			$v('#theme-options form.show #message').html('<img src="' + dirtoinc + '/images/loading.gif" class="loading" />').fadeIn();

			$v.post(
				ajaxurl, { action: "vz_tho_ajax", 
						   page: "vz_plugins_extra_delete_group", 
						   group: a_group.val(),
						 }, 
				function(response) {
					a_name.val('');
					$v('#theme-options form.show #message').hide().html(response).fadeIn();
				}
			);

		}

	});


	if($v('.wp-cpick').length>0) {
		$v(".wp-cpick")
		.focus(function () {
			input = $v(this);
	       	colorPicker = input.next();
	       	colorPicker.fadeIn('fast');
			$v.farbtastic(colorPicker, function(a) { input.val(a).css('background', a).change(); }).setColor(input.val());
	    })
	    .blur(function () {
	        $v(this).next().fadeOut('fast');
	    });
	}


});



/* TIMEPICKER EXTRA JS */
function tpStartOnHourShowCallback(hour) {
    var tpEndHour = $v('#timepicker_end').timepicker('getHour');
    // all valid if no end time selected
    if ($v('#timepicker_end').val() == '') { return true; }
    // Check if proposed hour is prior or equal to selected end time hour
    if (hour <= tpEndHour) { return true; }
    // if hour did not match, it can not be selected
    return false;
}
function tpStartOnMinuteShowCallback(hour, minute) {
    var tpEndHour = $v('#timepicker_end').timepicker('getHour');
    var tpEndMinute = $v('#timepicker_end').timepicker('getMinute');
    // all valid if no end time selected
    if ($v('#timepicker_end').val() == '') { return true; }
    // Check if proposed hour is prior to selected end time hour
    if (hour < tpEndHour) { return true; }
    // Check if proposed hour is equal to selected end time hour and minutes is prior
    if ( (hour == tpEndHour) && (minute < tpEndMinute) ) { return true; }
    // if minute did not match, it can not be selected
    return false;
}

function tpEndOnHourShowCallback(hour) {
    var tpStartHour = $v('#timepicker_start').timepicker('getHour');
    // all valid if no start time selected
    if ($v('#timepicker_start').val() == '') { return true; }
    // Check if proposed hour is after or equal to selected start time hour
    if (hour >= tpStartHour) { return true; }
    // if hour did not match, it can not be selected
    return false;
}
function tpEndOnMinuteShowCallback(hour, minute) {
    var tpStartHour = $v('#timepicker_start').timepicker('getHour');
    var tpStartMinute = $v('#timepicker_start').timepicker('getMinute');
    // all valid if no start time selected
    if ($v('#timepicker_start').val() == '') { return true; }
    // Check if proposed hour is after selected start time hour
    if (hour > tpStartHour) { return true; }
    // Check if proposed hour is equal to selected start time hour and minutes is after
    if ( (hour == tpStartHour) && (minute > tpStartMinute) ) { return true; }
    // if minute did not match, it can not be selected
    return false;
}