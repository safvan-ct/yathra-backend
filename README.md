# LARAVEL PROJECT SETUP GUIDE

1. Clone the Repository

---

git clone https://github.com/safvan-ct/yathra-backend.git
cd yathra-backend

2. Install Composer (if not installed)

---

curl -sS https://getcomposer.org/installer | php -- --2

3. Install Dependencies

---

php composer.phar install --optimize-autoloader

# OR for production

php composer.phar install --no-dev --optimize-autoloader

4. Environment Setup

---

cp .env.example .env

# Update .env with database and app configuration

5. Add .htaccess File

---

Place in public_html > .htaccess: OR
Place in public_html > sub folder > .htaccess:

<IfModule mod_rewrite.c>
    RewriteEngine On

    # Route storage files correctly
    RewriteCond %{REQUEST_URI} ^/storage/(.*)$ [NC]
    RewriteRule ^storage/(.*)$ /yathra-backend/storage/app/public/$1 [L]

    # Serve assets from Laravel public folder
    RewriteCond %{REQUEST_URI} ^/(backend|web|img|fonts|build|storage)/ [NC]
    RewriteRule ^(.*)$ /yathra-backend/public/$1 [L]

    # Laravel front controller
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [L]
</IfModule>

6. Set index.php

---

Place in public_html OR
Place in public_html > sub folder

<?php

define('LARAVEL_START', microtime(true));

require __DIR__ . '/yathra-backend/vendor/autoload.php';

$app = require_once __DIR__ . '/yathra-backend/bootstrap/app.php';

$request = Illuminate\Http\Request::capture();

$response = $app->handleRequest($request);

$response->send();


7. Set Permissions

---

chmod -R 755 .
chmod -R 755 storage bootstrap/cache
