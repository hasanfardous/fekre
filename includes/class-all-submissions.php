<?php

if ( ! class_exists( "WP_List_Table" ) ) {
    require_once( ABSPATH . "wp-admin/includes/class-wp-list-table.php" );
}

class Jam_All_Submissions extends WP_List_Table {
    function __construct( $data ) {
        parent::__construct();
        $this->items = $data;
    }   
    
    function get_columns() {
        return [
            'cb'              => '<input type="checkbox">',
            'first_name'      => __( 'First Name', 'job-app-manager' ),
            'last_name'       => __( 'Last Name', 'job-app-manager' ),
            'present_address' => __( 'Present Address', 'job-app-manager' ),
            'email_address'   => __( 'Email Address', 'job-app-manager' ),
            'mobile_no'       => __( 'Mobile No.', 'job-app-manager' ),
            'post_name'       => __( 'Post Name', 'job-app-manager' ),
            'cv_path'         => __( 'Attached CV', 'job-app-manager' ),
            'apply_time'      => __( 'Apply Time', 'job-app-manager' )
        ];
    }

    function column_cb( $item ) {
        $item_id = $item['id'];
        return "<input type='checkbox' value='{$item_id}'>";
    }

    function column_first_name( $item ) {
        $item_id    = $item['id'];
        $cv_path    = $item['cv_path'];
        $first_name = $item['first_name'];
        $nonce      = wp_create_nonce( 'jam_delete_submission' );
        $actions    = [
            'delete' => sprintf( "<a href='?page=jam_submissions&id=%s&action=%s&attachment_id=%s&nonce=%s' onclick='return confirm(\"Do you really want to delete record?\")'>%s</a>", $item_id, 'delete', $cv_path, $nonce, __( 'Delete', 'job-app-manager' ) )
        ];
        return sprintf( "%s %s", $first_name, $this->row_actions( $actions ) );
    }

    function get_shortable_columns() {
        return [
            'apply_time' => [ 'apply_time', true ]
        ];
    }

    function column_cv_path( $item ) {
        $cv_path        = $item['cv_path'];
        $download_link  = wp_get_attachment_url( $cv_path );
        return "<a href='{$download_link}' target='_blank'>".__( 'Download CV', 'job-app-manager' )."</a>";
    }
    
    function column_default( $item, $column_name ) {
        return $item[$column_name];
    }
    
    function prepare_items() {
        $this->_column_headers = array( $this->get_columns(), [], $this->get_shortable_columns() );
    }
}