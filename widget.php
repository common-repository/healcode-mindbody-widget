<?php
/**
 * MINDBODY Widget Insert Widget Class
 */

////*****************************Sidebar Widget**********************************////

class Hc_Insert_Html_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(false, $name = 'MINDBODY Widget');
    }

    /** @see WP_Widget::widget -- do not rename this */
    function widget($args, $instance) {
        extract( $args );
        global $wpdb;
        $title 		= apply_filters('widget_title', $instance['title']);
       	$hc_hmw_id = $instance['message'];
       	//echo "SELECT content FROM ".$wpdb->prefix."hc_hmw_short_code  WHERE id='$hc_hmw_title'";die;
        $entries = $wpdb->get_results($wpdb->prepare( "SELECT content FROM ".$wpdb->prefix."hc_hmw_short_code  WHERE id=%d",$hc_hmw_id ));

        $entry = $entries[0];

        echo $before_widget;
        if ( $title )
        echo $before_title . $title . $after_title;
		echo do_shortcode($entry->content);

        echo $after_widget;

    }

    /** @see WP_Widget::update -- do not rename this */
    function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['message'] = strip_tags($new_instance['message']);
        return $instance;
    }

    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {
    	global $wpdb;
    	$entries = $wpdb->get_results($wpdb->prepare( "SELECT * FROM ".$wpdb->prefix."hc_hmw_short_code WHERE status=%d  ORDER BY id DESC",1 ));


    	if(isset($instance['title'])){
    		$title	= esc_attr($instance['title']);
    	}else{
    		$title = '';
    	}

    	if(isset($instance['message'])){
    		$message	= esc_attr($instance['message']);
    	}else{
    		$message = '';
    	}

        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p>
          <label for="<?php echo $this->get_field_id('message'); ?>"><?php _e('Choose Widget Shortcode :'); ?></label>

          <!--  <input class="widefat" id="<?php echo $this->get_field_id('message'); ?>" name="<?php echo $this->get_field_name('message'); ?>" type="text" value="<?php echo $message; ?>" />-->
          <select name="<?php echo $this->get_field_name('message'); ?>">
          <?php
					if( count($entries)>0 ) {
						$count=1;
						$class = '';
						foreach( $entries as $entry ) {
					?>
					<option value="<?php echo $entry->id;?>" <?php if($message==$entry->id)echo "selected"; ?>><?php echo $entry->title;?></option>
					<?php
						}
					}
					?>
          </select>
        </p>
        <?php
    }


} // end class Hc_Insert_Html_Widget

function Register_Hc_Insert_Html_Widget() {
  return register_widget('Hc_Insert_Html_Widget');
}

add_action('widgets_init', 'Register_Hc_Insert_Html_Widget');
?>
