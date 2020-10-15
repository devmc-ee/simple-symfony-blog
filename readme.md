# Simple Symfony Blog
Symfone v.5 + MySQL (developed on MariaDB (ver. 10.4.11-MariaDB))

## Installation
1 . Clone repository
```bash
git clone https://github.com/devmc-ee/simple-symfony-blog.git
```
2 . install dependencies

```
composer install
``` 
3 . set database credationals in .env file, for example
```
DATABASE_URL=mysql://root:pass@127.0.0.1:3306/symfony_blog?serverVersion=mariadb-10.4.11
``` 

4 . Make db migrations
````
php bin/console doctrine:migrations:migrate
````
On this stage the app should be able to work from /public folder

5 . It is necessary to create the first user for using the app
THere are few ways. The most simple is to load the fixture.

````
$ php bin/console doctrine:fixtures:load
````

However, it might require to install doctrine-fixtures-bundle
````
composer require --dev doctrine/doctrine-fixtures-bundle
````
Then login with the following credentials:
`````
u: dev@milicity.eu
p: SimpleSymfonyBlog2020
``````

---
Another way, is to close the authentication until the first user is created via admin panel. In order 
do that the following string should be commented in the config/packages/security.yaml
````
#- { path: ^/admin, roles: ROLE_ADMIN }
````

## Using blog
* Login to admin panel, 
* create first post (only authorized user can create posts)
    * Add title
    * Add content
    * Add image
    
    Save it.
    
Go to home page (blog). The created post must appear in the list. 
Open it (link on the title). 
Under the content there is a comment form. It works in async way without page reloading.
But it might fell back to the regular http processing of form.

Every time new comment is added, all new comments are loaded as well. New comments are
those, that were added by all user after the current user has opened the post page. 
Adding comment doesn't require an authentication in the system. 

To edit post: login, and open admin page - posts -> select and open post. After all changes done
save the post.
To delete post: click delete. All related comments will be deleted too.

Comment can be hidden or deleted. Hidden comment can be seen only in admin panel.
To view all comments login and open admin panel -> comments. 
To see all comments related to a single post, open edit page of the post.

Use search for finding post by its content or title