<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Wprem_Staff_Manager
 * @subpackage Wprem_Staff_Manager/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wprem_Staff_Manager
 * @subpackage Wprem_Staff_Manager/public
 * @author     Sergio Cutone <sergio.cutone@yp.ca>
 */
class Wprem_Staff_Manager_Public
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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wprem-staff-manager-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wprem-staff-manager-public-min.js', array('jquery'), $this->version, false);

    }

    public function populate_checkbox($form)
    {
        foreach ($form['fields'] as &$field) {

        }
    }

    public function dynamic_form($form)
    {
        if ($form['title'] == 'Dynamic Form') {
            $props = array(
                array(
                    'id' => 123,
                    'label' => 'My Field Label',
                    'type' => 'text',
                ), array(
                    'id' => 321,
                    'label' => 'My Field Label2',
                    'type' => 'text',
                ),
            );
            $field = GF_Fields::create($props);
            array_push($form['fields'], $field);

            $form['notifications'] = array(
                '558a90489ced3' => array(
                    'isActive' => true,
                    'id' => '558a90489ced3',
                    'name' => 'Admin Notification',
                    'event' => 'form_submission',
                    'to' => 'sergio.cutone@yp.ca',
                    'toType' => 'email',
                    'subject' => 'New submission from {form_title}',
                    'message' => '{all_fields}',
                    'from' => 'sergio.cutone@yp.ca',
                    'disableAutoformat' => false,
                ),
            );

            return $form;
        } else {
            return $form;
        }
    }

    public function single_staff_content($content)
    {
        global $post;
        if (is_singular(WPREM_STAFF_MANAGER_CUSTOM_POST_TYPE)) {
            $content = do_shortcode('[wp_staff id=' . get_the_ID() . ']');
        } elseif (is_post_type_archive(WPREM_STAFF_MANAGER_CUSTOM_POST_TYPE)) {
            $content = do_shortcode('[wp_staff]');
        }
        return $content;
    }

    public function staff_member_shortcode($atts)
    {
        ob_start();

        extract(shortcode_atts(array(
            'id' => '',
            'view' => '',
            'service' => false,
            'cat' => [],
        ), $atts));

        $staff_options = get_option('wp_staff_options');

        if (is_archive() || $service) {
            $view = $staff_options['wp_allstaff_view'];
        } elseif (is_singular(WPREM_STAFF_MANAGER_CUSTOM_POST_TYPE)) {
            $view = $staff_options['wp_singlestaff_view'];
        } else {
            if ($view) {
                $view = $view;
            } else {
                $view = $staff_options['wp_allstaff_view'];
            }
        }

        $row = 0;
        $vu = get_post_custom($view);

        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $ppp = isset($vu["_data_select_ppp"][0]) ? $vu["_data_select_ppp"][0] : 1;

        $taxquery = $cat ? array(
            array(
                'taxonomy' => 'wprem_staff_member_category',
                'field' => 'id',
                'terms' => explode(',', $cat),
            ),
        ) : '';

        $args_staff = array('post_status' => 'publish',
            'post_type' => WPREM_STAFF_MANAGER_CUSTOM_POST_TYPE,
            'meta_key' => '_data_order',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'posts_per_page' => $ppp,
            'paged' => $paged,
            'tax_query' => $taxquery,
        );

        // If ID is selected then update array Post Per Page = 1 & Post ID
        if ($id || is_singular(WPREM_STAFF_MANAGER_CUSTOM_POST_TYPE)) {
            $args_staff['p'] = $id ? $id : get_the_ID();
            $args_staff['posts_per_page'] = 1;
        }
        // - - - - - //

        $staff_query = new WP_Query($args_staff);
        ?>
			<div class="wprem-staff-container">
				<div class="wprem-staff">
			<?php
$contact_form = '';
        while ($staff_query->have_posts()) {
            $staff_query->the_post();
            $value = get_post_custom(get_the_ID());
            $staff = array();

            if ($row === 0) {
                echo '<div class="row" data-id="' . get_the_ID() . '" data-slug="' . get_post_field('post_name', get_post()) . '">';
            }

            if ($row == $vu["_data_select_columns"][0] || ($row == 2 && $vu["_data_select_columns"][0] == 5)) {
                echo '</div><div class="row" data-id="' . get_the_ID() . '" data-slug="' . get_post_field('post_name', get_post()) . '">';
                $row = 0;
            }

            // - - - - - Add permalink to photo and name
            if (isset($vu['_data_select_link'][0]) && $vu['_data_select_link'][0] == 'true') {
                $staff["cred"] = (isset($value['_data_cred'][0]) && $vu['_data_select_cred'][0] == 'true') ? '<span class="wprem-cred">' . $value['_data_cred'][0] . '</span>' : '';
                $staff["name"] = (isset($vu['_data_select_name'][0]) && $vu['_data_select_name'][0] == 'true') ? '<div style="margin:0" class="wprem-name text-' . $vu['_data_select_nt_align'][0] . '"><a href=' . get_the_permalink() . '>' . get_the_title() . $staff["cred"] . '</a></div>' : '';
                $staff["photo"] = (isset($vu['_data_select_photo'][0]) && $vu['_data_select_photo'][0] == 'true') ? '<div class="wprem-image staff-photo-' . $vu['_data_select_photoshape'][0] . ' staff-photo-rnd-' . $vu['_data_select_photostyle'][0] . '"><a href=' . get_the_permalink() . '>' . get_the_post_thumbnail(get_the_ID(), $vu['_data_select_photosize'][0]) . '</a></div>' : '';
            } else {
                $staff["cred"] = (isset($value['_data_cred'][0]) && $vu['_data_select_cred'][0] == 'true') ? '<span class="wprem-cred">' . $value['_data_cred'][0] . '</span>' : '';
                $staff["name"] = (isset($vu['_data_select_name'][0]) && $vu['_data_select_name'][0] == 'true') ? '<div style="margin:0" class="wprem-name text-' . $vu['_data_select_nt_align'][0] . '">' . get_the_title() . $staff["cred"] . '</div>' : '';
                $staff["photo"] = (isset($vu['_data_select_photo'][0]) && $vu['_data_select_photo'][0] == 'true') ? '<div class="wprem-image staff-photo-' . $vu['_data_select_photoshape'][0] . ' staff-photo-rnd-' . $vu['_data_select_photostyle'][0] . '">' . get_the_post_thumbnail(get_the_ID(), $vu['_data_select_photosize'][0]) . '</div>' : '';
            }
            // - - - - - //

            // - - - - - Bio Settings
            $staff_bio_info = '';
            if (isset($vu['_data_select_bio'][0])) {
                switch ($vu['_data_select_bio'][0]) {
                    case '1':
                        $staff_bio_info = get_the_content();
                        break;
                    case '2':
                        $staff_bio_info = substr(get_the_content(), 0, 150) . '... ';
                        break;
                    case '3':
                        $staff_bio_info = get_the_excerpt();
                        break;
                    default:
                        break;
                }
            }

            $staff_readmore = '';
            if (isset($vu['_data_select_readmore'][0]) && $vu['_data_select_readmore'][0] == 'true') {
                $staff_readmore = '<a href="' . get_permalink() . '"
                >Read More</a>';
            }

            $staff["bio"] = $staff_bio_info ? '<div class="wprem-content">' . $staff_bio_info . $staff_readmore . '</div>' : '';
            // - - - - - //

            // - - - - - Social Media
            $social = '';
            if (isset($vu['_data_select_socialmedia'][0]) && $vu['_data_select_socialmedia'][0] == 'true') {
                $social .= (isset($value['_data_text_linkedin'][0]) && $value['_data_text_linkedin'][0]) ? '<a href="' . $value['_data_text_linkedin'][0] . '" target="_blank" class="fab fa-linkedin wprem-socialmedia"></a>' : '';
                $social .= (isset($value['_data_text_facebook'][0]) && $value['_data_text_facebook'][0]) ? '<a href="' . $value['_data_text_facebook'][0] . '" target="_blank" class="fab fa-facebook-square wprem-socialmedia"></a>' : '';
                $social .= (isset($value['_data_text_instagram'][0]) && $value['_data_text_instagram'][0]) ? '<a href="' . $value['_data_text_instagram'][0] . '" target="_blank" class="fab fa-instagram wprem-socialmedia"></a>' : '';
                $social .= (isset($value['_data_text_youtube'][0]) && $value['_data_text_youtube'][0]) ? '<a href="' . $value['_data_text_youtube'][0] . '" target="_blank" class="fab fa-youtube wprem-socialmedia"></a>' : '';
                $social .= (isset($value['_data_text_google'][0]) && $value['_data_text_google'][0]) ? '<a href="' . $value['_data_text_google'][0] . '" target="_blank" class="fab fa-google-plus-g wprem-socialmedia"></a>' : '';
            }
            $staff["social"] = $social;
            // - - - - - //

            // - - - - - Staff information
            $staff["title"] = (isset($vu['_data_select_title'][0]) && $vu['_data_select_title'][0] == 'true') ? '<div class="wprem-title text-' . $vu['_data_select_nt_align'][0] . '">' . $value['_data_title'][0] . '</div>' : '';
            $staff["tollfree"] = (isset($vu['_data_select_tollfree'][0]) && $vu['_data_select_tollfree'][0] == 'true') ? '<div class="wprem-tollfreed"><span class="wprem-label"><span>Toll-Free:</span> <a href="tel:' . preg_replace("/[^0-9]/", "", $value['_data_tollfree'][0]) . '" class="wprem-link">' . $value['_data_tollfree'][0] . '</a></div>' : '';
            $staff["extension"] = $value['_data_ext'][0] ? ' <span class="wprem-ext"><span class="wprem-label">Ext:</span> ' . $value['_data_ext'][0] . '</span>' : '';
            $staff["telephone"] = (isset($vu['_data_select_telephone'][0]) && $vu['_data_select_telephone'][0] == 'true' && $value['_data_telephone'][0]) ? '<div class="wprem-telephone"><span class="wprem-label">Telephone:</span> <a href="tel:' . preg_replace("/[^0-9]/", "", $value['_data_telephone'][0]) . '" class="wprem-link">' . $value['_data_telephone'][0] . '</a>' . $staff["extension"] . '</div>' : '';
            $staff["fax"] = (isset($vu['_data_select_fax'][0]) && $vu['_data_select_fax'][0] == 'true' && $value['_data_fax'][0]) ? '<div class="wprem-fax"><span class="wprem-label">Fax: </span>' . $value['_data_fax'][0] . '</div>' : '';
            // - - - - - //

            // - - - - - Email
            $contact_form = '';
            $staff["email"] = '';
            if (isset($vu['_data_select_email'][0]) && $vu['_data_select_email'][0] == 'true') {
                if ($staff_options['wp_staff_contact_form_io'] == '1') {
                    $staff["email"] = (isset($vu['_data_select_email'][0]) && $vu['_data_select_email'][0] == 'true' && $value['_data_email_address'][0]) ? '<div class="wprem-email"><span class="wprem-label">Email:</span> ' . $value['_data_email_address'][0] . '</div>' : '';
                } elseif ($staff_options['wp_staff_contact_form_io'] == '2') {
                    $staff["email"] = (isset($vu['_data_select_email'][0]) && $vu['_data_select_email'][0] == 'true' && $value['_data_email_address'][0]) ? '<div class="wprem-email"><span class="wprem-label">Email:</span> <a href="mailto:' . $value['_data_email_address'][0] . '" class="wprem-contact-staff wprem-link">' . $value['_data_email_address'][0] . '</a></div>' : '';
                } elseif ($staff_options['wp_staff_contact_form_io'] == '3') {
                    $staff["email"] = (isset($vu['_data_select_email'][0]) && $vu['_data_select_email'][0] == 'true' && $value['_data_email_address'][0]) ? '<div class="wprem-email"><span class="wprem-label">Email:</span> <a href="#staff-member-contact" class="wprem-contact-staff wprem-link" data-email="' . strtolower($value['_data_email_address'][0]) . '" data-name="' . get_the_title() . '">' . $value['_data_email_address'][0] . '</a></div>' : '';
                    $contact_form = '<div id="staff-member-contact" style="display:none">' . do_shortcode('[gravityforms id=' . $staff_options['wp_staff_contact_form'] . ']') . '</div>';
                }
            }
            // - - - - - //

            // - - - - - Services
            $staff['services'] = '';
            if (defined('WPREM_SERVICES_CUSTOM_POST_TYPE')) {
                if (isset($value['_data_post_services'][0]) && $value['_data_post_services'][0]) {
                    $services = '<div class="wprem-h3">Services</div>';
                    $allservices = maybe_unserialize($value['_data_post_services'][0]);
                    foreach ($allservices as $key => $val) {
                        $service = get_post($val);
                        $services .= '<a href="' . get_the_permalink($val) . '" class="wprem-link">' . $service->post_title . '</a><br/>';
                    }
                    $staff['services'] = '<div class="wp-service text-' . $vu['_data_select_info_align'][0] . '"><div class="wprem-block3">' . $services . '</div></div>';
                }
            }
            // - - - - - //

            // - - - - - Locations
            $staff['locations'] = '';
            if (defined('WPREM_LOCATIONS_CUSTOM_POST_TYPE')) {
                if (isset($value['_data_post_locations'][0]) && $value['_data_post_locations'][0]) {
                    $locations = '<div class="wprem-h3">Find Me At These Locations</div>';
                    $allocations = maybe_unserialize($value['_data_post_locations'][0]);
                    foreach ($allocations as $key => $val) {
                        $service = get_post($val);
                        $locations .= '<a href="' . get_the_permalink($val) . '" class="wprem-link">' . $service->post_title . '</a><br/>';
                    }
                    $staff['locations'] = '<div class="wp-location text-' . $vu['_data_select_info_align'][0] . '"><div class="wprem-block3">' . $locations . '</div></div>';
                }
            }
            // - - - - - //

            $staff_details = '';
            if ($staff["bio"] || $staff["email"] || $staff["telephone"] || $staff["tollfree"] || $staff["fax"] || $staff["social"]) {
                $staff_details = '<div class="wp-info text-' . $vu['_data_select_info_align'][0] . '">' . $staff["bio"] . $staff["email"] . $staff["telephone"] . $staff["tollfree"] . $staff["fax"] . $staff["social"] . '</div>';
            }

            $staff_info = '<div class="wprem-block2">' . $staff["name"] . $staff["title"] . $staff_details . $staff['services'] . $staff['locations'] . '</div>';
            $staff_photo_info = '<div class="wprem-block1" data-id="' . get_the_ID() . '" data-slug="' . get_post_field('post_name', get_post()) . '">' . $staff["photo"] . $staff_info . '</div>';

            if ($vu['_data_select_photo'][0] == 'true' && $vu['_data_select_columns'][0] == '1') {
                echo '<div class="col-sm-3">' . $staff["photo"] . '</div>';
                echo '<div class="col-sm-9">' . $staff_info . '</div>';
            } else {
                if ($vu['_data_select_columns'][0] == '4') {
                    echo '<div class="col-sm-3">' . $staff_photo_info . '</div>';
                } elseif ($vu['_data_select_columns'][0] == '3') {
                    echo '<div class="col-sm-4">' . $staff_photo_info . '</div>';
                } elseif ($vu['_data_select_columns'][0] == '5') {
                    echo '<div class="col-sm-6">' . $staff_photo_info . '</div>';
                } else {
                    echo '<div class="col-xs-12 col-12 col-sm-6 col-sm-offset-3 offset-sm-3 text-center" style="margin-bottom:25px">' . $staff_photo_info . '</div>';
                }
            }
            $row++;
        }
        ?>
					</div>
				</div>
			</div>
			<?php

        $pagination = paginate_links(array(
            'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
            'total' => $staff_query->max_num_pages,
            'current' => max(1, get_query_var('paged')),
            'format' => '?paged=%#%',
            'show_all' => false,
            'type' => 'array',
            'end_size' => 2,
            'mid_size' => 1,
            'prev_next' => true,
            'prev_text' => sprintf('%1$s', __('< Previous', 'fl-automator')),
            'next_text' => sprintf('%1$s', __('Next >', 'fl-automator')),
            'add_args' => false,
            'add_fragment' => '',
        ));

        if (!empty($pagination)):
            echo '<nav aria-label="Page navigation" class="text-center"><ul class="pagination">';
            foreach ($pagination as $key => $page_link):
                $active = strpos($page_link, 'current') ? 'active' : '';
                echo '<li class="' . $active . '">' . $page_link . '</li>';
            endforeach;
            echo '</ul></nav>';
        endif;

        wp_reset_postdata();

        echo $contact_form;

        $out = ob_get_clean();
        return $out;
    }

    //Tag pagination pages
    public function wpd_custom_types_on_tag_archives($query)
    {
        if ($query->is_tag() && $query->is_main_query()) {
            $query->set('post_type', array(WPREM_STAFF_MANAGER_CUSTOM_POST_TYPE));
        }
    }

}
