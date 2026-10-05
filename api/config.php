<?php
/**
 * Preencha com os dados reais da Hostinger e faça upload.
 * Modelo: config.sample.php
 */
return [
  'db_host' => 'localhost',
  'db_name' => 'u000000000_pocpoc',
  'db_user' => 'u000000000_pocpoc',
  'db_pass' => 'SUA_SENHA_DO_BANCO',
  'db_charset' => 'utf8mb4',

  'admin_email' => 'admin@pocpocgourmet.com.br',
  'admin_password' => 'pocpoc123',

  'upload_dir' => __DIR__ . '/uploads',
  'upload_url_path' => 'api/uploads',
  'max_upload_bytes' => 2_500_000,

  'cors_origin' => '*',
];
