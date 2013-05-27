	<div class="grid_12 alpha omega front-display">
	    <div class="image">
	        <img src="<?php echo get_bloginfo('template_url')?>/images/guadec2012.png" alt="Photo of the GNOME community at GUADEC 2012" />
	        <h2 class="image-label"><span>GUADEC is the annual conference of the GNOME community, held in Europe since 2000. GUADEC 2013 will be held in Brno, Czech Republic, a city which has played host to several other successful GNOME-related hackfests in the past.</span></h2>
	    </div>

	    <div class="clear"></div>

	    <div class="grid_12 front-container">

        <div class="news-posts">
<?php
query_posts('posts_per_page=3');

while ( have_posts() ) : the_post();
?>
	        <div id="post-<?php the_ID(); ?>" class="front-news">
		        <h2 class="entry-title front-title">
			        <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wordcampbase' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
		        </h2>

		        <div class="entry-meta"><?php twentyten_posted_on(); ?></div>

		        <div class="entry-summary front-summary"><?php the_excerpt(); ?></div>

		        <div class="entry-utility">
			        <?php edit_post_link( __( 'Edit', 'wordcampbase' ), '<span class="meta-sep">|</span> <span class="edit-link">', '</span>' ); ?>
		        </div>
	        </div>
<?php endwhile; // End the loop. Whew. ?>
	        <a href="<?php echo get_permalink(get_page_by_title( 'News Archive' )); ?>"><div class="button">News Archive</div></a>
        </div>

        <div class="sponsors-bar">
            <img src="<?php echo get_bloginfo('template_url')?>/images/sponsor-google.png" alt="Google" />
            <img src="<?php echo get_bloginfo('template_url')?>/images/sponsor-redhat.png" alt="Red Hat" />
            <a href="<?php echo get_permalink(get_page_by_title( 'Sponsors' )); ?>" class="become-sponsor">Become a sponsor too!</a>
        </div>
	</div>
