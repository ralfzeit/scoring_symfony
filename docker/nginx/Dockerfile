FROM nginx:alpine
COPY default.conf /etc/nginx/conf.d/

RUN apk update && apk upgrade && apk add --no-cache bash

WORKDIR /scoring_symfony