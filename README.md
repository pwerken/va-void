[![License](https://img.shields.io/:license-ISC-blue.svg)](https://tldrlegal.com/license/-isc-license)
[![CakePHP](https://img.shields.io/badge/powered%20by-CakePHP-red.svg)](http://cakephp.org)
# VA - VOID

[Vortex Adventues](http://www.the-vortex.nl) - **V**ortex **O**nline **I**ncharacter **D**atabase

The code can be found here: [pwerken/va-void](https://github.com/pwerken/va-void).

## Installation

Download [Composer](http://getcomposer.org/doc/00-intro.md) or update `composer self-update`.

1. `composer create-project --keep-vcs pwerken/va-void va-void dev-master`
2. Read and edit `config/app.php` and setup the 'Datasources' and any other configuration relevant for your site.
3. Run the database migrations with `./bin/cake migrations migrate`
