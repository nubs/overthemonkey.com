FROM nubs/composer-build

MAINTAINER Spencer Rinehart <anubis@overthemonkey.com>

USER root

RUN apk add --no-cache --virtual .nodejs nodejs
RUN apk add --no-cache --virtual .gyp-deps python make gcc g++

# Setup the site giving the build user access to execute (Docker sets uid/gid to 0 by default)
ADD . /code

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
