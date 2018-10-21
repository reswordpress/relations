<div class="wrap">
    <h1><?php _e( 'Related posts settings', 'relations' ) ?></h1>
    <form method="post" action="options.php">
		<?php
		settings_errors();
		settings_fields( 'relations_group' );
		do_settings_sections( 'relation_general_page' );
		submit_button();
		?>
    </form>
</div>