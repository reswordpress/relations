<?php
class Relations_i18n {
	public function load_plugin_textdomain() {

        $plugin_rel_path = plugin_basename( RELATIONS_PATH ).'/languages';
        load_plugin_textdomain( 'relations', false, $plugin_rel_path );

	}

}
