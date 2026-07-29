<?php get_template_part('partials/cp-nav'); ?>
<h3>Events for the Newsletter</h3>
<span class="newsletter-events-copy button">Copy Events HTML</span>
<span class="newsletter-events-result"></span>
<div class="newsletter-events">
  <?php echo newsletter_events(); ?>
</div>
<style>
  .newsletter-events {
    width: 420px;
    border: 1px #ccc solid;
    max-height: 400px;
    overflow: auto;
    margin-top: 1em;
  }

  .newsletter-events table {
    margin: 0;
  }

  .newsletter-events p {
    margin: 0;
    padding: 0;
  }

  .newsletter-events-result {
    display: none;
  }
</style>
<script>
  jQuery(document).ready(function($) {
    $('.newsletter-events-copy').on('click', function(e) {
      e.preventDefault();

      const html = $('.newsletter-events').html();

      $('.newsletter-events-result').removeClass('session-login-message').hide();
      
      navigator.clipboard.writeText(html)
        .then(function() {
          console.log('HTML copied to clipboard');
          $('.newsletter-events-result').html('Copied.').addClass('session-login-message').fadeIn();
        })
        .catch(function(err) {
          console.error('Failed to copy:', err);
        });
    });
  });
</script>