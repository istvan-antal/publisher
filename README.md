Publisher
=========

Simple and easy to use CMS that focuses on extensibility and performance.

### Publish workflow

Traditional CMS-es will query the database and render the page when a user requests it.
This is an easy to implement model, unfortunately it doesn't scale.
Traditional CMS-es mitigate this by caching.

Publisher's approach is to just generate the 'cache' up front and serve everything as static content.