# Pushwoosh test app

Система по сбору и хранению статистики и API для ее получения.

### С чего начать.
```angular2html
$ git clone https://github.com/dryyyyy/booksApp.git
```
### Пример использования.
Для синхронизации базы с папкой где хранятся книги нужно вполнить команду:
```angular2html
php bin/console app:books-watcher {$folderName}
```
*где `{$folderName}` - это название дирректории в которой хранятся книги.*

### Пример API запроса.
`http://127.0.0.1:8000/api/war_and_peace`  
`http://127.0.0.1:8000/api/war_and_peace/pony`
*Задать путь до watch folder для API можно параметром в /config/services.yaml*

ответ:
```angular2html
{
book: "war_and_peace",
Number of unique words: 29335
}
```
```angular2html
{
Book: "war_and_peace",
Word: "pony",
Number of entries: 0
}
```