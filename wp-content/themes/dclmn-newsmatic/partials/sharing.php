<?php
wp_enqueue_script('jquery-ui-sortable');
wp_enqueue_script('jquery-ui-draggable');
wp_enqueue_script('jquery-ui-droppable');
wp_enqueue_script('jquery-ui-datepicker');
wp_register_style('jquery-ui-2', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');

$refresh_svg = '<svg fill="#000000" height="14px" width="14px" version="1.1" id="events-refresh" class="newsletter-events-preview" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-48.96 -48.96 587.56 587.56" xml:space="preserve" stroke="#000000" stroke-width="9.7929"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.97929"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M460.656,132.911c-58.7-122.1-212.2-166.5-331.8-104.1c-9.4,5.2-13.5,16.6-8.3,27c5.2,9.4,16.6,13.5,27,8.3 c99.9-52,227.4-14.9,276.7,86.3c65.4,134.3-19,236.7-87.4,274.6c-93.1,51.7-211.2,17.4-267.6-70.7l69.3,14.5 c10.4,2.1,21.8-4.2,23.9-15.6c2.1-10.4-4.2-21.8-15.6-23.9l-122.8-25c-20.6-2-25,16.6-23.9,22.9l15.6,123.8 c1,10.4,9.4,17.7,19.8,17.7c12.8,0,20.8-12.5,19.8-23.9l-6-50.5c57.4,70.8,170.3,131.2,307.4,68.2 C414.856,432.511,548.256,314.811,460.656,132.911z"></path> </g> </g></svg>';
?>
<?php get_template_part('partials/cp-nav'); ?>
<h2>Events for the Newsletter</h2>
<span class="newsletter-events-result"></span>
<div class="flex">
  <div>
    <h3>Search Results</h3>
    <div>
      <ul id="available"></ul>
    </div>
  </div>
  <div>
    <h3>Selected Events</h3>
    <div>
      <ul id="selected"></ul>
    </div>
  </div>
  <div>
    <h3>
      Preview
      <?php echo $refresh_svg ?>
      <span class="newsletter-events-copy button">Copy Events HTML</span>
    </h3>
    <div>
      <ul id="preview"></ul>
    </div>
  </div>
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

  .flex {
    margin-top: 1em;
  }

  .flex>div {
    width: 32%;
  }

  .flex>div>div {
    border: 1px solid #000;
  }


  .flex>div h3 {
    margin: 0;
  }

  .flex>div ul {
    min-height: 400px;
    max-height: 400px;
    overflow: auto;
    margin: 0;
    padding: 0;
  }

  .flex>div ul li {
    font-size: .85em;
    padding: .25em .5em;
    position: relative;
    cursor: move;
  }

  .flex>div ul li.ui-draggable-disabled {
    background-color: #fafafa;
  }

  .flex>div ul li.ui-draggable-disabled small,
  .flex>div ul li.ui-draggable-disabled a,
  .flex>div ul li.ui-draggable-disabled a:hover {
    color: #ccc;
    background-color: transparent;
  }


  .flex>div ul li:nth-child(even) {
    background-color: #efefef;
  }

  .flex>div ul.loading {
    padding: 1em;
  }

  .sort_terms_posts_post_controls {
    position: absolute;
    bottom: 0;
    right: .5em;
    display: none;
  }

  .sort_terms_posts_post_controls span {
    margin-right: 12px;
    cursor: pointer;
  }

  #selected .sort_terms_posts_post_controls {
    display: block;
  }

  #preview table {
    margin: 0;
  }

  #preview p {
    margin: 1em 0;
  }

  .newsletter-events-preview {
    cursor: pointer;
  }

  .newsletter-events-copy.button {
    font-size: .65em;
    vertical-align: text-bottom;
    line-height: 1;
    padding: .25em .5em;
    float: right;
  }
</style>
<script>
  jQuery(document).ready(function($) {
    $('.newsletter-events-copy').on('click', function(e) {
      e.preventDefault();

      const html = $('#preview').html();

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

    function init_drag() {
      $("#available li").draggable({
        helper: "clone",
        connectToSortable: "#selected",
        revert: "invalid"
      });

      $("#selected").sortable({
        receive: function(event, ui) {
          var id = ui.item.data("post_id");

          // Disable dragging of the original
          $('#available li[data-post_id="' + id + '"]').addClass("used").draggable("disable");
        },

        stop: function(event, ui) {
          ui.item.css({
            width: "",
            height: ""
          });

          item = $(ui.item);
          item.attr('id', item.attr('data-post_id'));

          update_preview();
        }
      });
    }

    function update_preview() {
      var sortedIDs = jQuery("#selected").sortable("toArray");

      data = {
        action: 'events_preview',
        ids: sortedIDs,
      }

      $('#preview').addClass('loading').html('<img src="/wp-includes/images/spinner.gif">');

      $.get(ajaxurl, data, function(data) {
        $('#preview').removeClass('loading').html(data);
      });
    }

    function events_search() {
      data = {
        action: 'events_search',
      }

      $('#available').addClass('loading').html('<img src="/wp-includes/images/spinner.gif">');

      $.get(ajaxurl, data, function(data) {
        $('#available').removeClass('loading').html(data);

        //init drag and drop of events
        init_drag();
      });
    }

    //listen for removal
    $("#selected").on("click", ".delete", function() {
      var li = $(this).closest("li");
      var id = li.data("post_id");

      // Re-enable the original
      $('#available li[data-post_id="' + id + '"]').removeClass("used").draggable("enable");

      li.remove();

      update_preview();
    });

    $('.newsletter-events-preview').on('click', function() {
      update_preview();
    });

    //init the events search
    events_search();
  });
</script>