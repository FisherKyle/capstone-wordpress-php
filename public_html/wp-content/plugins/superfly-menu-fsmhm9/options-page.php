<div id="sf-options-wrap" class="widefat" style="opacity:0;">
    <script>
        var colorscheme = [
            '#c0392b',
            'a3503c',
            '925873',
            '927758',
            '589272',
            '588c92',
            '2bb1c0',
            '2b8ac0',
            'e96701',
            'c02b74'
        ]
    </script>
    <?php
    $options = sf_get_options();
    $js_options = json_encode($options);

    function custom_do_settings_sections($page)
    {
        global $wp_settings_sections, $wp_settings_fields;

        if (!isset($wp_settings_sections) || !isset($wp_settings_sections[$page])) {
            return;
        }

        foreach ((array)$wp_settings_sections[$page] as $section) {
            echo "<div class='postbox' id='{$section['id']}'><h3 class='hndle'><span>{$section['title']}</span></h3>\n";
            call_user_func($section['callback'], $section);
            if (!isset($wp_settings_fields) ||
                !isset($wp_settings_fields[$page]) ||
                !isset($wp_settings_fields[$page][$section['id']])
            ) {
                echo '</div>';
                continue;
            }

            echo '<div class="settings-form-wrapper '.$section['id'].'">';
            custom_do_settings_fields($page, $section['id']);
            echo "<div class='settings-form-row sf_label_sbmt'><input name='Submit' type='submit' id='sbmt_{$section['id']}' class='button-primary' value='Save Changes' /></div>";
            echo '<div class="sbmt-bg"></div></div></div>';
        }
    }

    function custom_do_settings_fields($page, $section)
    {
        global $wp_settings_fields;

        if (!isset($wp_settings_fields) ||
            !isset($wp_settings_fields[$page]) ||
            !isset($wp_settings_fields[$page][$section])
        ) {
            return;
        }
        $count = count($wp_settings_fields[$page][$section]);
        $i = 0;
        $k = 0;
        $column = 0;
        foreach ((array)$wp_settings_fields[$page][$section] as $field) {
            $subsection = !empty($field['args']['subsection']) ? $field['args']['subsection'] : '';
            if (!empty($field['args']['column'])) {
                $column = $field['args']['column'];
            }
            if (!empty($field['args']['chapter'])) {
                if ($column > $i && $column > 1) {
                    echo '</div>';
                }
                if ($column > $i) {
                    echo '<div class="column">';
                    $i++;
                }
                $chapter = $field['args']['chapter'];
                echo '<h1 id="'.str_replace(' ', '_', strtolower($chapter)).'">'.$chapter.'</h1>';
            }
            echo '<div class="settings-form-row'.(!empty($field['args']['hidden']) ? ' hidden-row' : '').' '.$field['id'].'" data-subsection="'.$subsection.'">';
            if (!empty($field['args']['label_for'])) {
                echo '<label for="'.$field['args']['label_for'].'">'.$field['title'].'</label><br />';
            } else {
                echo '<p class="field-title '.(!empty($field['args']['header_hidden']) ? 'header_hidden' : '').'">'.$field['title'].'</p>';
            }
            call_user_func($field['callback'], $field['args']);
            echo '</div>';
            if (++$k === $count && $column > 0) {
                echo '</div>';
            }
        }
    }

    screen_icon();
    ?>
    <h2 class="form-title"><img src="<?php echo plugins_url('/img/', __FILE__); ?>logo.png" alt="" target="_blank">
        Superfly <?php echo SF_VERSION_NUM ?> Settings </h2>
    <form method="post" action="options.php" enctype="multipart/form-data">
        <ul id="tabs-copy" class="section-tabs"></ul>
        <ul id="tabs" class="section-tabs"></ul>
        <?php settings_fields('sf_options'); ?>
        <?php custom_do_settings_sections('sf'); ?>
    </form>
    <div class="la">
        <span>
            Superfly v.
            <?php echo SF_VERSION_NUM; ?>
        </span>
        |
        <a target="_blank" href="http://superfly.looks-awesome.com/docs/Getting_Started">
            Documentation
        </a>
        |
        <a target="_blank" href="http://codecanyon.net/user/looks_awesome/portfolio">Awesome plugins</a>
    </div>
    <div id="fade-overlay">
        <div class="svg-wrapper">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100px" height="120px" viewBox="0 0 100 120" style="enable-background:new 0 0 50 50;" xml:space="preserve">
                <rect x="0" y="0" width="10" height="35" fill="#333" transform="translate(0 19.2561)">
                    <animateTransform attributeType="xml" attributeName="transform" type="translate" values="0 0; 0 25; 0 0" begin="0" dur="0.6s" repeatCount="indefinite"></animateTransform>
                </rect>
                <rect x="20" y="0" width="10" height="35" fill="#333" transform="translate(0 5.92276)">
                    <animateTransform attributeType="xml" attributeName="transform" type="translate" values="0 0; 0 25; 0 0" begin="0.2s" dur="0.6s" repeatCount="indefinite"></animateTransform>
                </rect>
                <rect x="40" y="0" width="10" height="35" fill="#333" transform="translate(0 7.41058)">
                    <animateTransform attributeType="xml" attributeName="transform" type="translate" values="0 0; 0 25; 0 0" begin="0.4s" dur="0.6s" repeatCount="indefinite"></animateTransform>
                </rect>
            </svg>
        </div>
    </div>
</div>
<script type="text/javascript">

    (function () {
        var $ = window.jQuery;
        var current;
        var current_sub;
        var $tabs;
        var $active;
        var $active_sub;
        var $content;
        var $wrap;
        var $saved;
        var $sbmt;
        var offset;
        var $win;
        var isLS = 'sessionStorage' in window && window['sessionStorage'] !== null;

        if ($ != null) {

            $wrap = $('#sf-options-wrap')
            $tabs = $('#tabs');
            $win = $(window);

            $(function () {

                var Superfly = Backbone.Model.extend();
                window['superfly'] = new Superfly(<?php echo $js_options; ?>);
                window['superfly_view'] = new SuperflyView({
                    model: window['superfly']
                });
                window['superfly_view'].setElement('#sf-options-wrap');
                window['superfly_view'].render();

                var map = {
                    'sf_source': 'display',
                    'sf_appearance': 'see',
                    'sf_menu_items': 'bars2',
                    'sf_label': 'diamonds',
                    'sf_advanced': 'more-2'
                }

                $('#sf-options-wrap .postbox').each(function (i, el) {
                    var $t = $(this);
                    var id = $t.attr('id');
                    var txt = $t.find('h3').html();
                    var icon = '<i class="flaticon-' + map[id] + '"></i>';
                    var active = isLS && sessionStorage.getItem('sf-section') && id === sessionStorage.getItem('sf-section').replace('for_', '') ? 'active' : '';
                    $tabs.append('<li id="for_' + id + '" class="' + active + '">' + icon + ' <span data-hover="' + txt + '">' + txt + '</span></li>');
                    if (isLS && sessionStorage.getItem('sf-section') === 'for_sf_feedback') insertFeedbackForm($('#sf_feedback'))
                });

                // Process Appearance tab sub tabs
                $('#sf_appearance').prepend('<ul id ="sf_appearance_tabs" class="section-subtabs"></ul>');
                var $subtabs = $('#sf_appearance #sf_appearance_tabs');
                var $appearance = $('#sf_appearance .settings-form-wrapper');
                $('h1', $appearance).each(function (i, el) {
                    var $tab = $(this);
                    var id = $tab.attr('id');
                    var subsections = $('[data-subsection="'+id+'"]');

                    var text = $tab.text().replace_all(' ', '_');
                    var active = isLS && sessionStorage.getItem('sf-appearance-section') && id === sessionStorage.getItem('sf-appearance-section') ? 'active' : '';
                    $subtabs.append('<li id="'+id+'" class="' + active + '">' + text + '</li>');
                    $appearance.prepend('<div id="wrapper_' + id + '" class="wrapper"></div>');

                    $('#wrapper_'+id, $appearance).append(subsections);
                });
                $subtabs.find('li').on('click', function () {
                    var $t = $(this);
                    var id = $t.attr('id');
                    var $wrapper = $('#wrapper_' + id);

                    $appearance.find('.wrapper').removeClass('active');
                    $wrapper.addClass('active');
                    $('li', $subtabs).removeClass('active');
                    $t.addClass('active');

                    if (isLS) {
                        sessionStorage.setItem('sf-appearance-section', id);
                    }

                });


                $tabs.append('<li id="save-tab"><span>Save Changes</span> <i class="flaticon-paperplane"></i></li>')


                if (isLS) {

                    current = sessionStorage.getItem('sf-section');
                    current_sub = sessionStorage.getItem('sf-appearance-section');

                    $active = current ? $('#tabs li#' + current) : $('#tabs li:first');
                    $content = $wrap.find('#' + $active.attr('id').replace('for_', ''))
                    $content.add($active).addClass('active');

                    $active_sub = current_sub ? $('#sf_appearance_tabs li#' + current) : $('#sf_appearance_tabs li:first');
                    
                    $('#wrapper_' + current_sub, $appearance).addClass('active');

                    current = sessionStorage.getItem('sf-section-scroll');

                    if (current) {
                        $('html, body').scrollTop(current)
                    }

                }

                $tabs.find('li').not('#save-tab').on('click', function () {
                    var $t = $(this);
                    var id = $t.attr('id').replace('for_', '');
                    var $content = $('#sf-options-wrap #' + id);

                    if (id === 'sf_feedback') insertFeedbackForm($content);
                    if(id == 'sf_appearance' && !sessionStorage.getItem('sf-appearance-section')){
                        $('#sf_appearance_tabs #general').trigger('click');
                    }

                    if ($saved) $saved.hide();

                    $wrap.find('.postbox, #tabs li').removeClass('active');
                    $t.add($content).addClass('active');

                    if (isLS) {
                        sessionStorage.setItem('sf-section', $t.attr('id'));
                    }

                });

                if (isLS) {
                    $(window).unload(function (e) {
                        sessionStorage.setItem('sf-section-scroll', $('body').scrollTop() || $('html').scrollTop());
                    });

                    $wrap.find(':submit').click(function () {
                        sessionStorage.setItem('sf-section-submit', $(this).attr('id'));
                    });

                    if (sessionStorage.getItem('sf-section-submit')) {
                        //$saved = $('<div id="saved"> Saved!</div>');
                        $sbmt = $('#' + sessionStorage.getItem('sf-section-submit'));

                        if ($sbmt.length) {
                            //$('body').append($saved);
                            //offset = $sbmt.offset();
                            // setTimeout(function(){$saved.addClass('hide')}, 1000);

                            // $saved.css({top: offset.top + 5, left: $sbmt.outerWidth() + offset.left + 10})

                        }
                        sessionStorage.setItem('sf-section-submit', '');

                    }

                }

                $wrap.css('opacity', 1);


                function insertFeedbackForm($content) {
                    if (!insertFeedbackForm.inserted) {

                        $content.append('<iframe src="https://docs.google.com/forms/d/1gjbcd7ieJeq7XxNgbHJ3w8fX78uXOm73dJnSTrauh9s/viewform?embedded=true" width="760" height="500" frameborder="0" marginheight="0" marginwidth="0">Loading...</iframe>');

                        insertFeedbackForm.inserted = true;
                    }
                }

            });

        } else {
            document.getElementById('sf-options-wrap').style.opacity = 1;
        }
    }())
</script>
