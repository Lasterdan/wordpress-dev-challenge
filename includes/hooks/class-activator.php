<?php
require_once(ABSPATH.'wp-admin/includes/upgrade.php');
class Activator
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
        dbDelta($table_citation);
    }
    public static function create_table_urls()
    {
        global $wpdb;

        $table_urls = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."urls` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `URL` varchar(255) NOT NULL,
            `status` varchar(255) NOT NULL DEFAULT '',
            `post_ID` bigint(20) unsigned NOT NULL,
        PRIMARY KEY (`id`)
        ) ".$wpdb->get_charset_collate().";";
        dbDelta($table_urls);
    }
    //shell_exec("0 0 * * * /.../php... -f /var/www/...class-url.php"); // Cronjobs
}