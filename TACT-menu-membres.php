<?php

/*
Plugin Name: TACT Menu Membres
Plugin URI: http://www.tactsolutions.ca/wp/tact-menu-membres
Description: Affiche un menu dynamique pour les membres
Version: 1.0
Author: Cédric Talbot
Author URI: http://www.tactsolutions.ca
License: GPLv2
*/

class TACT_Menu_Membres_Widget extends WP_Widget
{
	public function __construct() {
		parent::__construct('tact_menu_membres_widget', __("Menu des membres", "tact_menu_membres"), array('description' => __("Affiche un menu dynamique pour les membres", "tact_menu_membres")));
	}

	public function form($instance) {
		// Validation
		if (isset($instance['target_page'])) {
			$targetPage = $instance['target_page'];
		} else {
			$targetPage = null;
		}

		// Request pour les pages
		$args = array(
			'sort_order' => 'ASC',
			'sort_column' => 'post_title',
			'hierarchical' => 1,
			'exclude' => '',
			'include' => '',
			'meta_key' => '',
			'meta_value' => '',
			'authors' => '',
			'child_of' => 0,
			'parent' => -1,
			'exclude_tree' => '',
			'number' => '',
			'offset' => 0,
			'post_type' => 'page',
			'post_status' => 'publish,private'
		);

		// Liste des pages
		$pages = get_pages($args);

		// Formulaire
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'target_page' ); ?>">
				<?php echo __("Page servant d'accueil des membres", "tact_menu_membres"); ?>
			</label>
			<br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'target_page' ); ?>"
			        name="<?php echo $this->get_field_name( 'target_page' ); ?>">
				<?php foreach($pages as $page): ?>
					<option value="<?php echo $page->ID; ?>" <?php if ($targetPage === $page->ID) echo "selected='selected"; ?>><?php echo $page->post_title; ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = array();
		if (!empty($new_instance['target_page'])) {
			$instance['target_page'] = intval($new_instance['target_page']);
		}

		return $instance;
	}

	public function widget($args, $instance) {
		echo $args['before_widget'];

		if (is_user_logged_in()) {
			// Texte
			$welcome = __("Bienvenue", "tact_menu_membres");
			$linkText = __("Accueil des membres", "tact_menu_membres");

			// Lien
			echo "$welcome <strong></strong> | <a href='" . get_page_link($instance['target_page']) . "'>$linkText</a>";
		} else {
			// Texte
			$linkText = __("Connexion", "tact_menu_membres");

			// Lien
			echo "<a href='" . wp_login_url() . "'>$linkText</a>";
		}

		echo $args['after_widget'];
	}
}

add_action("widgets_init", function() {
	register_widget("TACT_Menu_Membres_Widget");
});