<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://adamwills.io
 * @since      1.0.0
 *
 * @package    Know_Before_You_Throw
 * @subpackage Know_Before_You_Throw/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<form action="" method="get">
	<div class="form-group">
		<label for="searchkbyt"><?= __('Search', $this->plugin_name ); ?></label>
		<input type="text" id="searchkbyt" name="searchkbyt" class="form-control" autocomplete="off">
	</div>
	<button class="btn btn-primary" type="submit">Submit</button>
</form>