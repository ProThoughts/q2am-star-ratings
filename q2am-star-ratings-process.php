<?php

/*
*	Q2AM Star Ratings
*
*	Star rating process and options codes.
*	File: q2am-star-ratings-process.php
*	
*	@author			Q2A Market
*	@category		Plugin
*	@Version: 		1.0
*   @author URL:	http://www.q2amarket.com
*	
*	@Q2A Version	1.5.3
*
*	Do not modify this file unless you know what you are doing
*/

class q2am_star_rating_process
{
    /**
     * This class containing codes related
     * to the system process
     *
     * Adding table to the database
     * Process the event for database
     * Add plugin options
     */

    function init_queries( $tableslc )
    {
        if ( qa_opt( 'q2am_star_ratings_enable' ) ) {
            $tablename = qa_db_add_table_prefix( 'star_ratings' );

            if ( !in_array( $tablename, $tableslc ) ) {
                require_once QA_INCLUDE_DIR . 'qa-app-users.php';
                require_once QA_INCLUDE_DIR . 'qa-db-maxima.php';

                qa_opt( 'q2am_star_ratings_version', '1.1.0' );
                qa_opt( 'q2am_star_ratings_db_version', 1 );

                return 'CREATE TABLE ^star_ratings (' .
                    'id INT (11) NOT NULL AUTO_INCREMENT PRIMARY KEY,' .
                    'post_id INT (11),' .
                    'rating FLOAT (11) DEFAULT 0,' .
                    'total_rating FLOAT (11) DEFAULT 0,' .
                    'total_rates INT (11) DEFAULT 0,' .
                    'ipaddress LONGTEXT' .
                    ') ENGINE=InnoDB DEFAULT CHARSET=utf8';
            }

            $check_record = qa_db_query_sub( "SELECT * FROM ^star_ratings" );

            if ($check_record->num_rows == 0 && qa_opt('q2am_star_ratings_db_version') == 1) {
                qa_db_query_sub("
                    INSERT INTO ^star_ratings(post_id)
                    SELECT postid
                    FROM qa_posts
                    WHERE type in ('Q', 'A')
                    ORDER BY postid ASC
                ");
            }

        }

    }

    /*------------------------------------------------------
        process event for each new question and answer
        it's add new row to qa_star_ratings on every
        new question or answer been created
     ------------------------------------------------------*/

    function process_event( $event, $userid, $handle, $cookieid, $params )
    {
        if ( $event == 'q_post' || $event == 'a_post' ) {
            $post_id = $params[ 'postid' ];
            qa_db_query_sub( "
				INSERT INTO ^star_ratings (post_id)
				VALUE ($post_id)
			" );
        }
    }

    function allow_template( $template )
    {
        return ( $template != 'admin' );
    }

    /*------------------------------------------------------
        defining all default option value for
        q2am star ratings plugin
     ------------------------------------------------------*/

    function option_default( $option )
    {

        switch ( $option ) {

            case 'q2am_star_ratings_star_size':
                return 'Small';

            case 'q2am_star_ratings_position':
                return 'below votebox';

            case 'q2am_affect_tick_position':
                return TRUE;

            case 'q2am_enable_rate_count':
                return TRUE;

            case 'q2am_tick_position_value':
                return 120;

            case 'q2am_stars_count':
                return 5;

            case 'q2am_stars_rates':
                return 5;

            case 'q2am_enable_rate_step':
                return TRUE;

            case 'q2am_enable_info':
                return FALSE;

            case 'q2am_info_pos':
                return -45;

            case 'q2am_decimal_length':
                return 1;

            default:
                return NULL;

        }

    }

    /*------------------------------------------------------
        add form element to plugin options
        this will allows usre to customize plugin
        by defined fields
     ------------------------------------------------------*/

    function admin_form( &$qa_content )
    {

        $ok = NULL;
        if ( qa_clicked( 'q2am_star_ratings_save_button' ) ) {

            qa_opt( 'q2am_star_ratings_enable', (bool)qa_post_text( 'q2am_star_ratings_enable' ) );
            qa_opt( 'q2am_star_ratings_loggedin', (bool)qa_post_text( 'q2am_star_ratings_loggedin' ) );
            qa_opt( 'q2am_star_ratings_star_size', qa_post_text( 'q2am_star_ratings_star_size' ) );
            qa_opt( 'q2am_star_ratings_position', qa_post_text( 'q2am_star_ratings_position' ) );
            qa_opt( 'q2am_affect_tick_position', (bool)qa_post_text( 'q2am_affect_tick_position' ) );
            qa_opt( 'q2am_enable_rate_count', (bool)qa_post_text( 'q2am_enable_rate_count' ) );
            qa_opt( 'q2am_tick_position_value', (int)qa_post_text( 'q2am_tick_position_value' ) );

            qa_opt( 'q2am_stars_count', (int)qa_post_text( 'q2am_stars_count' ) );
            qa_opt( 'q2am_stars_rates', (int)qa_post_text( 'q2am_stars_rates' ) );
            qa_opt( 'q2am_enable_rate_step', (bool)qa_post_text( 'q2am_enable_rate_step' ) );
            qa_opt( 'q2am_enable_info', (bool)qa_post_text( 'q2am_enable_info' ) );
            qa_opt( 'q2am_info_pos', (int)qa_post_text( 'q2am_info_pos' ) );
            qa_opt( 'q2am_decimal_length', (int)qa_post_text( 'q2am_decimal_length' ) );


            $ok = 'Q2AM Star Ratings Settings Saved';
        } else {
            if ( qa_clicked( 'q2am_star_ratings_reset_button' ) ) {
                foreach ( $_POST as $i => $v ) {
                    $def = $this->option_default( $i );
                    if ( $def !== NULL ) {
                        qa_opt( $i, $def );
                    }
                }
                $ok = 'Q2AM Star Ratings Settings Restored';
            }
        }


        $fields = [];

        $star_type = [

            'small'  => 'small',
            'medium' => 'medium',
            'big'    => 'big'
        ];

        $star_position = [

            'below votebox' => 'below votebox',
            'after content' => 'after content'
        ];


        $fields[] = [
            'label' => 'Enagle Star Ratings',
            'tags'  => 'NAME="q2am_star_ratings_enable"',
            'value' => (bool)qa_opt( 'q2am_star_ratings_enable' ),
            'type'  => 'checkbox',
        ];

        $fields[] = [
            'label' => 'Allow Only Logged In User',
            'tags'  => 'NAME="q2am_star_ratings_loggedin"',
            'value' => (bool)qa_opt( 'q2am_star_ratings_loggedin' ),
            'type'  => 'checkbox',
        ];

        $fields[] = [
            'label'   => 'Star Size',
            'tags'    => 'NAME="q2am_star_ratings_star_size"',
            'id'      => 'q2am_star_ratings_star_size',
            'type'    => 'select',
            'options' => $star_type,
            'value'   => qa_opt( 'q2am_star_ratings_star_size' ),
        ];

        $fields[] = [
            'label'   => 'Position',
            'tags'    => 'NAME="q2am_star_ratings_position"',
            'id'      => 'q2am_star_ratings_position',
            'type'    => 'select',
            'options' => $star_position,
            'value'   => qa_opt( 'q2am_star_ratings_position' ),
        ];

        $fields[] = [
            'label' => 'Modify Tick Position',
            'tags'  => 'NAME="q2am_affect_tick_position"',
            'value' => (bool)qa_opt( 'q2am_affect_tick_position' ),
            'type'  => 'checkbox',
        ];

        $fields[] = [
            'id'     => 'q2am_tick_position_value',
            'label'  => 'Custom Tick Position',
            'suffix' => 'pixel',
            'type'   => 'number',
            'value'  => (int)qa_opt( 'q2am_tick_position_value' ),
            'tags'   => 'NAME="q2am_tick_position_value"',
        ];

        $fields[] = [
            'label' => 'Display Rate Count',
            'tags'  => 'NAME="q2am_enable_rate_count"',
            'value' => (bool)qa_opt( 'q2am_enable_rate_count' ),
            'type'  => 'checkbox',
        ];

        $fields[] = [
            'id'     => 'q2am_stars_count',
            'label'  => 'Total Stars',
            'suffix' => 'characters',
            'type'   => 'number',
            'value'  => (int)qa_opt( 'q2am_stars_count' ),
            'tags'   => 'NAME="q2am_stars_count"',
        ];

        $fields[] = [
            'id'     => 'q2am_stars_rates',
            'label'  => 'Total Stars Rate',
            'suffix' => 'characters',
            'type'   => 'number',
            'value'  => (int)qa_opt( 'q2am_stars_rates' ),
            'tags'   => 'NAME="q2am_stars_rates"',
        ];

        $fields[] = [
            'label' => 'Step Rate By Star',
            'tags'  => 'NAME="q2am_enable_rate_step"',
            'value' => (bool)qa_opt( 'q2am_enable_rate_step' ),
            'type'  => 'checkbox',
        ];

        $fields[] = [
            'id'     => 'q2am_decimal_length',
            'label'  => 'Decimal Length',
            'suffix' => 'characters',
            'type'   => 'number',
            'value'  => (int)qa_opt( 'q2am_decimal_length' ),
            'tags'   => 'NAME="q2am_decimal_length"',
        ];

        $fields[] = [
            'label' => 'Show Rate Info',
            'tags'  => 'NAME="q2am_enable_info"',
            'value' => (bool)qa_opt( 'q2am_enable_info' ),
            'type'  => 'checkbox',
        ];

        $fields[] = [
            'id'     => 'q2am_info_pos',
            'label'  => 'Info Position',
            'suffix' => 'pixel',
            'type'   => 'number',
            'value'  => (int)qa_opt( 'q2am_info_pos' ),
            'tags'   => 'NAME="q2am_info_pos"',
        ];


        $fields[] = [
            'type' => 'blank',
        ];

        return [
            'ok' => ( $ok && !isset( $error ) ) ? $ok : NULL,

            'fields' => $fields,

            'buttons' => [
                [
                    'label' => qa_lang_html( 'main/save_button' ),
                    'tags'  => 'NAME="q2am_star_ratings_save_button"',
                ],
                [
                    'label' => qa_lang_html( 'admin/reset_options_button' ),
                    'tags'  => 'NAME="q2am_star_ratings_reset_button"',
                ],
            ],
        ];
    }

}
/*
	Omit PHP closing tag to help avoid accidental output
*/
