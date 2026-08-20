<?php if (1 || dclmn_auth('cp')): ?>
  <?php
  wp_enqueue_script('jquery-ui-sortable');
  wp_enqueue_script('jquery-ui-draggable');
  wp_enqueue_script('jquery-ui-droppable');
  wp_enqueue_script('jquery-ui-datepicker');
  wp_register_style('jquery-ui-2', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');

  $refresh_svg = '<svg fill="#000000" height="14px" width="14px" version="1.1" id="events-refresh" class="newsletter-events-preview" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-48.96 -48.96 587.56 587.56" xml:space="preserve" stroke="#000000" stroke-width="9.7929"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC" stroke-width="0.97929"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M460.656,132.911c-58.7-122.1-212.2-166.5-331.8-104.1c-9.4,5.2-13.5,16.6-8.3,27c5.2,9.4,16.6,13.5,27,8.3 c99.9-52,227.4-14.9,276.7,86.3c65.4,134.3-19,236.7-87.4,274.6c-93.1,51.7-211.2,17.4-267.6-70.7l69.3,14.5 c10.4,2.1,21.8-4.2,23.9-15.6c2.1-10.4-4.2-21.8-15.6-23.9l-122.8-25c-20.6-2-25,16.6-23.9,22.9l15.6,123.8 c1,10.4,9.4,17.7,19.8,17.7c12.8,0,20.8-12.5,19.8-23.9l-6-50.5c57.4,70.8,170.3,131.2,307.4,68.2 C414.856,432.511,548.256,314.811,460.656,132.911z"></path> </g> </g></svg>';
  $event_categories = get_terms(array(
    'taxonomy'   => 'tribe_events_cat',
    'hide_empty' => false,
  ));

  $pipe = '<span style="font-weight: normal; font-size: .75em; vertical-align: text-bottom; display: inline-block;">|</span>';

  $show_refresh = 0 || current_user_can('update_core');
  $first_color_width = ($show_refresh) ? 46 : 64;
  ?>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
  <?php get_template_part('partials/cp-nav'); ?>
  <h2>Events for the Newsletter</h2>
  <form id="events-search" method="post">
    <div>
      <input type="text" name="terms" placeholder="Search Terms">
      <cite>Search post titles and content for specific events.</cite>
    </div>
    <div>
      <input type="text" name="exclude_search" placeholder="Exclude Search Terms">
      <cite>A comma separated list of terms to exclude. Searches titles only. ex: "Postcarding, Canvassing, February"</cite>
    </div>
    <div>
      <select name="cats[]" multiple size="1" placeholder="Categories">
        <option></option>
        <?php foreach ($event_categories as $event_category): ?>
          <option value="<?php echo $event_category->term_id ?>"><?php echo $event_category->name ?></option>
        <?php endforeach; ?>
      </select>
      <cite>Choose specific categories to search.</cite>
    </div>
    <div class="search-dates">
      <div>
        <label>Start Date <input type="date" name="start_date"></label>
        <label>End Date <input type="date" name="end_date"></label>
      </div>
      <cite>Limit the event search by start and end dates.</cite>
    </div>
    <div>
      <input type="submit" value="search" class="button">
    </div>
  </form>
  <div class="flex">
    <div>
      <h3>Search Results</h3>
      <div>
        <ul id="available">
          <li class="default-li">1. Use the form above to search for events, then drag them to the <strong>"Selected Events"</strong> column.</li>
          <li class="default-li">2. Use the <strong>"Preview"</strong> column to control the output and copy the HTML.</li>
          <li style="padding: 1em; text-align: center;"><br><input type="button" class="button" value="Search All Events"></li>
        </ul>
      </div>
    </div>
    <div>
      <h3>Selected Events</h3>
      <div id="selected-events-wrapper">
        <ul id="selected"></ul>
      </div>
    </div>
    <div>
      <h3>
        Preview <?php echo $pipe ?>
        <?php if ($show_refresh): ?><?php echo $refresh_svg ?> <?php echo $pipe ?> <?php endif; ?>
      <label for="event-preview-images"><input type="checkbox" id="event-preview-images"> Images</label> <?php echo $pipe ?>
      <label>
        <select id="event-preview-first-color" style="width: <?php echo $first_color_width ?>px;">
          <option value="white">White First</option>
          <option value="blue">Blue First</option>
        </select>
      </label>
      <span class="newsletter-events-copy button">
        Copy Events HTML
        <?php /*
        <svg class="svg-copy-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="16">
          <path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z" fill="currentColor"˝ />
        </svg>
        <?php */ ?>
      </span>
      </h3>
      <div>
        <ul id="preview">
          <li class="default-li">1. Select at least one event to see a preview.</li>
          <li class="default-li">2. Use the tools to alter the output.</li>
          <li class="default-li">3. Copy the HTML.</li>
          <li class="default-li">Tip: You can paste the HTML into a text file and save it to your desktop for safe keeping.</li>
        </ul>
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
      justify-content: space-around;
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
      list-style-type: none;
    }

    .flex>div ul li.ui-draggable-disabled {
      background-color: #fafafa;
    }

    .flex>div ul li.ui-draggable-disabled,
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
      top: .25em;
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
      width: 130px;
      text-align: center;
    }

    .select2-selection--multiple .select2-selection__rendered:empty {
      display: none;
    }

    form#events-search {
      background-color: var(--color-light-gray);
      padding: 1em;
      border: 1px solid var(--color-gray);
    }

    form#events-search input[type=text],
    form#events-search select {
      width: 100%;
    }

    form#events-search>div {
      background-color: var(--color-white);
      padding: .5em;
      border: 1px solid var(--color-gray);
    }

    form#events-search>div:not(:last-of-type) {
      margin-bottom: .5em;
    }

    form#events-search .button {
      font-size: .85em;
      box-shadow: none;
    }

    form#events-search cite {
      font-size: .85em;
      font-style: normal;
      font-weight: bold;
    }

    .search-dates label {
      font-weight: bold;
      margin-right: 1.5em;
    }

    .flex>div ul li.default-li {
      padding: 1em;
      font-size: 1em;
    }

    #event-preview-images {
      vertical-align: middle;
    }

    #event-preview-first-color {
      font-size: .75em;
    }

    label[for="event-preview-images"] {
      font-weight: normal;
      font-size: 0.75em;
      vertical-align: text-bottom;
    }

    .list-highlight {
      border-color: red !important;
    }
  </style>
  <script>
    jQuery(document).ready(function($) {
      $('.newsletter-events-copy').on('click', function(e) {
        e.preventDefault();

        const $trigger = $(this);
        const trigger_text = $trigger.text();

        const html = $('#preview').html();

        navigator.clipboard.writeText(html)
          .then(function() {
            console.log('HTML copied to clipboard');
            $trigger.text('Copied.').addClass('copied-result');
            setTimeout(function() {
              $trigger.text(trigger_text).removeClass('copied-result')
            }, 1500);
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
          },
          over: function(event, ui) {
            // Add highlight class when dragged item enters
            $('#selected-events-wrapper').addClass("list-highlight");
          },
          out: function(event, ui) {
            // Remove highlight class when dragged item leaves
            $('#selected-events-wrapper').removeClass("list-highlight");
          }
        });
      }

      function update_preview() {
        var sortedIDs = jQuery("#selected").sortable("toArray");

        data = {
          action: 'events_preview',
          ids: sortedIDs,
          images: $('#event-preview-images').is(':checked'),
          first_color: $('#event-preview-first-color').val(),
        }

        $('#preview').addClass('loading').html('<img src="/wp-includes/images/spinner.gif">');

        $.get(ajaxurl, data, function(data) {
          $('#preview').removeClass('loading').html(data);
        });
      }

      function events_search() {
        data = {
          action: 'events_search',
          data: $('form#events-search').serialize()
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

      $('.newsletter-events-preview, #event-preview-images').on('click', function() {
        update_preview();
      });

      $('#event-preview-first-color').on('change', function() {
        update_preview();
      });

      $('form#events-search').on('submit', function(e) {
        e.preventDefault();
        events_search();
      });

      $('#available .button').on('click', function(e) {
        e.preventDefault();
        events_search();
      });

      $('form#events-search select').select2({
        placeholder: "Categories",
        allowClear: true,
      });

      //init the events search
      // events_search();
    });
  </script>
<?php endif; ?>