FROM yiisoftware/yii2-php:8.1-apache

EXPOSE 80

# Change document root for Apache
RUN sed -i -e 's|/app/web|/app/backend/web|g' /etc/apache2/sites-available/000-default.conf
