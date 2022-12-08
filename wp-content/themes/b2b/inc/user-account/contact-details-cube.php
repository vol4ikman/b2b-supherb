<?php
/**
 * Contact details cube
 *
 * @package WordPress
 */

$this_user = isset( $args['current_user'] ) ? $args['current_user'] : false;
?>
<div class="c-cube">

	<div class="cube-header">
		<div class="icon">
			<img src="<?php echo esc_url( THEME ); ?>/images/agent-mail-icon.png" alt="">
		</div>
		<div class="title">
			פרטי יצירת קשר
		</div>
	</div>

	<div class="c-cube-inner">

		<div class="agent-details">
			<ul>
				<li>
					<div class="label">
						שם פרטי:
					</div>
					<div class="data">
						123456789
					</div>
				</li>
				<li>
					<div class="label">
						שם משפחה:
					</div>
					<div class="data">
						0507526278
					</div>
				</li>
				<li>
					<div class="label">
						אימייל:
					</div>
					<div class="data">
						lior@gmail.com
					</div>
				</li>
			</ul>
		</div>
	</div>

</div>
