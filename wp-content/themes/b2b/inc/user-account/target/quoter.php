<?php
/**
 * Target cube - Quoter data
 *
 * @package WordPress
 */

$current_month = date( 'n' ); //phpcs:ignore.
// Calculate the year quarter.
$year_quarter = ceil( $current_month / 3 );

$tigmulim = isset( $args['tigmulim'] ) ? $args['tigmulim'] : array();

$tigmulim_by_quoter = array();
if ( $tigmulim ) {
	foreach ( $tigmulim as $item ) {
		if ( 'Q' . $year_quarter === $item['ZMET_PRDIODCODE'] ) {
			$tigmulim_by_quoter = $item;
		}
	}
}

if ( ! $tigmulim_by_quoter ) {
	return;
}

$target = 0;
if ( isset( $tigmulim_by_quoter['TARGET4'] ) && $tigmulim_by_quoter['TARGET4'] ) {
	$target = $tigmulim_by_quoter['TARGET4'];
} elseif ( isset( $tigmulim_by_quoter['TARGET3'] ) && $tigmulim_by_quoter['TARGET3'] ) {
	$target = $tigmulim_by_quoter['TARGET3'];
} elseif ( isset( $tigmulim_by_quoter['TARGET2'] ) && $tigmulim_by_quoter['TARGET2'] ) {
	$target = $tigmulim_by_quoter['TARGET2'];
} elseif ( isset( $tigmulim_by_quoter['TARGET1'] ) && $tigmulim_by_quoter['TARGET1'] ) {
	$target = $tigmulim_by_quoter['TARGET1'];
}

$total_sales = $tigmulim_by_quoter['TOTSALESCALC'] ? (int) $tigmulim_by_quoter['TOTSALESCALC'] : 0;

$percent       = round( ( $total_sales / $target ) * 100, 0 );
$circle_amount = $total_sales;

?>

<div class="target-cube">

	<div class="target-cube-inner">
		<div class="target-circle-wrapper">
			<div role="progressbar" aria-valuenow="<?php echo esc_html( $percent ); ?>"
				aria-valuemin="0" aria-valuemax="100"
				style="--value:<?php echo esc_html( $percent ); ?>"></div>
			<div class="target-circle-inner">
				<div class="percent-title">
					<?php echo esc_html( $percent ); ?>%
				</div>
				<div class="amount-title">
					<?php echo esc_html( number_format( $circle_amount ) ); ?> ₪
				</div>
			</div>
		</div>

		<div class="target-circle-meta">
			<div class="target-circle-title">
				יעד רבעוני
			</div>
			<div class="target-circle-desc">
				<?php echo esc_html( number_format( $target ) ); ?> ₪
			</div>
			<div class="target-circle-desc">
				2% הנחה
			</div>
		</div>
	</div>

</div>
