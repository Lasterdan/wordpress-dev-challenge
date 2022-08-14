<?php
class Shortcode_Citation
{
    public static function create_shortcode($post_id)
    {
        $object = Crud_Citations::set_query_citation((string) implode('', $post_id), array('citation_content'));
        return Crud_Citations::get_content($object);
    }
}
add_shortcode('mc-citation', 'Shortcode_Citation::create_shortcode');