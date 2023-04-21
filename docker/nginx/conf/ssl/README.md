# How to create local ssl certificate

## Run
````
docker run -v $PWD:/work -it nginx openssl req -x509 -nodes -days 3650 -newkey rsa:2048 -keyout /work/dev.blick.ch.key -out /work/dev.blick.ch.crt -config /work/dev.blick.ch.conf
````
