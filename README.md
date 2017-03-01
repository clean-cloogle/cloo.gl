# cloo.gl url shortener
You should read *soon* as: not implemented yet.
You should read *yet* as: to be discussed.

## How to setup
### Manual setup
- Install `apache2` `php` and `php-sqlite`
- Enable the `mod_rewrite` apache module
- Configure `apache` to serve this directory

### Docker setup
Just run(soon):
```
docker build -t cloo.gl .
docker run -v "$PWD":/var/www/html -p 80:80 cloo.gl
```

## How to use
### `GET` request
#### No variables and no url
The user will be redirected to `https://cloogle.org`

#### No variables but just a key
- `/e/####`

	Will redirect to `/?type=cloogle&key=####`
- `/####`

	Will redirect to `/?type=regular&key=####`

#### `type` and `key` variables
- `type=cloogle`

	The user will be redirected to the address associated with `key`. This is
	always a `cloogle.org` link.
- `type=regular`

	The user will be redirected to the address associated with `key`. This is
	can be any link. Note that not everyone can create such links it requires an
	authentication token(at least soon).

### `POST` request
All requests will print the url. When an error occurs a different `HTTP` code
will be returned(soon).

#### `type=cloogle`
Creates a shortened url for the `url` `POST` variable which is a  cloogle
query. This should only be called from the cloogle frontend and is available
for everyone

#### `type=regular`
Creates a shortened url for the `url` `POST` variable if and only when the
`token` `POST` variable is accepted(soon). Not everyone can create `cloo.gl`
shortened urls(yet).
