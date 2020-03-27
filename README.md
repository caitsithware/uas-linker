# uas-linker
Shortcode that embeds a link to UnityAssetStore on Wordpress

# install

1. Place ```uas-linker``` in ```[wordpress installation directory]/wp-content/plugins```
2. Log in to Wordpress admin page.
3. Open the Plugins page.
4. Enable "Unity Asset Store Linker".

# settings

1. Log in to Wordpress admin page.
2. Open "Plugins/Unity Asset Store Linker" page.
3. Set Affiliate ID (aid).
4. Save.

# Short codes

## [uas]

Embed links to assets

```
[ual id="Asset ID" type="link" class="custom_class" pubref="pubref"]TEXT[/uas]
[ual id="Asset ID" type="widget" class="custom_class" pubref="pubref" /]
```

* id(require) : Asset ID.
* type(optional) : link type. default widget.
    * link : anchor link. TEXT require.
    * icon : icon link.
    * banner : banner link.
    * banner-light : light theme banner link.
    * widget : widget link.
    * widget-light : light theme widget link.
* class(optional) : custom class
* pubref(optional) : pubref

## [uas_list]

Embed links to lists

```
[ual_link id="List ID" type="link" class="custom_class" pubref="pubref"]TEXT[/uas]
[ual_link id="List ID" type="widget" class="custom_class" pubref="pubref" /]
```

* id(require) : Asset ID.
* type(optional) : link type. default widget.
    * link : anchor link. TEXT require.
    * banner : banner link.
    * banner-light : light theme banner link.
    * widget : widget link.
    * widget-light : light theme widget link.
* class(optional) : custom class
* pubref(optional) : pubref
