<?php if (! defined('ABSPATH')) {
  die;
} // Cannot access directly.
/**
 *
 * Field: border
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */

if (! class_exists('EventfulField_border')) {
  class EventfulField_border extends EventfulFields
  {

    public function __construct($field, $value = '', $unique = '', $where = '', $parent = '')
    {
      parent::__construct($field, $value, $unique, $where, $parent);
    }

    public function render()
    {

      $args = wp_parse_args($this->field, array(
        'top_icon'           => '<i class="icofont-long-arrow-up"></i>',
        'left_icon'          => '<i class="icofont-long-arrow-left"></i>',
        'bottom_icon'        => '<i class="icofont-long-arrow-down"></i>',
        'right_icon'         => '<i class="icofont-long-arrow-right"></i>',
        'all_icon'           => '<i class="icofont-drag"></i>',
        'top_placeholder'    => esc_html__('top', 'eventful'),
        'right_placeholder'  => esc_html__('right', 'eventful'),
        'bottom_placeholder' => esc_html__('bottom', 'eventful'),
        'left_placeholder'   => esc_html__('left', 'eventful'),
        'all_placeholder'    => esc_html__('all', 'eventful'),
        'top'                => true,
        'left'               => true,
        'bottom'             => true,
        'right'              => true,
        'all'                => false,
        'color'              => true,
        'style'              => true,
        'unit'               => 'px',
        'min'                => '0',
        'hover_color'        => false,
        'border_radius'      => false,
        'show_units'         => false,
      ));

      $default_value = array(
        'top'        => '',
        'right'      => '',
        'bottom'     => '',
        'left'       => '',
        'color'      => '',
        'hover_color'   => '',
				'border_radius' => '',
        'style'      => 'solid',
        'all'        => '',
      );

      $border_props = array(
        'solid'     => esc_html__('Solid', 'eventful'),
        'dashed'    => esc_html__('Dashed', 'eventful'),
        'dotted'    => esc_html__('Dotted', 'eventful'),
        'double'    => esc_html__('Double', 'eventful'),
        'inset'     => esc_html__('Inset', 'eventful'),
        'outset'    => esc_html__('Outset', 'eventful'),
        'groove'    => esc_html__('Groove', 'eventful'),
        'ridge'     => esc_html__('ridge', 'eventful'),
        'none'      => esc_html__('None', 'eventful')
      );

      $default_value = (! empty($this->field['default'])) ? wp_parse_args($this->field['default'], $default_value) : $default_value;

      $value = wp_parse_args($this->value, $default_value);

      echo wp_kses_post($this->field_before());

      echo '<div class="eventful--inputs" data-depend-id="' . esc_attr($this->field['id']) . '">';

      if (! empty($args['all'])) {

        $placeholder = (! empty($args['all_placeholder'])) ? ' placeholder="' . esc_attr($args['all_placeholder']) . '"' : '';

        echo '<div class="eventful--border">';
        echo '<div class="eventful--title">' . esc_html__( 'Width', 'eventful' ) . '</div>';
        echo '<div class="eventful--input">';
        echo (! empty($args['all_icon'])) ? '<span class="eventful--label eventful--icon">' . wp_kses_post($args['all_icon']) . '</span>' : '';
        echo '<input type="number" name="' . esc_attr($this->field_name('[all]')) . '" value="' . esc_attr($value['all']) . '"' . wp_kses_post($placeholder) . ' class="eventful-input-number eventful--is-unit" step="any" />';
        echo (! empty($args['unit'])) ? '<span class="eventful--label eventful--unit">' . esc_attr($args['unit']) . '</span>' : '';
        echo '</div>';
        echo '</div>';
      } else {

        $properties = array();

        foreach (array('top', 'right', 'bottom', 'left') as $prop) {
          if (! empty($args[$prop])) {
            $properties[] = $prop;
          }
        }

        $properties = ($properties === array('right', 'left')) ? array_reverse($properties) : $properties;

        foreach ($properties as $property) {

          $placeholder = (! empty($args[$property . '_placeholder'])) ? ' placeholder="' . esc_attr($args[$property . '_placeholder']) . '"' : '';

          echo '<div class="eventful--input">';
          echo (! empty($args[$property . '_icon'])) ? '<span class="eventful--label eventful--icon">' . wp_kses_post($args[$property . '_icon']) . '</span>' : '';
          echo '<input type="number" name="' . esc_attr($this->field_name('[' . $property . ']')) . '" value="' . esc_attr($value[$property]) . '"' . wp_kses_post($placeholder) . ' class="eventful-input-number eventful--is-unit" step="any" />';
          echo (! empty($args['unit'])) ? '<span class="eventful--label eventful--unit">' . esc_attr($args['unit']) . '</span>' : '';
          echo '</div>';
        }
      }

      if (! empty($args['style'])) {
        echo '<div class="eventful--border">';
				echo '<div class="eventful--title">' . esc_html__( 'Style', 'eventful' ) . '</div>';
        echo '<div class="eventful--input">';
        echo '<select name="' . esc_attr($this->field_name('[style]')) . '">';
        foreach ($border_props as $border_prop_key => $border_prop_value) {
          $selected = ($value['style'] === $border_prop_key) ? ' selected' : '';
          echo '<option value="' . esc_attr($border_prop_key) . '"' . esc_attr($selected) . '>' . esc_attr($border_prop_value) . '</option>';
        }
        echo '</select>';
        echo '</div>';
        echo '</div>';
      }

      echo '</div>';

      if (! empty($args['color'])) {
        $default_color_attr = (! empty($default_value['color'])) ? ' data-default-color="' . esc_attr($default_value['color']) . '"' : '';
        echo '<div class="eventful--color">';
        echo '<div class="eventful-field-color">';
        echo '<div class="eventful--title">' . esc_html__( 'Color', 'eventful' ) . '</div>';
        echo '<input type="text" name="' . esc_attr($this->field_name('[color]')) . '" value="' . esc_attr($value['color']) . '" class="eventful-color"' . wp_kses_post($default_color_attr) . ' />';
        echo '</div>';
        echo '</div>';
      }
      if ( ! empty( $args['hover_color'] ) ) {
				$default_color_attr = ( ! empty( $default_value['hover_color'] ) ) ? ' data-default-color="' . esc_attr( $default_value['hover_color'] ) . '"' : '';
				echo '<div class="eventful--color">';
				echo '<div class="eventful-field-color">';
				echo '<div class="eventful--title">' . esc_html__( 'Hover Color', 'eventful' ) . '</div>';
				echo '<input type="text" name="' . esc_attr( $this->field_name( '[hover_color]' ) ) . '" value="' . esc_attr( $value['hover_color'] ) . '" class="eventful-color"' . wp_kses_post( $default_color_attr ) . ' />';
				echo '</div>';
				echo '</div>';
			}

      if ( ! empty( $args['border_radius'] ) ) {

				$placeholder = ( ! empty( $args['all_placeholder'] ) ) ? $args['all_placeholder'] : '';
				echo '<div class="eventful--color border-radius">';
				echo '<div class="eventful--title">' . esc_html__( 'Radius', 'eventful' ) . '</div>';
				echo '<div class="eventful--input">';
				echo ( ! empty( $args['all_icon'] ) ) ? '<span class="eventful--label eventful--icon"><i class="icofont-surface-tablet"></i></span>' : '';
				echo '<input type="number" name="' . esc_attr( $this->field_name( '[border_radius]' ) ) . '" value="' . esc_attr( $value['border_radius'] ) . '" placeholder="' . esc_attr( $placeholder ) . '" class="eventful-input-number eventful--is-unit" step="any" />';
        $units = !empty($args['units']) ? $args['units'] : array('px', 'em', 'rem', '%');
				if ( $args['show_units'] && ( $units ) > 1 ) {
					echo '<div class="eventful--input eventful--border-select">';
					echo '<select name="' . esc_attr( $this->field_name( '[unit]' ) ) . '">';
          if(is_array($units)) { 
            foreach ( $units as $unit ) {
              $selected = ( $value['unit'] === $unit ) ? ' selected' : '';
              echo '<option value="' . esc_attr( $unit ) . '"' . esc_attr( $selected ) . '>' . esc_attr( $unit ) . '</option>';
            }
          }
					echo '</select>';
					echo '</div>';
				} else {
					echo ( ! empty( $args['unit'] ) ) ? '<span class="eventful--label eventful--unit">' . esc_attr( $args['unit'] ) . '</span>' : '';

				}
				echo '</div></div>';

			}
      echo '<div class="clear"></div>';


      echo wp_kses_post($this->field_after());
    }

    public function output()
    {

      $output    = '';
      $unit      = (! empty($this->value['unit'])) ? $this->value['unit'] : 'px';
      $important = (! empty($this->field['output_important'])) ? '!important' : '';
      $element   = (is_array($this->field['output'])) ? join(',', $this->field['output']) : $this->field['output'];

      // properties
      $top     = (isset($this->value['top'])    && $this->value['top']    !== '') ? $this->value['top']    : '';
      $right   = (isset($this->value['right'])  && $this->value['right']  !== '') ? $this->value['right']  : '';
      $bottom  = (isset($this->value['bottom']) && $this->value['bottom'] !== '') ? $this->value['bottom'] : '';
      $left    = (isset($this->value['left'])   && $this->value['left']   !== '') ? $this->value['left']   : '';
      $style   = (isset($this->value['style'])  && $this->value['style']  !== '') ? $this->value['style']  : '';
      $color   = (isset($this->value['color'])  && $this->value['color']  !== '') ? $this->value['color']  : '';
      $all     = (isset($this->value['all'])    && $this->value['all']    !== '') ? $this->value['all']    : '';

      if (! empty($this->field['all']) && ($all !== '' || $color !== '')) {

        $output  = $element . '{';
        $output .= ($all   !== '') ? 'border-width:' . $all . $unit . $important . ';' : '';
        $output .= ($color !== '') ? 'border-color:' . $color . $important . ';'       : '';
        $output .= ($style !== '') ? 'border-style:' . $style . $important . ';'       : '';
        $output .= '}';
      } else if ($top !== '' || $right !== '' || $bottom !== '' || $left !== '' || $color !== '') {

        $output  = $element . '{';
        $output .= ($top    !== '') ? 'border-top-width:' . $top . $unit . $important . ';'       : '';
        $output .= ($right  !== '') ? 'border-right-width:' . $right . $unit . $important . ';'   : '';
        $output .= ($bottom !== '') ? 'border-bottom-width:' . $bottom . $unit . $important . ';' : '';
        $output .= ($left   !== '') ? 'border-left-width:' . $left . $unit . $important . ';'     : '';
        $output .= ($color  !== '') ? 'border-color:' . $color . $important . ';'                 : '';
        $output .= ($style  !== '') ? 'border-style:' . $style . $important . ';'                 : '';
        $output .= '}';
      }

      $this->parent->output_css .= $output;

      return $output;
    }
  }
}
