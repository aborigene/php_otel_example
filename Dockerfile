#FROM php:8.0.24-zts-buster 
FROM php:8.2-rc-apache-buster
#COPY . /usr/src/myapp
COPY . /var/www/html
WORKDIR /usr/src/myapp
# change the line below to the appropriate token
ENV OTEL_EXPORTER_OTLP_TRACES_HEADERS="Authorization=Api-Token dt0c01.XXXXXXXXXXXXXXXXXXXXXXXX.YYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYY"
ENV OTEL_EXPORTER_OTLP_TRACES_ENDPOINT=https://xxx12345.live.dynatrace.com/api/v2/otlp/v1/traces
ENV REMOTE_ENDPOINT=172.31.64.160
#CMD [ "php", "./hello.php" ]
