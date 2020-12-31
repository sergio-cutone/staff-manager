<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Wprem_Staff_Manager
 * @subpackage Wprem_Staff_Manager/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wprem_Staff_Manager
 * @subpackage Wprem_Staff_Manager/admin
 * @author     Sergio Cutone <sergio.cutone@yp.ca>
 */
class Wprem_Staff_Manager_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wprem_Staff_Manager_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wprem_Staff_Manager_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wprem-staff-manager-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wprem_Staff_Manager_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wprem_Staff_Manager_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wprem-staff-manager-admin.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name, 'jquery-ui-core');
    }

    public function menu_settings()
    {
        add_submenu_page(
            'edit.php?post_type=' . WPREM_STAFF_MANAGER_CUSTOM_POST_TYPE,
            'Settings', // The title to be displayed in the browser window for this page.
            'Settings', // The text to be displayed for this menu item
            'manage_options', // Which type of users can see this menu item
            $this->plugin_name, // The unique ID - that is, the slug - for this menu item
            array($this, 'settings_page') // The name of the function to call when rendering this menu's page
        );
    }

    public function settings_page()
    {
        include_once 'partials/wprem-staff-manager-admin-display.php';
    }

    public function remove_yoast_metabox()
    {
        remove_meta_box('wpseo_meta', WPREM_STAFF_VIEWS_CUSTOM_POST_TYPE, 'normal');
    }

    public function add_button($x)
    {
        echo '<a href="#TB_inline?width=480&height=500&inlineId=wp_staff_shortcode" class="button thickbox wp_doin_media_link" id="add_div_shortcode">ST</a>';
    }

    public function staff_shortcode_popup()
    {
        ?>
		<div id="wp_staff_shortcode" style="display:none;">
			<div class="wrap wp_doin_shortcode">
				<div>
					<div style="padding:10px">
						<h3 style="color:#5A5A5A!important; font-family:Georgia,Times New Roman,Times,serif!important; font-size:1.8em!important; font-weight:normal!important;">Staff Member Shortcode</h3>
						<p>Select to show all staff or a specific staff member.</p>
						<div class="field-container">
							<div class="label-desc">
								<?php
$args = array(
            'post_status' => 'published',
            'post_type' => WPREM_STAFF_MANAGER_CUSTOM_POST_TYPE,
            'meta_key' => '_data_order',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'posts_per_page' => -1,
        );
        echo '<select id="wp_staff_id"><option value="">Show All Staff Members</option>';
        $staffs = get_posts($args);
        foreach ($staffs as $staff):
            setup_postdata($staff);
            echo "<option value=" . $staff->ID . ">" . $staff->post_title . "</option>";
        endforeach;
        wp_reset_postdata();
        echo "</select>";
        ?>
							</div>
						</div>
                        <p>Select category</p>
                        <div class="field-container">
                            <div class="label-desc">
                                <select id="wp_staff_cat">
                                    <option value="">All Categories</option>
                                    <?php
$staff_terms = get_terms('wprem_staff_member_category');
        foreach ($staff_terms as $staff_term) {
            echo '<option value="' . $staff_term->term_id . '">' . $staff_term->name . '</option>';
        }
        ?>
                                </select>
                            </div>
                        </div>
						<p>Select the View you would like to display.</p>
						<div class="field-container">
							<div class="label-desc">
								<?php
$args = array(
            'post_status' => 'published',
            'post_type' => WPREM_STAFF_VIEWS_CUSTOM_POST_TYPE,
            'orderby' => 'post_title',
            'order' => 'ASC',
            'posts_per_page' => -1,
        );
        echo '<select id="wp_staffview_id"><option value="">Select View</option>';
        $staffviews = get_posts($args);
        foreach ($staffviews as $staffview):
            setup_postdata($staffview);
            echo "<option value=" . $staffview->ID . ">" . $staffview->post_title . "</option>";
        endforeach;
        wp_reset_postdata();
        echo "</select>";
        ?>
							</div>
						</div>

						<?php
if (defined('WPREM_SERVICES_CUSTOM_POST_TYPE')) {
            ?>

						<p>Select Staff by Service</p>
						<?php
$args = array(
                'post_type' => WPREM_SERVICES_CUSTOM_POST_TYPE,
                'orderby' => 'post_title',
                'order' => 'ASC',
                'posts_per_page' => -1,
            );
            echo '<select id="wp_allservices_id"><option value="">Select Service</option>';
            $staffviews = get_posts($args);
            foreach ($staffviews as $staffview):
                setup_postdata($staffview);
                echo '<option value="' . $staffview->ID . '">' . $staffview->post_title . '</option>';
            endforeach;
            wp_reset_postdata();
            echo "</select>";
        }
        ?>
					</div>
					<hr />
					<div style="padding:15px;">
						<input type="button" class="button-primary" value="Insert Staff" id="staff-insert" />
						&nbsp;&nbsp;&nbsp;<a class="button" href="#" onclick="tb_remove(); return false;">Cancel</a>
					</div>
				</div>
			</div>
		</div>
		<?php
}

    public function content_types()
    {

        $services = array('value1' => 'Value 1', 'value2' => 'Value 2', 'value3' => 'Value 3');

        $addons_arr = $tab_arr = $addon_services = $addon_locations = array();

        if (defined('WPREM_SERVICES_CUSTOM_POST_TYPE')) {
            $addon_services = array(
                'id' => '_data_post_services',
                'type' => 'post_checkboxes',
                'label' => 'Add To Service Pages',
                'args' => array(
                    'post_type' => WPREM_SERVICES_CUSTOM_POST_TYPE,
                    'orderby' => 'title',
                    'order' => 'ASC',
                ),
            );
            array_push($addons_arr, $addon_services);
        }

        if (defined('WPREM_LOCATIONS_CUSTOM_POST_TYPE')) {
            $addon_locations = array(
                'id' => '_data_post_locations',
                'type' => 'post_checkboxes',
                'label' => 'Add To Location Pages',
                'args' => array(
                    'post_type' => WPREM_LOCATIONS_CUSTOM_POST_TYPE,
                    'orderby' => 'title',
                    'order' => 'ASC',
                ),
            );
            array_push($addons_arr, $addon_locations);
        }

        $panels_arr = array(
            array(
                'id' => '_data_tabs_panel_1', 'title' => 'Main Information',
                'fields' => array(
                    array(
                        'id' => '_data_cred', 'label' => 'Credentials', 'type' => 'text',
                    ),
                    array(
                        'id' => '_data_title', 'label' => 'Title', 'type' => 'text',
                    ),
                    array(
                        'id' => '_data_email_address', 'label' => 'Email Address', 'type' => 'text',
                    ),
                    array(
                        'id' => '_data_telephone', 'label' => 'Telephone', 'type' => 'text',
                    ),
                    array(
                        'id' => '_data_ext', 'label' => 'Extension', 'type' => 'text',
                    ),
                    array(
                        'id' => '_data_tollfree', 'label' => 'Toll-Free Telephone', 'type' => 'text',
                    ),
                    array(
                        'id' => '_data_fax', 'label' => 'Fax Number', 'type' => 'text',
                    ),
                    array(
                        'id' => '_data_order', 'label' => 'Order', 'type' => 'text', 'default_value' => '0',
                    ),
                ),
            ),
            array(
                'id' => '_data_tabs_panel_2',
                'title' => 'Social Media',
                'fields' => array(
                    array(
                        'id' => '_data_text_linkedin', 'label' => 'LinkedIn', 'type' => 'text',
                    ),
                    array(
                        'id' => '_data_text_facebook', 'label' => 'FaceBook', 'type' => 'text',
                    ),
                    array(
                        'id' => '_data_text_instagram', 'label' => 'Instagram', 'type' => 'text',
                    ),
                    array(
                        'id' => '_data_text_youtube', 'label' => 'YouTube', 'type' => 'text',
                    ),
                    array(
                        'id' => '_data_text_google', 'label' => 'Google', 'type' => 'text',
                    ),
                ),
            ),
        );

        if (count($addons_arr) > 0) {
            $tab_arr = array_push($panels_arr, array('id' => '_data_tabs_panel_3', 'title' => 'Extra', 'fields' => $addons_arr));
        }

        $exludefromsearch = (esc_attr(get_option('wprem_searchable_wprem-staff-manager')) === "1") ? false : true;
        $staff_options = get_option('wp_staff_options');
        $single_slug = isset($staff_options['wp_staff_slug']) ? $staff_options['wp_staff_slug'] : 'staff-member';

        $args = array(
            'exclude_from_search' => $exludefromsearch,
            'rewrite' => array(
                'slug' => $single_slug,
                'with_front' => false,
            ),
            "menu_icon" => "dashicons-groups",
            'labels' => array(
                "name" => "Staff",
                'menu_name' => 'Staff Members',
                "add_new" => "Add New Staff",
                "add_new_item" => "Add New Staff Member",
                "all_items" => "All Staff",
            ),
            "has_archive" => false,
            'supports' => array(
                'title', 'editor', 'thumbnail', 'excerpt',
            ),
        );
        $staff = register_cuztom_post_type(WPREM_STAFF_MANAGER_CUSTOM_POST_TYPE, $args);
        $box = register_cuztom_meta_box('data', WPREM_STAFF_MANAGER_CUSTOM_POST_TYPE,
            array(
                'title' => 'Staff Member Information',
                'fields' => array(
                    array(
                        'id' => '_data_tabs',
                        'type' => 'tabs',
                        'panels' => $panels_arr,
                    ),
                ),
            )
        );

        $category = register_cuztom_taxonomy(
            'wprem_staff_member_category',
            WPREM_STAFF_MANAGER_CUSTOM_POST_TYPE,
            array(
                'labels' => array('name' => __('Categories', 'cuztom'), 'menu_name' => __('Categories', 'cuztom')),
                'show_admin_column' => true,
                'admin_column_sortable' => true,
                'admin_column_filter' => true,
                'show_in_rest' => true,
            )
        );
        #$tax_arg = array('show_admin_column'=>true,"admin_column_sortable"=>true,"admin_column_filter"=>true,'labels' => array('view_item'=>'View Staff Service','edit_item'=>'Edit Staff Service',"name"=>"Staff Services","add_new_item"=>"Add New Staff Service"), 'rewrite'=>array('slug'=>"staff-services", 'with_front'=>false), 'hierarchical'=>true, 'public'=>false, 'publicly_queryable'=>true, 'query_var'=>true);
        #$taxonomy = register_cuztom_taxonomy( 'service', WPREM_STAFF_MANAGER_CUSTOM_POST_TYPE, $tax_arg );
        # Replaced with Services Custom Post Type

        function op_gen($v, $n)
        {
            return array(
                'id' => '_data_select_' . $v, 'type' => 'select',
                'label' => $n,
                'options' => array('true' => 'YES', 'false' => 'NO'),
            );
        }

        $args_view = array('exclude_from_search' => true, 'rewrite' => false, 'show_ui' => true, 'publicly_queryable' => true, 'has_archive' => false, 'hierarchical' => false, 'public' => false, 'show_in_menu' => 'edit.php?post_type=' . WPREM_STAFF_MANAGER_CUSTOM_POST_TYPE, 'labels' => array("name" => "Staff Views", "add_new_item" => "Staff View", 'edit_item' => 'Edit Staff View'), 'supports' => array('title'));
        $staff_views = register_cuztom_post_type(WPREM_STAFF_VIEWS_CUSTOM_POST_TYPE, $args_view);
        $staff_views_box = register_cuztom_meta_box('data', WPREM_STAFF_VIEWS_CUSTOM_POST_TYPE,
            array(
                'title' => 'Staff View Settings',
                'fields' => array(

                    array(
                        'id' => '_data_tabs',
                        'type' => 'tabs',
                        'panels' => array(
                            array(
                                'id' => '_data_tabs_panel_1',
                                'title' => 'Display',
                                'fields' => array(
                                    array(
                                        'id' => '_data_select_columns', 'type' => 'select', 'label' => 'Layout',
                                        'options' => array(
                                            '1' => 'List View',
                                            '2' => 'Single Centred',
                                            '5' => '2 Columns',
                                            '3' => '3 Columns',
                                            '4' => '4 Columns',
                                        ),
                                    ),
                                    op_gen('link', 'Link to Profile'),
                                    array(
                                        'id' => '_data_select_ppp', 'type' => 'select', 'label' => 'Staff Per Page',
                                        'options' => array(
                                            '-1' => 'Show All',
                                            '1' => '1',
                                            '2' => '2',
                                            '3' => '3',
                                            '4' => '4',
                                            '5' => '5',
                                            '6' => '6',
                                            '7' => '7',
                                            '8' => '8',
                                            '9' => '9',
                                            '10' => '10',
                                            '11' => '11',
                                            '12' => '12',
                                            '13' => '13',
                                            '14' => '14',
                                            '15' => '15',
                                            '16' => '16',
                                            '17' => '17',
                                            '18' => '18',
                                            '19' => '19',
                                            '20' => '20',
                                        ),
                                    ),
                                ),
                            ),
                            array(
                                'id' => '_data_tabs_panel_4',
                                'title' => 'Name & Title',
                                'fields' => array(
                                    op_gen('name', 'Name'),
                                    op_gen('title', 'Title'),
                                    op_gen('cred', 'Credential'),
                                    array(
                                        'id' => '_data_select_nt_align', 
                                        'type' => 'select', 
                                        'label' => 'Name & Title Alignment',
                                        'options' => array(
                                            'left' => 'Left',
                                            'center' => 'Centre',
                                            'right' => 'Right',
                                        ),
                                    ),
                                ),
                            ),
                            array(
                                'id' => '_data_tabs_panel_2',
                                'title' => 'Photo',
                                'fields' => array(
                                    op_gen('photo', 'Photo'),
                                    array(
                                        'id' => '_data_select_photosize', 'type' => 'select', 'label' => 'Photo Size',
                                        'options' => array(
                                            'medium' => 'Medium',
                                            'large' => 'Large',
                                        ),
                                    ),
                                    array(
                                        'id' => '_data_select_photoshape', 'type' => 'select', 'label' => 'Photo Shape',
                                        'options' => array(
                                            '' => 'Natural',
                                            'circle' => 'Circle',
                                            'square' => 'Square',
                                        ),
                                    ),
                                    array(
                                        'id' => '_data_select_photostyle', 'type' => 'select', 'label' => 'Photo Style',
                                        'options' => array(
                                            '' => 'Natural',
                                            'sm' => 'Rounded Corners - Small',
                                            'md' => 'Rounded Corners - Medium',
                                            'lg' => 'Rounded Corners - Large',
                                        ),
                                    ),
                                ),
                            ),
                            array(
                                'id' => '_data_tabs_panel_3',
                                'title' => 'Info',
                                'fields' => array(
                                    array(
                                        'id' => '_data_select_bio', 'type' => 'select', 'label' => 'Bio Type',
                                        'options' => array(
                                            '0' => 'No Bio',
                                            '1' => 'Full Bio',
                                            '2' => 'Shortened Bio',
                                            '3' => 'Excerpt Bio',
                                        ),
                                    ),
                                    op_gen('readmore', 'Read More Link'),
                                    op_gen('email', 'Email'),
                                    op_gen('telephone', 'Telephone'),
                                    op_gen('tollfree', 'Toll-Free'),
                                    op_gen('fax', 'Fax'),
                                    op_gen('socialmedia', 'Social Media'),
                                    op_gen('services', 'Services'),
                                    array(
                                        'id' => '_data_select_info_align', 'type' => 'select', 'label' => 'Info Alignment',
                                        'options' => array(
                                            'left' => 'Left',
                                            'center' => 'Centre',
                                            'right' => 'Right',
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            )
        );
        flush_rewrite_rules();
    }

    public function options_staff_views_callback()
    {

        $is_save = isset($_POST["save"]) ? $_POST["save"] : '';

        // option_name - wp_staff_cpt_views = array of id's of views
        // option_name - wp_staff_cpt_view_n = view settings

        function view_custom($n, $ob)
        {
            $options = explode("|", $ob);
            $out = '<select name="' . $n . '">';
            foreach ($options as $option) {
                $break = explode(",", $option);
                $out .= '<option value="' . $break[0] . '" ' . selected($staff_options[$n], $break[0], false) . '>' . $break[1] . '</option>';
            }
            return $out . '</select>';
        }

        function row_gen($v, $n)
        {
            return '<tr><td>' . view_io($v) . '</td><td>' . $n . '</td></tr>';
        }

        function view_io($a)
        {
            return '<select name="view[' . $a . ']"><option value="1">ON</option><option value="0">OFF</option>';
        }

        function form_gen($n)
        {
            $out = '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post" name="' . $n . '">' .
            '<input type="hidden" name="save" value="1">' .
            '<table class="widefat fixed" cellspacing="0"><tbody>' .
            '<tr><td colspan="2"><h2>Name & Title</h2></td></tr>' . row_gen('name', "Name") . row_gen('title', "Title") .
            '<tr><td colspan="2"><h2>Photo</h2></td></tr>' . row_gen('photo', "Photo") .
            '<tr><td>' . view_custom('photosize', 'medium,Medium|large,Large') . '</td><td>Photo Size</td></tr>' .
            '<tr><td>' . view_custom('photosize', ',Natural|circle,Circle|square,Square') . '</td><td>Photo Shape</td></tr>' .
            '<tr><td>' . view_custom('photosize', ',Natural|sm,Small Round|md,Medium Round|lg,Large Round') . '</td><td>Photo Corners Style</td></tr>' .
            '<tr><td colspan="2"><h2>Staff Information</h2></td></tr>' .
            row_gen('bio', "Bio") . row_gen('email', "Email") . row_gen('telephone', "Telephone") . row_gen('tollfree', "Toll-Free") . row_gen('fax', "Fax") . row_gen('socialmedia', "Social Media") . row_gen('services', "Services") .
                '<tr><td colspan="2"><input type="submit" value="Save All Staff Settings" class="button button-primary button-large" /></td></tr>' .
                '</tbody</table></form>';
            echo $out;
        }

        ?>
		<div class="wp-admin-container">
			<h1>Staff Member View Settings</h1>
			<p>Create preset layouts to include into any post or page</p>
			<button id="staff-view-create">Create New View</button>

			<div id="staff-new-view">
				<?php form_gen("a");?><?php form_gen("a");?><?php form_gen("a");?>
			</div>
		</div>
		<?php
}

    public function options_staff_callback()
    {
        $is_save = isset($_POST["save"]) ? $_POST["save"] : '';

        if ($is_save == 1) {
            if (isset($_POST["staff_sort"])) {
                $order_ar = explode(",", $_POST["staff_sort"]);
                for ($a = 0; $a < count($order_ar); $a++) {
                    update_post_meta($order_ar[$a], '_data_order', $a);
                }
            }

            $display = $_POST["display"];
            print_r($_POST["display"]);
            update_option('wp_staff_options', $display);
        }

        $staff_options = get_option('wp_staff_options');
        ?>
		<div class="wp-admin-container">
			<h1>Staff Member Settings</h1>
			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
				<input type="hidden" name="save" value="1" />
				<p>This section allows you to set how your list of staff members will be viewed on your <a href="<?php get_site_url();?>/staff" target="_blank">staff page</a>.</p>
				<hr/>
				<h3>Staff Sorting</h3>
				<p>Drag and drop the staff members to how you would like them ordered on the frontend.</p>
				<?php
$args = array(
            'post_type' => WPREM_STAFF_MANAGER_CUSTOM_POST_TYPE,
            'meta_key' => '_data_order',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'posts_per_page' => -1,
        );
        echo '<ul id="sortable">';
        $staffs = get_posts($args);
        foreach ($staffs as $staff):
            setup_postdata($staff);
            echo '<li class="ui-state-default" data-id="' . $staff->ID . '"><strong>' . $staff->post_title . '</strong> - ' . get_post_meta($staff->ID, '_data_title', true) . '<br/><a href="post.php?post=' . $staff->ID . '&action=edit" target="_blank" style="color:#0073aa;">Edit</a></li>';
        endforeach;
        wp_reset_postdata();
        echo "</ul>";
        ?>
				<hr/>
				<h3>All Staff View</h3>
				<p>Select the default Staff View when all Staff Members are shown.</p>
				<?php
$args = array(
            'post_type' => WPREM_STAFF_VIEWS_CUSTOM_POST_TYPE,
            'orderby' => 'post_title',
            'order' => 'ASC',
        );
        echo '<select name="display[wp_allstaff_view]">';
        $staffviews = get_posts($args);
        foreach ($staffviews as $staffview):
            setup_postdata($staffview);
            echo '<option value="' . $staffview->ID . '" ' . selected($staff_options['wp_allstaff_view'], $staffview->ID, false) . '>' . $staffview->post_title . '</option>';
        endforeach;
        wp_reset_postdata();
        echo "</select>";
        ?>
				<hr/>
				<h3>Single Staff View</h3>
				<p>Select the default Staff View when a single Staff Members is shown.</p>
				<?php
$args = array(
            'post_type' => WPREM_STAFF_VIEWS_CUSTOM_POST_TYPE,
            'orderby' => 'post_title',
            'order' => 'ASC',
        );
        echo '<select name="display[wp_singlestaff_view]">';
        $staffviews = get_posts($args);
        foreach ($staffviews as $staffview):
            setup_postdata($staffview);
            echo '<option value="' . $staffview->ID . '" ' . selected($staff_options['wp_singlestaff_view'], $staffview->ID, false) . '>' . $staffview->post_title . '</option>';
        endforeach;
        wp_reset_postdata();
        echo "</select>";
        ?>
				<input type="hidden" name="staff_sort" id="staff_sort" />
				<p>
					<input type="submit" value="Save All Staff Settings" class="button button-primary button-large" />
				</p>
			</form>
		</div>
		<?php
}
}
