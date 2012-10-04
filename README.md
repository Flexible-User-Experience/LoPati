Lo Pati CMS
========================


0) Requirements
----------------------------------
· Git

· Composer

· PHP

· MySQL

· Apache



1) Installing
----------------------------------

1 - cd your/root/folder

2 - git clone https://github.com/Flexible-User-Experience/LoPati.git

3 - cd LoPati

4 - curl -s https://getcomposer.org/installer | php

5 - cp app/config/parameters.yml.dist app/config/parameters.yml

6 - nano app/config/parameters.yml

7 - php composer.phar update

8 - php app/console doctrine:schema:update --force

