# Meadow

Meadow is a WordPress Fields API. WordPress has various content types (e.g. posts,
comments, site settings), a fields API gives you a declarative syntax to register
metadata. This allows the `wp-admin/` application (i.e. the WordPress admin interface)
to intelligently scaffold UI for the user to edit this information. This also
allows third-party applications that interface with a WordPress site (e.g. the WordPress iOS app)
to read this data in a structured manner and scaffold UI in a similar manner.

## Internal structure

Metadata is registered via [`meadow_register_meta()`](https://github.com/ericandrewlewis/meadow/blob/d4b64edfdf05192599fff5fba4af13dd5dd88e49/library/functions.php#L13-L24).
This is creates a new instance of a meta object, e.g.
also stores it [in a global store](https://github.com/ericandrewlewis/meadow/blob/d4b64edfdf05192599fff5fba4af13dd5dd88e49/library/class-metadata-store.php#L47)
so structured data about fields could be described to the REST API.

## Todo

Multi-entry support

Should is protected meta affect whether to display meta or not? Probably.