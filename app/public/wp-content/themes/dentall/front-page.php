<?php

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main-content" class="dentall-main">
	<div class="dentall-container">
		<section class="dentall-hero" aria-labelledby="dentall-hero-title">
			<p class="dentall-eyebrow"><?php esc_html_e( 'DentAll starter theme', 'dentall' ); ?></p>
			<h1 id="dentall-hero-title"><?php esc_html_e( '专业口腔产品，清晰地呈现。', 'dentall' ); ?></h1>
			<p><?php esc_html_e( '这是 DentAll WordPress + WooCommerce 项目的最小主题基线，用来验证主题加载、响应式布局和后续 Git 同步。', 'dentall' ); ?></p>
			<a class="dentall-button" href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ); ?>">
				<?php esc_html_e( '查看商城', 'dentall' ); ?>
			</a>
		</section>

		<section class="dentall-section" aria-labelledby="dentall-features-title">
			<h2 id="dentall-features-title"><?php esc_html_e( '当前基线', 'dentall' ); ?></h2>
			<div class="dentall-grid">
				<article class="dentall-card">
					<h3><?php esc_html_e( '移动优先', 'dentall' ); ?></h3>
					<p><?php esc_html_e( '从手机布局开始，再向平板和 PC 增强。', 'dentall' ); ?></p>
				</article>
				<article class="dentall-card">
					<h3><?php esc_html_e( '原生 WooCommerce', 'dentall' ); ?></h3>
					<p><?php esc_html_e( '保留商品、购物车和结账的原生扩展路径。', 'dentall' ); ?></p>
				</article>
				<article class="dentall-card">
					<h3><?php esc_html_e( '代码可追踪', 'dentall' ); ?></h3>
					<p><?php esc_html_e( '主题代码进入 Git，后台内容仍由 WordPress 管理。', 'dentall' ); ?></p>
				</article>
			</div>
		</section>
	</div>
</main>

<?php
get_footer();
