<?php
/*
Template Name: News Archives
*/

get_header(); ?>

		<div id="container">
			<div id="content" role="main">
			<?php query_posts(array('post_type'=>'post')); ?>
			<?php if ( $wp_query->max_num_pages > 1 ) : ?>
				<div id="nav-above" class="navigation">
					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'wordcampbase' ) ); ?></div>
					<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'wordcampbase' ) ); ?></div>
				</div>
			<?php endif; ?>

			<?php /* If there are no posts to display, such as an empty archive page */ ?>
			<?php if ( ! have_posts() ) : ?>
				<div id="post-0" class="post error404 not-found">
					<h1 class="entry-title"><?php _e( 'Not Found', 'wordcampbase' ); ?></h1>
					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive.
									 Perhaps searching will help find a related post.', 'wordcampbase' ); ?></p>
						<?php get_search_form(); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h2 class="entry-title">
					<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wordcampbase' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
				</h2>
				<div class="entry-meta"><?php twentyten_posted_on(); ?></div>
				<div class="entry-content"><?php the_content(); ?></div>
				<div class="entry-utility">
					<?php edit_post_link( __( 'Edit', 'wordcampbase' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
				</div>
			</div>

			<?php endwhile; // End the loop. Whew. ?>

			<?php if (  $wp_query->max_num_pages > 1 ) : ?>
				<div id="nav-below" class="navigation">
					<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'wordcampbase' ) ); ?></div>
					<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'wordcampbase' ) ); ?></div>
				</div><!-- #nav-below -->
<?php endif; ?>
			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
