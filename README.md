php-support-center
======================

Support center for the minimalist. Generates a beautiful, responsive support center with advanced search and analytics. It is search friendly and you can deep link into articles.

The support center uses Swiftype.com for searching, which allows you to index a subdomain such as support.shopstarter.com and it shows you what people are searching for, but do not find! For analytics, it uses Google Analytics and generates events when visitors report article as helpful, not helpful, or incorrect. The last dependency is on Normalize CSS hosted by Yahoo's CDN. With only these few dependencies you don't need to create any SQL tables.

View live demo here: http://support.shopstarter.com

How to use
======================

If you already have a GIT repo for your server, then:

    git submodule add https://github.com/kennberg/php-support-center support
    git submodule init

Or, for installation inside your server directory:

    git clone https://github.com/kennberg/php-support-center support

To add a new article:

    cd php-support-center
    mkdir -p articles/example
    vim articles/example/first-article.php

You should also add this article to the whitelist:

    vim articles/visible.php

Template for the article is located in the "templates" folder.

All the media such as CSS, images for the articles and so on are located in "media" folder.

Advanced info
======================

You should modify your ".htaccess" in "www" folder for Apache to rewrite all the traffic to support.yourdomain.com to go directly to the index.php file. You could also just host it from the /support/ path. Either way here are some rewrite rules for you reference:

    # Mapping for subdomain suh as support.yourdomain.com
    RewriteCond ${lowercase:%{SERVER_NAME}}               ^support\.yourdomain\.com
    RewriteRule media/(.*)$                               media/$1 [L] 

    RewriteCond ${lowercase:%{SERVER_NAME}}               ^support\.yourdomain\.com
    RewriteRule ^.*$                                      index.php [L] 

    # Mapping for direct use without custom subdomain
    RewriteRule media/(.*)$                               media/$1 [L] 
    RewriteRule ^.*$                                      index.php [L] 

License
======================
Apache v2. See the LICENSE file.
