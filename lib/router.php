<?php

define('ROOT_DIR', getcwd() . '/');
define('TEMPLATES_DIR', ROOT_DIR . 'templates/');
define('ARTICLES_DIR', ROOT_DIR . 'articles/');
define('LIB_DIR', ROOT_DIR . 'lib/');


//
// Error reporting
//

error_reporting(E_ALL | E_STRICT);

function custom_error_handler($code, $message, $file, $line, $context) {
  if (!(error_reporting() & $code)) {
    return;
  }

  $err = "Error $code: $message ($file:$line)";
  error_log($err);

  if (strpos($message, 'not found') !== false) {
    echo '404 - Not Found';
  }
  else {
    echo '500 - Internal Server Error';
  }
  exit;
}
set_error_handler('custom_error_handler');


//
// Helpers
//


function html_escape($str) {
  $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
  return $str;
}


//
// Route
//

function route() {
  $path = parse_url(
    (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' .
    $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']
  );
  $path = $relative_path = $path['path'];
  $i = strrpos($path, 'support/');
  if ($i !== false) {
    $relative_path = substr($path, $i + 8);
    $path = substr($path, 0, $i + 7);
  }
  else if (substr($path, 0, 1) == '/') {
    $relative_path = substr($path, 1);
    $path = '';
  }

  $temp = explode('/', $relative_path);
  $api_version = 1;
  $base_i = 0;
  $category = @$temp[$base_i] ? strtolower($temp[$base_i]) : 'index';
  $base_i++;
  $article = @$temp[$base_i] ? strtolower($temp[$base_i]) : 'index';
  $base_i++;

  // Load article file.
  $short_path = $category . '/' . $article;
  $article_path = ARTICLES_DIR . $short_path . '.php';
  if (!file_exists($article_path)) {
    trigger_error("File not found for $category $article");
  }
  ob_start();
  require_once($article_path);
  $article['content'] = ob_get_clean();

  // Load visible articles.
  require_once(ARTICLES_DIR . 'visible.php');
  $found = false;
  foreach ($visible_articles as $visible_category_path => $visible_category) {
    foreach ($visible_category['articles'] as $visible_article_path => $visible_article_title) {
      if ($short_path == $visible_category_path . '/' . $visible_article_path) {
        $article['title'] = $visible_article_title;
        $found = true;
        break;
      }
    }
    if ($found) {
      break;
    }
  }

  // Load template file.
  $template = @strlen($article['template']) ? $article['template'] : 'article';
  $template_path = TEMPLATES_DIR . $template . '.php';
  if (!file_exists($template_path)) {
    trigger_error("Template not found for $category $article");
  }
  require_once($template_path);
}

route();

