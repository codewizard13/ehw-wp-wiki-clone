<link rel="stylesheet" href="notes.css" />

# ERIC'S NOTES

<a id="top-bookmark-btn" class="bookmark-link" href="#bookmark">JUMP TO BOOKMARK</a>


## 2024-06-18

**ChatGPT**: https://chatgpt.com/c/ea995f0b-a37e-4046-8b39-3303dc59d46f

To create a custom post template for the "video" post type in WordPress, follow these steps:

### Step 1: Create a Template File

1. **Create a New File**:
   - In your theme directory (usually found at `wp-content/themes/your-theme`), create a new file named `single-video.php`.

2. **Add Template Code**:
   - Open `single-video.php` and add the necessary PHP and HTML code to structure your video post. Here is a basic example:

   ```php
   <?php
   /**
    * The template for displaying all single videos
    *
    * @package YourTheme
    */

   get_header(); ?>

   <div id="primary" class="content-area">
       <main id="main" class="site-main" role="main">

       <?php
       while ( have_posts() ) :
           the_post();
           ?>

           <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
               <header class="entry-header">
                   <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
               </header><!-- .entry-header -->

               <div class="entry-content">
                   <?php
                   the_content();
                   ?>
               </div><!-- .entry-content -->

               <footer class="entry-footer">
                   <?php edit_post_link( __( 'Edit', 'your-theme' ), '<span class="edit-link">', '</span>' ); ?>
               </footer><!-- .entry-footer -->
           </article><!-- #post-## -->

           <?php
           // If comments are open or we have at least one comment, load up the comment template.
           if ( comments_open() || get_comments_number() ) :
               comments_template();
           endif;

       endwhile; // End of the loop.
       ?>

       </main><!-- #main -->
   </div><!-- #primary -->

   <?php
   get_sidebar();
   get_footer();
   ```

### Step 2: Customize Your Template

- Customize the `single-video.php` template to match your desired layout and styling.
- Use WordPress functions such as `the_title()`, `the_content()`, `the_post_thumbnail()`, etc., to display your video's title, content, and other custom fields if necessary.

### Step 3: Create a Custom Loop (Optional)

If you need to create a custom loop to display a list of videos on a specific page or archive, you can create a custom template file for that as well:

1. **Create Archive Template**:
   - Create a new file named `archive-video.php` in your theme directory.

2. **Add Archive Code**:
   - Add the necessary PHP and HTML code to structure your video archive. Here is a basic example:

   ```php
   <?php
   /**
    * The template for displaying video archive pages
    *
    * @package YourTheme
    */

   get_header(); ?>

   <div id="primary" class="content-area">
       <main id="main" class="site-main" role="main">

       <?php if ( have_posts() ) : ?>

           <header class="page-header">
               <h1 class="page-title"><?php post_type_archive_title(); ?></h1>
           </header><!-- .page-header -->

           <?php
           // Start the Loop.
           while ( have_posts() ) :
               the_post();

               /*
                * Include the Post-Format-specific template for the content.
                * If you want to override this in a child theme, then include a file
                * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                */
               get_template_part( 'template-parts/content', get_post_format() );

           endwhile;

           // Previous/next page navigation.
           the_posts_pagination(
               array(
                   'prev_text'          => __( 'Previous page', 'your-theme' ),
                   'next_text'          => __( 'Next page', 'your-theme' ),
                   'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'your-theme' ) . ' </span>',
               )
           );

       else :
           get_template_part( 'template-parts/content', 'none' );

       endif;
       ?>

       </main><!-- #main -->
   </div><!-- #primary -->

   <?php
   get_sidebar();
   get_footer();
   ```

### Step 4: Flush Permalinks (if necessary)

If you encounter any 404 errors or permalink issues after creating the custom post type and templates, go to your WordPress dashboard, navigate to **Settings > Permalinks**, and click "Save Changes" to flush the rewrite rules.

These steps should provide you with a basic template for your custom post type "video". You can further customize these templates according to your specific requirements.

---

<a id="bookmark" href="#top-bookmark-btn" title="back to top">BOOKMARK</a>
---