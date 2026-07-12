<?php

/**
 * Views class for Shortcode generator section.
 *
 * @link       https://themeatelier.net
 * @since      1.0.0
 *
 * @package eventful
 * @subpackage eventful/src/Admin/Views/License
 * @author     ThemeAtelier<themeatelierbd@gmail.com>
 */

 namespace ThemeAtelier\Eventful\Admin\Views\Settings;

 use ThemeAtelier\Eventful\Admin\Framework\Classes\Eventful;

class License
{

    /**
     * Create Option fields for the setting section.
     *
     * @param string $prefix Option setting key prefix.
     * @return void
     */
    public static function section($prefix)
    {
        //
        // Field: advance
        //
        Eventful::createSection($prefix, array(
            'title'       => esc_html__('LICENSE', 'eventful'),
            'icon'        => 'icofont-key',

            'fields'      => array(
                array(
                    'id'   => 'license_key',
                    'type' => 'license',
                ),
            )
        ));
    }
}
