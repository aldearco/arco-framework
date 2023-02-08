<img src="https://raw.githubusercontent.com/aldearco/arco-framework/main/public/assets/img/arco-logo-color.svg" width="100" alt="Arco Framework Icon">

# Arco Framework
<a href="https://packagist.org/packages/aldearco/arco-framework"><img src="https://img.shields.io/packagist/dt/aldearco/arco-framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/aldearco/arco-framework"><img src="https://img.shields.io/packagist/v/aldearco/arco-framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/aldearco/arco-framework"><img src="https://img.shields.io/packagist/l/aldearco/arco-framework" alt="License"></a>

This framework is inspired by Laravel and in no way wants to compete against Laravel.

Arco Framework has been created within an educational aim following the Mastermind course below:

**`Create your own Web Framework with PHP`**
- https://www.mastermind.ac/courses/crea-tu-propio-framework-desde-cero

## Warnings

This framework is not suitable for production environments and may have several errors. Currently, its development is only used for educational purposes.

# Create project

## Composer
    composer create-project aldearco/arco

## Download source code
Latest release:
- https://github.com/aldearco/arco/releases

Unzip and run `composer install`.

# Deployment
## Root folder
If you perform the deployment in the root folder, you don't need to add any additional files to the project, but you will need to specify the default public folder name of your server in the file. By default, the public folder is /public, but if you need to change it to /public_html, you can do so in the file ./config/app.php in the key "public".

## Shared Hosting
If you're deploying the project on shared hosting within the public or public_html folders, you may need to create certain files, depending on your server system. It is likely that you will need to modify or add some lines with proposed code.

### Apache
**`.htaccess`**

    <IfModule mod_rewrite.c>
    RewriteEngine on

    # Serve existing files in the /public folder as if they were in the root of the site.
    RewriteCond %{DOCUMENT_ROOT}public%{REQUEST_URI} -f
    RewriteRule (.+) /public/$1 [L]

    # Route requests for /storage/ to the /storage/ directory using the P(passthrough) flag.
    RewriteRule ^storage/(.+) /storage/$1 [PT]

    # Route everything else to /public/index.php
    RewriteRule ^ /public/index.php [L]
    </IfModule>

    #Disable index view
    options -Indexes

    <Files .env>
    order allow,deny
    Deny from all
    </Files>

### Nginx
**`mysite.conf`**

    server {
    listen 80;
    server_name example.com;
    root /var/www/example.com;

    location / {
        try_files $uri $uri/ /public/index.php;
    }

    location /public {
        try_files $uri $uri/ /public/index.php;
    }

    location /storage {
        internal;
    }

    location ~ \.env$ {
        deny all;
    }

    location ~ ^/storage/ {
        internal;
    }
