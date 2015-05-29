<?php
/*
Plugin Name: Restrict Private Messaging
Plugin URI: http://coderspress.com/forum/restrict-private-messaging
Description: Restricts private messaging through membership levels.
Version: 2015.0526
Updated: 26th March 2015
Author: sMarty 
Author URI: http://coderspress.com
License:
*/
add_action( 'init', 'rm_plugin_updater' );
function rm_plugin_updater() {
	if ( is_admin() ) { 
	include_once( dirname( __FILE__ ) . '/updater.php' );
		$config = array(
			'slug' => plugin_basename( __FILE__ ),
			'proper_folder_name' => 'Restrict-Messaging',
			'api_url' => 'https://api.github.com/repos/CodersPress/Restrict-Private-Messaging',
			'raw_url' => 'https://raw.github.com/CodersPress/Restrict-Private-Messaging/master',
			'github_url' => 'https://github.com/CodersPress/Restrict-Private-Messaging',
			'zip_url' => 'https://github.com/CodersPress/Restrict-Private-Messaging/zipball/master',
			'sslverify' => true,
			'access_token' => 'b64f7857d1949f086ccdca4ca26a632c62e0d349',
		);
		new WP_RM_UPDATER( $config );
	}
}
add_action('admin_menu', 'restrict_messaging_menu');
function restrict_messaging_menu() {
	add_menu_page('Restrict Messaging', 'Restrict PM\'s', 'administrator', __FILE__, 'restrict_messaging_page',plugins_url('/images/i_icon_pm.png', __FILE__));
	add_action( 'admin_init', 'register_restrict_messaging_settings' );
}
function register_restrict_messaging_settings() {
    register_setting("restrict-messaging-settings-group", "restrict_messaging_priceStructure");
    register_setting("restrict-messaging-settings-group", "restrict_messaging_alert_box");
	register_setting("restrict-messaging-settings-group", "restrict_messaging_alert_title");
	register_setting("restrict-messaging-settings-group", "restrict_messaging_alert_subtitle");
    register_setting("restrict-messaging-settings-group", "restrict_messaging_alert_message");
    register_setting("restrict-messaging-settings-group", "restrict_messaging_alert_custom_img");
}
function restrict_messaging_defaults()
{
    $option = array(
        "restrict_messaging_priceStructure" => "<",
        "restrict_messaging_alert_box" => "error",
        "restrict_messaging_alert_title" => "Membership Notice",
        "restrict_messaging_alert_subtitle" => "Membership upgrade required",
        "restrict_messaging_alert_message" => "<br>&nbsp;It is not possible for you to send this messages to a higher level member. <br><br>We apologize for any inconvenience caused.<br><br><div align=\"center\"><a target=\"\" title=\"Upgrade\" href=\"add-profile/\">Upgrade Options</a><br>",
        "restrict_messaging_alert_custom_img" => plugins_url( 'Restrict-Messaging/images/dialog-custom.png' ),
    );
  foreach ( $option as $key => $value )
    {
       if (get_option($key) == NULL) {
        update_option($key, $value);
       }
    }
    return;
}
register_activation_hook(__FILE__, "restrict_messaging_defaults");
function restrict_messaging_page() {
if ($_REQUEST['settings-updated']=='true') {
echo '<div id="message" class="updated fade"><p><strong>Plugin settings Changed.</strong></p></div>';
}
?>
<script language="JavaScript">
var upload_image_button = false;
jQuery(document).ready(function () {
    jQuery('.upload_image_button').click(function () {
        upload_image_button = true;
        formfieldID = jQuery(this).prev().attr("id");
        formfield = jQuery("#restrict_messaging_alert_custom_img").attr('name');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        if (upload_image_button == true) {
            var oldFunc = window.send_to_editor;
            window.send_to_editor = function (html) {
                imgurl = jQuery('img', html).attr('src');
                jQuery("#restrict_messaging_alert_custom_img").val(imgurl);
                tb_remove();
                window.send_to_editor = oldFunc;
            }
        }
        upload_image_button = false;
    });
});
</script>
<div class="wrap">
    
<h2>Usage and Customizing an Upgrade Message</h2>

    <hr />
    <form method="post" action="options.php">
        <?php settings_fields( "restrict-messaging-settings-group");?>
        <?php do_settings_sections( "restrict-messaging-settings-group");?>
        <table class="widefat" style="width:800px;">
            <thead style="background:#2EA2CC;color:#fff;">
                <tr>
                    <th style="color:#fff;">Membership Price Structure</th>
                    <th style="color:#fff;">Priced</th>
                    <th style="color:#fff;"></th>
                </tr>
            </thead>
            <tr>
                <td>MemberShip Packages - Left to Right are priced:</td>
                <td>
                    <select name="restrict_messaging_priceStructure" />
                    <option value="<" <?php if ( get_option( 'restrict_messaging_priceStructure')=='<' ) echo 'selected="selected"'; ?>>low - High</option>
                    <option value=">" <?php if ( get_option( 'restrict_messaging_priceStructure')=='>' ) echo 'selected="selected"'; ?>>High - Low</option>
                    </select>
                </td>
                <td></td>
            </tr>
            <thead style="background:#2EA2CC;color:#fff;">
                <tr>
                    <th style="color:#fff;">Message Alert Box Style</th>
                    <th style="color:#fff;">Select</th>
                    <th style="color:#fff;">Preview</th>
                </tr>
            </thead>
            <tr>
                <td>Select an <b>Alert</b> style or set to <b>Custom</b>:</td>
                <td>
                    <select id="myselect" name="restrict_messaging_alert_box" />
                    <option value="alert" <?php if ( get_option( 'restrict_messaging_alert_box')==alert ) echo 'selected="selected"'; ?>>Alert</option>
                    <option value="info" <?php if ( get_option( 'restrict_messaging_alert_box')==info ) echo 'selected="selected"'; ?>>Info</option>
                    <option value="error" <?php if ( get_option( 'restrict_messaging_alert_box')==error ) echo 'selected="selected"'; ?>>Error</option>
                    <option value="help" <?php if ( get_option( 'restrict_messaging_alert_box')==help) echo 'selected="selected"'; ?>>Help</option>
                    <option value="custom" <?php if ( get_option( 'restrict_messaging_alert_box')==custom) echo 'selected="selected"'; ?>>Custom</option>
                    </select>
                </td>
                <td id="preview" style="display: block;">
                    <img src="<?php bloginfo('url');?>/wp-content/plugins/Restrict-Messaging/images/dialog-<?php echo get_option(" restrict_messaging_alert_box ");?>.png">
                </td>
                <td id="custom" style="display: none;">
                    <img src="<?php echo get_option(" restrict_messaging_alert_custom_img ");?>">
                </td>
            </tr>
            <tr>
                <td>
                    <input type="hidden" id="restrict_messaging_alert_custom_img" name="restrict_messaging_alert_custom_img" value="<?php echo get_option(" restrict_messaging_alert_custom_img ");?>"/>
                </td>
                <td id="uploadCustom" style="display: none;">
                    <input class="upload_image_button" type="button" value="Upload Custom" />
                </td>
            </tr>
            <script>
                var checkCustom = jQuery("select#myselect").val();
                if (checkCustom == "custom") {
                    jQuery("#preview").hide();
                    jQuery("#custom, #uploadCustom").show();
                }
                 jQuery( "#myselect" ).change(function() {
                   jQuery( "#submit" ).click();
                   });
            </script>
            <thead style="background:#2EA2CC;color:#fff;">
                <tr>
                    <th style="color:#fff;">Upgrade <b>Title</b>, <em>Subtitle</em> and Message.</th>
                    <th style="color:#fff;"></th>
                    <th style="color:#fff;"></th>
                </tr>
            </thead>
            <tr>
                <td>Box Title</td>
                <td>
                    <input type="text" name="restrict_messaging_alert_title" value="<?php echo get_option(" restrict_messaging_alert_title ");?>"/>
                </td>
                <td></td>
            </tr>
            <tr>
                <td>Box Subtitle</td>
                <td>
                    <input type="text" name="restrict_messaging_alert_subtitle" value="<?php echo get_option(" restrict_messaging_alert_subtitle ");?>"/>
                </td>
                <td></td>
            </tr>
            <tr>
                <td>Upgrade Message</td>
                <td>
                    <script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script>
                    <script type="text/javascript">
                        //<![CDATA[
                        bkLib.onDomLoaded(function() {
                            nicEditors.allTextAreas()
                        });
                        //]]>
                    </script>
                    <textarea name="restrict_messaging_alert_message" cols=40 rows=6>
                        <?php echo get_option( "restrict_messaging_alert_message");?>
                    </textarea>
                </td>
                <td></td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
<? } 
add_action( "wp_footer", "da_restrict");
function da_restrict(){ global $CORE;
if ( is_page_template( 'tpl-account.php' ) )
	{

global $wpdb;
$sql = "SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'wlt_membership' AND user_id = ' " . get_current_user_id() . " ' ";
$PackageID = mysql_query($sql) or die (mysql_error());
$myPackageID = mysql_fetch_array($PackageID);
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script src="<?php echo plugins_url('/Restrict-Messaging/js/jquery.easing.1.3.js')?>"></script>
<script src="<?php echo plugins_url('/Restrict-Messaging/js/sexyalertbox.v1.2.jquery.js')?>"></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo plugins_url('/Restrict-Messaging/css/sexyalertbox.css')?>"/>
<style>
#SexyAlertBox-Box .BoxCustom {
  background: url('<?php echo get_option("restrict_messaging_alert_custom_img");?>') top left no-repeat;
}
</style>

<script type="text/javascript">
var myPackageID = '<?php echo $myPackageID[0];?>';

if (window.addEventListener) window.addEventListener('load', setField, false);
else if (window.attachEvent) window.attachEvent('onload', setField);

function setField() {
    var usernamefield = document.getElementById("usernamefield")
    if (usernamefield !== null) 
	{
		usernamefield.setAttribute("onblur", "namefield();");
    }
}

function namefield() {
    var usernamefield = document.getElementById("usernamefield").value;
    sendToID = jQuery.ajax({
        type: "POST",
        cache: false,
        async: false,
        url: "<?php bloginfo('url');?>/wp-content/plugins/Restrict-Messaging/checkmembership.php",
        data: "usernamefield=" + usernamefield,
        dataType: "text",
    }).responseText;
} 

namefield();

jQuery("button.btn-warning:contains(<?php echo $CORE->_e(array('account','34')); ?>)").click(function(e) {
namefield();
	if(!sendToID) 
	{
		sendToID = myPackageID; 
	}

	if ( !myPackageID || myPackageID <?php echo get_option("restrict_messaging_priceStructure");?> sendToID) 
		{
			e.preventDefault();
			Sexy.<?php echo get_option("restrict_messaging_alert_box");?>('<h1><?php echo get_option("restrict_messaging_alert_title");?></h1><em><?php echo get_option("restrict_messaging_alert_subtitle");?></em><br/><p><?php $message = get_option("restrict_messaging_alert_message"); $message = str_replace(array("\r","\n"),"",$message); echo $message;?></p>');
		}
    });
</script>
<?php
	}
}
?>