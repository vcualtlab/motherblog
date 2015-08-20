# ALT Lab Mother Blog

Requires at least: 3.0.1
Tested up to: 4.2.2

FeedWordPress extension for simple front end syndication.

## Description

Simplifies the MotherBlog registration process for both faculty and students through a single form.

What does it do exactly?

If user is logged in and has blogs on your network:

* Create select form of blogs owned by user on your network
* Create the specified category on the child blog chosen by the user
* Add the user to the mother blog with the role subscriber
* Determine the URL for that category's RSS feed
* Add RSS feed to FeedWordPress.

If user is not logged in and has blogs on your network:

* Prompt user to login

If user does not have blogs on your network but would like one:

* Provide signup link.

If user does not have blogs on your network, does not want one but has their own RSS feed:

* Provide field for submission of custom RSS feed
* Add RSS feed to FeedWordPress

If user does not have blogs on your network, does not want one, and does not have their own RSS feed:

* Tell them they need a blog somewhere and prompt them to signup on your network

## Installation

1. Upload `altlab-motherblog.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

## Usage

1. Add a url in the plugin options for user signup to your network.
1. Add the shortcode `[altlab-motherblog category="Some Category"]` to a page.
1. You can also add subcategories `[altlab-motherblog category="Some Category" sub_categories = "cat1, cat2, cat3, cat4"]`
1. You can also gather comments by adding `get_comments="true"`

## Frequently Asked Questions
1. People are signing up. Why don't I see sites showing up under Syndication?
Go to Links in your Dashboard. You should see the sites there. You may need to add them to the Contributor category. Usually this only needs to be done once.


## Changelog

v1.2
 * Prevent duplicate signup
 * Prevent current user role change on signup
 * Add comments feed signup

v1.1

 * Added support for auto creation of sub categories
 * refactoring
 * bug fixes
 * Options Page improvements