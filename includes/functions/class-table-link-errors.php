<?php
require_once ABSPATH . 'wp-admin/includes/template.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-screen.php';
require_once ABSPATH . 'wp-admin/includes/screen.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

class Table_Link_Errors extends WP_List_Table
{
    public function prepare_items()
    {
        $data         = $this->wp_list_table_data();
        $per_page     = 8;
        $current_page = $this->get_pagenum();
        $total_items  = count($data);
        $this->set_pagination_args(
            array(
                'total_items' => $total_items,
                'per_page'    => $per_page,
            )
        );
        $this->items = array_slice($data, (($current_page - 1) * $per_page), $per_page);
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $this->_column_headers = array( $columns, $hidden );
    }
    public function get_hidden_columns()
    {
        return array( 'post_ID' );
    }
    public function get_columns()
    {
        $columns = array(
            'post_ID' => 'ID',
            'URL' => __('URL'),
            'status'  => __('Status'),
            'post_title'   => __('Title')
        );
        return $columns;
    }
    public function column_default($item, $column_name)
    {
        switch ($column_name)
        {
            case 'post_ID':
            case 'post_title':
                return '<a href="'.get_edit_post_link($item['ID']).'">'.$item[$column_name].'</a>';
            case 'status':
                return $item[$column_name];
            case 'URL':
                return $item[$column_name];
            default:
                return 'N/A';
        }
    }
    public function wp_list_table_data()
    {
        global $wpdb;
        return $wpdb->get_results("SELECT urls.URL, urls.status, urls.post_ID, posts.post_title FROM ".$wpdb->prefix."urls AS urls INNER JOIN ".$wpdb->prefix."posts AS posts ON urls.post_ID = posts.ID", ARRAY_A);
    }
}