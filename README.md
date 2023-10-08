[![License](https://img.shields.io/:license-ISC-blue.svg)](LICENSE.txt)
[![CakePHP](https://img.shields.io/badge/powered%20by-CakePHP-red.svg)](https://cakephp.org)
# VA - VOID

[Vortex Adventues](http://www.the-vortex.nl) - **V**ortex **O**nline **I**ncharacter **D**atabase

## Install

Download [Composer](https://getcomposer.org/doc/00-intro.md) or update `composer self-update`.

1. Clone the repository
2. Run `composer install`  
   If this didn't create the `config/app.php` (or set folder permissions),
   then run this command again.
3. Run `./bin/cake admin checks`
   * Fix everything it reports as NOT ok (the red lines).
   * This mostly consists of reading and editing `config/app.php`.
   * There you need to setup the `'Datasources'` and any other configuration
     relevant for your site.
   * The database tables can be created with Migrations
     `./bin/cake migrations migrate`
4. Configure apache to serve the `webroot` folder.  
   Example apache vhost.conf:
    ```
    <VirtualHost *:80>
        ServerName api.your.domain
        DocumentRoot /var/www/void/webroot

        <Directory />
            Options FollowSymLinks
            AllowOverride None
        </Directory>
        <Directory /var/www/>
            Options Indexes FollowSymLinks MultiViews
            AllowOverride All
            Order allow,deny
            allow from all
        </Directory>

        CustomLog ${APACHE_LOG_DIR}/access.void.log combined
    </VirtualHost>

   ```
5. Browse to /admin/checks
   * Again, fix everything it reports as NOT ok.
6. Optionally: load some initial database content with
   `./bin/cake migrations seed`


## Update

1. `./bin/cake backup export`
   * Make a backup of your data.
2. `git pull`
   * Retrieves the latest code
3. `composer update`
   * Installs/updates package dependencies.
   * This is required if `composer.json` was modified, otherwise it is still recommended.
4. `./bin/cake migrations migrate`
   * Updates the database table structure(s).
5. `./bin/cake backup export`
   * Optionaly: create a backup before resuming regular usage/operations.


## Database backups

Database backups can be listed, exported and imported using the CLI.
* `./bin/cake backup` lists all the database backups present.
* `./bin/cake backup export [description]` will created a new backup file.
* `./bin/cake backup import <file>` Import a backup (or any other) sql file.

This tool uses the commandline mysql and mysqldump commands internally.  
The created backup files are stored in the `backups/` folder.

**Warning**: old backups might not be compatible with newer tables structures.  It is possible to use `cake migrations` to revert to an earlier database structure.  Don't forget to save your data / make a backup before doing this!


## Social provider login

Call the `/auth/social` api endpoint to get the list of all supported social login providers.  For each provider the result contains a `url` and `authUri` link.  Both need to be customized by the front-end before they can be used.

1. First in the `authUri` replace the `STATE` and `CALLBACK` strings:
   - `STATE` should be a random string used to prevent cross-site request forgery
   - `CALLBACK` is the front-end url where the user gets redirect to after login

2. Now redirect the user to this modified `authUri` to start the login proces.

3. On succesful login the user gets redirected to the `CALLBACK` location.

4. Check that the returned `state` query parameter matches with the earlier provided `STATE` value.

5. In the `url` of the social provider replace `CODE` and `CALLBACK`:
   - `CODE` with the `code` string we got in the query parameter after the login
   - `CALLBACK` must be the same as used in the `authUri`

6. Perform a GET on the modified `url`.  This should yield the same result as a regular user+name password.  The result contains a JWT that can be used for all following interactions with the void api.  Similar, a failed login will result in a 401 error response.
