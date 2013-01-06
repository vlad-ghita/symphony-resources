# Symphony 2 bundled with Resources

This is a version of Symphony 2.3.1 bundled with the [Resources][1] utilities.

It is a working example of the approach explained in this [article][2].

The goal in this ensemble was to add a Newsletter box on all pages and to
illustrate the new ways of adding datasources & events to pages and widgets.

Browse the code for Home page, Master widget and Newsletter widget to understand
what's going on.

In short:

- `/workspace/pages/home/config/resources.widgets.xml` holds the relation from `Home` page to `Master` widget
- `/workspace/widgets/master/config/resources.datasources.xml` holds the relations from `Master` widget to common datasources required for all pages
- `/workspace/widgets/master/config/resources.widgets.xml` holds the relation from `Master` widget to `Newsletter` widget
- `/workspace/widgets/newsletter/config/resources.events.xml` holds the relation from `Newsletter` widget to `Mail Chimp` event

The rest is XSLT.

NB: I added some `hooks` in `master.xsl`. I suggest you have a look at them.

[1]: http://gihub.com/vlad-ghita
[2]: http://gihub.com/vlad-ghita
