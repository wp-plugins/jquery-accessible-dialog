<?php

if (!function_exists('get_tweets')) {

    function get_tweets($before = '<td>', $after = '</td>') {
        /** Define ABSPATH as this files directory */
        define('ABSPATH', dirname(__FILE__) . '/../../../');
        include_once(ABSPATH . "wp-config.php");
        include_once(ABSPATH . "wp-load.php");
        include_once(ABSPATH . "wp-includes/wp-db.php");

        global $wpdb;

        $table_name = $wpdb->prefix . "jquery_accessible_dialog_tweets";

        $sql = "SELECT * FROM $table_name";
        $results = $wpdb->get_results($sql, ARRAY_A);

        $output = '';

        foreach ($results as $result) {
            $output .= '</tr>';
            $output .= $before . $result["name"] . $after;
            $output .= $before . $result["tweet"] . $after;
            $output .= '</tr>';
        }
        return $output;
    }

}

?>
