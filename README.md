# Meadow

Meadow is a WordPress Fields API.

WordPress has various content types (e.g. posts, comments, site settings).
A fields API gives you a declarative syntax to register metadata for any type.
UI in the `wp-admin/` application will be created (i.e. the WordPress admin interface)
for the user to edit this information. Third-party applications that interface
with a WordPress site (e.g. the WordPress iOS app) can look at this registered metadata
and create UI.

## Internal structure

Metadata is registered via [`meadow_register_meta()`](https://github.com/ericandrewlewis/meadow/blob/2951bbcda502d99fba7b9d60aeef3c2a3de950f8/library/functions.php#L13-L24).
This is creates a new instance of a meta object, e.g. [`Meadow_Postmeta`](https://github.com/ericandrewlewis/meadow/blob/2951bbcda502d99fba7b9d60aeef3c2a3de950f8/library/post.php#L8),
puts it [in a global store of all registered fields](https://github.com/ericandrewlewis/meadow/blob/2951bbcda502d99fba7b9d60aeef3c2a3de950f8/library/class-metadata-store.php#L47)
and creates a UI control, e.g. [`Meadow_Postmeta_UI_Control`](https://github.com/ericandrewlewis/meadow/blob/2951bbcda502d99fba7b9d60aeef3c2a3de950f8/library/post.php#L36).

A meta object (e.g. `Meadow_Postmeta`) is responsible for defining the way it should
handle data (sanitization, validation, and saving). When registered, instances are
places in a global store. This pattern allows consumers like the REST API to
get a structured description of the metadata.

The UI control handles registering native UI in the `wp-admin` application, as well
as saving routines as necessary.

## Todo

Multi-entry support

Should is protected meta affect whether to display meta or not? Probably.