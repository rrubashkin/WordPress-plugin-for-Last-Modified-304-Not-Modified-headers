# WordPress plugin for Last Modified & 304 Not Modified headers
A simple wordpress plugin to set "last modified" and "if modified since" for single and archive pages.
Just archivate it, install and activate it.

Important:
  This plugin uses 'template_redirect' hook, so, it works only with pages, loaded using templates (e.g. It will not work for RSS feed).
  This plugin sets Last-Modified date for single and archive pages.
  For other pages it sets Last-Modified date for now (time()). 
