var $v = jQuery.noConflict();

$v(function() {

	/* Superfishing main navigation */
	$v('#main_menu').superfish({ speed:'fast', autoArrows: false, delay: 500 });

	/* Before firing submit */
	$v('#subscribe_button').click(function() { $v(this).parent().attr('id','subscribe'); });
	$v('#unsubscribe_button').click(function() { $v(this).parent().attr('id','unsubscribe'); });

	/************ GENERAL ************/
	/* Ajaxing the form submission */
	$v("body").on("submit", "form", function(event){

		if( $v(this).attr('class') === 'vz_ajax' ) {
			event.preventDefault();
			var the_form = $v(this);

			var action = "&action="+vz_vj_settings.ajaxaction+"&sub_action=" + $v(this).attr('id');

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
			}

			$v.post(vz_vj_settings.ajaxurl, $v(this).serialize() + action , function(response) {
				the_form.hide().html(response).fadeIn('normal');
			});

			if( the_form.parent().hasClass('class') === 'dialog' ) {
				the_form.parent().dialog('close');
			}
		}

		if( $v(this).attr('class') === 'vzforms_ajax' ) {
			event.preventDefault();
			var the_form2 = $v(this);
			var sub_action = $v(this).attr('id').split('_');

			var count_errors = 0;
			var faction = "&action=" + vz_vj_settings.ajaxaction + "&sub_action=" + sub_action[0] + "&form_id=" + sub_action[1];

			$v( ".vzinput_req" ).each(function() {
				if( $v(this).val().length === 0 ) { $v(this).addClass( "warn_border" ); count_errors++; }
			});

			if(count_errors===0) { 

				$v.post(vz_vj_settings.ajaxurl, $v(this).serialize() + faction , function(response) {
					the_form2.hide().html(response).fadeIn('normal');
				});

			}

		}

	});

	/* Removing warn borders in any input when trying to type again */
	$v("body").on("keyup", "input", function(){
		if( $v(this).hasClass('warn_border') ) {
			$v(this).removeClass('warn_border');
		}
	});


	/************ CONTENT ACTIONS ************/
	/* Ajaxing featured posts */
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

	/* Replying into any comment */
	$v('.content .comment .comment-content a.reply_comment').click( function() {

		var cid = $v(this).attr('class').split(' '); 
		cid = cid[1].split('_'); cid = cid[1];
		$v('#comment_parent').val(cid);

	});

	/* Dynamic dialogs */
	if( $v('.container .dialog').length>0 ) {
		$v('.container .dialog').dialog({
			modal: true,
			autoOpen: false,
			resizable: false
		});
	}

	/* Open dialog */
	$v('.container a#add_event').click( function(event) {
		event.preventDefault();
		$v('.dialog').dialog('open');
	});

	/* Closing a modal dialog */ 
	$v('.dialog input.close').click( function() {
		$v('.dialog').dialog('close');
	});

	/* Loading more items */ 
	$v('#load_more').click( function(event) {
		event.preventDefault();

		var item_id = $v('#item_id').val();
		var item_type = $v(this).attr('class');
		var item_offset = $v('#item_offset');
		var max_offset = $v('#max_offset').val();
		var counter = $v('#count-showing');

		if( item_offset.val() !== max_offset ) {

			$v('#more_content').append('<div id="loading_gif" style="margin:10px"> <div class="clear"></div> <img src="'+ vz_vj_settings.wpe_dir +'/includes/images/act.gif" style="margin:0 auto;display:block" /> </div>');
			$v.post(vz_vj_settings.ajaxurl, { 
				action: vz_vj_settings.ajaxaction, 
				sub_action: 'load_more', 
				post_id: item_id, 
				post_type: item_type,
				load_offset: item_offset.val()
			},function(response) { 
				$v('#loading_gif').remove();
				$v('#more_content').append(response);
				item_offset.val( parseInt( item_offset.val(), 10 ) + 1 );
				if( item_offset.val() === max_offset ) {
					counter.html( $v('#all_items').html() );
				} else {
					counter.html( parseInt(counter.html(), 10) + 30 );
				}

			});

		}
	});


	/* Sliding next */ 
	$v('.slider .arrows #prev,.slider .arrows #next').click( function(event) {
		event.preventDefault();

		var slide = $v(this).attr('id');
		switch(slide) {
			case 'next' : 
				$v('.sliding').flexslider("next");
			break;
			case 'prev' : 
				$v('.sliding').flexslider("prev");
			break;
		}

	});


	/* Popular widget tablinks */ 
	$v('.widget.popular .tab-links a').click( function(event) {
		event.preventDefault();
		var pop_tab = $v(this);

		switch (pop_tab.attr('class')) {
			case 'popular' :
				pop_tab.addClass('active');
				pop_tab.next().removeClass('active');
				pop_tab.parent().nextAll('.display').fadeOut('fast',function(){
					$v(this).removeClass().prev().addClass('display').fadeIn('fast');
				});
			break;

			case 'commented' :
				pop_tab.addClass('active');
				pop_tab.prev().removeClass('active');
				pop_tab.parent().nextAll('.display').fadeOut('fast',function(){
					$v(this).removeClass().next().addClass('display').fadeIn('fast');
				});
			break;
		}

	});


	/************ ELEMENTS POLYMORPH ************/
	/* If events ticker exists */
	if( $v(".ticker").length > 0 ) {
		$v('.ticker').Ticker();
	}

	/* If vz_tabs exists */
	if( $v(".vz_tabs").length > 0 ) {
		$v('.vz_tabs').tabs();
	}

	/* If vz_accordion exists */
	if( $v(".vz_accordion").length > 0 ) {
		$v('.vz_accordion').accordion();
	}

	/* If mixitup exists */
	if( $v(".mixitup").length > 0 ) {
		$v('.mixitup').mixitup();
	}

	
/*
	if( $v("#tomorrow_events").length > 0 ) {

	} */

	/* If datepicker exists */
	if( $v(".datepicker").length > 0 ) {
		$v('.datepicker').datepicker({ dateFormat: 'dd MM yy', beforeShowDay: noSunday, firstDay: 1 });
	}

	/* Generating form jquery select boxes if exists */
	if( $v(".selectbox").length > 0 ) {
		$v('.selectbox').selectbox();
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

$v.fn.teletype = function(opts){
	var $this = this,
		defaults = {
			animDelay: 50
		},
		settings = $v.extend(defaults, opts);

	$v.each(settings.text, function(i, letter){
		setTimeout(function(){
			$this.html($this.html() + letter);
		}, settings.animDelay * i);
	});
};