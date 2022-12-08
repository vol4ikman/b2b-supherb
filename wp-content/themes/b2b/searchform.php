<?php
/**
 * Default search form template
 *
 * @package WordPress
 */

$get_search_query = get_search_query();
?>
<form class="search" method="get" action="<?php echo esc_url( home_url() ); ?>" role="search" autocomplete="off">
	<button class="search-submit" type="submit" title="<?php esc_html_e( 'חיפוש', 'b2b' ); ?>"></button>
	<input class="search-input" type="search" name="s" placeholder="אני מחפש/ת..." autocomplete="off" value="<?php echo $get_search_query; ?>">
</form>
