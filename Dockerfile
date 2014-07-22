FROM base/archlinux

MAINTAINER Spencer Rinehart <anubis@overthemonkey.com>

RUN pacman --sync --refresh --sysupgrade --ignore filesystem --noconfirm --noprogressbar --quiet
RUN pacman --sync --noconfirm --noprogressbar --quiet php nodejs git

# Configure PHP and install composer
RUN echo -e '[PHP]\nopen_basedir =\n[Date]\ndate.timezone = America/New_York' >/etc/php/conf.d/common.ini
RUN echo -e '[PHP]\nextension = openssl.so\nextension = phar.so\nextension = zip.so' >/etc/php/conf.d/composer-dependencies.ini
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# It's best to use a separate user, for security reasons.
RUN useradd --create-home --comment "Build User" build

# Setup the site giving the build user access to execute (Docker sets uid/gid to 0 by default)
ADD . /home/build/site
RUN chown -R build /home/build
USER build
ENV HOME /home/build
WORKDIR /home/build/site

# Remove references to the git repository (mainly for bower's benefit - it has issues if the directory is in a git submodule)
RUN rm -r .git

# Install dependencies and run the build
RUN composer install
RUN npm install
ENV PATH node_modules/.bin:$PATH
RUN gulp

EXPOSE 8000

# Kick off a dev server by default - not suitable for production.
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
