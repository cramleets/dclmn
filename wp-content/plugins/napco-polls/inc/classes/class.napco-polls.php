<?php

class DCLMN_Polls {

  function __construct() {
    add_action('init', array($this, 'init'));

    //rewrites
    add_filter('rewrite_rules_array', array($this, 'poll_rewrite_rules'));
    add_filter('the_content', array($this, 'the_content'));

    add_filter('manage_poll_entry_posts_columns', array($this, 'manage_poll_entry_columns'));
    add_action('manage_poll_entry_posts_custom_column', array($this, 'manage_poll_entry_posts_custom_column'), 10, 2);

    add_action('add_meta_boxes', array($this, 'add_meta_boxes'), 10, 2);
    add_action('wp', array($this, 'request_action_listeners')); //hook into napco wp site to init request listeners

    add_action('template_redirect', array($this, 'template_redirect_module_view'));
    add_filter('query_vars', array($this, 'query_vars'));
  }



  public function query_vars($vars) {
    $vars[] = 'module';
    $vars[] = 'action';
    $vars[] = 'module_view';
    $vars[] = 'module_primary_directory';
    return $vars;
  }



  /**
   * "Intercepts" the display and looks to see if our custom module view flag
   * has been set in the non-rewritten URL query variables. If it is found then 
   * the module is displayed and the script exits.
   * @param object $query WP Query object.
   */
  public function template_redirect_module_view() {
    global $wp_query;

    if (!empty($wp_query->query_vars['module_view'])) {
      $module = get_query_var('module');
      $action = get_query_var('action');
      $module_primary_directory = get_query_var('module_primary_directory');

      $args = array();
      if ($module_primary_directory) $args['primary_directory'] = $module_primary_directory;

      header('HTTP/1.1 200 OK');
      $wp_query->is_404 = false;
      $wp_query->is_home = false;

      ob_start();
      require dirname(__FILE__) . '/../../modules/' . $module . '/controller.php';
      include dirname(__FILE__) . '/../../modules/' . $module . '/views/' . $action . '.phtml';
      echo ob_get_clean();

      die();
    }
  }

  function request_action_listeners() {
    if (!empty($_GET['action'])) {
      switch ($_GET['action']) {
        case 'reset':
          if ($_GET['poll_id']) {
            $this->reset_status($_GET['poll_id']);
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
          }
      }
    }
  }

  function init() {
    $this->plugin_path = str_replace('/inc/classes', '', plugin_dir_path(__FILE__));
    $this->plugin_url = str_replace('/inc/classes', '', plugin_dir_url(__FILE__));
    $this->register_post_types();
    $this->register_ajax_listeners();
  }

  function register_ajax_listeners() {
    add_action('wp_ajax_poll_choice_modal', array($this, 'ajax_poll_choice_modal'));
    add_action('wp_ajax_add_edit_poll_choice', array($this, 'ajax_add_edit_poll_choice'));
    add_action('wp_ajax_get_poll_choices', array($this, 'ajax_get_poll_choices'));
    add_action('wp_ajax_poll_choice_delete', array($this, 'ajax_poll_choice_delete'));
    add_action('wp_ajax_sort_poll_choices', array($this, 'ajax_sort_poll_choices'));
    add_action('wp_ajax_results_refresh', array($this, 'ajax_results_refresh'));
    add_action('wp_ajax_nopriv_results_refresh', array($this, 'ajax_results_refresh'));
    add_action('wp_ajax_votes_refresh', array($this, 'ajax_votes_refresh'));
    add_action('wp_ajax_dclmn_poll_vote', array($this, 'ajax_dclmn_poll_vote'));
    add_action('wp_ajax_nopriv_dclmn_poll_vote', array($this, 'ajax_dclmn_poll_vote'));
    add_action('wp_ajax_votes_delete', array($this, 'ajax_votes_delete'));
  }

  function ajax_poll_choice_modal() {
    $primary_directory = dirname(__FILE__) . '/../../';
    $view_file = 'poll_choice_modal';
    include $primary_directory . '/modules/admin/controller.php';
    include $primary_directory . '/modules/admin/views/' . $view_file . '.phtml';
    exit;
  }

  function ajax_get_poll_choices() {
    $primary_directory = dirname(__FILE__) . '/../../';
    $view_file = 'poll_choices_list';
    include $primary_directory . '/modules/admin/controller.php';
    include $primary_directory . '/modules/admin/views/' . $view_file . '.phtml';
    exit;
  }

  function ajax_add_edit_poll_choice() {
    $user = wp_get_current_user();
    parse_str($_REQUEST['data'], $data);

    $date = date('Y-m-d H:i:s');

    $args = [
      'post_type' => 'poll_choice',
      'post_content' => $data['choice_description'],
      'post_parent' => $data['parent_id'],
      'post_status' => 'publish',
      'orderby' => 'date',
      'order' => 'DESC',
    ];

    if ($data['choice_id']) {
      $args['ID'] = $data['choice_id'];
      $post_id = wp_update_post($args);
    } else {
      $args['post_title'] = "Poll #{$data['parent_id']} : {$data['choice_label']} : $date";
      $post_id = wp_insert_post($args);
    }

    update_post_meta($post_id, 'choice_label', $data['choice_label']);
    update_post_meta($post_id, 'choice_url', $data['choice_url']);
    update_post_meta($post_id, 'choice_image', $data['choice_image']);
    update_post_meta($post_id, 'user_id', get_current_user_id());

    die('' . $post_id);
  }

  function ajax_poll_choice_delete() {
    wp_delete_post($_REQUEST['post_id']);
    die('Done.');
  }

  function register_post_types() {
    $post_type_paths = array(
      trailingslashit(plugin_dir_path(__FILE__)) . '../post-types',
    );

    $post_type_paths = apply_filters('dclmn_poll_post_type_paths', $post_type_paths);

    foreach ($post_type_paths as $post_type_path) {
      $Directory = new RecursiveDirectoryIterator($post_type_path);
      $Iterator = new RecursiveIteratorIterator($Directory);
      $dir_files = new RegexIterator($Iterator, '/^.+\.php/i', RecursiveRegexIterator::GET_MATCH);

      foreach ($dir_files as $file) {
        $file = $file[0];
        $post_type = basename($file, '.php');
        include_once $file;
        $labels = apply_filters("dclmn_post_type_labels_$post_type", $labels);
        $args['labels'] = $labels;
        $args = apply_filters("dclmn_post_type_args_$post_type", $args);
        register_post_type($post_type, $args);
      }
    }
  }

  function poll_rewrite_rules($rules) {
    $module_primary_directory = dirname(__FILE__) . '/../../';

    $poll_rules = [
      'poll' => [
        //'poll-choices/?$' => 'index.php?module_view=1&module=poll&action=peoples-choice&module_primary_directory=' . $module_primary_directory,
        'poll-vote/?$' => 'index.php?module_view=1&module=poll&action=record-vote&module_primary_directory=' . $module_primary_directory,
        'poll/([^/]+)/results/?$' => 'index.php?module_view=1&module=poll&poll=$matches[1]&action=results&module_primary_directory=' . $module_primary_directory,
        'poll/([^/]+)/qrs/?$' => 'index.php?module_view=1&module=poll&poll=$matches[1]&action=qr-codes&module_primary_directory=' . $module_primary_directory,
        //'poll-qr-codes/?$' => 'index.php?module_view=1&module=poll&action=qr-codes&module_primary_directory=' . $module_primary_directory,
        //'poll-thank-you/?$' => 'index.php?module_view=true&module=poll&action=thank-you&module_primary_directory=' . $module_primary_directory,
        //'poll-results/?$' => 'index.php?module_view=true&module=poll&action=results&module_primary_directory=' . $module_primary_directory,
      ],
    ];


    $poll_rules = apply_filters('poll_rewrite_rules', $poll_rules, $module_primary_directory);
    $poll_rules = array_reverse($poll_rules);

    foreach ($poll_rules as $group => $group_rules) {
      $rules = $group_rules + $rules;
    }

    return $rules;
  }

  /**
   * WP list table page.
   * @param array $columns
   * @return array
   */
  function manage_poll_entry_columns($columns) {
    $columns['ip'] = 'IP Address';
    $columns['ua'] = 'User Agent';
    $columns['referer'] = 'Referer';
    $columns['qr_unique_id'] = 'QR Unique ID';
    return $columns;
  }

  /**
   * WP list table page.
   * @param string $column_name
   * @param string $id
   */
  function manage_poll_entry_posts_custom_column($column_name, $id) {
    $meta = get_post_meta($id, 'vote_meta', true);

    switch ($column_name) {
      case 'ip':
        echo $meta['ip'];
        break;
      case 'ua':
        echo $meta['ua'];
        break;
      case 'referer':
        echo $meta['referer'];
        break;
      case 'qr_unique_id':
        echo $meta['qr_unique_id'];
        break;
      default:
        break;
    } // end switch
  }

  function add_meta_boxes($post_type) {
    if (current_user_can('edit_others_posts')) {
      add_meta_box('poll_choices', 'Poll Choices', array($this, 'meta_box_poll_choices'), 'poll', 'normal', 'default');
      add_meta_box('poll_results', 'Poll Results', array($this, 'meta_box_poll_results'), 'poll', 'normal', 'default');
      add_meta_box('poll_votes', 'Poll Votes', array($this, 'meta_box_poll_votes'), 'poll', 'normal', 'default');
      add_meta_box('choice_info', 'Choice Info', array($this, 'meta_box_choice_info'), 'poll_choice', 'normal', 'default');
      add_meta_box('vote_info', 'Vote Info', array($this, 'meta_box_vote_info'), 'poll_vote', 'normal', 'default');
      add_meta_box('poll_reset', 'Delete Poll Votes', array($this, 'meta_box_poll_votes_delete'), 'poll', 'normal', 'default');
    }
  }

  function meta_box_poll_choices() {
    $primary_directory = dirname(__FILE__) . '/../../';
    $view_file = 'meta_box_poll_choices';
    include $primary_directory . '/modules/admin/controller.php';
    include $primary_directory . '/modules/admin/views/' . $view_file . '.phtml';
  }

  function meta_box_poll_results() {
    $primary_directory = dirname(__FILE__) . '/../../';
    $view_file = 'meta_box_poll_results';
    include $primary_directory . '/modules/admin/controller.php';
    include $primary_directory . '/modules/admin/views/' . $view_file . '.phtml';
  }

  function meta_box_poll_votes() {
    $primary_directory = dirname(__FILE__) . '/../../';
    $view_file = 'meta_box_poll_votes';
    include $primary_directory . '/modules/admin/controller.php';
    include $primary_directory . '/modules/admin/views/' . $view_file . '.phtml';
  }

  function meta_box_choice_info() {
    $primary_directory = dirname(__FILE__) . '/../../';
    $view_file = 'meta_box_choice_info';
    include $primary_directory . '/modules/admin/controller.php';
    include $primary_directory . '/modules/admin/views/' . $view_file . '.phtml';
  }

  function meta_box_vote_info() {
    $primary_directory = dirname(__FILE__) . '/../../';
    $view_file = 'meta_box_vote_info';
    include $primary_directory . '/modules/admin/controller.php';
    include $primary_directory . '/modules/admin/views/' . $view_file . '.phtml';
  }

  function meta_box_poll_votes_delete() {
    $primary_directory = dirname(__FILE__) . '/../../';
    $view_file = 'meta_box_poll_votes_delete';
    include $primary_directory . '/modules/admin/controller.php';
    include $primary_directory . '/modules/admin/views/' . $view_file . '.phtml';
  }

  function reset_status($poll_id) {
    $time = ($time) ?: time() - HOUR_IN_SECONDS * 24;
    $this->set_cookie($poll_id, false, $time);
    $this->unset_session_var($poll_id, 'vote_id');
  }

  function get_cookie($poll_id) {
    return $_COOKIE[$this->get_cookie_name($poll_id)] ?? null;
  }

  function get_cookie_name($poll_id) {
    return 'poll-vote-' . $poll_id;
  }

  function set_cookie($poll_id, $vote_id, $time = false, $path = '/') {
    $time = ($time) ?: time() + HOUR_IN_SECONDS;
    setcookie($this->get_cookie_name($poll_id), $vote_id, $time, $path);
  }

  function get_session_name($poll_id) {
    return $this->get_cookie_name($poll_id);
  }

  function get_session($poll_id) {
    session_start();
    return $_SESSION[$this->get_session_name($poll_id)];
  }

  function set_session_var($poll_id, $key, $value) {
    $_SESSION[$this->get_session_name($poll_id)][$key] = $value;
  }

  function unset_session_var($poll_id, $key) {
    unset($_SESSION[$this->get_session_name($poll_id)][$key]);
  }

  function get_session_var($poll_id, $key) {
    return $this->get_session($poll_id)[$key] ?? null;
  }

  //  function throttle_user( $poll_id ) {
  //    $cookie_name = $this->get_cookie_name( $poll_id );
  //    return isset( $_COOKIE[$cookie_name] );
  //  }

  function get_user_status($poll_id) {
    $status = false;
    if ($this->get_cookie($poll_id)) {
      $status = 'voted';
    } else if ($this->get_session_var($poll_id, 'vote_id')) {
      $status = 'voted';
    }
    return $status;
  }

  function user_has_voted($poll_id) {
    return ('voted' == $this->get_user_status($poll_id));
  }

  function record_vote($poll_id, $choice_id) {
    $poll = dclmn_get_post($poll_id);
    if ('poll' != $poll->post_type) {
      die('invalid poll.');
    }

    $choice = dclmn_get_post($choice_id);
    if ('poll_choice' != $choice->post_type) {
      die('invalid poll choice.');
    }

    if ($poll->poll_closed || $this->user_has_voted($poll_id)) {
      //send them to the throttle page
      header('Location: ' . trailingslashit($poll->href));
      exit;
    }

    //record the vote by inserting a post
    $args = [
      'post_type' => 'poll_vote',
      'post_title' => $poll->post_title . ' - ' . $choice->choice_label . ' - ' . current_time('Y-m-d H:i:s'),
      'post_status' => 'publish',
      'post_parent' => $poll->ID,
    ];
    $vote_id = wp_insert_post($args);

    //check for failure
    if (!$vote_id) {
      logger('Error recording poll vote. ' . base64_encode(serialize($_REQUEST)), 'poll', 'critical');
      die('Sorry, an error occurred.');
    } else {
      //set the first two post meta
      update_post_meta($vote_id, 'poll_id', $poll->ID);
      update_post_meta($vote_id, 'choice_id', $choice->ID);

      //record vote meta
      $meta = [
        'choice_label' => $choice->choice_label,
        'ip' => $_SERVER['REMOTE_ADDR'],
        'ua' => $_SERVER['HTTP_USER_AGENT'],
        'referer' => $_SERVER['HTTP_REFERER'],
        'unique_id' => uniqid(),
      ];

      update_post_meta($vote_id, 'vote_meta', $meta);

      $this->set_cookie($poll->ID, $vote_id);
      $this->set_session_var($poll_id, 'vote_id', $vote_id);

      logger("Poll Vote Recorded. Poll ID: {$vote_id} | Choice ID: {$choice_id} | Vote ID: {$vote_id}", 'poll');
      logger('Poll Vote ' . $vote_id . ' | ' . base64_encode(serialize($_REQUEST)), 'awards-poll');

      return $vote_id;
    }

    logger('Unhandled vote action. ' . base64_encode(serialize($_REQUEST)), 'poll', 'critical');

    return false;
  }

  function ajax_results_refresh() {
    $poll = new DCLMN_Poll($_REQUEST['poll_id']);
    $out = $this->partial('results-bar', 'poll', ['poll' => $poll]);

    if (!$out) {
      $status = 'fail';
      $msg = 'No results found.';
    } else {
      $status = 'success';
      $msg = $out;
    }

    $return = [
      'status' => $status,
      'msg' => $msg,
    ];

    die(json_encode($return));

    exit;
  }

  function ajax_votes_refresh() {
    $poll = new DCLMN_Poll($_REQUEST['poll_id']);
    $out = $this->partial('votes-table', 'admin', ['poll' => $poll]);


    // ob_start();
    // require dirname(__FILE__) . '/../../modules/poll/controller.php';
    // include dirname(__FILE__) . '/../../modules/poll/views/single.phtml';
    // $out = ob_get_clean();
    // $content .= $out;


    if (!$out) {
      $status = 'fail';
      $msg = 'No votes found.';
    } else {
      $status = 'success';
      $msg = $out;
    }

    $return = [
      'status' => $status,
      'msg' => $msg,
    ];

    die(json_encode($return));

    exit;
  }

  function ajax_sort_poll_choices() {
    global $wpdb;

    $poll_id = $_REQUEST['poll_id'];
    $choice_ids = $_REQUEST['choice_ids'];

    if (!$poll_id) {
      $status = 'fail';
      $msg = 'No poll id.';
    } elseif (!$choice_ids || !is_array($choice_ids)) {
      $status = 'fail';
      $msg = 'No choice ids.';
    } else {
      $i = 1;
      foreach ($choice_ids as $choice_id) {
        $sql = "UPDATE {$wpdb->posts} SET menu_order=%d WHERE ID=%d";
        $sql = $wpdb->prepare($sql, $i, $choice_id);
        $wpdb->query($sql);
        $i++;
      }
      $msg = 'Sort order updated.';
    }

    $return = [
      'status' => $status,
      'msg' => $msg,
    ];

    die(json_encode($return));
  }

  function ajax_dclmn_poll_vote() {
    parse_str($_POST['data'], $params);
    $poll_id = $params['poll_id'];
    $choice_id = $params['choice_id'];

    if (!$poll_id || !$choice_id) {
      $status = 'fail';
      $msg = 'missing info.';
    } else {
      $vote_id = $this->record_vote($poll_id, $choice_id);
      $status = 'success';
      $msg = 'Thank you for voting.';
    }

    $return = [
      'status' => $status,
      'msg' => $msg,
    ];

    die(json_encode($return));
  }

  function ajax_votes_delete() {
    $poll_id = $_REQUEST['poll_id'];

    if (!$poll_id) {
      $status = 'fail';
      $msg = 'missing info.';
    } else {
      $posts = get_posts([
        'posts_per_page' => -1,
        'post_type' => ['poll_vote'],
        'post_status' => ['publish'],
        'post_parent' => $poll_id,
      ]);

      foreach ($posts as $post) {
        wp_delete_post($post->ID, 1);
      }

      $status = 'success';
      $msg = 'Votes deleted. Refresh the page for the latest info.';
    }

    $return = [
      'status' => $status,
      'msg' => $msg,
    ];

    die(json_encode($return));
  }

  function the_content($content) {
    global $post;
    if ('poll' == $post->post_type) {
      ob_start();
      require dirname(__FILE__) . '/../../modules/poll/controller.php';
      include dirname(__FILE__) . '/../../modules/poll/views/single.phtml';
      $out = ob_get_clean();
      $content .= $out;
    }
    return $content;
  }

  function partial($partial, $module = 'poll', $vars = []) {
    extract($vars);
    ob_start();
    include dirname(__FILE__) . '/../../modules/' . $module . '/views/partials/' . $partial . '.phtml';
    return ob_get_clean();
  }
}
