## Installation Instructions

### Prerequisites

1.	Web server like Apache or Nginx.
1.	PHP 5.5 or above. We use some APIs which only available in PHP V5.5 or above.
1.	MySQL. You need a MySQL instance to create a database for Open Cloud API.
1.	PHP modules:
	* php-mysql. We use this to connect your MySQL database.
	* php-apc. We use APC as the default cache backend.
1.	CodeIgniter.
	* Make sure that you have installed [CodeIgniter](http://www.codeigniter.com) V2.2.1 correctly.
	* Set the configuration of your CodeIgniter installation, especically the database settings.

1. Enable you web server for pretty URL to access the PHP scripts. If you are using Apache, you can enable mod_rewrite module of Apache and put the content bellow in `.htaccess` file in the top directory of your CodeIgniter installation:

		<IfModule mod_rewrite.c>
			RewriteEngine On
			RewriteBase /
			RewriteCond %{REQUEST_FILENAME} !-f
			RewriteCond %{REQUEST_FILENAME}/index.html !-f
			RewriteCond %{REQUEST_FILENAME}/index.php !-f
			RewriteRule . index.php [L]
		</IfModule>
For nginx add this to your server config:

 		location / {
        	try_files $uri $uri/ /index.php?/$request_uri;
        }
        
### Install Open Cloud API for development

1.	Get the source code from GitHub or download the source code tarball and extract the tarball into `application/` directory of your CodeIgniter installation. Note that this will overwrite the files in `application/` directory.
1.	Make sure that `cache/` and `logs/` directories in `application/` directory can be written by your Web server.
1.	Run Install controller in your browser to create tables and an app key for test:

		http://<www.examples.com>/install/create_tables
		http://<www.examples.com>/install/create_test_app_key

1.	Visit the following URL in your browser to check the installation:

		http://<www.examples.com>/list/computer_languages/items

1.	Done.

