# Arco Framework

This framework is inspired by Laravel and in no way wants to compete against Laravel.

Arco Framework has been created within an educational aim following the Mastermind course below:

**`Create your own Web Framework with PHP`**
- https://www.mastermind.ac/courses/crea-tu-propio-framework-desde-cero

## Warnings

This framework is not suitable for production environments and may have several errors. Currently, its development is only used for educational purposes.

# Deployment

## Apache
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

## Nginx
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
