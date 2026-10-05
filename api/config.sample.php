<?php
/**
 * Copie este arquivo para config.php e preencha com os dados do hPanel Hostinger.
 * Nunca publique config.php com senha real no GitHub público.
 */
return [
  // Banco (hPanel → Bancos de dados MySQL)
  'db_host' => 'localhost',
  'db_name' => 'u000000000_pocpoc',      // ex.: u123456789_pocpoc
  'db_user' => 'u000000000_pocpoc',      // ex.: u123456789_pocpoc
  'db_pass' => 'SUA_SENHA_DO_BANCO',
  'db_charset' => 'utf8mb4',

  // Admin (mesmo e-mail/senha do login do painel)
  'admin_email' => 'admin@pocpocgourmet.com.br',
  'admin_password' => 'pocpoc123', // usada só no install.php; depois troque

  // Upload de fotos
  'upload_dir' => __DIR__ . '/uploads',
  'upload_url_path' => 'api/uploads', // relativo à raiz do site
  'max_upload_bytes' => 2_500_000,

  // CORS (deixe * no começo; depois restrinja ao domínio)
  'cors_origin' => '*',
];
