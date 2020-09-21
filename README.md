# Sanitize JSON

## Prerrequisites
PHP 7.4

## Config structure

### The config structure has the key to be redacted separated by new line:

### Examples:
`auth.user.password` will replace all the passwords contained into the level `auth -> ... -> user -> ... -> password` with asterisks (this way we can hide some specific keys in a specific level)

Config:

```
auth.user.password
```

Data Input:

```
{"password": "secret", "auth": {"name": "frank", "password": "abc123"}, "extra": [{"auth": "secret123", "x": 42}, "password"]}
{"passwd": "secret", "auth": {"name": "frank", "password": "abc123"}, "extra": [{"auth": "secret123", "x": 42}]}
{"auth": {"user": {"name": "jorge", "password": "test"}}}
```

Data Output:

```
{"password":"secret","auth":{"name":"frank","password":"abc123"},"extra":[{"auth":"secret123","x":42},"password"]}
{"passwd":"secret","auth":{"name":"frank","password":"abc123"},"extra":[{"auth":"secret123","x":42}]}
{"auth":{"user":{"name":"jorge","password":"******"}}}
```

`password` and `auth` will replace all the info related with both keys with asterisks

Config:

```
password
auth
```

Data Input:

```
{"password": "secret", "auth": {"name": "frank", "password": "abc123"}, "extra": [{"auth": "secret123", "x": 42}, "password"]}
{"passwd": "secret", "auth": {"name": "frank", "password": "abc123"}, "extra": [{"auth": "secret123", "x": 42}]}
{"auth": {"user": {"name": "jorge", "password": "test"}}}
```

Data Output:

```
{"password":"******","auth":"******","extra":[{"auth":"******","x":42},"password"]}
{"passwd":"secret","auth":"******","extra":[{"auth":"******","x":42}]}
{"auth":"******"}
```

## Steps

### Execute sanitize script with two params (config path and data path):
```
./sanitize files/config files/data
```

## Optional
### Install docker and run the following command:

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
