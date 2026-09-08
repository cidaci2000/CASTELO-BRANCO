<?php
// Caminho: barbearia/bootstrap.php

define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', APP_PATH . '/config');
define('VIEWS_PATH', BASE_PATH . '/views');

// Carregar configurações
require_once(CONFIG_PATH . '/config.php');