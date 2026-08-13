<?php

$out = '';
$i = 0;
$bgcolors = ['#E0F1F8', '#ffffff'];
$fgcolors = ['#1930a6', '#1930a6'];
$images = filter_var($_REQUEST['images'], FILTER_VALIDATE_BOOLEAN);

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


foreach ($_REQUEST['ids'] as $id) {
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

  $html = '';
  $html .= '<table border="0" cellpadding="24" cellspacing="0" width="100%" style="border-collapse:separate" role="presentation">';
  $html .= '<tbody>';
  $html .= '<tr>';
  $html .= '<td valign="top" bgcolor="' . $bgcolor . '" fgcolor="' . $fgcolor . '" style="background-color: ' . $bgcolor . '; color: ' . $fgcolor . '; font-size: 16px; font-family: \'DM Sans\', sans-serif; padding-left:24px; padding-right:24px; padding-top:12px; padding-bottom:12px">';
  if ($images && !empty($img_src)) $html .= '<p><img src="' . $img_src . '"></p>';
  $html .= '<p>';
  $html .= '<a href="' . $event_url . '" target="_blank"style="color:' . $fgcolor . '; text-decoration: none; font-size: 16px;"><strong>' . $event->post_title . '</strong></a>';
  $html .= '<br>';
  $html .= '<span style="font-size: 14px;">' . $formatted_date . '</span>';
  $html .= '</p>';
  $html .= '<div style="font-size: 15px; color: #000;">' . $event->post_content . '</div>';
  $html .= '<p><a href="' . $event_url . '" target="_blank"style="color:' . $fgcolor . '; text-decoration: underline;"><strong>CLICK HERE</strong></a></p>';
  $html .= '</td>';
  $html .= '</tr>';
  $html .= '</tbody>';
  $html .= '</table>';

  $out .= $html . PHP_EOL;
}

die($out);
