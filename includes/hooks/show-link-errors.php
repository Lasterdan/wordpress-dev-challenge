<?php
function custom_plugin_init()
{
    add_action('admin_menu', 'custom_admin_menu');
}
function custom_admin_menu()
{
    add_menu_page(__('Show link errors'), __('Show link errors'), 'read', 'show-link-errors', 'custom_main_page');
}
function custom_main_page()
{
    echo '<div class="wrap">
            <h1 class="wp-heading-inline">'.__('Show link errors').'</div>';
            $table = new Table_Link_Errors();
            $table->prepare_items();
            $table->display();
    echo '</div>';

}
add_action('plugins_loaded', 'custom_plugin_init');