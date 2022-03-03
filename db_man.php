<?php
/**
 * Plugin Name: Video Database
 * Plugin URI: http://github.com/vaclmat/videodb
 * Description: Plugin for video database filling and update
 * Version: 1.0
 * Author: vaclmat
 * Author URI: http://github.com/vaclmat
 * License: Plugin comes under GPL Licence.
 */

function db_man_options_page() {
    add_menu_page(
        'Video database',
        'Video DB',
        'manage_options',
        'db_man_example',
        'db_man_options_page_html',
        plugin_dir_url(__FILE__) . 'images/video-camera-2.png'
    );
}
 
 
/**
 * Register our jqueryajaxexample_options_page to the admin_menu action hook.
 */
add_action( 'admin_menu', 'db_man_options_page' );


// Register the script

wp_enqueue_script('vaclmat', plugins_url( '/js/demo.js' , __FILE__ ) , array( 'jquery' ));
wp_enqueue_style('vaclmat', plugins_url( '/css/videodb_test.css' , __FILE__ ) , array( ));
// including ajax script in the plugin Myajax.ajaxurl
wp_localize_script( 'vaclmat', 'DB_man', array( 'ajaxurl' => admin_url( 'admin-ajax.php')));

/**
 * Top level menu callback function
 */
function db_man_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    global $wpdb;
    $arr_videos = $wpdb->get_results( "SELECT id, videoname, linktv FROM ".$wpdb->prefix."mbvideos" );
    ?>
 
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <br>
        <div class="row">
            <div class="column">
                <br><br><br>
                <p>Available video names:</p>
                <select name="videos" class="videos" id="videos">
                    <option value="default">--SELECT VIDEO--</option>
                    <?php foreach ( $arr_videos as $video ) : ?>
                    <option value='{ "id":"<?php echo $video->id; ?>", "name":"<?php echo $video->videoname; ?>", "linktv":"<?php echo $video->linktv; ?>" } '> <?php echo $video->videoname; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="column">
                <p>Add video form:</p>
                <form id='addvideof'>
                    <label>Video name</label><br>
                    <input type='text' id='videoname' name='videoname' value=''/><br/>
                    <label>Link to video</label><br>
                    <input type='text' id='linktv' name='linktv' value=''/><br/>
                    <br>
                    <input type='button' id='addvideob' name='addvideob' value='Add video' disabled="true"/>
                </form>
            </div>
            <div class="column">
                <p>Update/Remove video form:</p>
                <form id='urvideof'>
                    <label>Video name</label><br>
                    <input type="text" id="urvideoname" class="urvideoname" name="urvideoname" value='' readonly="true"/><br/>
                    <label>Link to video</label><br>
                    <input type="text" id="urlinktv" class="urlinktv" name="urlinktv" value=""/><br/>
                    <br>
                    <input type="button" id="removevideob" name="removevideob" value="Remove video" disabled="true"/>
                    <input type="button" id="updatevideob" name="updatevideob" value="Update link to video" disabled="true"/>
                </form>
            </div>
        </div>
        <br>
        <div class row2>
            
            <div class="column2">
                <p>User(s) with access to selected video:</p><br>
                <select name="userswas" class="userswas" id="userswas">
                    <option value="default">--SELECT USER--</option>
                </select>
            </div> 
        </div>
        <div class="column2">
                <p>Add user for selected video form:</p>
                <form id="adduserwaf">
                    <label>User name</label><br>
                    <select id="userwaname" name="userwaname" class="userwaname">
                        <option value="default">-SELECT WP USER-</option>
                    </select><br>
                    <br>
                    <input type="button" id="adduserwab" name="adduserwab" value="Add user with access" disabled="true"/>
                </form>
            </div>
            <div class="column2">
                <p>Remove selected user for selected video form:</p>
                <form id="ruserwaf">
                    <label>User name</label><br>
                    <input type='text' id='ruserwaname' name='ruserwaname' value='' readonly />
                    <input type='hidden' id='ruserwaid' name='ruserwaid' value='' /><br/>
                    <br>
                    <input type="button" id="removeuserwab" name="removeuserwab" value="Remove user" disabled="true"/>
                </form>
            </div>
        
        <br>
        
    </div>

    <?php
}


function post_video_to_db(){
    $video_name = filter_input(INPUT_POST,'videoname');
    $link_tv = filter_input(INPUT_POST,'linktv');
    if ($video_name != '' && $link_tv != '') {
        global $wpdb;
        $wpdb->insert(
            'wp_mbvideos',
            array(
                    'videoname' => $video_name,
                    'linktv' => $link_tv
            ),
            array(
                    '%s',
                    '%s'
            )
        );
    }
    die();
    return true;
}
add_action('wp_ajax_post_video_to_db', 'post_video_to_db');
add_action('wp_ajax_nopriv_post_video_to_db', 'post_video_to_db');

function post_remove_video(){
    global $wpdb;
    $id = filter_input(INPUT_POST,'id');
    $table = 'wp_mbvideos';
    $wpdb->delete( $table, array( 'id' => $id ) );
    die();
    return true;
}
add_action('wp_ajax_post_remove_video', 'post_remove_video');
add_action('wp_ajax_nopriv_post_remove_video', 'post_remove_video');

function post_update_video(){
    global $wpdb;
    $id = filter_input(INPUT_POST,'id');
    $videonameu = filter_input(INPUT_POST,'videoname');
    $linktvu = filter_input(INPUT_POST,'linktv');
    $table = 'wp_mbvideos';
    $exu = $wpdb->update(
            $table,
            array(
                    'videoname' => $videonameu,
                    'linktv' => $linktvu
            ),
            array('id' => $id),
            array(
                    '%s',
                    '%s'
            ),
             array('%d'));
    if($exu > 0) {
        echo "Successfully Updated";
    } else {
        exit( var_dump( $wpdb->last_query ) );
    }
    $wpdb->flush();
    die();
    return true;
}
add_action('wp_ajax_post_update_video', 'post_update_video');
add_action('wp_ajax_nopriv_post_update_video', 'post_update_video');

function get_users_by_ajax() {
    global $wpdb;
    $arr_users = $wpdb->get_results( $wpdb->prepare( "SELECT id, user_name, vid FROM ".$wpdb->prefix."mbvideos_userwa WHERE vid = %d", filter_input(INPUT_POST,'vid') ) );
    $users_arr = array();
    if ( $arr_users ) {
            foreach ($arr_users as $userwa) {
                $id = $userwa->id;
                $user_name = $userwa->user_name;
                $vid = $userwa->vid;

                $users_arr[] = array("id"=>$id, "user_name"=>$user_name, "vid"=>$vid);
            }
    }
//    wp_die();
    echo json_encode($users_arr);
}

add_action('wp_ajax_get_users_by_ajax', 'get_users_by_ajax');
add_action('wp_ajax_nopriv_get_users_by_ajax', 'get_users_by_ajax');

function offer_users_by_ajax() {
    global $wpdb;
    $arr_wp_users = $wpdb->get_results( $wpdb->prepare( "SELECT id, user_login FROM ".$wpdb->prefix."users" ) );
    $arr_users_fsv = $wpdb->get_results( $wpdb->prepare( "SELECT id, user_name, vid FROM ".$wpdb->prefix."mbvideos_userwa WHERE vid = %d", filter_input(INPUT_POST,'vid') ) );
    $wp_users_arr = array();

    if ( $arr_wp_users ) {
            foreach ($arr_wp_users as $wp_user) {
//                $user_id = $wp_user->id;
                $user_login = $wp_user->user_login;
//                $wp_users_arr[] = array("id"=>$user_id, "user_name"=>$user_login);
//                $wp_users_arr[] = array("user_name"=>$user_login);
                $wp_users_arr[] = $user_login;
            }
    }
    $fsv_arr_users  = array();
    $fsv_arr_userso  = array();
    if ( $arr_users_fsv ) {
            foreach ($arr_users_fsv as $fsv_user) {
                $user_id_fsv = $fsv_user->id;
                $user_name = $fsv_user->user_name;
                $vid_fsv = $fsv_user->vid;
                $fsv_arr_users[] = array("id"=>$user_id_fsv, "user_name"=>$user_name, "vid"=>$vid_fsv);
                $fsv_arr_userso[] = $user_name;
            }
    }
    $difference = array_values(array_diff($wp_users_arr, $fsv_arr_userso));
    echo json_encode($difference);
}

add_action('wp_ajax_offer_users_by_ajax', 'offer_users_by_ajax');
add_action('wp_ajax_nopriv_offer_users_by_ajax', 'offer_users_by_ajax');

function post_adduser_to_db(){
    $userwa_name = filter_input(INPUT_POST,'userwaname');
    $vid = filter_input(INPUT_POST,'vid');
    if ($userwa_name != '' && $vid != '') {
        global $wpdb;
        $wpdb->insert(
            'wp_mbvideos_userwa',
            array(
                    'user_name' => $userwa_name,
                    'vid' => $vid
            ),
            array(
                    '%s',
                    '%d'
            )
        );
    }
    die();
    return true;
}
add_action('wp_ajax_post_adduser_to_db', 'post_adduser_to_db');
add_action('wp_ajax_nopriv_post_adduser_to_db', 'post_adduser_to_db');

function post_remove_userwa(){
    global $wpdb;
    $ruserwaid = filter_input(INPUT_POST,'ruserwaid');
    $table = 'wp_mbvideos_userwa';
    $wpdb->delete( $table, array( 'id' => $ruserwaid ) );
    die();
    return true;
}
add_action('wp_ajax_post_remove_userwa', 'post_remove_userwa');
add_action('wp_ajax_nopriv_post_remove_userwa', 'post_remove_userwa');