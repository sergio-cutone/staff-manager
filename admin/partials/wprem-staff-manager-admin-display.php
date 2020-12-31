<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Wprem_Staff_Manager
 * @subpackage Wprem_Staff_Manager/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php
$is_save = isset($_POST["save"]) ? $_POST["save"] : '';

if ($is_save == 1) {
    if (isset($_POST["staff_sort"])) {
        $order_ar = explode(",", $_POST["staff_sort"]);
        for ($a = 0; $a < count($order_ar); $a++) {
            update_post_meta($order_ar[$a], '_data_order', $a);
        }
    }
    $display = $_POST["display"];
    update_option('wp_staff_options', $display);
}
$staff_options = get_option('wp_staff_options');
?>


<div class="wrap">
	<h1>Staff Member Settings</h1>
	<p></p>
	<hr/>
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<input type="hidden" name="save" value="1" />
		<div class="cuztom cuztom--post v-cuztom">
			<div class="cuztom__content">
				<table class="form-table cuztom-table cuztom-main">
					<tbody>
						<tr class="cuztom-cell cuztom-tabs">
							<td class="cuztom-field" id="_data_tabs" colspan="2">
								<div class="js-cuztom-tabs ui-tabs ui-widget ui-widget-content ui-corner-all">
									<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
										<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active" role="tab" tabindex="0" aria-controls="_data_tabs_panel_4" aria-labelledby="ui-id-4" aria-selected="true" aria-expanded="true">
											<a href="#_data_tabs_panel_4" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-4">
												Main
											</a>
										</li>
										<li class="ui-state-default ui-corner-top ui-tabs-active" role="tab" tabindex="0" aria-controls="_data_tabs_panel_1" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true">
											<a href="#_data_tabs_panel_1" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1">
												Staff Sorting
											</a>
										</li>
										<li class="ui-state-default ui-corner-top" role="tab" tabindex="0" aria-controls="_data_tabs_panel_2" aria-labelledby="ui-id-2" aria-selected="true" aria-expanded="true">
											<a href="#_data_tabs_panel_2" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-2">
												Staff View: Single Staff
											</a>
										</li>
										<li class="ui-state-default ui-corner-top" role="tab" tabindex="0" aria-controls="_data_tabs_panel_3" aria-labelledby="ui-id-3" aria-selected="true" aria-expanded="true">
											<a href="#_data_tabs_panel_3" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-3">
												Staff View: All Staff
											</a>
										</li>
									</ul>
									<div id="_data_tabs_panel_1" aria-labelledby="ui-id-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-hidden="false">
										<table border="0" cellading="0" cellspacing="0" class="form-table cuztom-table">
											<tbody>
												<tr class="cuztom-cell">
													<td class="cuztom-field cuztom-field--text" data-id="_data_title">
														<p>
															<strong>Drag and drop the staff members to how you would like them ordered on the frontend.</strong>
														</p>
														<?php
$args = array(
    'post_type' => WPREM_STAFF_MANAGER_CUSTOM_POST_TYPE,
    'meta_key' => '_data_order',
    'orderby' => 'meta_value_num',
    'order' => 'ASC',
    'posts_per_page' => -1,
);
echo '<ul id="sortable" class="wprem-staff-sort">';
$staffs = get_posts($args);
foreach ($staffs as $staff):
    setup_postdata($staff);
    echo '<li class="ui-state-default" data-id="' . $staff->ID . '"><strong>' . $staff->post_title . '</strong> - ' . get_post_meta($staff->ID, '_data_title', true) . '<br/><a href="post.php?post=' . $staff->ID . '&action=edit" target="_blank" style="color:#0073aa;">Edit</a></li>';
endforeach;
wp_reset_postdata();
echo "</ul>";
?>
														<input type="hidden" name="staff_sort" id="staff_sort" />
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div id="_data_tabs_panel_2" aria-labelledby="ui-id-2" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-hidden="false">
										<table border="0" cellading="0" cellspacing="0" class="form-table cuztom-table">
											<tbody>
												<tr class="cuztom-cell">
													<th>Select the default Staff View when a single Staff Members is shown.</th>
													<td class="cuztom-field cuztom-field--text" data-id="_data_title">
														<?php
$args = array(
    'post_type' => WPREM_STAFF_VIEWS_CUSTOM_POST_TYPE,
    'orderby' => 'post_title',
    'order' => 'ASC',
);
echo '<select name="display[wp_singlestaff_view]" class="selected-view">';
$staffviews = get_posts($args);
foreach ($staffviews as $staffview):
    setup_postdata($staffview);
    echo '<option value="' . $staffview->ID . '" ' . selected($staff_options['wp_singlestaff_view'], $staffview->ID, false) . '>' . $staffview->post_title . '</option>';
endforeach;
wp_reset_postdata();
echo "</select>";
?>
														<br/>
														<a href="#" class="edit-view">Edit View</a>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div id="_data_tabs_panel_3" aria-labelledby="ui-id-3" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-hidden="false">
										<table border="0" cellading="0" cellspacing="0" class="form-table cuztom-table">
											<tbody>
												<tr class="cuztom-cell">
													<th>Select the default Staff View when all Staff Members are shown.</th>
													<td class="cuztom-field cuztom-field--text" data-id="_data_title">
														<?php
$args = array(
    'post_type' => WPREM_STAFF_VIEWS_CUSTOM_POST_TYPE,
    'orderby' => 'post_title',
    'order' => 'ASC',
);
echo '<select name="display[wp_allstaff_view]" class="selected-view">';
$staffviews = get_posts($args);
foreach ($staffviews as $staffview):
    setup_postdata($staffview);
    echo '<option value="' . $staffview->ID . '" ' . selected($staff_options['wp_allstaff_view'], $staffview->ID, false) . '>' . $staffview->post_title . '</option>';
endforeach;
wp_reset_postdata();
echo "</select>";
?>
														<br/>
														<a href="#" class="edit-view">Edit View</a>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div id="_data_tabs_panel_4" aria-labelledby="ui-id-4" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-hidden="false">
										<table border="0" cellading="0" cellspacing="0" class="form-table cuztom-table">
											<tbody>
												<tr class="cuztom-cell">
													<th>Default action for staff email address</th>
													<td class="cuztom-field cuztom-field--text" data-id="_data_title">
														<select name="display[wp_staff_contact_form_io]">
															<option value="1" <?php selected($staff_options['wp_staff_contact_form_io'], '1', true);?>>No Link</option>
															<option value="2" <?php selected($staff_options['wp_staff_contact_form_io'], '2', true);?>>Email Link</option>
															<option value="3" <?php selected($staff_options['wp_staff_contact_form_io'], '3', true);?>>Contact Form</option>
														</select>
													</td>
												</tr>
												<tr class="cuztom-cell">
													<th>Select the Contact Form for your Staff Members</th>
													<td class="cuztom-field cuztom-field--text" data-id="_data_title">
														<?php
$select = '<select name="display[wp_staff_contact_form]">';
$forms = RGFormsModel::get_forms(null, 'title');
foreach ($forms as $form):
    $select .= '<option value="' . $form->id . '" ' . selected($staff_options['wp_staff_contact_form'], $form->id, false) . '>' . $form->title . '</option>';
endforeach;
$select .= '</select><p>Please ensure that the form is set up properly with a <i>To: Email Field</i> with the classes: <strong>staff-member-email gf_readonly</strong></p> ';

echo $select;
?>
													</td>
												</tr>
												<tr class="cuztom-cell">
													<th>Change single Staff Member slug</th>
													<td class="cuztom-field cuztom-field--text" data-id="_data_title">
														<input type="text" name="display[wp_staff_slug]" value="<?php echo isset($staff_options['wp_staff_slug']) ? $staff_options['wp_staff_slug'] : ''; ?>" />
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<p>
			<input type="submit" value="Save Staff Settings" class="button button-primary button-large" />
		</p>
	</form>
</div>