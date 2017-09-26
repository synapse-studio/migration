# Migration Starter

# 1. Установка:
## 1.1 Добавляем 2 модуля, они в зависимостях:
 * `composer require drupal/migrate_tools`
 * `composer require drupal/migrate_plus`

## 1.2 Включаем и проверяем работоспособность:
  * `drush en migration` - вклю
  * `drush ms` - статус текущих миграций в консоли.
  * migrate_tools в веб интерфейсе `/admin/structure/migrate/manage/import/migrations`,
  * `/migration/list` страница модуля

# 2. Работаем:

## 2.1 Работа с миграцими
  * `drush mi migration_node_country` - импорт
  * `drush mi migration_node_country --update` обновить, если что-то поменялось в выгрузке
  * `drush mr migration_node_country` - снести если что-то пошло не так
  * `drush mrs migration_node_country` - сбросить стаутс если что-то сломалось
  * `drush dre -y migration` - переустановить модуль, для переприменения конфигов
  * `drush mi --group=import --update` - загрузить всю группу импорт

# 3. Картинки
## 3.1 Интерфейс migrate_tools
<img src="https://github.com/politsin/help/blob/master/migration/migration-group.png?raw=true">
## 3.2 Интерфейс модуля
<img src="https://github.com/politsin/help/blob/master/migration/migration-exec.png?raw=true">
