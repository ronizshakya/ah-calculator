<?php

/**
 * Admin class
 */
class calculatorAdmin
{

    function __construct()
    {
        /* Admin Scripts Enqueue*/
        add_action('admin_enqueue_scripts', array($this, 'calculator_admin_scripts'));

        /* Create Custom Post Type */
        add_action('init', array($this, 'calculator_create_post_type'));

        /* Add custom meta box to the Post Type */
        add_action('add_meta_boxes', array($this,  'add_your_calculator_fields_meta_box'));
        add_action('save_post', array($this, 'save_your_calculator_fields_meta'));
        add_action('admin_menu', array($this, 'calculator_add_export_panel'));
        add_action('init', array($this, 'export_function'));

        add_action('admin_menu', array($this, 'calculator_settings'));
        add_action('admin_init', array($this, 'calculator_settings_init'));
    }

    /* Admin Scripts Enqueue*/
    public function calculator_admin_scripts()
    {
    }


    /* Create Custom Post Type */
    public function calculator_create_post_type()
    {
        $labels = array(
            'name'                => _x('Calculator Data', 'Post Type General Name', 'calculator'),
            'singular_name'       => _x('Calculator Data', 'Post Type Singular Name', 'calculator'),
            'menu_name'           => __('Calculator Datas', 'calculator'),
            'parent_item_colon'   => __('Parent Calculator Data', 'calculator'),
            'all_items'           => __('All Calculator Data', 'calculator'),
            'view_item'           => __('View Calculator Data', 'calculator'),
            'add_new_item'        => __('Add New Calculator Data', 'calculator'),
            'add_new'             => __('Add New', 'calculator'),
            'edit_item'           => __('Edit Calculator Data', 'calculator'),
            'update_item'         => __('Update Calculator Data', 'calculator'),
            'search_items'        => __('Search Calculator Data', 'calculator'),
            'not_found'           => __('Not Found', 'calculator'),
            'not_found_in_trash'  => __('Not found in Trash', 'calculator'),
        );

        $args = array(
            'label'               => __('Calculator Data', 'calculator'),
            'description'         => __('Your Calculator Data', 'calculator'),
            'labels'              => $labels,
            'supports'            => array('title', 'editor'),
            'hierarchical'        => false,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'rewrite'             => array('slug' => 'calculator_datas'),
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'capability_type'     => 'post'
        );

        register_post_type('calculator_data', $args);
    }


    /* Add custom meta box to the Post Type */
    public function add_your_calculator_fields_meta_box()
    {
        add_meta_box(
            'calculator_fields_meta_box', // $id
            'Calculator Form Datas', // $title
            array($this, 'show_calculator_fields_meta_box'), // $callback
            'calculator_data', // $screen
            'normal', // $context
            'high' // $priority
        );
    }
    public function show_calculator_fields_meta_box($post)
    {
        include(CALCULATOR_PLUGIN_PATH . 'includes/admin/meta-box-layout.php');
    }
    public function save_your_calculator_fields_meta($post_id)
    {
        // verify nonce
        if (isset($_POST['your_meta_box_nonce']) && !wp_verify_nonce($_POST['your_meta_box_nonce'], basename(__FILE__))) {
            return $post_id;
       
        // check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }
        // check permissions
        if ('calculator_data' === $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            } elseif (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }
        }

        $post_meta = get_post_meta($post_id);

        if ($_POST['first_name'] && $_POST['first_name'] !== $post_meta['first_name'][0]) {
            update_post_meta($post_id, 'first_name', $_POST['first_name']);
        } elseif ('' === $_POST['first_name'] && $post_meta['first_name'][0]) {
            delete_post_meta($post_id, 'first_name', $post_meta['first_name'][0]);
        }
        if (
            $_POST['last_name'] && $_POST['last_name'] !== $post_meta['last_name'][0]
        ) {
            update_post_meta($post_id, 'last_name', $_POST['last_name']);
        } elseif ('' === $_POST['last_name'] && $post_meta['last_name'][0]) {
            delete_post_meta($post_id, 'last_name', $post_meta['last_name'][0]);
        }
        if ($_POST['user_email'] && $_POST['user_email'] !== $post_meta['user_email'][0]) {
            update_post_meta($post_id, 'user_email', $_POST['user_email']);
        } elseif ('' === $_POST['user_email'] && $post_meta['user_email'][0]) {
            delete_post_meta($post_id, 'user_email', $post_meta['user_email'][0]);
        }
        if ($_POST['agency_name'] && $_POST['agency_name'] !== $post_meta['agency_name'][0]) {
            update_post_meta($post_id, 'agency_name', $_POST['agency_name']);
        } elseif ('' === $_POST['agency_name'] && $post_meta['agency_name'][0]) {
            delete_post_meta($post_id, 'agency_name', $post_meta['agency_name'][0]);
        }
        if ($_POST['telephone'] && $_POST['telephone'] !== $post_meta['telephone'][0]) {
            update_post_meta($post_id, 'telephone', $_POST['telephone']);
        } elseif ('' === $_POST['telephone'] && $post_meta['telephone'][0]) {
            delete_post_meta($post_id, 'telephone', $post_meta['telephone'][0]);
        }
        if ($_POST['state'] && $_POST['state'] !== $post_meta['state'][0]) {
            update_post_meta($post_id, 'state', $_POST['state']);
        } elseif ('' === $_POST['state'] && $post_meta['state'][0]) {
            delete_post_meta($post_id, 'state', $post_meta['state'][0]);
        }
        if ($_POST['type_of_agency'] && $_POST['type_of_agency'] !== $post_meta['type_of_agency'][0]) {
            update_post_meta($post_id, 'type_of_agency', $_POST['type_of_agency']);
        } elseif ('' === $_POST['type_of_agency'] && $post_meta['type_of_agency'][0]) {
            delete_post_meta($post_id, 'type_of_agency', $post_meta['type_of_agency'][0]);
        }
        if ($_POST['commission_revenue_2021'] && $_POST['commission_revenue_2021'] !== $post_meta['commission_revenue_2021'][0]) {
            update_post_meta($post_id, 'commission_revenue_2021', $_POST['commission_revenue_2021']);
        } elseif ('' === $_POST['commission_revenue_2021'] && $post_meta['commission_revenue_2021'][0]) {
            delete_post_meta($post_id, 'commission_revenue_2021', $post_meta['commission_revenue_2021'][0]);
        }
        if ($_POST['commission_revenue_2020'] && $_POST['commission_revenue_2020'] !== $post_meta['commission_revenue_2020'][0]) {
            update_post_meta($post_id, 'commission_revenue_2020', $_POST['commission_revenue_2020']);
        } elseif ('' === $_POST['commission_revenue_2020'] && $post_meta['commission_revenue_2020'][0]) {
            delete_post_meta($post_id, 'commission_revenue_2020', $post_meta['commission_revenue_2020'][0]);
        }
        if ($_POST['commission_revenue_2019'] && $_POST['commission_revenue_2019'] !== $post_meta['commission_revenue_2019'][0]) {
            update_post_meta($post_id, 'commission_revenue_2019', $_POST['commission_revenue_2019']);
        } elseif ('' === $_POST['commission_revenue_2019'] && $post_meta['commission_revenue_2019'][0]) {
            delete_post_meta($post_id, 'commission_revenue_2019', $post_meta['commission_revenue_2019'][0]);
        }
        if ($_POST['average_annual_commission_revenue_growth'] && $_POST['average_annual_commission_revenue_growth'] !== $post_meta['average_annual_commission_revenue_growth'][0]) {
            update_post_meta($post_id, 'average_annual_commission_revenue_growth', $_POST['average_annual_commission_revenue_growth']);
        } elseif ('' === $_POST['average_annual_commission_revenue_growth'] && $post_meta['average_annual_commission_revenue_growth'][0]) {
            delete_post_meta($post_id, 'average_annual_commission_revenue_growth', $post_meta['average_annual_commission_revenue_growth'][0]);
        }
        if ($_POST['ebitda_margin'] && $_POST['ebitda_margin'] !== $post_meta['ebitda_margin'][0]) {
            update_post_meta($post_id, 'ebitda_margin', $_POST['ebitda_margin']);
        } elseif ('' === $_POST['ebitda_margin'] && $post_meta['ebitda_margin'][0]) {
            delete_post_meta($post_id, 'ebitda_margin', $post_meta['ebitda_margin'][0]);
        }
        if ($_POST['how_much_of_the_book_is_non_standard'] && $_POST['how_much_of_the_book_is_non_standard'] !== $post_meta['how_much_of_the_book_is_non_standard'][0]) {
            update_post_meta($post_id, 'how_much_of_the_book_is_non_standard', $_POST['how_much_of_the_book_is_non_standard']);
        } elseif ('' === $_POST['how_much_of_the_book_is_non_standard'] && $post_meta['how_much_of_the_book_is_non_standard'][0]) {
            delete_post_meta($post_id, 'how_much_of_the_book_is_non_standard', $post_meta['how_much_of_the_book_is_non_standard'][0]);
        }
        if ($_POST['discount_for_non_standard_books'] && $_POST['discount_for_non_standard_books'] !== $post_meta['discount_for_non_standard_books'][0]) {
            update_post_meta($post_id, 'discount_for_non_standard_books', $_POST['discount_for_non_standard_books']);
        } elseif ('' === $_POST['discount_for_non_standard_books'] && $post_meta['discount_for_non_standard_books'][0]) {
            delete_post_meta($post_id, 'discount_for_non_standard_books', $post_meta['discount_for_non_standard_books'][0]);
        }
        if ($_POST['personal_lines_mix'] && $_POST['personal_lines_mix'] !== $post_meta['personal_lines_mix'][0]) {
            update_post_meta($post_id, 'personal_lines_mix', $_POST['personal_lines_mix']);
        } elseif ('' === $_POST['personal_lines_mix'] && $post_meta['personal_lines_mix'][0]) {
            delete_post_meta($post_id, 'personal_lines_mix', $post_meta['personal_lines_mix'][0]);
        }

        if ($_POST['non_personal_lines_mix'] && $_POST['non_personal_lines_mix'] !== $post_meta['non_personal_lines_mix'][0]) {
            update_post_meta($post_id, 'non_personal_lines_mix', $_POST['non_personal_lines_mix']);
        } elseif ('' === $_POST['non_personal_lines_mix'] && $post_meta['non_personal_lines_mix'][0]) {
            delete_post_meta($post_id, 'non_personal_lines_mix', $post_meta['non_personal_lines_mix'][0]);
        }
        if ($_POST['discount_for_non_personal_lines_books'] && $_POST['discount_for_non_personal_lines_books'] !== $post_meta['discount_for_non_personal_lines_books'][0]) {
            update_post_meta($post_id, 'discount_for_non_personal_lines_books', $_POST['discount_for_non_personal_lines_books']);
        } elseif ('' === $_POST['discount_for_non_personal_lines_books'] && $post_meta['discount_for_non_personal_lines_books'][0]) {
            delete_post_meta($post_id, 'discount_for_non_personal_lines_books', $post_meta['discount_for_non_personal_lines_books'][0]);
        }
        if ($_POST['homeowners_mix'] && $_POST['homeowners_mix'] !== $post_meta['homeowners_mix'][0]) {
            update_post_meta($post_id, 'homeowners_mix', $_POST['homeowners_mix']);
        } elseif ('' === $_POST['homeowners_mix'] && $post_meta['homeowners_mix'][0]) {
            delete_post_meta($post_id, 'homeowners_mix', $post_meta['homeowners_mix'][0]);
        }
        if ($_POST['non_homeowners_mix'] && $_POST['non_homeowners_mix'] !== $post_meta['non_homeowners_mix'][0]) {
            update_post_meta($post_id, 'non_homeowners_mix', $_POST['non_homeowners_mix']);
        } elseif ('' === $_POST['non_homeowners_mix'] && $post_meta['non_homeowners_mix'][0]) {
            delete_post_meta($post_id, 'non_homeowners_mix', $post_meta['non_homeowners_mix'][0]);
        }
        if ($_POST['discount_for_non_homeowners_books'] && $_POST['discount_for_non_homeowners_books'] !== $post_meta['discount_for_non_homeowners_books'][0]) {
            update_post_meta($post_id, 'discount_for_non_homeowners_books', $_POST['discount_for_non_homeowners_books']);
        } elseif ('' === $_POST['discount_for_non_homeowners_books'] && $post_meta['discount_for_non_homeowners_books'][0]) {
            delete_post_meta($post_id, 'discount_for_non_homeowners_books', $post_meta['discount_for_non_homeowners_books'][0]);
        }
        if ($_POST['retention_ratio'] && $_POST['retention_ratio'] !== $post_meta['retention_ratio'][0]) {
            update_post_meta($post_id, 'retention_ratio', $_POST['retention_ratio']);
        } elseif ('' === $_POST['retention_ratio'] && $post_meta['retention_ratio'][0]) {
            delete_post_meta($post_id, 'retention_ratio', $post_meta['retention_ratio'][0]);
        }
        if ($_POST['discount_for_low_retention'] && $_POST['discount_for_low_retention'] !== $post_meta['discount_for_low_retention'][0]) {
            update_post_meta($post_id, 'discount_for_low_retention', $_POST['discount_for_low_retention']);
        } elseif ('' === $_POST['discount_for_low_retention'] && $post_meta['discount_for_low_retention'][0]) {
            delete_post_meta($post_id, 'discount_for_low_retention', $post_meta['discount_for_low_retention'][0]);
        }
        if ($_POST['multiple_for_large_agencies'] && $_POST['multiple_for_large_agencies'] !== $post_meta['multiple_for_large_agencies'][0]) {
            update_post_meta($post_id, 'multiple_for_large_agencies', $_POST['multiple_for_large_agencies']);
        } elseif ('' === $_POST['multiple_for_large_agencies'] && $post_meta['multiple_for_large_agencies'][0]) {
            delete_post_meta($post_id, 'multiple_for_large_agencies', $post_meta['multiple_for_large_agencies'][0]);
        }
        if ($_POST['multiple_for_mid_size_agencies'] && $_POST['multiple_for_mid_size_agencies'] !== $post_meta['multiple_for_mid_size_agencies'][0]) {
            update_post_meta($post_id, 'multiple_for_mid_size_agencies', $_POST['multiple_for_mid_size_agencies']);
        } elseif ('' === $_POST['multiple_for_mid_size_agencies'] && $post_meta['multiple_for_mid_size_agencies'][0]) {
            delete_post_meta($post_id, 'multiple_for_mid_size_agencies', $post_meta['multiple_for_mid_size_agencies'][0]);
        }
        if ($_POST['multiple_for_small_agencies'] && $_POST['multiple_for_small_agencies'] !== $post_meta['multiple_for_small_agencies'][0]) {
            update_post_meta($post_id, 'multiple_for_small_agencies', $_POST['multiple_for_small_agencies']);
        } elseif ('' === $_POST['multiple_for_small_agencies'] && $post_meta['multiple_for_small_agencies'][0]) {
            delete_post_meta($post_id, 'multiple_for_small_agencies', $post_meta['multiple_for_small_agencies'][0]);
        }
        if ($_POST['calculator_date_published'] && $_POST['calculator_date_published'] !== $post_meta['calculator_date_published'][0]) {
            update_post_meta($post_id, 'calculator_date_published', $_POST['calculator_date_published']);
        } elseif ('' === $_POST['calculator_date_published'] && $post_meta['calculator_date_published'][0]) {
            delete_post_meta($post_id, 'calculator_date_published', $post_meta['calculator_date-published'][0]);
        }
    }
    }
    public function calculator_add_export_panel()
    {

        add_submenu_page(
            'edit.php?post_type=calculator_data', //$parent_slug
            'Export',  //$page_title
            'Export Member',        //$menu_title
            'manage_options',           //$capability
            'export-member-slug', //$menu_slug
            array($this, 'export_member_panel'), // $callback
        );
    }

    //add_submenu_page callback function

    public function export_member_panel()
    { ?>
        <div class="wrap">
            <h1>Export Members To CSV</h1>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <label>From Date</label>
                <input type="date" name="dateFrom" value="<?php echo date('Y-m-d'); ?>" />
                <label>To Date</label>
                <input type="date" name="dateTo" value="<?php echo date('Y-m-d'); ?>" />
                <input type="submit" name="exportbtn" value="Export">

            </form>
        </div>
    <?php
    }


    public function export_function()
    {
        if (isset($_POST['exportbtn'])) {
            $startDate = sanitize_text_field($_POST["dateFrom"]);
            $endDate = sanitize_text_field($_POST["dateTo"]);

            $range = array(
                $startDate,
                $endDate,
            );

            $arg = array(
                'post_type' => 'calculator_data',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key'     => 'calculator_date_published',
                        'value'   => $range,
                        'compare' => 'BETWEEN',
                        'type'    => 'date',
                    )
                ),
            );

            global $post;
            $arr_post = get_posts($arg);
            if ($arr_post) {

                header('Content-type: text/csv');
                header("Content-Disposition: attachment; filename=" . "Calculator-Data-" . date('Y-m-d') . ".csv");

                header('Pragma: no-cache');
                header('Expires: 0');

                $file = fopen('php://output', 'w');

                fputcsv($file, array(
                    'Submission Date',
                    'First Name',
                    'Last Name',
                    'Email',
                    'Agency Name',
                    'Phone',
                    'State',
                    'Type of Agency',
                    'Commission Revenue 2021',
                    'Commission Revenue 2020',
                    'Commission Revenue 2019',
                    'Average Annual Commission Revenue Growth',
                    'Take home / Profit on the Book',
                    'EBITDA Margin',
                    'How much of the book is Non_Standard?',
                    'Discount for Non_Standard Books',
                    'Personal lines mix',
                    'Non Personal lines mix',
                    'Discount for Non Personal lines Books',
                    'Homeowners mix',
                    'Non Homeowners mix',
                    'Discount for Non Homeowners Books',
                    'Retention Ratio',
                    'Discount for Low Retention',
                    'Multiple for Large Agencies',
                    'Multiple for Mid Size Agencies',
                    'Multiple for Small Agencies',
                    'Commission Revenue Multiple: Min Value',
                    'Commission Revenue Multiple: Max Value',
                ));
                // fclose($file);
                foreach ($arr_post as $post) {
                    setup_postdata($post);

                    $calculator_date_published = get_post_meta($post->ID, "calculator_date_published");
                    $first_name = get_post_meta($post->ID, "first_name");
                    $last_name = get_post_meta($post->ID, "last_name");
                    $user_email = get_post_meta($post->ID, "user_email");
                    $agency_name = get_post_meta($post->ID, "agency_name");
                    $telephone = get_post_meta($post->ID, "telephone");
                    $state = get_post_meta($post->ID, "state");
                    $type_of_agency = get_post_meta($post->ID, "type_of_agency");
                    $commission_revenue_2021 = get_post_meta($post->ID, "commission_revenue_2021");
                    $commission_revenue_2020 = get_post_meta($post->ID, "commission_revenue_2020");
                    $commission_revenue_2019 = get_post_meta($post->ID, "commission_revenue_2019");
                    $average_annual_commission_revenue_growth = get_post_meta($post->ID, "average_annual_commission_revenue_growth");
                    $take_home_profit_on_the_book = get_post_meta($post->ID, "take_home_profit_on_the_book");
                    $ebitda_margin = get_post_meta($post->ID, "ebitda_margin");
                    $how_much_of_the_book_is_non_standard = get_post_meta($post->ID, "how_much_of_the_book_is_non_standard");
                    $discount_for_non_standard_books = get_post_meta($post->ID, "discount_for_non_standard_books");
                    $personal_lines_mix = get_post_meta($post->ID, "personal_lines_mix");
                    $non_personal_lines_mix = get_post_meta($post->ID, "non_personal_lines_mix");
                    $discount_for_non_personal_lines_books = get_post_meta($post->ID, "discount_for_non_personal_lines_books");
                    $homeowners_mix = get_post_meta($post->ID, "homeowners_mix");
                    $non_homeowners_mix = get_post_meta($post->ID, "non_homeowners_mix");
                    $discount_for_non_homeowners_books = get_post_meta($post->ID, "discount_for_non_homeowners_books");
                    $retention_ratio = get_post_meta($post->ID, "retention_ratio");
                    $discount_for_low_retention = get_post_meta($post->ID, "discount_for_low_retention");
                    $multiple_for_large_agencies = get_post_meta($post->ID, "multiple_for_large_agencies");
                    $multiple_for_mid_size_agencies = get_post_meta($post->ID, "multiple_for_mid_size_agencies");
                    $multiple_for_small_agencies = get_post_meta($post->ID, "multiple_for_small_agencies");
                    $commission_revenue_multiple_min_value = get_post_meta($post->ID, "commission_revenue_multiple_min_value");
                    $commission_revenue_multiple_max_value = get_post_meta($post->ID, "commission_revenue_multiple_max_value");
                    fputcsv($file, array(
                        $calculator_date_published[0],
                        $first_name[0],
                        $last_name[0],
                        $user_email[0],
                        $agency_name[0],
                        $telephone[0],
                        $state[0],
                        $type_of_agency[0],
                        $commission_revenue_2021[0],
                        $commission_revenue_2020[0],
                        $commission_revenue_2019[0],
                        $average_annual_commission_revenue_growth[0],
                        $take_home_profit_on_the_book[0],
                        $ebitda_margin[0],
                        $how_much_of_the_book_is_non_standard[0],
                        $discount_for_non_standard_books[0],
                        $personal_lines_mix[0],
                        $non_personal_lines_mix[0],
                        $discount_for_non_personal_lines_books[0],
                        $homeowners_mix[0],
                        $non_homeowners_mix[0],
                        $discount_for_non_homeowners_books[0],
                        $retention_ratio[0],
                        $discount_for_low_retention[0],
                        $multiple_for_large_agencies[0],
                        $multiple_for_mid_size_agencies[0],
                        $multiple_for_small_agencies[0],
                        $commission_revenue_multiple_min_value[0],
                        $commission_revenue_multiple_max_value[0],
                    ));
                }
                fclose($file);
                exit();
            }
        }
    }

    
    /*Section for calulator settings*/
    public function calculator_settings(){
        add_submenu_page(
            'edit.php?post_type=calculator_data', //$parent_slug
            'Calculator Settings',  //$page_title
            'Settings',        //$menu_title
            'manage_options',           //$capability
            'calculator-settings-slug', //$menu_slug
            array($this, 'settings_block_template'), // $callback
        );
    }
    public function settings_block_template(){
    ?>

        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form method="post" action="options.php">                        

                <?php 
                    //security field
                    settings_fields('calculator-settings-slug');

                    //output settings section
                    do_settings_sections('calculator-settings-slug');

                    //save settings button
                    submit_button();
                ?>

            </form>
        </div>

    <?php

    }

    //Settings Template

    public function calculator_settings_init(){

        //Setup settings section
        add_settings_section(
            'calc_settings_section',
            'Settings Page',
            '',
            'calculator-settings-slug'
        );

        //Register Input Field
        register_setting(
            'calculator-settings-slug',
            'calculator_settings_input_field',
            array(
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default' => ''
            )
        );

        //Register Input Email Field
        register_setting(
            'calculator-settings-slug',
            'calculator_settings_input_email_field',
            array(
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default' => ''
            )
        );

        //Register From Email Field
        register_setting(
            'calculator-settings-slug',
            'calculator_settings_from_email',
            array(
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default' => ''
            )
        );

        //Add a settings field
        add_settings_field(
            'calculator_settings_input_field',
            __('Email Subject', 'calculator'),
            array($this, 'calculator_settings_input_field_callback'),
            'calculator-settings-slug',
            'calc_settings_section'            
        );

        //add settings for email field
        add_settings_field(
            'calculator_settings_input_email_field',
            __('To Email', 'calculator'),
            array($this, 'calculator_settings_input_email_field_callback'),
            'calculator-settings-slug',
            'calc_settings_section'          
        );

        //add settings for From email field
        add_settings_field(
            'calculator_settings_from_email',
            __('From Email', 'calculator'),
            array($this, 'calculator_settings_from_email_callback'),
            'calculator-settings-slug',
            'calc_settings_section'          
        );

    }
    
    /*Setting Input field template*/
    public function calculator_settings_input_field_callback($args){
        $settings_input_field = get_option('calculator_settings_input_field');
    ?>
    <input type="text" name="calculator_settings_input_field" class="regular-text" value="<?php echo isset($settings_input_field) ? esc_attr( $settings_input_field ) : '';?> " />

    <?php }

     /*Setting Input Email Field template*/
    public function calculator_settings_input_email_field_callback($args){
        $settings_input_field_email = get_option('calculator_settings_input_email_field');
    ?>
        <input type="email" name="calculator_settings_input_email_field" class="regular-text" value="<?php echo isset($settings_input_field_email) ? esc_attr( $settings_input_field_email ) : '';?> " />

    <?php
    }

     /*Setting Input From Email Field template*/
     public function calculator_settings_from_email_callback($args){
        $settings_input_from_field_email = get_option('calculator_settings_from_email');
    ?>
        <input type="email" name="calculator_settings_from_email" class="regular-text" value="<?php echo isset($settings_input_from_field_email) ? esc_attr( $settings_input_from_field_email ) : '';?> " />

    <?php
    }
}


if (is_admin()) {

    new calculatorAdmin();
}
