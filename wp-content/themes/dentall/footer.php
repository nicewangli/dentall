<?php

defined( 'ABSPATH' ) || exit;
?>
<footer class="dentall-footer">
	<div class="dentall-container dentall-footer__inner">
		<p><?php echo esc_html( sprintf( __( '© %s DentAll', 'dentall' ), gmdate( 'Y' ) ) ); ?></p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
