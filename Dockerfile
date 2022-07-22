FROM php:7.4-apache


ARG user
ARG uid

RUN a2enmod rewrite

# MSSQL drivers version
ARG MSSQL_DRIVER_VER=5.6

# Install the PHP Driver for SQL Server
RUN apt-get update -yqq \
        && apt-get install -y apt-transport-https gnupg \
        && curl -s https://packages.microsoft.com/keys/microsoft.asc | apt-key add - \
        && curl https://packages.microsoft.com/config/ubuntu/20.04/prod.list > /etc/apt/sources.list.d/mssql-release.list \
        && apt-get update -yqq \
        && ACCEPT_EULA=Y apt-get install -y unixodbc unixodbc-dev libgss3 odbcinst msodbcsql17

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN apt-get update -yqq \
    && apt-get install -y --no-install-recommends openssl \ 
    && sed -i 's,^\(MinProtocol[ ]*=\).*,\1'TLSv1.0',g' /etc/ssl/openssl.cnf \
    && sed -i 's,^\(CipherString[ ]*=\).*,\1'DEFAULT@SECLEVEL=1',g' /etc/ssl/openssl.cnf\
    && rm -rf /var/lib/apt/lists/*

# Install pdo_sqlsrv and sqlsrv
RUN pecl install -f pdo_sqlsrv-${MSSQL_DRIVER_VER} sqlsrv-${MSSQL_DRIVER_VER} \
        && docker-php-ext-enable pdo_sqlsrv sqlsrv

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

WORKDIR /var/www/html

EXPOSE 80


