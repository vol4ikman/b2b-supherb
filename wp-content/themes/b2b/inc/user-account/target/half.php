<?php
/**
 * Target cube - Half year data
 *
 * @package WordPress
 */

$tigmulim_by_half = array();
$tigmulim         = isset( $args['tigmulim'] ) ? $args['tigmulim'] : array();
if ( ! $tigmulim ) {
	return;
}

if ( $tigmulim ) {
	foreach ( $tigmulim as $item ) {
		if ( isset( $item['ZMET_PRDIODCODE'] ) && 'H1' === $item['ZMET_PRDIODCODE'] ) {
			$tigmulim_by_half = $item;
		} elseif ( isset( $item['ZMET_PRDIODCODE'] ) && 'H1' === $item['ZMET_PRDIODCODE'] ) {
			$tigmulim_by_half = $item;
		}
	}
}
if ( ! $tigmulim_by_half ) {
	return;
}

$total_sales = $tigmulim_by_half['TOTSALESCALC'] ? (int) $tigmulim_by_half['TOTSALESCALC'] : 0;

$percent       = round( ( $total_sales / $target ) * 100, 0 );
$circle_amount = $total_sales;

// $percent       = 45;
// $circle_amount = 67000;
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
				יעד חציוני
			</div>
			<div class="target-circle-desc">
				150,000 ₪
			</div>
			<div class="target-circle-desc">
				4% הנחה
			</div>
		</div>
	</div>

</div>
