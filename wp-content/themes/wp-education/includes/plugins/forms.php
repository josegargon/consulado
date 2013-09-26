<?php

/****************************************
* Forms plugin
****************************************/



# Registering the newsletter post type which will save subscribers
add_action( 'init', 'register_vz_custom_forms' );
function register_vz_custom_forms() {

    $args = array(
        'label'              => 'Forms',
        'labels'             => array('name' => _x('Forms', 'Post type general name','vz_terms')),
        'supports'           => array('title'),
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => false,
        'show_in_menu'       => false,
        'show_in_nav_menus'  => false,
        'query_var'          => false,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false
    );

    register_post_type( 'vz_custom_forms', $args );
}



# Actual forms (Page generator element) [ADMIN SIDE, theme options]
function generate_forms($arguments) {
    global $wpdb;
    $totalForms = $wpdb->get_row(" SELECT COUNT(*) as total FROM $wpdb->posts WHERE $wpdb->posts.post_type = 'vz_custom_forms' ",ARRAY_A);
    $totalForms = $totalForms['total'];

    ?>

    <div class="content">

        <div class="idents gray"> <span> Name </span> <span class="shortcode"> Shortcode </span> <span class="alignright"> Action </span>  </div>
        <ul class="forms_list">

        </ul>

        <input type="hidden" id="total" value="<?php echo $totalForms; ?>" />
        <input type="hidden" id="offset" value="0" />

        <a href="#" class="prev"> Prev </a>
        <a href="#" class="next"> Next </a>

        <div class="clear"></div>
    </div>

    <div class="label first"> <h1> Manage form <span class="formstatus">Add new</span></h1> </div>

    <div class="content">
        <span class="info"> Email (receiver): </span>
        <div class="clear"></div>
        <div class="field wide">
            <input type="text" name="formemail" id="formemail" class="wide">
        </div>
    </div>

    <div class="content">
        <span class="info">Form name:</span>
        <div class="clear"></div>
        <div class="field wide">
            <input type="text" name="formname" id="formname" class="wide">
        </div>
    </div>

    <div class="content" id="theform">
        <input type="hidden" name="form_id" id="form_id" value="" />
        <span class="info">Form elements:</span>
        <div class="clear"></div>

        <div class="idents gray"> 
            <div style="width:25%"> Label </div> 
            <div style="width:25%"> Placeholder </div> 
            <div style="width:25%"> Type </div>
            <div style="width:25%"> Required </div>
        </div>
        <div class="clear"></div>

        <ul class="elements">
            <li>
                <div> <input type="text" name="fieldname[]" /> </div>
                <div> <input type="text" name="placeholder[]" /> </div>
                <div>
                    <select name="type[]" style="height:25px">
                        <option value="text"> Text </option> 
                        <option value="email"> Email </option>
                        <option value="textarea"> Text Area </option>
                        <option value="checkbox"> Checkbox </option>
                    </select>
                </div>
                <div>
                    <select name="required[]" style="height:25px">
                        <option value=""> No </option>
                        <option value="yes"> Yes </option> 
                    </select>
                </div>
            </li>
        </ul>

        <div class="clear"></div>
        <a class="add-new-element" href=""> +New field </a>
    </div>

    <div class="content">
        <span class="info">Submit button name:</span>
        <div class="clear"></div>
        <div class="field wide">
            <input type="text" name="submitname" id="submitname" class="wide">
        </div>
    </div>
    <?php

}



