<?php

// Configuration Start
$company_name = 'Demo';
$copyright_notice = 'Demo, Inc';
$swiftype_key = 'SWIFTYPE_KEY'; // http://swiftype.com
$google_analytics_id = 'UA-1111-2'; // http://analytics.google.com
$google_analytics_domain = 'demodomain.com';
// Configuration End


$page_title = trim($company_name . ' Support Center');
if (@strlen($article['title'])) {
  $page_title = $article['title'] . ' - ' . $page_title;
}
?>
<!DOCTYPE html>
<html>
<head>
<title><?=$page_title ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content='width=device-width, initial-scale=1, maximum-scale=1'/>
<meta name="title" content="<?=html_escape($article['title']) ?>" />
<meta property="st:title" content="<?=html_escape($article['title']) ?>" />
<?php
foreach ($visible_articles as $visible_category_path => $visible_category) {
  if ($visible_category_path == $category) {
    echo '<meta property="st:section" content="' . html_escape($visible_category['title']) . '" />';
    break;
  }
}
?>
<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.3.0/base-min.css">
<link rel="stylesheet" href="media/main.css">
</head>
<body>
<div class="page">
  <div class="sidebar">
    <header>
      <h1>Support Center</h1>
      <form>
        <input type="text" id="st-search-input" class="st-search-input" />
      </form>
      <nav>
        <?php
        foreach ($visible_articles as $visible_category_path => $visible_category) {
          echo '<div class="title">' . html_escape($visible_category['title']) . '</div>';
          foreach ($visible_category['articles'] as $visible_article_path => $visible_article_title) {
            echo '<a href="' . "$path/$visible_category_path/$visible_article_path" . '">' .
                html_escape($visible_article_title) . '</a>';
          }
        }
        ?>
      </nav>
    </header>
  </div>
  <div class="page-content">
    <div class="article">
      <article>
        <?php if (@strlen($article['title'])): ?>
          <div class="title"><h2><?=html_escape($article['title']) ?></h2></div>
        <?php endif; ?>
        <div class="content" data-swiftype-index="true"><?=$article['content'] ?></div>
      </article>

      <?php
      if (@count($article['related']) > 0) {
        echo '<section>';
        echo '<div class="related"><div class="title">Related articles</div>';
        foreach ($article['related'] as $related_path) {
          $found = false;
          foreach ($visible_articles as $visible_category_path => $visible_category) {
            foreach ($visible_category['articles'] as $visible_article_path => $visible_article_title) {
              if ($related_path == $visible_category_path . '/' . $visible_article_path) {
                echo '<a href="' . "$path/$related_path" . '">' . html_escape($visible_article_title) . '</a>';
                $found = true;
                break;
              }
            }
            if ($found) {
              break;
            }
          }
        }
        echo '</div>';
        echo '</section>';
      }
      ?>

      <?php if (!@$article['search_results']): ?>
      <section>
        <div class="helpful">
          <div class="title">Was this article helpful?</div>
          <div class="content" id="helpful-content">
            <a href="javascript:void(0);" onclick="clickHelpful('Yes');">Yes</a>
            <a href="javascript:void(0);" onclick="clickHelpful('No');">No</a>
            <a href="javascript:void(0);" onclick="clickHelpful('Incorrect');">Report incorrect</a>
          </div>
        </div>
      </section>
      <?php endif; ?>

      <footer>
        <div class="footer">&copy; 2013 <?=$copyright_notice ?></div>
      </footer>
    </div>
  </div>
</div>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', '<?=$google_analytics_id ?>', '<?=$google_analytics_domain ?>');
  ga('send', 'pageview');

  var Swiftype = window.Swiftype || {};
  (function() {
    Swiftype.key = '<?=$swiftype_key ?>';
    Swiftype.inputElement = '#st-search-input';
    Swiftype.resultContainingElement = '#st-results-container';
    Swiftype.attachElement = '#st-search-input';
    Swiftype.renderStyle = '<?=@$article['search_results'] ? 'inline' : 'new_page' ?>';

    Swiftype.resultPageURL = '/search';

    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.async = true;
    script.src = '//swiftype.com/embed.js';
    var entry = document.getElementsByTagName('script')[0];
    entry.parentNode.insertBefore(script, entry);
  }());

  function clickHelpful(action) {
    var el = document.getElementById('helpful-content');
    el.innerHTML = 'Thank you!';
    ga('send', 'event', 'Helpful', action, '<?=html_escape($short_path) ?>');
  }
</script>
</body>
</html>
