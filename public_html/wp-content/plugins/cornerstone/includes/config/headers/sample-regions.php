<?php

return array(

  'top' => array(

    '_modules' => array(

      // Bar
      array(
        '_type'        => 'bar',
        'flex'         => 'row center-justify',
        'width'        => '12em',
        'height'       => '6em',
        'bg_color'     => '#333333',
        'side_padding' => '35px',
        'inner_width'  => '100%',
        'inner_max'    => '1200px',
        'font_size'    => '21px',
        'context'      => 'desktop',
        'location'     => 'top',
        'position'     => 'fixed',
        'shrink'       => '.65',
        'breakpoint'   => '1200',
        'hide'         => array(),
        '_modules'      => array(

          // Container
          array(
            '_type'   => 'container',
            'flex'    => 'row center-justify',
            'hide'    => array(),
            '_modules' => array(

              // Logo
              array(
                '_type'          => 'logo',
                'linked'         => true,
                'href'           => 'http://google.com/',
                'text'           => 'Logo Text',
                'img_src'        => 'https://placeholdit.imgix.net/~text?txtsize=64&bg=e36a5c&txtclr=333333&&txt=380x120&w=380&h=120',
                // 'img_src'        => '',
                'font_size'      => '21px',
                'font_weight'    => '700',
                'letter_spacing' => '0.35em',
                'text_transform' => 'uppercase',
                'color'          => '#fff',
                'color_hover'    => '#e46958',
                'hide'           => array(),
              ),
            ),
          ),

          // Container
          array(
            '_type'   => 'container',
            'flex'    => 'row center-justify',
            'hide'    => array(),
            '_modules' => array(

              // Nav: Inline
              array(
                '_type'                    => 'nav-inline',
                'menu'                     => 'Test: Main',
                'menu_class'               => '',
                'menu_flex'                => 'row center-justify',
                'menu_link_style'          => 'underline',
                'menu_link_divider'        => '2px solid rgba(255, 255, 255, 0.15)',
                'menu_link_outer_padding'  => '0 15px',
                'menu_link_inner_padding'  => '15px 0',
                'menu_link_font_size'      => '14px',
                'menu_link_font_weight'    => '700',
                'menu_link_letter_spacing' => '0.15em',
                'menu_link_text_transform' => 'uppercase',
                'menu_link_color'          => '#fff',
                'menu_link_color_hover'    => '#e46958',
                'hide'                     => array(),
              ),

              // // Nav: Modal
              // array(
              //   '_type'                   => 'nav-modal',
              //   'modal_close_x'           => 'right',
              //   'modal_close_y'           => 'top',
              //   'toggle_width'            => '50px',
              //   'toggle_height'           => '50px',
              //   'toggle_color'            => '#ffffff',
              //   'toggle_color_hover'      => '#e46958',
              //   'toggle_color_active'     => '#e46958',
              //   'toggle_bg_color'         => 'transparent',
              //   'toggle_bg_color_hover'   => 'transparent',
              //   'toggle_bg_color_active'  => 'transparent',
              //   'toggle_burger_animation' => false,
              //   'toggle_burger_width'     => '44%',
              //   'toggle_burger_height'    => '2px',
              //   'toggle_bun_spacing'      => '300%',
              //   'hide'                    => array(),
              // ),

              // Search: Modal
              array(
                '_type'                   => 'search-modal',
                'modal_close_x'           => 'right',
                'modal_close_y'           => 'top',
                'search_align'            => 'left',
                'toggle_width'            => '50px',
                'toggle_height'           => '50px',
                'toggle_color'            => '#ffffff',
                'toggle_color_hover'      => '#e46958',
                'toggle_color_active'     => '#e46958',
                'toggle_bg_color'         => 'transparent',
                'toggle_bg_color_hover'   => 'transparent',
                'toggle_bg_color_active'  => 'transparent',
                'toggle_burger_animation' => false,
                'toggle_burger_width'     => '44%',
                'toggle_burger_height'    => '2px',
                'toggle_bun_spacing'      => '300%',
                'hide'                    => array(),
              ),
            ),
          ),
        ),
      ),
    )
  )
);
