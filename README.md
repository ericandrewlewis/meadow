# Meadow

Meadow is a WordPress Fields API.

WordPress has various content types (e.g. posts, comments, site settings).
A fields API gives you a declarative syntax to register metadata for any type.
UI in the `/wp-admin/` interface will be created for users to edit meta.
Third-party applications that interface with a WordPress site (e.g. the WordPress iOS app) 
can inspect registered metadata and create UI accordingly.

## Internal structure

A **meta object** (e.g. [`Meadow_Postmeta`](https://github.com/ericandrewlewis/meadow/blob/2951bbcda502d99fba7b9d60aeef3c2a3de950f8/library/post.php#L8))
is an encapsulated piece of metadata. It's defines data-related requirements for metadata,
e.g. sanitization, authorization callbacks, etc. Utilizing existing APIs is preferred
over reinventing the world.

Meta objects are put on a global store so they can be described to third-parties, like
the REST API.

A **UI control** (e.g. [`Meadow_Postmeta_UI_Control`](https://github.com/ericandrewlewis/meadow/blob/2951bbcda502d99fba7b9d60aeef3c2a3de950f8/library/post.php#L30))
configures pieces of UI (think a form field) for a user to edit a meta object inside
the `/wp-admin/` interface. A control must be bound to a meta object.

A **section** is a collection of UI controls (think a metabox or a Settings API section)
that fit into a specific place in an admin screen.

Metadata is registered via [`meadow_register_meta()`](https://github.com/ericandrewlewis/meadow/blob/2951bbcda502d99fba7b9d60aeef3c2a3de950f8/library/functions.php#L13-L24).

## Todo

Multi-entry support

Should is protected meta affect whether to display meta or not? Probably.