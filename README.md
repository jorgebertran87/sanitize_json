# Sanitize JSON

## Prerrequisites
PHP 7.4

## Config structure

### The config structure has the key to be redacted separated by new line:

### Examples:
`auth.user.password` will replace all the passwords contained into the level `auth -> ... -> user -> ... -> password` with asterisks

`extra` will replace all the extra info with asterisks

## Steps

### Execute sanitize script with two params (config path and data path):
```
./sanitize files/config files/data
```

## Optional
### Install docker and run following commands:

Create php-cli + composer container
```
docker build -t php_composer .
```

Run sanitize script
```
docker run --rm -v $(pwd):/opt -w /opt php_composer ./sanitize files/config files/data
```

### Install vendors and run unit tests

Run composer install
```
docker run --rm -v $(pwd):/opt -w /opt php_composer composer install
```

Run unit tests
```
docker run --rm -v $(pwd):/opt -w /opt php_composer ./vendor/bin/phpunit tests
```
