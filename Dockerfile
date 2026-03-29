FROM php:8.2-apache

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

# Activer mod_rewrite pour les URLs réécrites
RUN a2enmod rewrite

# Permettre le .htaccess
RUN sed -i 's/<Directory \/var\/www\/html>/&\n    AllowOverride All/' /etc/apache2/apache2.conf

# Définir le DocumentRoot vers public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de l'application
COPY . .

# Permissions Apache
RUN chown -R www-data:www-data /var/www/html

# Port 80
EXPOSE 80

CMD ["apache2-foreground"]
