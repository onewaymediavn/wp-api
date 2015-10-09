# OVERVIEW
This (WP API - developed by Oneway Media VN) is an attempt to use wordpress as an admin system for text heavily - content website.

	Version: 0.0.1

# SETUP
- Document root: **domain.me/**
- Create **/core** and **/api**.
- Install *Worpress (https://wordpress.org/)* to **/core**.
- Load the API to **/api**. Set it up accordingly.

# WORDPRESS
- Create a MySQL database.
- Load down *Worpress (https://wordpress.org/download/)*.
- Extract. Edit **wp-config.php** for DB.
- Add **Bare** theme.
- Plugins:
	- Install **ACF** (For hidden-protected fields, use *ACF Location Rules* or use https://github.com/folbert/acf-hidden).
	- Install **WP Clean Up** plugin.
	- Install **WP REST API** plugin.
	- Intall **Enhanced Media Library** plugin.
	- Install **WP Admin UI Customize** or **AG Custom Admin** plugin.
	- Install **Admin Columns** plugin.
	- Install **Adminer** (+ https://github.com/pappu687/adminer-theme) plugin.
	- Install **Fancy Admin UI** or **Slate Admin Theme** or ****.
	- Install **Columns** plugin.

- Edit **functions.php** for **custom post types** and **custom taxonomies**. Pattern:
	+ For post type, singular name; **[post_type]** (Ex: movie, flower, book, ...).
	+ For taxonomy; **[post\_type]_[taxonomy]** (Ex: movie_genre, flower_rose, book_fiction, ...
- Change theme to **Bare**, remove other themes.
- Use built-in **custom fields** or **ACF** plugin to create custom meta boxes. Pattern:
	+ Built-in custom fields; **[post\_type]__[field_name]** (Ex: post\__more, post\__star, ...).
	+ ACF plugin; **acf\_\_[post\_type]\_\_[field\_name]** (Ex: acf\__movie\__trailer, acf\__book\__publisher, ...).
- Do some clean-up with **WP Clean Up**.

# ROUTES
https://domain.me/api

## General
### /
- **GET**
*Homepage.*
	- Request params: **N/A**
	- Response: **Some greeting or Site infomation ...**

## Post

### /post
- **GET**
*Dead-end.*

    - Request params: **N/A**
    - Response: Redirect to **/**
- **POST**: *N/A*

### /post-term
- **GET**: *N/A*
- **POST**
*Add | Edit | Remove a term.*

	- Request params:
		- **id | slug** (required): ID or Slug of term to be effected
		- ...
	- Response:
		- Effected term ID
		- Errors

### /post-term/:params
- **GET**
*Get a list of terms belong to **post_type** and **taxonomy**. Or get the detail of a term by its **id** or **slug**.*
    - Request params:
    	- **post_type** (required): Type of post (ex. post, movie, ...)
    	- **taxonomy** (required): Taxonomy (ex. category, post_tag, movie_genre, ...)
    	- **id | slug** (optional): ID or Slug of a term
    - Response:
    	- Array of terms belong to **post_type** and **taxonomy**
    	- Array of term detail belong to **post_type** and **taxonomy** and **id | slug**
    	- Errors
- **POST**: *N/A*

### /post-post
- **GET**: *N/A*
- **POST**
*Add, Update a post.*
	- Request params:
		- ++ADD++:
			- All infomation that a post has (key=value).
		- ++UPDATE++:
			- **id** (required): ID of a post.
			- **post_type** (required): type of a post.
			- **key=value** : To be updated.
	- Response:
		- Effected post ID
		- Errors

### /post-post/:params
- **GET**
*Get a list of posts belong to specific scenarios. Or get the detail of a post by its **id** or **slug**.*
	- Request params:
		- **get_posts()** args: https://codex.wordpress.org/Template_Tags/get_posts
		- **id | slug** (optional): ID or Slug of a post
	- Response:
		- Array of posts according to **args**
		- Array of post detail belong to **id | slug**
		- Errors
- **POST**: *N/A*

## Comment

### /comment
- **GET**: *N/A*
- **POST**
*Add, Update a comment.*
	- Request params:
		- All infomation that a comment has (key=value).
	- Response:
		- Effected comment ID
		- Errors

### /comment/:params
*Get a list of comments belong to specific scenarios. Or get the detail of a comment by its **id**.*
- **GET**
	- Request params:
		- **get_comments()** args: https://codex.wordpress.org/Function_Reference/get_comments
		- **id** (optional): ID of a comment
	- Response:
		- Array of comments according to **args**
		- Array of comment detail belong to **id**
		- Errors
- **POST**: *N/A*


# Models
## Post.php

	class Post {}

- private **$_CONFIG**

- private **_get_meta($posts = null)**

- private **_sanitize($posts = null)**

## Comment.php

## Media.php

## User.php