<?php
/**
 * YBI Customizer Awesome.
 *
 * @package WordPress
 * @subpackage YBICustomizer
 * @since 1.0
 */
  function ybi_customizer_awesome_js() {

	//wp_register_style( 'slider-handle', get_template_directory_uri() . '/ybi-customizer-awesome/css/cupertino/jquery-ui-1.10.2.custom.css','1.10.2');
    //wp_enqueue_style( 'slider-handle' );
    wp_enqueue_script( 'ybi-jqui-customizer', 'http://code.jquery.com/ui/1.10.2/jquery-ui.js');
}
add_action( 'customize_preview_init', 'ybi_customizer_awesome_js' );
 
class YBI_IncludeCSS extends WP_Customize_Control {
public function render_content() {
	// include the style sheet for the slider
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/ybi-customizer-awesome/css/cupertino/jquery-ui-1.10.2.custom.css">
<?php			
	}}
	
	
		class YBI_Slider extends WP_Customize_Control {
		// the slider minimum
		public $slider_min = -1;
		// the slider maximum
		public $slider_max = 100;
		// can user edit the text box
		public $can_edit = true;
		// construct for the slider class
		public function __construct($manager, $id, $args = array(),$sliderArgs = array()) 
		{
			// we set the base variables based on the SliderArg array
			$keys = array_keys( get_object_vars( $this ) );
			foreach ( $keys as $key ) {
				if ( isset( $sliderArgs[ $key ] ) )
					$this->$key = $sliderArgs[ $key ];
			}
			// call the parent construct of WP_Customize_Control
	    	parent::__construct($manager, $id, $args);
	   }
		public function setSliderMax($new_value)
		{
		    $this->slider_max = $new_value;
		}
		public function setSliderMin($new_value)
		{
		    $this->slider_min = $new_value;
		}
		// now we call render content
		public function render_content() {
			// set the type of input (is it visible)
			$type = "text";
			// if the user can't edit then we hide the input box
			// if it is hidden we still display the value it's only in a span element down below
			if(!$this->can_edit)
				$type = "hidden";
		
			// get the id set for this elmeent, we use a ton below to set the specific input, slider, and other variables
			$item_name = $this->id;
?>
			<label style="padding: 6px 0;"><span class="customize-control-title" style="padding: 8px 0;"><?php echo esc_html( $this->label ); ?>:
             <?php if(!$this->can_edit) : ?><span style="font-weight: bold;" class="<?php echo $item_name; ?>"></span><?php endif; ?>
            <input type="<?php echo $type; ?>" id="<?php echo $item_name; ?>" value="<?php echo $this->value(); ?>" <?php $this->link(); ?> style="width: 50px;" /></span>
		    <div id="<?php echo $item_name; ?>_slider"></div></label>
			<script>
            jQuery(document).ready(function($)
            {
                // we create a new function from the name so we can call it anywhere, this allows for easy onclick updates for instance
                function <?php echo $item_name; ?>(theValue)
                {
                    // we call .change() here so the customizer recognizes a change happened and will pass it to the js file for live updates, must have transport'=>'postMessage'
                    $('#<?php echo $item_name; ?>').val(theValue).change();
                    $('#<?php echo $item_name; ?>').val(theValue);
                    $('.<?php echo $item_name; ?>').html(theValue);
                    // we call this here so this method can be used for setup and other quick shortcuts, that way no matter what the slider is updated correctly
                    $( "#<?php echo $item_name; ?>_slider" ).slider( "option", "value", theValue );
                }
                var myOptions = 
                {
                    slide: function(event, ui){
                        // on event call the function and pass the value
                        <?php echo $item_name; ?>($("#<?php echo $item_name; ?>_slider").slider( "option", "value" ));
                    }	
                }
                //set up the slider
                $( "#<?php echo $item_name; ?>_slider" ).slider(myOptions);
                $( "#<?php echo $item_name; ?>_slider" ).slider( "option", "min", <?php echo $this->slider_min; ?> );
                $( "#<?php echo $item_name; ?>_slider" ).slider( "option", "max", <?php echo $this->slider_max; ?> );
                // setting the initial value by calling function on page load
                <?php echo $item_name; ?>(<?php echo $this->value(); ?>);

                 $('#<?php echo $item_name; ?>').each(function() {
                   var elem = $(this);
                   // Save current value of element
                   elem.data('oldVal', elem.val());
                   // Look for changes in the value
                   elem.bind("propertychange keyup input paste", function(event){
                      if (elem.data('oldVal') != elem.val()) {
                       elem.data('oldVal', elem.val());
                        if(elem.val() != '')
                        {
							// if it changed then we update the slider, all other changes should be picked up via the customizer and passed to the js
                            $( "#<?php echo $item_name; ?>_slider" ).slider( "option", "value", elem.val() );
                        }
                     }
                   });
                 });
            });
            </script>
           
           <?php
		} //function render_content()
	} //YBI_Slider extends WP_Customize_Control
	class YBI_Textarea_Control extends WP_Customize_Control {
		public $type = 'textarea';
		public function render_content() {
			?>
			<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
			</label>
			<?php
			} // render_content()
	} //YBI_Textarea_Control extends WP_Customize_Control
?>