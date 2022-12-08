<?php
/**
 * Target cube - Year data
 *
 * @package WordPress
 */

$tigmulim = isset( $args['tigmulim'] ) ? $args['tigmulim'] : array();
if ( ! $tigmulim ) {
	return;
}

$tigmulim_by_year = array();
if ( $tigmulim ) {
	foreach ( $tigmulim as $item ) {
		if ( isset( $item['ZMET_PRDIODCODE'] ) && 'Y1' === $item['ZMET_PRDIODCODE'] ) {
			$tigmulim_by_year = $item;
		}
	}
}

if ( ! $tigmulim_by_year ) {
	return;
}

$target = 0;
if ( isset( $tigmulim_by_year['TARGET4'] ) && $tigmulim_by_year['TARGET4'] ) {
	$target = $tigmulim_by_year['TARGET4'];
} elseif ( isset( $tigmulim_by_year['TARGET3'] ) && $tigmulim_by_year['TARGET3'] ) {
	$target = $tigmulim_by_year['TARGET3'];
} elseif ( isset( $tigmulim_by_year['TARGET2'] ) && $tigmulim_by_year['TARGET2'] ) {
	$target = $tigmulim_by_year['TARGET2'];
} elseif ( isset( $tigmulim_by_year['TARGET1'] ) && $tigmulim_by_year['TARGET1'] ) {
	$target = $tigmulim_by_year['TARGET1'];
}

$total_sales = $tigmulim_by_year['TOTSALESCALC'] ? (int) $tigmulim_by_year['TOTSALESCALC'] : 0;

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
				יעד שנתי
			</div>
			<div class="target-circle-desc">
				<?php echo esc_html( number_format( $target ) ); ?> ₪
			</div>
			<div class="target-circle-desc">
				8% הנחה
			</div>
		</div>
	</div>

</div>
