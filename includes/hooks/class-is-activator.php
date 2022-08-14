<?php
class IS_Activator
{
    public static function create_table_citations()
    {
        global $wpdb;

        $table_citation = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."citations` (
            `ID` int(11) NOT NULL AUTO_INCREMENT,
            `citation_content` varchar(255) NOT NULL,
            `post_ID` bigint(20) unsigned NOT NULL,
            PRIMARY KEY (`ID`)
        ) ".$wpdb->get_charset_collate().";";

        require_once(ABSPATH.'wp-admin/includes/upgrade.php');
        dbDelta($table_citation);
    }
}