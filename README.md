# cloo.gl url shortener

**This repository has been moved to
https://gitlab.science.ru.nl/cloogle/cloo-gl.**

You should read *soon* as: not implemented yet.
You should read *yet* as: to be discussed.

## How to setup
### Manual setup
- Install `apache2` `php` and `php-sqlite`
- Enable the `mod_rewrite` apache module
- Configure `apache` to serve this directory
- Run the `init.php` script to initialize the database. This also serves as an
  update script for older versions.

If you also want paste support:
- Install `fiche` (https://github.com/solusipse/fiche)
- Make sure `fiche` runs and open port 9999

### Docker setup
Just run(soon):
```
docker build -t cloo.gl .
docker run -v "$PWD":/var/www/html -p 80:80 cloo.gl
```

## How to use
### Helper scripts
The `contrib` directory contains some helper scripts to use cloo.gl
functionality from different programs.

### `GET` request
#### No variables and no url
The user will be redirected to `https://cloogle.org`

#### No variables but just a key
- `/e/####`

	Will redirect to `/?type=cloogle&key=####`
- `/p/####`

	Will redirect to `/?type=paste&key=####`
- `/####`

	Will redirect to `/?type=regular&key=####`

#### `type` and `key` variables
- `type=cloogle`

	The user will be redirected to the address associated with `key`. This is
	always a `cloogle.org` link.
- `type=paste`

	The pasted file (using `fiche`) will be shown to the user.
- `type=regular`

	The user will be redirected to the address associated with `key`. This is
	can be any link. Note that not everyone can create such links it requires an
	authentication token(at least soon).

### `POST` request
All requests will print the url. When an error occurs a different `HTTP` code
will be returned.

#### `type=cloogle`
Creates a shortened url for the `url` `POST` variable which is a  cloogle
query. This should only be called from the cloogle frontend and is available
for everyone

#### `type=regular`
Creates a shortened url for the `url` `POST` variable if and only when the
`token` `POST` variable is accepted(soon). Not everyone can create `cloo.gl`
shortened urls(yet).

## Changelog
- 0.4: Added `fiche` support.
- 0.3: Added dates for the keys.
- 0.2: Added logging for urls.
- 0.1: Initial version.
