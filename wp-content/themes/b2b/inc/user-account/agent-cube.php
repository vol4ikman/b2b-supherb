<?php
/**
 * Agent cube
 *
 * @package WordPress
 */

$this_user = isset( $args['current_user'] ) ? $args['current_user'] : false;

$agent_code = get_field( 'agent_code', 'user_' . $this_user->ID );
$agent_name = get_field( 'agent_name', 'user_' . $this_user->ID );

$agent = get_agent_by_agent_code( $agent_code ); // api.php.
?>

<div class="c-cube">

	<div class="cube-header">
		<div class="icon">
			<img src="<?php echo esc_url( THEME ); ?>/images/agent-icon.png" alt="">
		</div>
		<div class="title">
			פרטי סוכן
		</div>
	</div>

	<div class="c-cube-inner">

		<div class="agent-card">
			<div class="agent-image">
				<img src="<?php echo esc_url( THEME ); ?>/images/agent-avatar.png" alt="">
			</div>
			<?php if ( $agent_name ) : ?>
				<div class="agent-name">
					<?php echo esc_html( $agent_name ); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="agent-details">
			<ul>
				<?php if ( $agent_code ) : ?>
					<li>
						<div class="label">
							מספר סוכן:
						</div>
						<div class="data">
							<?php echo esc_html( $agent_code ); ?>
						</div>
					</li>
				<?php endif; ?>

				<?php if ( isset( $agent['PHONE'] ) && $agent['PHONE'] ) : ?>
					<li>
						<div class="label">
							טלפון:
						</div>
						<div class="data">
							<?php echo esc_html( $agent['PHONE'] ); ?>
						</div>
					</li>
				<?php endif; ?>

				<?php if ( isset( $agent['EMAIL'] ) && $agent['EMAIL'] ) : ?>
					<li>
						<div class="label">
							דוא״ל:
						</div>
						<div class="data">
							<?php echo esc_html( $agent['EMAIL'] ); ?>
						</div>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>

</div>
