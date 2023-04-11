# dev.bx

Для установки модуля необходимо:
</br>

1) Клонировать репозиторий в корень проекта с фреймворком
2) Установить модуль при помощи админ панели
3) Установить шаблон Liblab Template в соответствующем пункте админ панели
4) Добавить 'litlab.php' в секцию 'routing' в файле /bitrix/.settings.php

```php
   'routing' => ['value' =>[
        'config' => ['litlab.php']
   ]]
```

5) Добавить следующие строки в файл /index.php

```php
<?php
require_once __DIR__ . '/bitrix/routing_index.php';
```

6) Заменить строки в файле /.htaccess

```text
-RewriteCond %{REQUEST_FILENAME} !/bitrix/urlrewrite.php$
-RewriteRule ^(.*)$ /bitrix/urlrewrite.php [L]

+RewriteCond %{REQUEST_FILENAME} !/index.php$
+RewriteRule ^(.*)$ /index.php [L]
```