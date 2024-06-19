<?php
/*
Template Name: Custom Posts
*/

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php
        // Define the custom query arguments
        $args = array(
            'post_type' => 'custom_post', // Custom post type
            'tax_query' => array(
                array(
                    'taxonomy' => 'custom_taxonomy', // Custom taxonomy
                    'field' => 'slug',
                    'terms' => 'term-slug', // Replace with your term slug
                ),
            ),
        );

        // Execute the custom query
        $query = new WP_Query($args);

        // Loop through the posts
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title('<h2 class="entry-title">', '</h2>'); ?>
                    </header><!-- .entry-header -->

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div><!-- .entry-content -->
                </article><!-- #post-## -->
                <?php
            }
        } else {
            echo '<p>No posts found.</p>';
        }

        // Restore original Post Data
        wp_reset_postdata();
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
