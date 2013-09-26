<?php

/****************************************
* Theme Options Pages Generator Functions
****************************************/



# Page generator
function pages_generator($pages) {

    foreach ($pages as $page => $elements ) {
        page_generator($page,$elements);
    }

}



# Page generator
function page_generator($page,$elements) {
    $page = "{$page}";

    echo '<form id="'.$page.'">';

        foreach ($elements as $title => $arguments) {

            switch ($arguments['type']) {
                case 'heading':             generate_separator_heading($title,$arguments);      break;
                case 'input':               generate_input($page,$title,$arguments);            break;
                case 'doubleinput':         generate_doubleinput($page,$title,$arguments);      break;
                case 'textarea':            generate_textarea($page,$title,$arguments);         break;
                case 'checkbox':            generate_checkbox($page,$title,$arguments);         break;
                case 'selectbox':           generate_select_box($page,$title,$arguments);       break;
                case 'slider':              generate_slider($page,$title,$arguments);           break;
                case 'colorpicker':         generate_colorpicker($page,$title,$arguments);      break;
                case 'presets':             generate_presets($page,$arguments);                 break;
                case 'architecture':        generate_architecture($page);                       break;
                case 'button':              generate_button($arguments);                        break;
                case 'subscribers':         generate_subscribers($arguments);                   break;
                case 'forms':               generate_forms($arguments);                         break;
                case 'pagination':          generate_pagination($arguments);                    break;
                case 'extra_part':          generate_extra_part($page);                         break;
                case 'mtranslation':        generate_mtranslation();                            break;
                case 'short_blank':         generate_short_blank();                             break;
                case 'tall_blank':          generate_tall_blank();                              break;
            }

        }

        generate_savebox($page);

    echo '</form>';

}



######################### Elements Generation #########################

# Heading for page
function generate_separator_heading($title,$arguments) {
    $toggle = null; extract($arguments);

    ?>

    <div class="label first"> <h1> <?php echo $title; if($toggle) echo '<a href="#" class="toggle"> '.__('Toggle', 'vz_terms').' </a>'; ?> </h1> </div>

    <?php

}



# Text Input generation
function generate_input($page,$title,$arguments) {
    $file = null; $cb_show = null; $init = null; $size = null; 
    $default = null; $cclass = null; $class = null; $id = null;
    extract($arguments);
    if(!$size) { $size = 'wide'; }
    $imageShow = '';
    $actualValue = get_option("{$page}_{$name}");
    $actualValueShow = $actualValue;
    $inputname = ' name="'.$name.'" ';

    if($file) { 
        $size = ''; 


        if(get_option("{$page}_{$name}")) {

            if( is_numeric( $actualValue ) ) :
                $actualValueShow = wp_get_attachment_url( $actualValue );
                $actualValue     = $actualValueShow;
            else :
                if( ! @GetImageSize($actualValue) ) { $actualValueShow = VZ_THEMEOPTIONS_INC.'/images/notfound.png'; }
            endif;

            $imageShow = '<img src="'.$actualValueShow.'" />';
        }

        $inputfilename = ' name="'.$name.'" ';
        $inputname = '';

    } else {
        if( strlen($actualValue)==0 ) $actualValue = $default;
    }


    $actualValue = str_replace("\'","'", str_replace('\"','"', $actualValue ) );

    ?>

    <div class="content <?php echo $size; ?> <?php echo $cclass; ?>">
        <span class="info">
            <?php echo $title; if($cb_show=='yes') : ?> 
                <span style="float:right"> Show in site: 
                    <input type="checkbox" name="<?php echo $name; ?>_show" <?php echo ( get_option("{$page}_{$name}_show") ) ? 'checked="checked"' : ''; ?> value="1" /> 
                </span> 
            <?php endif; ?>
        </span>
        <div class="clear"></div>

        <div class="show"> <?php echo $imageShow; ?> </div>

        <div class="field <?php echo $size ?>">
            <?php if($init) echo "<p> $init </p>"; ?>
            <input type="text" class="<?php echo $class.' '.$size; ?>"  id="<?php echo $id; ?>" value="<?php echo $actualValue; ?>" <?php echo $inputname; ?> />
        </div>

        <?php if($file) : ?>

            <span class="or"> <?php _e('or', 'vz_terms'); ?> </span>
            <a href="#" id="browse"> <?php _e('BROWSE', 'vz_terms'); ?> </a>
            <input type="hidden" value="<?php echo get_option("{$page}_{$name}"); ?>" <?php echo $inputfilename; ?> />

        <?php endif; ?>

    </div>

    <?php

}



# Text Double Input generation
function generate_doubleinput($page,$title,$arguments) {
    $imageShow = null; $init = null; $class = null; $id = null;
    extract($arguments);
    $actualValue = str_replace("\'","'", str_replace('\"','"', get_option("{$page}_{$name}") ) );
    $actualValue2 = str_replace("\'","'", str_replace('\"','"', get_option("{$page}_{$name}2") ) );

    ?>

    <div class="content">
        <span class="info"><?php echo $title; ?>:</span>
        <div class="clear"></div>

        <div class="show"> <?php echo $imageShow; ?> </div>

        <div class="field shorter">
            <?php if($init) echo "<p> $init </p>"; ?>
            <input type="text" class="<?php echo $class; ?>"  id="<?php echo $id; ?>" value="<?php echo $actualValue; ?>" name="<?php echo "{$name}"; ?>" />
        </div>

        <div class="field">
            <?php if($init) echo "<p> $init </p>"; ?>
            <input type="text" class="<?php echo $class; ?>"  id="<?php echo $id; ?>" value="<?php echo $actualValue2; ?>" name="<?php echo "{$name}2"; ?>" />
        </div>

    </div>

    <?php

}


# Slider generation
function generate_slider($page,$title,$arguments) {
    extract($arguments);
    $actualValue = get_option( "{$page}_{$name}", $default_val );
    ?>

    <div class="content">
        <span class="info"><?php echo $title; ?>: </span>
        <input type="text" class="s_value" name="<?php echo $name; ?>" value="<?php echo $actualValue; ?>" />
        <div class="slider"></div>
        <div class="clear"></div>
    </div>

    <?php

}



# Select Box generation
function generate_select_box($page,$title,$arguments) {
    $none = null; $id = null; $class = null; $use_key = null; 
    $default = null; $cclass = null; $size = null;
    extract($arguments);
    $actualValue = get_option( "{$page}_{$name}" );
    if(!$none) { $none = __('None', 'vz_terms'); }

    ?>

    <div class="content <?php echo $size; ?> <?php echo $cclass; ?>">
        <span class="info"><?php echo $title; ?>: </span>
        <div class="clear"></div>
        <select name="<?php echo $name; ?>" id="<?php echo $id; ?>" class="selectbox <?php echo $class; ?>">
            <option value=""> <?php echo $none; ?> </option>
            <?php if(is_array($options)) : foreach ($options as $key => $option) :
                $the_key = ($use_key) ? $key : $option; ?>
                <option value="<?php echo ($use_key) ? $key : $option; ?>"
                    <?php echo ($the_key==$default) ? 'selected="selected"' : ''; ?>> 
                    <?php echo ucfirst($option); ?> </option>
            <?php endforeach; endif; ?>
        </select>
    </div>

    <?php

}



# Color Picker generation
function generate_colorpicker($page,$title,$arguments) {
    $default = null; $class = null; extract($arguments);
    $actualcolor = get_option("{$page}_{$name}", $default); 

    ?>

    <div class="content <?php echo $size ?>">
        <span class="info"><?php echo $title; ?>:</span>
        <div class="clear"></div>

        <div class="field colorpicker">
            <?php if($init) echo "<p> $init </p>"; ?>
            <input type="text" name="<?php echo $name; ?>" value="<?php echo $actualcolor; ?>" class="<?php echo $class; ?>" style="background-color: <?php echo $actualcolor; ?>" />
            <div id="picker" style="display:none"></div>
        </div>

    </div>

    <?php

}



# Text Area generation
function generate_textarea($page,$title,$arguments) {
    $file = null; $class = null; $id = null; $height = null;
    extract($arguments);
    $size = 'wide';
    if($file) { $size = ''; }

    ?>

    <div class="content">
        <span class="info"><?php echo $title; ?>:</span>
        <div class="clear"></div>

        <div class="text <?php echo $size ?>">
            <textarea class="<?php echo $class.' '.$size; ?>" id="<?php echo $id; ?>" 
                name="<?php echo $name; ?>" <?php echo ($height) ? 'style="height: '.$height.'px"' : ''; ?> ><?php echo esc_textarea( str_replace("\'","'", str_replace('\"','"', get_option("{$page}_{$name}") ) ) ); ?></textarea>
        </div>

        <?php if($file) : ?>

            <span class="or"> <?php _e('or', 'vz_terms'); ?> </span>
            <a href="#" id="browse"> <?php _e('BROWSE', 'vz_terms'); ?> </a>

        <?php endif; ?>

    </div>

    <?php

}



# Checkbox generation
function generate_checkbox($page,$title,$arguments) {
    $default = null; 
    extract($arguments); 
    $_random_id = rand(00,99);
    if( get_option("{$page}_{$name}") == 'enabled' || $default =='enabled') { $state = 'checked="checked"'; } else { $state = ''; }
    if( get_option("{$page}_{$name}") == 'disabled' ) { $state = ''; }

    ?>

    <div class="checkbox">
        <div class="first-column"> <h1><?php echo $title; ?>:</h1> </div>
        <div class="second-column">

            <div class="onoffswitch">
                <input type="checkbox" class="hcbox" id="switch<?php echo $_random_id ?>" <?php echo $state; ?> >
                <label class="onoffswitch-label" for="switch<?php echo $_random_id ?>">
                    <div class="onoffswitch-inner"></div>
                    <div class="onoffswitch-switch"></div>
                </label>
            </div>
            <input type="hidden" name="<?php echo $name; ?>" value="<?php echo get_option("{$page}_{$name}"); ?>" />

        </div>
        <div class="third-column">
            <p> <?php echo $description; ?> </p>
        </div>
    </div>

    <?php

}



# Button sample generation
function generate_button($arguments) {
    extract($arguments);
    ?>

    <div class="content short blank button_sample"><input type="button" value="<?php _e('Sample','vz_terms'); ?>" class="<?php echo $class; ?>" /></div>

    <?php

}



# Blank field generation
function generate_short_blank() {

    ?>

    <div class="content short blank"></div>

    <?php

}



# Blank field generation
function generate_tall_blank() {

    ?>

    <div class="content tall blank"></div>

    <?php

}



# Presets generation
function generate_presets($page,$arguments) {
    extract($arguments);

    $actualPreset = get_option("{$page}_{$name}",'red');

    ?>

    <div class="content">

        <ul class="presets">

        <?php foreach ($presets as $preset) :
              $checked = ($preset == $actualPreset) ? 'checked="checked"' : '' ; ?>

            <li> 
                <img src="<?php echo VZ_THEMEOPTIONS_INC."/skins/{$preset}.png"; ?>" /> <br/>
                <input type="radio" name="<?php echo $name; ?>" value="<?php echo $preset; ?>" <?php echo $checked; ?> /> 
                <?php echo ucfirst($preset); ?>
            </li>

        <?php endforeach; ?>

        </ul>

        <div class="clear"></div>
    </div>

    <?php

}



# Architecture generation
function generate_architecture($page) {
    $con_terms = array(
        'social'        => __('Social',             'vz_terms'),
        'topmenu'       => __('Top menu',           'vz_terms'),
        'logo'          => __('Logo',               'vz_terms'),
        'sidebar'       => __('Sidebar',            'vz_terms'),
        'content'       => __('Content',            'vz_terms'),
        'footer1'       => __('Widget 1',           'vz_terms'),
        'footer2'       => __('Widget 2',           'vz_terms'),
        'footer3'       => __('Widget 3',           'vz_terms'),
        'footer4'       => __('Widget 4',           'vz_terms'),
        'footer_nav'    => __('Footer Navigation',  'vz_terms'),
    );

    $_containers    = array('header' => __('Header', 'vz_terms'),
                            'body'   => __('Body', 'vz_terms'),
                            'footer' => __('Footer', 'vz_terms'),);

    $containers = array();
    $containers['header'] = explode(',', get_option("{$page}_header",   'social,topmenu') );
    $containers['body']   = explode(',', get_option("{$page}_body",     'sidebar,content') );
    $containers['footer'] = explode(',', get_option("{$page}_footer",   'footer1,footer2,footer3,footer4') ); 

    echo '<p style="padding:20px;margin-bottom: -20px;clear:both"> Hints: drag & drop, click the tick icon to enable/disable container </p>';

    $show_logo = FALSE; $_logo_disabled = null;
    foreach ($_containers as $_index => $_container) {

    ?>

    <div class="architecture-content">

        <h1> <?php echo $_container; ?>: </h1>
        <ul class="sortable <?php echo $_index; ?>">
            <?php 
            
            foreach ($containers[$_index] as $container) :
                $boxclass = '';
                if($_index == 'body' && $container == 'content') { $boxclass = ' big'; }
                if($_index == 'footer') { $boxclass = ' footer'; }

                $_disabled = '';
                if(get_option("{$page}_{$_index}_{$container}_disabled")) {
                    $_disabled = ' disabled';
                }

                ?>

                <li class="box<?php echo $boxclass.$_disabled; ?> <?php echo $container; ?>"> 
                    <h2> <?php echo $con_terms[$container]; ?> </h2>
                    <input type="hidden" name="<?php echo $_index; ?>[]" value="<?php echo $container.':'.$_disabled; ?>" />
                    <?php if($container!='content' && $container!='sidebar') : ?> <a href="#" class="status<?php echo $_disabled; ?>"></a> <?php endif; ?>
                </li>


            <?php endforeach;

            ?>
        </ul>

        <?php

            if($_index=='header') : 

                if(get_option("{$page}_{$_index}_logo_disabled")) {
                    $_logo_disabled = ' disabled';
                } ?>

                <li class="box disabled logo"> 
                    <h2> <?php echo $con_terms['logo']; ?> </h2>
                    <input type="hidden" name="<?php echo $_index; ?>[]" value="<?php echo 'logo:'.$_disabled; ?>" />
                    <a href="#" class="status<?php echo $_logo_disabled; ?>"></a>
                </li>

        <?php endif; ?>
        <div class="clear"></div>
    </div>

    <?php

    }

}



# Savebox generation
function generate_savebox($page) {

    $button = __('Save Changes', 'vz_terms');
    if($page=='vz_plugins_newsletter') $button = __('Send message', 'vz_terms');

    ?>

    <div class="clear"></div>

    <div class="message-holder">
        <input type="submit" name="save" id="save" value="<?php echo $button ?>" />
        <div id="message">
            <?php // <p>$ajaxedmessage</p> <img id="type" src="ajaxedimage" /> ?>
        </div>
    </div>

    <?php

}



# Extra part
function generate_extra_part($page) {
    $blogging       = get_option('vz_plugins_extra_blogging');
    $calendar       = get_option('vz_plugins_extra_calendar');
    $file_sharing   = get_option('vz_plugins_extra_file_sharing');

    if( $blogging || $calendar || $file_sharing ) : ?>

        <div class="label first"> <h1> <?php _e('Manage extra plugins', 'vz_terms'); ?> <a href="#" class="toggle"> <?php _e('Toggle', 'vz_terms'); ?> </a> </h1> </div>
        <div>

            <div class="extra_content">

                <?php if( $blogging ) : ?>
                    <div id="blogging" class="show">

                        <?php generate_checkbox($page,__('Professors can post', 'vz_terms'),array( 'name' => 'profpost_disabled','default' => 'enabled', 'description' => __('Let professors post their opinions and researches.', 'vz_terms') )); ?>
                        <?php generate_checkbox($page,__('Students can post', 'vz_terms'),array( 'name' => 'studpost_disabled','default' => 'enabled', 'description' => __('Let students post their opinions and researches.', 'vz_terms') )); ?>

                    </div>

                    <div id="user_groups" class="hide">

                        <div class="alignleft" style="width:240px;height:270px;border-bottom:1px solid #e2e2e2;border-right:1px solid #e2e2e2;">
                            <div class="content half" style="padding: 18px;border-bottom:1px solid #e2e2e2;margin-bottom:25px;">
                                <span class="info no_margin"><?php _e('Add group', 'vz_terms'); ?></span>
                            </div>
                            <?php generate_input($page,__('Name', 'vz_terms'), array('name'=>'n_name','id' => 'n_name', 'size'=>'half','cclass'=>'less_padding')); ?>

                            <a class="main" id="confirm_add"> <?php _e('Confirm','vz_terms'); ?> </a>
                        </div>

                        <div class="alignright" style="width:257px;height:270px;border-bottom:1px solid #e2e2e2;">
                            <div class="content half" style="padding: 18px;border-bottom:1px solid #e2e2e2;margin-bottom:25px;">
                                <span class="info no_margin"><?php _e('Edit group', 'vz_terms'); ?></span>
                            </div>
                            <?php generate_select_box($page,__('Group', 'vz_terms'), array('name' => 'a_group','id' => 'a_group', 'size'=>'half','cclass'=>'less_padding', 'options' => @array_reverse( get_option('professor_groups') ) )); ?>
                            <?php generate_input($page,__('Name', 'vz_terms'), array('name' => 'a_name','size'=>'half','id' => 'a_name','cclass'=>'less_padding')); ?>

                            <a class="main" id="confirm_edit"> <?php _e('Confirm','vz_terms'); ?> </a>
                            <a class="second" id="confirm_delete"> <?php _e('Delete','vz_terms'); ?> </a>

                        </div>

                    </div>
                <?php endif; ?>

                <?php if( $calendar ) : ?>
                    <div id="calendar" <?php echo ($blogging) ? 'class="hide"' : 'class="show"'; ?> >
                        <?php generate_checkbox($page,__('Professors can use calendar', 'vz_terms'),array( 'name' => 'profcal_disabled','default' => 'enabled', 'description' => __('Let professors use calendar and let students know their lectures easily.', 'vz_terms') )); ?>
                        <?php generate_checkbox($page,__('Student can use calendar', 'vz_terms'),array( 'name' => 'studcal_disabled','default' => 'enabled', 'description' => __('Let students manage their time using the calendar.', 'vz_terms') )); ?>
                    </div>
                <?php endif; ?>

                <?php if( $file_sharing ) : ?>
                    <div id="file_sharing" <?php echo ($blogging || $calendar) ? 'class="hide"' : ' class="show"'; ?> >
                        <?php generate_checkbox($page,__('Professors can upload file to Filebox', 'vz_terms'),array( 'name' => 'proffile_disabled','default' => 'enabled', 'description' => __('Filebox helps professors upload their ebooks, projects, slides to public download.', 'vz_terms') )); ?>
                        <?php generate_checkbox($page,__('Student can upload file to Filebox', 'vz_terms'),array( 'name' => 'studfile_enabled','default' => 'disabled', 'description' => __('If you are sure. You can allow students to upload their files to public download.', 'vz_terms') )); ?>
                    </div>
                <?php endif; ?>            

                <div class="clear"></div>
            </div>
            <div class="clear"></div>

            <div class="extra_buttons">

                <?php if( $blogging ) : ?>
                    <a href="#" class="blogging" id="active"> <?php _e('Blogging','vz_terms'); ?> </a>
                    <a href="#" class="user_groups"> <?php _e('User Groups', 'vz_terms'); ?> </a>
                <?php endif; ?>

                <?php if( $calendar ) : ?>
                    <a href="#" class="calendar" <?php echo ($blogging) ? '' : 'id="active"'; ?>> <?php _e('Calendar',    'vz_terms'); ?> </a>
                <?php endif; ?>

                <?php if( $file_sharing ) : ?>
                    <a href="#" class="file_sharing" <?php echo ($blogging || $calendar) ? '' : 'id="active"'; ?>> <?php _e('File Sharing','vz_terms'); ?> </a>
                <?php endif; ?>

                <div class="clear"></div>

            </div>

        </div>

    <?php

    endif;

}



# Manual translation
function generate_mtranslation() { ?>

        <div class="clear"></div>

        <div>

            <div class="extra_content" style="padding:8px">

                <br/>
                <span style="padding:10px"> <?php _e('How to translate theme terms by po file via POEdit.','vz_terms'); ?> </span>

                <iframe width="480" height="275" src="http://www.youtube.com/embed/1xqcxncNmT0" frameborder="0" allowfullscreen></iframe>
                <br/><br/>
           
                <span style="padding:10px"> <?php _e('How to translate theme terms by Codestyling Localization plugin for wordpress.','vz_terms'); ?> </span>

                <iframe width="480" height="275" src="http://www.youtube.com/embed/9jIc_La6C3M" frameborder="0" allowfullscreen></iframe>

                <div class="clear"></div>
            </div>
            <div class="clear"></div>

        </div>

    <?php

}