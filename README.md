# ThinkPHP 8 Deployment Notes

## Required files
- `composer.json`
- `think`
- `public/index.php`
- `route/app.php`
- `config/app.php`
- `config/database.php`
- `app/controller/*`
- `app/common/*`

## Cloud runtime env
Use these environment variables in cloud container:
- `MYSQL_HOST`
- `MYSQL_PORT`
- `MYSQL_DATABASE`
- `MYSQL_USERNAME`
- `MYSQL_PASSWORD`
- `WX_APPID`
- `WX_SECRET`

## Cloud container call
Frontend can call the service via `wx.cloud.callContainer` without a public domain.
