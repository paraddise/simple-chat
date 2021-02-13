Необходимо реализовать чат на фреймворке Yii2, включающий в себя:
1. Три роли: гость, пользователь, администратор.
   1.1 Гость - может просматривать все сообщения, но не может их писать.
   1.2 Пользователь - может просматривать сообщения и писать их в чат.
   1.3 Администратор
   - может писать и читать сообщения,
   - сообщение администратора выделяется в общем списке сообщений,
   - может просматривать таблицу пользователей, менять роли пользователей,
   - может помечать сообщения некорректными: некорректные сообщения видны в чате только администратору и не видны другим пользователям (они должны быть как-то выделены). У администратора есть таблица со списком некорректных сообщений, он может просматривать таблицу «некорректных сообщений», и может вернуть сообщение в чат, тогда оно становится доступно всем.
2. Чат не обязательно делать realtime, то есть новые сообщения могут добавляться при обновлении страницы в браузере.

Требований в верстке никаких нет, можно использовать стандартные возможности bootstrap, можно самому написать css.


# Как запустить
### Запустите все миграции
```shell 
php yii migrate --migrationPath=@yii/rbac/migrations
php yii migrate
```

### Запускаете сервер
```shell
php yii serve
cd vuejs && npm run serve
```

Проект пока не окончен