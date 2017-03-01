# cloo.gl url shortener

## How to setup:
Copy all these files to some apache directory. Note that you have to enable
`mod_rewrite` to have the full experience. Also the `sqlite3` library for `php`
has to be available. The `Dockerfile` included can serve as a ready to use
solution but is not working yet.

## API calls:
### GET
- A GET without variables will forward the user to `cloogle.org`
- A GET request with a key will forward the user to the url associated with the
  key if available.

### POST
POST-variable `type` is required. When the request is successfull it will echo
the shortened url.

#### `type=regular`
Regular url shortening service. Required arguments:
- token

	Authentication token, this service is not for everyone. If you want to use it
	please contact one of the cloogle developers.
- url

	The url to be shortened

#### `type=cloogle`
Cloogle url shortening service. Required arguments:
- url

	The url to be shortened
