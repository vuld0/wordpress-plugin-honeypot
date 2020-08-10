# Wordpress plugin Honeypot [Under Development]

This plugin is made to include the functionality of honeypot and it's behaviour. 


### Prerequisites

1. Wordpress Installed
2. Contact-form-7 must be installed

### Installing

Install the wordpress and add the zip file of this repository into it.

To install the plugin you need to go for Wordpress admin area and visit 

```
Plugin >> Add new >> Upload plugin 
```

Choose the zip file and do not forget to activate the plugin.


### Deployment

#### Login Attempts Logs
To use the login attempts logs feature we need to perform some steps before:

1. After activating the plugin goto

```
Settings >> Honeypot >> and check the log name to be saved.
```
2. Log out as a user and try giving wrong credentials to log in.
3. Then go to the plugin directory and open the log, you can get the timestamp, username, password.

#### Contact form hidden field
1. Use the contact-form-7 plugin to create a form.
2. The form can then have a will have a form-tag-generator named "Honeypot".
3. That includes a form which have several details to be filled.(Just remember the tag would be hidden)
4. So when someone tries to fill the hidden field there is a validation error shown and the form is not sent further.
5. While the form throws a validation error there is a variable which turns into "true" indicating that someone is trying to hack in.

#### Change the wp-login url
1. In order to change the login url for the admin, go to

```
Settings >> Permalinks >> Change wp-admin login url >> Custom the url you want >> Save changes
```

2. Log out and test the site

### Honeypots

1. Login attempts logs
2. Hidden form field (contact form-7)
3. Change the wp-login admin url


### Built with

* [contact-form-7-honeypot](https://github.com/nocean/cf7-honeypot) - The codebase used
* [change wp-login](https://wordpress.org/plugins/change-wp-admin-login/)

## Authors

* **Chirag Bablani** - *Initial work* - [vuld0](https://github.com/vuld0)

