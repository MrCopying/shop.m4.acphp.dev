<?php
use Library\Config;

Config::set('site_name', 'Your Site Name');
Config::set('isMultiLanguages', 0);
Config::set('languages', array('en', 'fr', 'ru', 'ua'));

Config::set('routes', array(
    'default'   => '',
    'admin'     => 'admin_',
));

Config::set('default_route','default');
Config::set('default_language','en');
Config::set('default_controller','pages');
Config::set('default_action','index');

Config::set('db.host', 'localhost');
Config::set('db.user', 'root');
Config::set('db.password', '');
//Config::set('db.db_name', 'mvc');
Config::set('db.db_name', 'nova');

Config::set('salt', 'nEkc43Dn32Fv5oHsdD');