FROM php:apache

# PHP dependencies
RUN apt-get update && apt-get install -y php5-sqlite
