# spell-checker
A wordpress plugin to check spellings.

-------------------------------------

## Dependencies

## 1. LAMP Stack
Just install apache, mysql and php stack in your linux machine.  
Also configure them properly.

## 2. Wordpress
Download latest wordpress tarball and extract it into `~/public_html/`

## 3. Django
Install django using pip

```bash
pip install django
```

## 4. nspell
We have used [nspell](https://github.com/tnagorra/nspell) by **tnagorra**.  
By default, the nspell project is already packaged inside a django working environment here.  
That is, **spellchecker**, being a django project, comes with **nspell**.

-------------------------------------

# Usage

## start apache server

## plugin directory
Put the plugin directory (this repo) in side `~/public_html/wordpress/wp-content/plugins/`

## start django server
The **spellchecker** folder is a django project. So, start the server as:

```bash
python manage.py runserver
```

## launch wordpress
In your browser, launch the wordpress with url : `localhost/~username/wordpress/`

-------------------------------------

# Special Thanks 
Special Thanks to [tnagorra](https://github.com/tnagorra/) for the awesome nepali spell checker.  
**Cheers**... **Be awesome**...
