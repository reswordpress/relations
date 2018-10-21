<ul class="relations_form_fields">
    <li class="relation_field posts_per_page">
        <div class="field_title">
            <label for="relations_posts_per_page"><?php _e('Number of posts:', 'relations'); ?></label>
        </div>
        <div class="field_element">
            <input type="number" id="relations_posts_per_page" name="posts_per_page" step="1" min="1" value="<?php echo $current_posts_per_page; ?>">
        </div>
    </li>
    <li class="relation_field position">
        <div class="field_title">
            <label for="relations_position"><?php _e('Position:', 'relations'); ?></label>
        </div>
        <div class="field_element">
            <select name="relations_position" id="relations_position" class="relations_select">
                <?php foreach( $positions as $position_key => $position ): ?>
                    <option value="<?php echo $position_key; ?>" <?php selected($current_position, $position_key); ?><?php echo $position_key === 'custom' ? 'disabled="disabled"' : ''  ?>>
                        <?php echo $position ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </li>
    <li class="relation_field inject_paragraph_number" <?php echo ($current_position === 'inline') ? 'style="display:block"' : ''; ?>>
        <div class="field_title">
            <label for="relations_inject_paragraph_number"><?php _e('Inject paragraph number:', 'relations'); ?></label>
        </div>
        <div class="field_element">
            <input type="number" id="relations_inject_paragraph_number" name="inject_paragraph_number" step="1" min="1" value="<?php echo $current_inject_paragraph_number; ?>">
        </div>
    </li>
    <li class="relation_field content_type">
        <div class="field_title">
            <label for="relations_content_type"><?php _e('Content type selection:', 'relations'); ?></label>
        </div>
        <div class="field_element">
            <select name="content_type" id="relations_content_type" class="relations_select">
                <?php foreach( $content_types as $content_type_key => $content_type ): ?>
                    <option
                        value="<?php echo $content_type_key; ?>"
                        <?php selected($current_content_type, $content_type_key); ?>
	                    <?php echo $content_type_key === 'custom' ? 'disabled="disabled"' : ''  ?>
                    >
                        <?php echo $content_type ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </li>
    <li class="relation_field display_type">
        <div class="field_title">
            <label for="relations_display_type"><?php _e('Display type:', 'relations'); ?></label>
        </div>
        <div class="field_element">
            <select name="display_type" id="relations_display_type" class="relations_select">
                <?php foreach( $display_types as $display_type_key => $display_type ): ?>
                    <option
                        value="<?php echo $display_type_key; ?>"
                        <?php selected($current_display_type, $display_type_key); ?>
                        <?php echo $display_type_key === 'custom' ? 'disabled="disabled"' : ''  ?>
                    >
                        <?php echo $display_type ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </li>

    <li class="relation_field order_type">
        <div class="field_title">
            <label for="relations_order_type"><?php _e('Order by:', 'relations'); ?></label>
        </div>
        <div class="field_element">
            <select name="order_type" id="relations_order_type" class="relations_select">
                <?php foreach( $order_types as $order_type_key => $order_type ): ?>
                    <option value="<?php echo $order_type_key; ?>" <?php selected($current_order_type, $order_type_key); ?>><?php echo $order_type ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </li>
</ul>
