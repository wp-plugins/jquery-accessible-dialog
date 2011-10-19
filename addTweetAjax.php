<?php

define('ABSPATH', dirname(__FILE__) . '/../../../');
include_once(ABSPATH . "wp-config.php");
include_once(ABSPATH . "wp-load.php");
include_once(ABSPATH . "wp-includes/wp-db.php");

global $wpdb;
$table_name = $wpdb->prefix . "jquery_accessible_dialog_tweets";
if (isset($_GET['name'])) {
    if (isset($_GET['tweet'])) {
        $name = mysql_real_escape_string($_GET['name']);
        $tweet = mysql_real_escape_string($_GET['tweet']);
        $rows_affected = $wpdb->insert($table_name, array('name' => $name, 'tweet' => $tweet));
    }
}
?>
