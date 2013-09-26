var $v = jQuery.noConflict();

$v(function() {

	/************ GENERAL ************/
	/* Ajaxing the form submission */
	$v("body").on("submit", "form", function(event){

		if( $v(this).attr('class') === 'vz_uajax' ) {
			event.preventDefault();
			var the_form = $v(this);
			var is_modal;

			if( the_form.parent().hasClass('modal') ) {
				is_modal = true;
				the_form.children('h1').children('span').html('<img src="'+ vz_vj_settings.wpe_dir +'/includes/images/act.gif" />');
			}

			var action = "&action=vz_ufrontajax&sub_action=" + $v(this).attr('id');

			switch( $v(this).attr('id') ) {
				case 'subscribe':
					if( $v('#subscribe_email').val()==='testing@mail.org' ) { return; }
				break;
				case 'unsubscribe':
					if( $v('#subscribe_email').val()==='testing@mail.org' ) { return; }
				break;
				case 'add_lecture':
					if( $v('#lecture_name').val().length===0 ) { $v('#lecture_name').addClass('warn_border'); return; }
					if( $v('#lecture_date').val().length===0 ) { $v('#lecture_date').addClass('warn_border'); return; }
				break;
				case 'edit_lecture':
					if( $v('#lecture_name2').val().length===0 ) { $v('#lecture_name2').addClass('warn_border'); return; }
					if( $v('#lecture_date2').val().length===0 ) { $v('#lecture_date2').addClass('warn_border'); return; }
				break;	
			}

			$v.post(vz_vj_settings.ajaxurl, $v(this).serialize() + action , function(response) {
				the_form.hide().html(response).fadeIn('normal');

				if( is_modal ) {

					setTimeout(function() { the_form.parent().dialog('close'); }, 2000);

					setTimeout(window.location.reload(),2500);

				}

			});

		}

	});

	/* Removing warn borders in any input when trying to type again */
	$v("body").on("keyup", "input", function(){
		if( $v(this).hasClass('warn_border') ) {
			$v(this).removeClass('warn_border');
		}
	});


	/************ CONTENT ACTIONS ************/
	/* HOME: Ajaxing featured posts */
	$v('.content .featured .arrows a').click( function(event) {
		event.preventDefault();

		var move = $v(this).attr('id'); 
		var route = $v(this).parent().parent();
		var actual = route.nextAll('.featured_post.display');

		if(move === 'next') {
			if( actual.next().length>0 ) {
				actual.fadeOut('fast').attr('class', 'featured_post hidden').next().attr('class', 'featured_post display').fadeIn('fast');
			}
		} else {
			if( actual.prev().filter('.featured_post.hidden').length>0 ) {
				actual.fadeOut('fast').attr('class', 'featured_post hidden').prev().attr('class', 'featured_post display').fadeIn('fast');
			}
		}

	});

	/* POST: Replying into any comment */
	$v('.content .comment .comment-content a.reply_comment').click( function() {

		var cid = $v(this).attr('class').split(' '); 
		cid = cid[1].split('_'); cid = cid[1];
		$v('#comment_parent').val(cid);

	});


	/* CALENDAR: Open Add dialog */
	$v('.container a#add_event').click( function(event) {
		event.preventDefault();
		$v('.modal.add').dialog({
			modal: true,
			autoOpen: true,
			resizable: false
		});
	});


	/* CALENDAR: Open Edit dialog */
	$v('.container a.edit_cal').click( function(event) {
		event.preventDefault();
		var the_cal = $v(this).attr('id').split('_');

		$v.post(vz_vj_settings.ajaxurl, { 
			action: "vz_ufrontajax", 
			sub_action: "get_lecture", 
			the_cal: the_cal[1] } , 
			function(response) {
				var rdata = response.split('||');
				$v('.modal.edit #lecture_id').val(the_cal[1]);
				$v('.modal.edit #lecture_name2').val(rdata[0]);
				$v('.modal.edit #lecture_date2').val(rdata[1]);
				$v('.modal.edit #timepicker_start2').val(rdata[2]);
				$v('.modal.edit #timepicker_end2').val(rdata[3]);
				$v('.modal.edit #lecture_hall').val(rdata[4]);

				if(rdata[5].length>0) {
					$v('.modal.edit #lecture_group').next().children().next('.sbSelector').html(rdata[5]);
				}

				if(rdata[5]!=='once') {
					$v('.modal.edit #lecture_repeat').next().children().next('.sbSelector').html(rdata[6]);
				}

				$v('.modal.edit').dialog({
					modal: true,
					autoOpen: true,
					resizable: false
				});
			}
		);
		
	});


	/* CALENDAR: Open delete dialog */
	$v('.container a.delete_cal').click( function(event) {
		event.preventDefault();
		var the_cal = $v(this).attr('id').split('_');
		$v('.modal.delete #lecture_id').val( the_cal[1] );
		$v('.modal.delete #del_id').html( the_cal[1] );
		$v('.modal.delete').dialog({
			modal: true,
			autoOpen: true,
			resizable: false
		});
	});

	/* CALENDAR: Closing add modal dialog */ 
	$v(".modal.add input.close").click( function() {
		$v('.modal.add').dialog('close');
	});

	/* CALENDAR: Closing edit modal dialog */ 
	$v(".modal.edit input.close").click( function() {
		$v('.modal.edit').dialog('close');
	});

	/* CALENDAR: Closing edit modal dialog */ 
	$v(".modal.delete input.close").click( function() {
		$v('.modal.delete').dialog('close');
	});


	/* CALENDAR: If datepicker exists */
	if( $v("#lecture_date,#lecture_date2").length > 0 ) {
		$v('#lecture_date,#lecture_date2').datepicker({ dateFormat: 'dd MM yy', beforeShowDay: noSunday, firstDay: 1 });
	}


	/* CALENDAR: If exists timepicker field */
	if( $v("#timepicker_start,#timepicker_start2").length > 0 ) {
		$v('#timepicker_start,#timepicker_start2').timepicker({
			showPeriod: true,
			showLeadingZero: true,
			onHourShow: tpStartOnHourShowCallback,
			onMinuteShow: tpStartOnMinuteShowCallback
		});
		$v('#timepicker_end,#timepicker_end2').timepicker({
			showPeriod: true,
			showLeadingZero: true,
			onHourShow: tpEndOnHourShowCallback,
			onMinuteShow: tpEndOnMinuteShowCallback
		});
	}


	/* FILEBOX: Open Upload dialog */
	$v('.sidebar a#upload_filebox').click( function(event) {
		event.preventDefault();
		$v('.modal.upload').dialog({
			modal: true,
			autoOpen: true,
			resizable: false
		});
	});


	/* FILEBOX: File SELECT */
	$v('.sidebar #filebox-file').change(function() {
		$v(this).next().html( $v(this).val() );
	});


	/* FILEBOX: Closing filebox dialog */ 
	$v(".modal.upload input.close").click( function() {
		$v('.modal.upload').dialog('close');
	});


	/* FILEBOX: Deleting filebox dialog */ 
	$v('.sidebar a.filebox.delete').click( function(event) {
		event.preventDefault();
		var the_cal = $v(this).attr('id').split('_');
		$v('.modal.fdelete #file_id').val( the_cal[1] );
		$v('.modal.fdelete #del_id').html( the_cal[1] );
		$v('.modal.fdelete').dialog({
			modal: true,
			autoOpen: true,
			resizable: false
		});
	});


	/* FILEBOX: Closing filebox dialog */ 
	$v(".modal.fdelete input.close").click( function() {
		$v('.modal.fdelete').dialog('close');
	});



	/* Ajaxing the form submission */
	$v("body").on("submit", "form#fbox_upload", function(){
		$v(this).children('h1').children('span').html('<img src="'+ vz_vj_settings.wpe_dir +'/includes/images/act.gif" />');
	});



	/* GENERAL: Generating form jquery select boxes */
	if( $v(".selectbox").length > 0 ) {
		$v('.selectbox').selectbox();
	}


	/* Post: Follow and unfollow */
	$v('a.main.follow,a.main.unfollow').click( function(event) {
		event.preventDefault();

		var f_button = $v(this);
		var action = f_button.attr('class').split(' '); action = action[1];
		var the_post = f_button.attr('id').split('_'); the_post = the_post[1];

		$v.post(vz_vj_settings.ajaxurl, { action: "vz_ufrontajax", sub_action: action+"_post", the_post: the_post } );

		if(action === 'follow') {
			f_button.removeClass().addClass('main unfollow').html('Unfollow');
		} else {
			f_button.removeClass().addClass('main follow').html('Follow');
		}

	});

	/* Author: Follow and unfollow */
	$v('a.author.follow,a.author.unfollow').click( function(event) {
		event.preventDefault();

		var f_button = $v(this);
		var action = f_button.attr('class').split(' ');  action = action[4];
		var the_author = f_button.attr('id').split('_'); the_author = the_author[1];

		$v.post(vz_vj_settings.ajaxurl, { action: "vz_ufrontajax", sub_action: action+"_author", the_author: the_author } );

		if(action === 'follow') {
			f_button.removeClass().addClass('view alignleft rounded_2 author unfollow').html('Unfollow');
		} else {
			f_button.removeClass().addClass('view alignleft rounded_2 author follow').html('Follow');
		}

	});

	/* Author: Unfollow */
	$v('a.author.single.unfollow').click( function(event) {
		event.preventDefault();

		var the_author = $v(this).attr('id').split('_'); the_author = the_author[1];
		$v.post(vz_vj_settings.ajaxurl, { action: "vz_ufrontajax", sub_action: "unfollow_author", the_author: the_author } );

		$v(this).parent().fadeOut('normal');
	});

	/* Posts: Unfollow */
	$v('a.post.single.unfollow').click( function(event) {
		event.preventDefault();

		var the_post = $v(this).attr('id').split('_'); the_post = the_post[1];
		$v.post(vz_vj_settings.ajaxurl, { action: "vz_ufrontajax", sub_action: "unfollow_post", the_post: the_post } );

		$v(this).parent().fadeOut('normal');
	});

	/* Comments: Report */
	$v('a.report.comment').click( function() {
		var the_comment = $v(this).attr('id').split('_'); the_comment = the_comment[1];
		$v.post(vz_vj_settings.ajaxurl, { action: "vz_ufrontajax", sub_action: "report_comment", the_comment: the_comment } );
	});

	/* If events slider */
	if( $v(".sliding").length > 0 ) {

		if ( $v('.sliding ul li').length > 1 ) {

			$v(".sliding").flexslider({
				animation: "fade",
				slideshow: false,
				controlNav: false, 
				directionNav: false, 
				slideshow: true,
				slideshowSpeed: vz_vj_settings.next_slide_sec,
				manualControls: ".sliding .arrows a",
				controlsContainer: ".slide",
				before: function(slider) {
					
					$v('.sliding .caption h1').fadeOut('normal', function(){
						$v('.sliding .caption h1').empty()
			    	    .html( $v('.sliding li.flex-active-slide .caption-title').html() ).fadeIn('normal');
					});

					$v('.sliding .caption p').fadeOut('normal', function(){
						$v('.sliding .caption p').empty()
			    	    .html( $v('.sliding li.flex-active-slide .caption-text').html() ).fadeIn('normal');
					});
				}

	    	});

	    }

    	$v(".sliding .caption h1").html( $v(".sliding li.flex-active-slide .caption-title").html() );
    	$v(".sliding .caption p").html( $v(".sliding li.flex-active-slide .caption-text").html() );
	}

});


/* FILEBOX EXTRA JS */
function filebox_success(message) {
	$v('#fbox_upload').hide().html(message).fadeIn('normal');
	setTimeout(function() { $v('#fbox_upload').parent().dialog('close'); }, 1000);
	setTimeout(window.location.reload(),1500);
}

function fileboxdelete_success(message) {
	$v('#filebox_delete').hide().html(message).fadeIn('normal');
	setTimeout(function() { $v('#filebox_delete').parent().dialog('close'); }, 1000);
	setTimeout(window.location.reload(),1500);
}


/* TIMEPICKER EXTRA JS */
function tpStartOnHourShowCallback(hour) {
	var tpEndHour = $v('#timepicker_end').timepicker('getHour');
	// all valid if no end time selected
	if ($v('#timepicker_end').val() === '') { return true; }
	// Check if proposed hour is prior or equal to selected end time hour
	if (hour <= tpEndHour) { return true; }
	// if hour did not match, it can not be selected
	return false;
}
function tpStartOnMinuteShowCallback(hour, minute) {
	var tpEndHour = $v('#timepicker_end').timepicker('getHour');
	var tpEndMinute = $v('#timepicker_end').timepicker('getMinute');
	// all valid if no end time selected
	if ($v('#timepicker_end').val() === '') { return true; }
	// Check if proposed hour is prior to selected end time hour
	if (hour < tpEndHour) { return true; }
	// Check if proposed hour is equal to selected end time hour and minutes is prior
	if ( (hour === tpEndHour) && (minute < tpEndMinute) ) { return true; }
	// if minute did not match, it can not be selected
	return false;
}

function tpEndOnHourShowCallback(hour) {
	var tpStartHour = $v('#timepicker_start').timepicker('getHour');
	// all valid if no start time selected
	if ($v('#timepicker_start').val() === '') { return true; }
	// Check if proposed hour is after or equal to selected start time hour
	if (hour >= tpStartHour) { return true; }
	// if hour did not match, it can not be selected
	return false;
}
function tpEndOnMinuteShowCallback(hour, minute) {
	var tpStartHour = $v('#timepicker_start').timepicker('getHour');
	var tpStartMinute = $v('#timepicker_start').timepicker('getMinute');
	// all valid if no start time selected
	if ($v('#timepicker_start').val() === '') { return true; }
	// Check if proposed hour is after selected start time hour
	if (hour > tpStartHour) { return true; }
	// Check if proposed hour is equal to selected start time hour and minutes is after
	if ( (hour === tpStartHour) && (minute > tpStartMinute) ) { return true; }
	// if minute did not match, it can not be selected
	return false;
}

function noSunday(date){ 
	var day = date.getDay(); 
	return [(day > 0), '']; 
}