<?php
class Crud_Citations
{
    /**
     *
     * @param  int $post_id, array $var
     * @return  boolean
     *
     */
    public static function set_query_citation($post_id, $var)
    {
        global $wpdb;

        $fields = '';
        if(is_array($var))
            $fields = implode(', ', $var);

        return $wpdb->get_results($wpdb->prepare("SELECT ".$fields." from ".$wpdb->prefix."citations WHERE post_ID = %d", array($post_id)));
    }
    /**
     *
     * Return the content
     *
     * @param  array $object
     * @return  string
     *
     */
    public static function get_content($object)
    {
        foreach($object as $item)
        {
            return $item->citation_content;
        }
    }
    /**
     *
     * Set the content
     *
     * @param  array $params
     * @return  boolean
     *
     */
    public static function set_content($params)
    {
        global $wpdb;

        if(0 == $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."citations WHERE post_ID = ".$params['post_ID']))
        {
            return $wpdb->insert($wpdb->prefix."citations", $params, array('%s', '%d'));
        } else {
            return $wpdb->update($wpdb->prefix."citations", array('citation_content' => $params['citation_content']), array('post_ID' => $params['post_ID']), '%s', '%d');
        }
    }
}