FROM php:8.2-apache

ARG APACHE_DOCUMENT_ROOT=/var/www/html/public

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    && docker-php-ext-install pdo pdo_mysql gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configuration Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Activer mod_rewrite pour les URLs reecrites
RUN a2enmod rewrite

# Permettre le .htaccess
RUN sed -i 's/<Directory \/var\/www\/html>/&\n    AllowOverride All/' /etc/apache2/apache2.conf

# Definir le DocumentRoot
ENV APACHE_DOCUMENT_ROOT=${APACHE_DOCUMENT_ROOT}

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Aliases pour uploads partages
RUN printf "Alias /uploads /var/www/html/public/uploads\n<Directory /var/www/html/public/uploads>\n    Options FollowSymLinks\n    AllowOverride All\n    Require all granted\n</Directory>\nAlias /robots.txt /var/www/html/frontoffice/public/robots.txt\nAlias /sitemap.php /var/www/html/frontoffice/public/sitemap.php\n" > /etc/apache2/conf-available/irannews-shared.conf \
    && a2enconf irannews-shared

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de l'application
COPY . .

# Permissions Apache
RUN chown -R www-data:www-data /var/www/html

# Port 80
EXPOSE 80

CMD ["apache2-foreground"]
