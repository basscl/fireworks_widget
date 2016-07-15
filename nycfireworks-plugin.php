<?php
/*
Plugin Name: Fireworks Plugin for lyingibex.com
Description: NYC Fireworks
*/
/* Start Adding Functions Below this Line */

// Creating the widget 
class fw_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'fw_widget', 

// Widget name will appear in UI
__('NYC Fireworks Widget', 'fw_widget_domain'), 

// Widget description
array( 'description' => __( 'Displays upcoming fireworks dislays in New York City', 'fw_widget_domain' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// This is where you run the code and display the output
error_reporting(0);
echo "<div id='fireworks_widget'>";
function DOMinnerHTML($element)
{
    $innerHTML = "";
    $children = $element->childNodes;
    $date = date('Y-m-d');

    #loop through children
    for ($i = 0; $i < $children->length; $i++) {
	    $child = $children->item($i);
	    #catch the first of each
    	if (get_class($child)=="DOMText") {
		if ($i==0) {
			#get date of event
			$dateWithSpaces = str_ireplace(","," ",$child->wholeText);
			$dateArray =  (explode(" ",$dateWithSpaces));
			$m = $dateArray[2];
			$d = $dateArray[3];
			$input = "$m-$d-2016";
			$ymd = DateTime::createFromFormat('M-d-Y', $input)->format('Y-m-d');
			#compare to today's date
			if ($ymd < $date) {
				#if past
				echo "<div class = 'firework_date past'>"; 				
			}
			else {
			echo "<div class = 'firework_date'>"; 
		}
		}
		else {
			if ($ymd < $date) {
				#if past
				echo "<div class = 'firework_details past'>"; 				
			}
			else {
			echo "<div class = 'firework_details'>"; 
		}
		}
		
		#parse as html to process special characters correctly
        $tmp_dom = new DOMDocument();
        $tmp_dom->appendChild($tmp_dom->importNode($child, true));
        $innerHTML=trim($tmp_dom->saveHTML());	
	echo $innerHTML;

	echo "</div>";
    	}
    }
} 


$fireworks_page='http://www1.nyc.gov/nyc-resources/service/206/fireworks-displays';
// Load and parse fireworks page
$dom = new DOMDocument;
$dom->loadHTMLfile($fireworks_page);

// get all <p>
$p_tags = $dom->getElementsByTagName('p');
$tags_length=$p_tags->length-7;
for ($i = 0; $i < $tags_length; $i++) {
        echo DOMinnerHTML($p_tags->item($i)); 
}
echo "</div>";

//end of widget php

echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'fw_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class fw_widget ends here

// Register and load the widget
function fw_load_widget() {
	register_widget( 'fw_widget' );
}
add_action( 'widgets_init', 'fw_load_widget' );

/* Stop Adding Functions Below this Line */
?>
