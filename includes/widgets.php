<?php
/******************************************************************
Author: Chris Baldelomar
URL: http://webplantmedia.com

All widget code should go here.
******************************************************************/

/**
 * Register Widgets
 *
 * @since 3.6.1
 * @access public
 *
 * @return void
 */
function wc_widgets_register_widgets() {
	// Register about me widget
	register_widget('WC_Widgets_About_Me_Widget');

	// uses official Pinterest javascript widget
	register_widget('WC_Widgets_Pinterest_Widget');

	// image widget
	register_widget( 'WC_Widgets_Image_Widget' );
}
add_action('widgets_init', 'wc_widgets_register_widgets');


/**
 * WC_Widgets_Pinterest_Widget 
 *
 * My own widget class for pinterest. Works well, execpt for
 * one small display bug in footer.
 * 
 * @uses WP
 * @uses _Widget
 */
class WC_Widgets_Pinterest_Widget extends WP_Widget {
	function __construct() {
		$widget_ops = array( 'description' => __('Add your latest pins form Pinterest to your sidebar.') );
		parent::__construct( 'wordpresscanvas_pinterest', __('WP Canvas - Pinterest Widget'), $widget_ops );
	}

	function widget($args, $instance) {
		wp_enqueue_script( 'pinterest' );

		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];

		if ( !empty($instance['title']) )
			echo $args['before_title'] . $instance['title'] . $args['after_title'];

		if ( ! empty( $instance['width'] ) && is_numeric( $instance['width'] ) )
			$width = ( (int) $instance['width'] - 20 );
		else
			$width = 280;

		if ( ! empty( $instance['height'] ) && is_numeric( $instance['height'] ) )
			$height = (int) $instance['height'];
		else
			$height = 400;

		if ( !empty( $instance['username'] ) ) {
			$scale_width = round( $width / 2 ) - 2;
			echo '<a data-pin-do="embedUser" href="http://pinterest.com/'.$instance['username'].'/" data-pin-scale-width="' . $scale_width . '" data-pin-scale-height="' . $height . '" data-pin-board-width="' . $width . '"></a>';
		}

		echo $args['after_widget'];
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
		$instance['username'] = strip_tags( stripslashes($new_instance['username']) );
		$instance['height'] = (int) strip_tags( stripslashes($new_instance['height']) );
		$instance['width'] = (int) strip_tags( stripslashes($new_instance['width']) );
		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : 'Latest Pins!';
		$username = isset( $instance['username'] ) ? $instance['username'] : '';
		$height = isset( $instance['height'] ) ? $instance['height'] : 400;
		$width = isset( $instance['width'] ) ? $instance['width'] : 300;
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Pinterest Username:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" value="<?php echo $username; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width: (px)') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" value="<?php echo $width; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height: (px)') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" value="<?php echo $height; ?>" />
		</p>
		<?php
	}
}

class WC_Widgets_About_Me_Widget extends WP_Widget {
	function __construct() {
		$widget_ops = array( 'description' => __('Add and customize your "About Me" information.') );
		parent::__construct( 'wordpresscanvas_about_me', __('WP Canvas - About Me'), $widget_ops );
	}

	function widget($args, $instance) {
		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];

		if ( !empty($instance['title']) )
			echo $args['before_title'] . $instance['title'] . $args['after_title'];

		$class = !empty( $instance['style'] ) ? $instance['style'] : 'none';

		$style = array();
		if ( 'circle' == $class ) {
			$style[] = 'border-radius:50%';
		}


		$url = !empty( $instance['url'] ) ? esc_url( $instance['url'] ) : '';
		$image = $instance['image'];

		if ( ! empty( $image ) ) {
			if ( !empty( $url ) )
				echo '<a class="image-hover" href="'.$url.'">';

			echo '<img class="img-'.$class.'" src="'.$image.'" style="'.implode( ';', $style ).'" />';

			if ( !empty( $url ) )
				echo '</a>';
		}

		$allowed_html = array(
			'a' => array(
				'href' => array(),
				'title' => array(),
				'target' => array(),
			),
			'br' => array(),
			'em' => array(),
			'strong' => array(),
		);
		if ( !empty( $instance['description'] ) )
			echo '<p class="sidebar-caption">'.wp_kses( $instance['description'], $allowed_html ).'</p>';

		echo $args['after_widget'];
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
		$instance['image'] = esc_url_raw( $new_instance['image'] );
		$instance['description'] = stripslashes( $new_instance['description'] );
		$instance['style'] = strip_tags( $new_instance['style'] );
		$instance['url'] = esc_url_raw( $new_instance['url'] );
		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : 'About Me!';
		$image = isset( $instance['image'] ) ? $instance['image'] : '';
		$imagestyle = '';
		if ( empty( $image ) )
			$imagestyle = ' style="display:none"';

		$description = isset( $instance['description'] ) ? $instance['description'] : '';
		$description = esc_textarea($description);
		$style = isset( $instance['style'] ) ? $instance['style'] : 'none';
		$url = isset( $instance['url'] ) ? $instance['url'] : '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</p>
		<div class="wc-widgets-image-field">
			<input class="widefat" id="<?php echo $this->get_field_id( 'image' ); ?>" name="<?php echo $this->get_field_name( 'image' ); ?>" type="text" value="<?php echo $image; ?>" />
			<a class="wc-widgets-image-upload button inline-button" data-target="#<?php echo $this->get_field_id( 'image' ); ?>" data-preview=".wc-widgets-preview-image" data-frame="select" data-state="wc_widgets_insert_single" data-fetch="url" data-title="Insert Image" data-button="Insert" data-class="media-frame wc-widgets-custom-uploader" title="Add Media">Add Media</a>
			<a class="button wc-widgets-delete-image" data-target="#<?php echo $this->get_field_id( 'image' ); ?>" data-preview=".wc-widgets-preview-image">Delete</a>
			<div class="wc-widgets-preview-image"<?php echo $imagestyle; ?>><img src="<?php echo esc_attr($image); ?>" /></div>
		</div>
		<p>
			<label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description:') ?></label>
			<textarea class="widefat" rows="4" cols="20" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>"><?php echo $description; ?></textarea>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Select Style:'); ?></label>
			<select id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>">
				<option value="none"<?php selected( $style, 'none' ); ?>>None</option>';
				<option value="circle"<?php selected( $style, 'circle' ); ?>>Circle</option>';
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('URL:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" value="<?php echo $url; ?>" />
		</p>
		<?php
	}
}

class WC_Widgets_Image_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_image', 'description' => __('Add and customize your "About Me" information.') );
		parent::__construct( 'image', __('WP Canvas - Image', 'wc_widgets' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		echo $before_widget;

		$title = apply_filters( 'widget_title', $instance['title'] );

		if ( $title )
			echo $before_title . esc_html( $title ) . $after_title;

		if ( '' != $instance['img_url'] ) {

			$output = '<img src="' . esc_attr( $instance['img_url'] ) .'" ';
			if ( '' != $instance['alt_text'] )
				$output .= 'alt="' . esc_attr( $instance['alt_text'] ) .'" ';
			if ( '' != $instance['img_title'] )
				$output .= 'title="' . esc_attr( $instance['img_title'] ) .'" ';
			$output .= '/>';

			if ( '' != $instance['link'] )
				$output = '<a class="thumbnail-link image-hover" href="' . esc_attr( $instance['link'] ) . '">' . $output . '</a>';

			$allowed_html = array(
				'a' => array(
					'href' => array(),
					'title' => array(),
					'target' => array(),
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			);
			if ( '' != $instance['caption'] )
				$output = $output . '<p class="sidebar-caption">' . wp_kses( $instance['caption'], $allowed_html ) . '</p>';

			echo '<div class="wc-widgets-image-container">' . do_shortcode( $output ) . '</div>';
		}

		echo "\n" . $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['img_url'] = esc_url( $new_instance['img_url'], null, 'display' );
		$instance['alt_text'] = strip_tags( $new_instance['alt_text'] );
		$instance['img_title'] = strip_tags( $new_instance['img_title'] );
		$instance['caption'] = $new_instance['caption'];
		$instance['link'] = esc_url( $new_instance['link'], null, 'display' );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance,
			array(
				'title' => '',
				'img_url' => '',
				'alt_text' => '',
				'img_title' => '',
				'caption' => '',
				'img_width' => '',
				'img_height' => '',
				'link' => ''
			));

		$title = esc_attr( $instance['title'] );
		$img_url = esc_url( $instance['img_url'], null, 'display' );
		$imagestyle = '';

		if ( empty( $img_url ) )
			$imagestyle = ' style="display:none"';

		$alt_text = esc_attr( $instance['alt_text'] );
		$img_title = esc_attr( $instance['img_title'] );
		$caption = esc_attr( $instance['caption'] );

		$link = esc_url( $instance['link'], null, 'display' );

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo esc_html__( 'Widget title:', 'wc_widgets' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
			</label>
		</p>
		<div class="wc-widgets-image-field">
			<input class="widefat" id="<?php echo $this->get_field_id( 'img_url' ); ?>" name="<?php echo $this->get_field_name( 'img_url' ); ?>" type="text" value="<?php echo $img_url; ?>" />
			<a class="wc-widgets-image-upload button inline-button" data-target="#<?php echo $this->get_field_id( 'img_url' ); ?>" data-preview=".wc-widgets-preview-image" data-frame="select" data-state="wc_widgets_insert_single" data-fetch="url" data-title="Insert Image" data-button="Insert" data-class="media-frame wc-widgets-custom-uploader" title="Add Media">Add Media</a>
			<a class="button wc-widgets-delete-image" data-target="#<?php echo $this->get_field_id( 'img_url' ); ?>" data-preview=".wc-widgets-preview-image">Delete</a>
			<div class="wc-widgets-preview-image"<?php echo $imagestyle; ?>><img src="<?php echo esc_attr( $img_url ); ?>" /></div>
		</div>
		<p>
			<label for="<?php echo $this->get_field_id( 'alt_text' ); ?>"><?php echo esc_html__( 'Alternate text:', 'wc_widgets' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'alt_text' ); ?>" name="<?php echo $this->get_field_name( 'alt_text' ); ?>" type="text" value="<?php echo $alt_text; ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'img_title' ); ?>"><?php echo esc_html__( 'Image title:', 'wc_widgets' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'img_title' ); ?>" name="<?php echo $this->get_field_name( 'img_title' ); ?>" type="text" value="<?php echo $img_title; ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'caption' ); ?>"><?php echo esc_html__( 'Caption:', 'wc_widgets' ); ?>
			<input class="widefat" id="<?php echo $this->get_field_id( 'caption' ); ?>" name="<?php echo $this->get_field_name( 'caption' ); ?>" type="text" value="<?php echo $caption; ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php echo esc_html__( 'Link URL (when the image is clicked):', 'wc_widgets' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo $link; ?>" />
			</label>
		</p>
		<?php
	}
}
