<?php

$out = '';
$i = 0;
$bgcolors = ['#E0F1F8', '#ffffff'];
$fgcolors = ['#1930a6', '#1930a6'];

$images = filter_var($_REQUEST['images'], FILTER_VALIDATE_BOOLEAN);
$first_color = (!empty($_REQUEST['first_color'])) ? $_REQUEST['first_color'] : 'white';

if ('blue' == $first_color) $i++;

$old_html = <<<HTML
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:separate" role="presentation">
  <tbody>
    <tr>
      <td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;border:0;border-radius:0" valign="top">
        <table width="100%" style="border:0;background-color:#bgcolor#;border-radius:0;border-collapse:separate">
          <tbody>
            <tr>
              <td style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px">
                <p style="text-align: left;">
                  <strong>
                    <span style="color:#fgcolor#;">
                      <span style="font-family: 'DM Sans', sans-serif">
                         <a href="#event_url#" target="_blank"style="color:#fgcolor#; text-decoration: none;">#event_post_title#</a>
                      </span>
                    </span>
                  </strong>
                  <br>
                  <span style="color:#fgcolor#;">
                    <span style="font-size: 14px">
                      <span style="font-family: 'DM Sans', sans-serif">#formatted_date#</span>
                    </span>
                  </span>
                </p>
                <p style="text-align: left;">
                  <span style="color:#000;">
                    <span style="font-size: 15px">
                      <span style="font-family: &quot;DM Sans&quot;, sans-serif">#event_content#</span>
                    </span>
                  </span>
                </p>
                <p style="text-align: left;">
                  <a href="#event_url#" target="_blank"style="color:#fgcolor#; text-decoration: underline;">
                    <strong>
                      <span style="font-size: 15px">
                        <span style="font-family: 'DM Sans', sans-serif" style="color:#fgcolor#;">CLICK HERE</span>
                      </span>
                    </strong>
                  </a>
                </p>
              </td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </tbody>
</table>
HTML;


$ids = (array) $_REQUEST['ids'];

if (!count($ids)) {
  //yeah this should be shred with the form view file but omg does it really?
  $out .= '<li class="default-li">1. Select at least one event to see a preview.</li>
  <li class="default-li">2. Use the tools to alter the output.</li>
  <li class="default-li">3. Copy the HTML.</li>
  <li class="default-li">Tip: You can paste the HTML into a text file and save it to your desktop for safe keeping.</li>';
} else {
  foreach ($ids as $id) {
    $i++;

    $event = tribe_get_event($id);

    $display_date = $event->dates->start_display;

    $event_week_day  = $display_date->format_i18n('l');
    $event_week_day_short  = $display_date->format_i18n('D');
    $event_week_day_shorter = substr($event_week_day_short, 0, 2);

    $event_day_num   = $display_date->format_i18n('j');
    $event_month   = $display_date->format_i18n('F');
    $event_month_short   = $display_date->format_i18n('n');

    $event_time = tribe_format_date($event->start_date, false, 'g:i A');
    $event_time .= ' – ';
    $event_time .= tribe_format_date($event->end_date, false, 'g:i A');
    $display_time_format = $event->dates->start->format('i') === '00'
      ? 'g a'
      : 'g:i a';

    $event_time_short = $event->dates->start->format_i18n($display_time_format);

    $formatted_date = format_event_schedule($event->start_date, $event->end_date, $event->all_day);

    $bgcolor = $bgcolors[$i % count($bgcolors)];
    $fgcolor = $fgcolors[$i % count($fgcolors)];

    $event_url = get_permalink($event);

    $event_content = strip_tags($event->post_content, ['b', 'i', 'u', 'strong', 'em', 'span', 'a']);
    $event_content = trim($event_content);

    $img_src = dclmn_thumb(get_the_post_thumbnail_url($event->ID, 'full'), ['width' => 280]);

    $content = trim($event->post_content);
    $content = preg_replace('/<\/?div[^>]*>/i', '', $content);
    $content = preg_replace('/\R/', '<br>', $content);
   
    //replace h tags
    $content = preg_replace('#<h[1-6][^>]*>(.*?)</h[1-6]>#is', '<strong>$1</strong><br>', $content);

    //replace wonky br tags
    $content = preg_replace('/^(?:\s*<br\s*\/?>\s*)+|(?:\s*<br\s*\/?>\s*)+$/i', '', $content);

    // $content = htmlentities($content);

    $html = '';
    $html .= '<table border="0" cellpadding="24" cellspacing="0" width="100%" style="border-collapse:separate" role="presentation">';
    $html .= '<tbody>';
    $html .= '<tr>';
    $html .= '<td valign="top" bgcolor="' . $bgcolor . '" fgcolor="' . $fgcolor . '" align="left" style="text-align: left; background-color: ' . $bgcolor . '; color: ' . $fgcolor . '; font-size: 16px; font-family: \'DM Sans\', sans-serif; padding-left:24px; padding-right:24px; padding-top:12px; padding-bottom:12px">';
    if ($images && !empty($img_src)) $html .= '<p style="text-align: left;"><img src="' . $img_src . '"></p>';
    $html .= '<p style="text-align: left;">';
    $html .= '<a href="' . $event_url . '" target="_blank" style="text-decoration: none; text-decoration-line: none; color:' . $fgcolor . '; font-size: 16px;"><strong>' . $event->post_title . '</strong></a>';
    $html .= '<br>';
    $html .= '<span style="font-size: 14px;">' . $formatted_date . '</span>';
    $html .= '</p>';
    // $html .= '<br />';
    $html .= '<p style="text-align: left; font-size: 15px; color: #000; margin-top: 10px; margin-bottom: 15px;">' . $content . '</p>';
    // $html .= '<br>';
    $html .= '<p style="text-align: left;"><a href="' . $event_url . '" target="_blank"style="color:' . $fgcolor . '; text-decoration: underline;"><strong>CLICK HERE</strong></a></p>';
    $html .= '</td>';
    $html .= '</tr>';
    $html .= '</tbody>';
    $html .= '</table>';

    $out .= $html . PHP_EOL;
  }
}


die($out);
