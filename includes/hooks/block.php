<?php
function editor_custom($post_id) 
{
    wp_nonce_field(plugin_basename(__FILE__), 'custom' );
    
    $object = Crud_Citations::set_query_citation($post_id->ID, array('citation_content'));
    wp_editor(Crud_Citations::get_content($object), 'editor-custom');
}
function save_editor_custom($post_id)
{
    if (defined('DOING_AUTOSAVE')) 
        return;
    if (!isset($_POST['editor-custom']) || !wp_verify_nonce($_POST['custom'], plugin_basename(__FILE__)))
        return;

    global $wpdb;
    if(!empty($_POST['editor-custom']))
        Crud_Citations::set_content(['citation_content' => $_POST['editor-custom'], 'post_ID' => $post_id]);
}
add_action('save_post', 'save_editor_custom');
function register_meta_box_custom()
{
    add_meta_box('meta-box-custom', __('Citations'), 'editor_custom', 'post');
}
add_action('add_meta_boxes', 'register_meta_box_custom');