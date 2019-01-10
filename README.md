# Codelight.house landingpage

Source code of https://codelight.house/

## Vagrant config

etc/hosts:

```
192.168.56.123 codelighthouse.vagrant
```

Vagrant docker-compose plugin

```
vagrant plugin install vagrant-docker-compose
```

Copy custom docker configuration for vagrant `<root>/provision/vagrant/docker-compose.override.yml` to `<root>/docker-compose.override.yml`

Start vagrant:
```
vagrant up
vagrant ssh
```

Start services:
```
cd /vagrant
docker-compose up -d
```

http://codelighthouse.vagrant/

## API configuration

Put configutation in `<root>/src/.env` file or configure server to provide environmental variables:
```
SLACK_URL= #enter valid incomming webhook URL
```