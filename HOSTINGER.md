# Poc Poc Gourmet — Deploy Hostinger

Site estático + painel admin (`/admin`) + API PHP/MySQL (`/api`).

## Login do painel

- URL: `https://SEU-DOMINIO/admin/login.html`
- E-mail: `admin@pocpocgourmet.com.br`
- Senha inicial: `pocpoc123` (troque depois)

## Passo a passo Hostinger

### 1. Criar banco MySQL
1. hPanel → **Bancos de dados MySQL**
2. Crie banco + usuário + senha
3. Anote: host (geralmente `localhost`), nome do banco, usuário, senha

### 2. Importar o SQL
1. Abra **phpMyAdmin**
2. Selecione o banco
3. **Importar** o arquivo `api/schema.sql`
4. Confirme que as tabelas `admin_users` e `store_state` existem

### 3. Enviar os arquivos do site
Envie **toda a pasta do projeto** para `public_html` (ou a pasta do domínio), incluindo:
- `index.html`, `style.css`, `script.js`
- `admin/`
- `api/`
- `js/`, `products/`, `css/`

### 4. Configurar a API (obrigatório para pedidos no painel)

No File Manager da Hostinger, edite `api/config.php` e preencha o MySQL real:

```php
'db_host' => 'localhost',
'db_name' => 'uXXXX_pocpoc',   // nome do banco no hPanel
'db_user' => 'uXXXX_pocpoc',   // usuário do banco
'db_pass' => 'SUA_SENHA',      // senha do banco
```

Sem isso, o site abre, mas **pedidos NÃO aparecem no painel** (`api/ping.php` dá erro).

### 5. Rodar o install (uma vez)
Abra:

`https://pocpocgourmet.seuproximosite.com.br/api/install.php`

Depois **apague** `api/install.php`.

### 6. Testar
1. `https://SEU-DOMINIO/api/ping.php` → deve retornar `{"ok":true,...}`
2. `https://SEU-DOMINIO/admin/login.html` → entrar no painel
3. No painel, altere um preço e confira se o site atualiza

## Arquivos importantes

| Arquivo | Função |
|---------|--------|
| `api/schema.sql` | Tabelas MySQL |
| `api/config.php` | Credenciais (não versionar senha real) |
| `api/data.php` | API principal (login, pedidos, catálogo) |
| `api/upload.php` | Upload de fotos no admin |
| `api/photo.php` | Serve fotos |
| `api/geocode.php` | Proxy de geocode |
| `api/install.php` | Seed inicial (apagar após uso) |
| `admin/login.html` | Login do painel |
| `admin/index.html` | Painel (pedidos, produtos, estoque…) |

## Problemas comuns

- **503 / falha MySQL:** confira usuário/senha/banco no `config.php`
- **Login não entra:** rode `install.php` de novo ou confira e-mail/senha
- **Pedido não aparece no painel:** teste `api/ping.php`; o site precisa da API online
- **Foto não sobe:** pasta `api/uploads` precisa de permissão de escrita (755/775)
- **catalog.json não grava:** permissão de escrita na raiz do site
