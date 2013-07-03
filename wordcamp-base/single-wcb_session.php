<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<div id="container">
			<div id="content" role="main">
			<div class="entry-content">
			    <img alt="Photo of a talk at GUADEC 2010, with speaker talking and several attendees with laptops" src="https://www.guadec.org/wp-content/themes/wordcamp-base/images/pages/talks.jpg" />
                <span class="image-credits">By Mario Sánchez Prada [<a href="http://creativecommons.org/licenses/by-sa/2.0">CC-BY-SA-2.0</a>], <a href="https://www.flickr.com/photos/mariosp/4840482146/in/photostream/">via Flickr</a></span>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h1 class="entry-title sponsor-title talk-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wordcampbase' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php
						if ( has_post_thumbnail() )
							the_post_thumbnail();
						else
							the_title();
					?></a></h1>

					<div class="entry-meta session-speakers">
						<?php wcb_entry_meta(); ?>
					</div>

					<div class="talk-description">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'wordcampbase' ), 'after' => '</div>' ) ); ?>
					</div><!-- .talk-description -->
				</div><!-- #post-## -->

<?php endwhile; // end of the loop. ?>
            </div>
			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
