[![License](https://img.shields.io/:license-ISC-blue.svg)](https://tldrlegal.com/license/-isc-license)
[![CakePHP](https://img.shields.io/badge/powered%20by-CakePHP-red.svg)](http://cakephp.org)
# VA - VOID

[Vortex Adventues](http://www.the-vortex.nl) - **V**ortex **O**nline **I**ncharacter **D**atabase

## Installation

Download [Composer](http://getcomposer.org/doc/00-intro.md) or update `composer self-update`.

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
   * Browse to it, it should now work!
5. Optionally: load some initial database content with
   `./bin/cake migrations seed`


## Database backups

Database backups can be listed, exported and imported using the CLI.
* `./bin/cake backup` lists all the database backups present.
* `./bin/cake backup export` will created a new backup file.
* `./bin/cake backup import` Import a backup (or any other) sql file.

This tool uses the commandline mysql and mysqldump commands.
The created files are stored in `backups/` folder.

**Warning**: old backups might not be compatible with newer tables structures.
