<?php
/*
Plugin Name: JQuery Accessible Dialog
Plugin URI: http://wordpress.org/extend/plugins/jquery-accessible-dialog/
Description: WAI-ARIA Enabled Dialog Plugin for Wordpress
Author: Kontotasiou Dionysia
Version: 3.0
Author URI: http://www.iti.gr/iti/people/Dionisia_Kontotasiou.html
*/

include_once 'getTweets.php';

global $wp_jquery_accessible_dialog_tweets_version;
$wp_jquery_accessible_dialog_tweets_version = "1.0";

register_activation_hook(__FILE__,'jquery_accessible_dialog_tweets_activate');

function jquery_accessible_dialog_tweets_activate() {
    /** Define ABSPATH as this files directory */
    define('ABSPATH', dirname(__FILE__) . '/../../../');
    include_once(ABSPATH . "wp-config.php");
    include_once(ABSPATH . "wp-load.php");
    include_once(ABSPATH . "wp-includes/wp-db.php");

    global $wpdb;
    global $wp_jquery_accessible_dialog_tweets_version;

    $table_name = $wpdb->prefix . "jquery_accessible_dialog_tweets";
    if ($wpdb->get_var("show tables like '$table_name'") != $table_name) {

        $sql = "CREATE TABLE " . $table_name . " (
                    id mediumint(9) NOT NULL AUTO_INCREMENT,
                    name tinytext NOT NULL,
                    tweet text NOT NULL,
                    UNIQUE KEY id (id)
                    );";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $name = "Fanis";
        $tweet = "Hello :)";
        $rows_affected = $wpdb->insert($table_name, array('name' => $name, 'tweet' => $tweet));

        add_option("wp_jquery_accessible_dialog_tweets_version", $wp_jquery_accessible_dialog_tweets_version);
    }
}

register_deactivation_hook( __FILE__, 'jquery_accessible_dialog_tweets_deactivate' );

function jquery_accessible_dialog_tweets_deactivate() {
    /** Define ABSPATH as this files directory */
    define('ABSPATH', dirname(__FILE__) . '/../../../');
    include_once(ABSPATH . "wp-config.php");
    include_once(ABSPATH . "wp-load.php");
    include_once(ABSPATH . "wp-includes/wp-db.php");

    global $wpdb;

    $table_name = $wpdb->prefix . "jquery_accessible_dialog_tweets";
    //Delete any options thats stored also?
    delete_option('wp_jquery_accessible_dialog_tweets_version');

    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}

add_action("plugins_loaded", "JQueryAccessibleDialog_init");
function JQueryAccessibleDialog_init() {
    register_sidebar_widget(__('JQuery Accessible Dialog'), 'widget_JQueryAccessibleDialog');
    register_widget_control(   'JQuery Accessible Dialog', 'JQueryAccessibleDialog_control', 200, 200 );
    
    if ( !is_admin() && is_active_widget('widget_JQueryAccessibleDialog') ) {
        wp_register_style('jquery.ui.all', ( get_bloginfo('wpurl') . '/wp-content/plugins/jquery-accessible-dialog/lib/jquery-ui/themes/base/jquery.ui.all.css'));
        wp_enqueue_style('jquery.ui.all');

        wp_deregister_script('jquery');

        // add your own script
        wp_register_script('jquery-1.6.4', ( get_bloginfo('wpurl') . '/wp-content/plugins/jquery-accessible-dialog/lib/jquery-ui/jquery-1.6.4.js'));
        wp_enqueue_script('jquery-1.6.4');

        wp_register_script('jquery.ui.core.js', ( get_bloginfo('wpurl') . '/wp-content/plugins/jquery-accessible-dialog/lib/jquery-ui/ui/jquery.ui.core.js'));
        wp_enqueue_script('jquery.ui.core.js');

        wp_register_script('jquery.ui.widget', ( get_bloginfo('wpurl') . '/wp-content/plugins/jquery-accessible-dialog/lib/jquery-ui/ui/jquery.ui.widget.js'));
        wp_enqueue_script('jquery.ui.widget');

        wp_register_script('jquery.ui.mouse', ( get_bloginfo('wpurl') . '/wp-content/plugins/jquery-accessible-dialog/lib/jquery-ui/ui/jquery.ui.mouse.js'));
        wp_enqueue_script('jquery.ui.mouse');

        wp_register_script('jquery.ui.button', ( get_bloginfo('wpurl') . '/wp-content/plugins/jquery-accessible-dialog/lib/jquery-ui/ui/jquery.ui.button.js'));
        wp_enqueue_script('jquery.ui.button');

        wp_register_script('jquery.ui.draggable', ( get_bloginfo('wpurl') . '/wp-content/plugins/jquery-accessible-dialog/lib/jquery-ui/ui/jquery.ui.draggable.js'));
        wp_enqueue_script('jquery.ui.draggable');

        wp_register_script('jquery.ui.position', ( get_bloginfo('wpurl') . '/wp-content/plugins/jquery-accessible-dialog/lib/jquery-ui/ui/jquery.ui.position.js'));
        wp_enqueue_script('jquery.ui.position');

        wp_register_script('jquery.ui.resizable', ( get_bloginfo('wpurl') . '/wp-content/plugins/jquery-accessible-dialog/lib/jquery-ui/ui/jquery.ui.resizable.js'));
        wp_enqueue_script('jquery.ui.resizable');

        wp_register_script('jquery.ui.dialog', ( get_bloginfo('wpurl') . '/wp-content/plugins/jquery-accessible-dialog/lib/jquery-ui/ui/jquery.ui.dialog.js'));
        wp_enqueue_script('jquery.ui.dialog');

        wp_register_script('jquery.effects.core', ( get_bloginfo('wpurl') . '/wp-content/plugins/jquery-accessible-dialog/lib/jquery-ui/ui/jquery.effects.core.js'));
        wp_enqueue_script('jquery.effects.core');

        wp_register_script('jquery.ui.accordion', ( get_bloginfo('wpurl') . '/wp-content/plugins/jquery-accessible-dialog/lib/jquery-ui/ui/jquery.ui.accordion.js'));
        wp_enqueue_script('jquery.ui.accordion');

        wp_register_script('jquery.ui.checkbox', ( get_bloginfo('wpurl') . '/wp-content/plugins/jquery-accessible-dialog/lib/jquery-ui/ui/jquery.ui.checkbox.js'));
        wp_enqueue_script('jquery.ui.checkbox');

        wp_register_style('demos', ( get_bloginfo('wpurl') . '/wp-content/plugins/jquery-accessible-dialog/lib/jquery-ui/demos.css'));
        wp_enqueue_style('demos');

        wp_register_style('JQueryAccessibleDialog_css', ( get_bloginfo('wpurl') . '/wp-content/plugins/jquery-accessible-dialog/lib/JQueryAccessibleDialog.css'));
        wp_enqueue_style('JQueryAccessibleDialog_css');

        wp_register_script('JQueryAccessibleDialog', ( get_bloginfo('wpurl') . '/wp-content/plugins/jquery-accessible-dialog/lib/JQueryAccessibleDialog.js'));
        wp_enqueue_script('JQueryAccessibleDialog');
    }
}

function widget_JQueryAccessibleDialog($args) {
    extract($args);

    $options = get_option("widget_JQueryAccessibleDialog");
    if (!is_array( $options )) {
        $options = array(
            'title' => 'JQuery Accessible Dialog',
            'name' => 'Name',
            'tweet' => 'Tweet',
            'tweetButton' => 'Create new tweet',
            'dialogText' => 'All form fields are required.'
        );
    }

    echo $before_widget;
    echo $before_title;
    echo $options['title'];
    echo $after_title;

    //Our Widget Content
    JQueryAccessibleDialogContent();
    echo $after_widget;
}

function JQueryAccessibleDialogContent() {
    $tweets = get_tweets();

    $options = get_option("widget_JQueryAccessibleDialog");
    if (!is_array( $options )) {
        $options = array(
            'title' => 'JQuery Accessible Dialog',
            'name' => 'Name',
            'tweet' => 'Tweet',
            'tweetButton' => 'Create new tweet',
            'dialogText' => 'All form fields are required.'
        );
    }

    echo '<div class="demo" role="application">
<div id="dialog-form" title="' . $options['tweetButton'] . '">
	<p class="validateTips">' . $options['dialogText'] . '</p>
	<form>
	<fieldset>
		<label for="name">' . $options['name'] . '</label>
		<input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all" />
		<label for="email">' . $options['tweet'] . '</label>
		<input type="text" name="tweet" id="tweet" value="" class="text ui-widget-content ui-corner-all" />
	</fieldset>
	</form>
</div>

<button id="create-user">' . $options['tweetButton'] . '</button>

<div id="users-contain" class="ui-widget">

	<table id="users" class="ui-widget ui-widget-content">
		<thead>
			<tr class="ui-widget-header ">
				<th>' . $options['name'] . '</th>
				<th>' . $options['tweet'] . '</th>
			</tr>
		</thead>
		<tbody>
                    ' . $tweets . '
		</tbody>
	</table>
</div>
</div>';
}

function JQueryAccessibleDialog_control() {
    $options = get_option("widget_JQueryAccessibleDialog");
    if (!is_array( $options )) {
        $options = array(
            'title' => 'JQuery Accessible Dialog',
            'name' => 'Name',
            'tweet' => 'Tweet',
            'tweetButton' => 'Create new tweet',
            'dialogText' => 'All form fields are required.'
        );
    }

    if ($_POST['JQueryAccessibleDialog-SubmitTitle']) {
        $options['title'] = htmlspecialchars($_POST['JQueryAccessibleDialog-WidgetTitle']);
        update_option("widget_JQueryAccessibleDialog", $options);
    }
    if ($_POST['JQueryAccessibleDialog-SubmitName']) {
        $options['name'] = htmlspecialchars($_POST['JQueryAccessibleDialog-WidgetName']);
        update_option("widget_JQueryAccessibleDialog", $options);
    }
    if ($_POST['JQueryAccessibleDialog-SubmitTweet']) {
        $options['tweet'] = htmlspecialchars($_POST['JQueryAccessibleDialog-WidgetTweet']);
        update_option("widget_JQueryAccessibleDialog", $options);
    }
    if ($_POST['JQueryAccessibleDialog-SubmitTweetButton']) {
        $options['tweetButton'] = htmlspecialchars($_POST['JQueryAccessibleDialog-WidgetTweetButton']);
        update_option("widget_JQueryAccessibleDialog", $options);
    }
    if ($_POST['JQueryAccessibleDialog-SubmitDialogText']) {
        $options['dialogText'] = htmlspecialchars($_POST['JQueryAccessibleDialog-WidgetDialogText']);
        update_option("widget_JQueryAccessibleDialog", $options);
    }
    ?>
    <p>
        <label for="JQueryAccessibleDialog-WidgetTitle">Widget Title: </label>
        <input type="text" id="JQueryAccessibleDialog-WidgetTitle" name="JQueryAccessibleDialog-WidgetTitle" value="<?php echo $options['title'];?>" />
        <input type="hidden" id="JQueryAccessibleDialog-SubmitTitle" name="JQueryAccessibleDialog-SubmitTitle" value="1" />
    </p>
    <p>
        <label for="JQueryAccessibleDialog-WidgetName">Translation for "Name": </label>
        <input type="text" id="JQueryAccessibleDialog-WidgetName" name="JQueryAccessibleDialog-WidgetName" value="<?php echo $options['name'];?>" />
        <input type="hidden" id="JQueryAccessibleDialog-SubmitName" name="JQueryAccessibleDialog-SubmitName" value="1" />
    </p>
    <p>
        <label for="JQueryAccessibleDialog-WidgetTweet">Translation for "Tweet": </label>
        <input type="text" id="JQueryAccessibleDialog-WidgetTweet" name="JQueryAccessibleDialog-WidgetTweet" value="<?php echo $options['tweet'];?>" />
        <input type="hidden" id="JQueryAccessibleDialog-SubmitTweet" name="JQueryAccessibleDialog-SubmitTweet" value="1" />
    </p>
    <p>
        <label for="JQueryAccessibleDialog-WidgetTweetButton">Translation for "Create new tweet": </label>
        <input type="text" id="JQueryAccessibleDialog-WidgetTweetButton" name="JQueryAccessibleDialog-WidgetTweetButton" value="<?php echo $options['tweetButton'];?>" />
        <input type="hidden" id="JQueryAccessibleDialog-SubmitTweetButton" name="JQueryAccessibleDialog-SubmitTweetButton" value="1" />
    </p>
    <p>
        <label for="JQueryAccessibleDialog-WidgetDialogText">Translation for "All form fields are required.": </label>
        <input type="text" id="JQueryAccessibleDialog-WidgetDialogText" name="JQueryAccessibleDialog-WidgetDialogText" value="<?php echo $options['dialogText'];?>" />
        <input type="hidden" id="JQueryAccessibleDialog-SubmitDialogText" name="JQueryAccessibleDialog-SubmitDialogText" value="1" />
    </p>
    
    <?php
}

?>
