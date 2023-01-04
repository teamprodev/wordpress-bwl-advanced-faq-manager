<?php

/***********************************************************
* @Description: BWL Advanced FAQ Manager Widget
* @Created At: 20-03-2013
* @Last Edited AT: 21-05-2014
* @Created By: Mahbub
***********************************************************/

function bwl_advanced_faq_manager_widget_init() {
   
    register_widget('Bwl_Advanced_Faq_Manager_Widget');
     
}

add_action( 'widgets_init', 'bwl_advanced_faq_manager_widget_init' ); 


class Bwl_Advanced_Faq_Manager_Widget extends WP_Widget {

    public function __construct() {     
 
            parent::__construct(
                    'bwl_advanced_faq_manager_widget',
                    esc_html__('BWL Advanced FAQ Manager Widget' , 'bwl-adv-faq'),
                    array(
                            'classname'     =>  'Bwl_Advanced_Faq_Manager_Widget',
                            'description'    =>   esc_html__('Display FAQ Lists In Main sidebar or footer sidebar' , 'bwl-adv-faq')
                    )
            );
        
    }
    
    public function form($instance) {
 
        $defaults = array(
            'title'                                                            =>  esc_html__('FAQ' , 'bwl-adv-faq'),
            'bwl_advanced_faq_manager_shortcode'     =>  '[bwla_faq]'
        );
        
        $instance = wp_parse_args((array) $instance, $defaults);
        
        extract($instance);
        
        if (is_rtl()) {

            wp_enqueue_style('bwl-advanced-faq-rtl-admin-faq-style');
        }
        ?>
 
        
        <p>
            <label for="<?php echo $this->get_field_id('title') ?>"><?php esc_html_e('FAQ' , 'bwl-adv-faq'); ?></label>
            <input type="text" 
                       class="widefat" 
                       id="<?php echo $this->get_field_id('title') ?>" 
                       name="<?php echo $this->get_field_name('title') ?>"
                       value="<?php echo esc_attr($title) ?>"/>
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('bwl_advanced_faq_manager_shortcode') ?>"><?php esc_html_e('FAQ Shortcode' , 'bwl-adv-faq'); ?></label>            
            <textarea id="<?php echo $this->get_field_id('bwl_advanced_faq_manager_shortcode') ?>" name="<?php echo $this->get_field_name('bwl_advanced_faq_manager_shortcode') ?>" cols="30" rows="3" class="widefat"><?php echo esc_attr($bwl_advanced_faq_manager_shortcode) ?></textarea>         
            
        </p>
        
        <div class="baf-sc-hints">
            <ol>
                <li><p>Display Random FAQ:</p>
<small>[bwla_faq orderby='rand' /]</small>
                </li>
                <li><p>Display Top Voted FAQs:</p>
<small>[bwla_faq meta_key='votes_count' orderby='meta_value_num' order='DESC'/]</small>
                </li>
                <li><p>Display Excerpt:</p>
<small>[bwla_faq sc_excerpt=1/]</small>
                </li>
            </ol>
        </div>
        
        <?php
        
    }
    
    public function update($new_instance, $old_instance) {
        
        $instance          = $old_instance;
        
        $instance['title'] = sanitize_title( $new_instance['title'] );
        
        $instance['bwl_advanced_faq_manager_shortcode']  =  sanitize_textarea_field( $new_instance['bwl_advanced_faq_manager_shortcode'] );
        
        return $instance;
        
    }
    
    public function widget($args, $instance) {
        
        extract($args);
        
        $title = apply_filters('widget-title' , $instance['title']);
        
        $bwl_advanced_faq_manager_shortcode = $instance['bwl_advanced_faq_manager_shortcode'];
        
        echo $before_widget;
        
        if($title) :
            
            echo $before_title.esc_html( $title ).$after_title;
        
        endif;         
        
        if( $bwl_advanced_faq_manager_shortcode ):
    
            echo html_entity_decode( esc_attr( do_shortcode( $bwl_advanced_faq_manager_shortcode ) ) );
       
        endif;
    
        echo $after_widget;
        
    }
 
    
}