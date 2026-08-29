<?php
/**
 * Generic page fallback — used for any page that isn't the front page,
 * Bookshelf, or Events (e.g. a Privacy Policy page).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<section class="pagehead">
		<div class="wrap">
			<h1><?php the_title(); ?></h1>
		</div>
	</section>
	<section class="band band-paper">
		<div class="wrap entry-content">
			<?php the_content(); ?>
		</div>
	</section>
	<?php
endwhile;

get_footer();
