<ul id="bwl_faq_items">

    <?php
    $args = array(
        'post_type' => 'bwl_advanced_faq',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'post_status' => 'publish'
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) :
            $query->the_post();
            ?>

            <li id="<?php the_id(); ?>" class="menu-item">
                <dl class="menu-item-bar">
                    <dt class="menu-item-handle">
                    <span class="menu-item-title"><?php the_title(); ?></span>
                    </dt>
                </dl>
                <ul class="menu-item-transport"></ul>
            </li>

            <?php
        endwhile;
    endif;
    wp_reset_query();  //reset the query   
    ?>           

</ul> <!-- end #bwl_faq_items  -->