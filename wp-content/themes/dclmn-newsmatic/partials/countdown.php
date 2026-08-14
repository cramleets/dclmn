<?php
$date_live = '2026-11-03 07:00:00';

//prevent a possible infinite loop.
if (strtotime($date_live) <= current_time('timestamp')) {
  return;
}
//JUST USE BELOW FOR COMPARE ^^^^
$date = new DateTime();
$event_date = new DateTime($date_live, new DateTimeZone('America/New_York'));
$interval = $date->diff($event_date);
$header = (!empty($args['header'])) ? $args['header'] : '';
$footer = (!empty($args['footer'])) ? $args['footer'] : '';
if (1 || 0 == $interval->days):
?>
  <div id="countdown">
    <?php if (!empty($header)): ?><h3 style=""><?php echo $header ?></h3><?php endif; ?>
    <div id="countdown-timer"></div>
    <?php if (!empty($footer)): ?><h3 style=""><?php echo $footer ?></h3><?php endif; ?>
  </div>
  <script>
    function runCountdown(countDownDate, refreshURL) {
      var resourceTimer = setInterval(function() {
        var now = new Date().getTime();
        var timeleft = countDownDate - now;

        //countdown is done
        if (timeleft <= 0) {
          //clear the timer
          clearInterval(resourceTimer);

          //redirect the user
          window.location.href = refreshURL;

          //a pretty little message in case refresh fails
          jQuery('#countdown').fadeOut(function() {
            setTimeout(function() {
              jQuery('#countdown').html('<a href="' + refresh_url + '">Click Here If Your Browser Does Not Refresh</a>').fadeIn();
            }, 500);
          });
        }

        //ticking away the moments that make up a dull day
        else {
          //init countdown parts
          var countdown = {
            day: Math.floor(timeleft / (1000 * 60 * 60 * 24)),
            hour: Math.floor((timeleft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
            minute: Math.floor((timeleft % (1000 * 60 * 60)) / (1000 * 60)),
            second: Math.floor((timeleft % (1000 * 60)) / 1000),
          };

          //init string
          var time_string = '';

          //loop through countdown parts
          for (var k in countdown) {
            //get the value
            v = countdown[k];

            //build the string
            time_string += '<span class="countdown-piece countdown-' + k + '">';
            time_string += '<span class="countdown-value">' + v + '</span>';
            time_string += '<span class="countdown-label">' + ((v == 1) ? k : k + 's') + '</span>';
            time_string += '</span>';
          }
          jQuery('#countdown').fadeIn(250);
          jQuery('#countdown-timer').html(time_string);
        }
      }, 1000);
    }
  </script>
  <script>
    if (typeof runCountdown == 'function') {
      var countDownDate = new Date('<?php echo $event_date->format('D M d Y H:i:s O') ?>');
      console.log(countDownDate);
      var refreshURL = '<?php echo $post->href ?>';
      runCountdown(countDownDate, refreshURL);
    }
  </script>
<?php endif; //interval is zero days, i.e. today    
?>