# Example project name
This is an example project created for demoing how documentation should be written.

## Installation

Just copy this template into your projects `readme.md` file in the root directory. Edit this wiki page to see the markdown source.

## Requirements

- A project repository to put this in
- [WordPress](http://wordpress.org/)
- [Media Library Categories](http://wordpress.org/extend/plugins/media-library-categories/)

## Features

### Random Rotating Banners
On each page load, the site will grab a random image from that group and make a banner. These can be managed from the Wordpress admin.
    
** Settings: **

- Install [Media Library Categories Plugin](http://wordpress.org/extend/plugins/media-library-categories/)
- Create a media category named "banners"
- Add some images to this category

** Requirements: **

- Ensure banner images are at least 960x250px

## About the theme

** Based on: **
Bones Responsive Edition  
A Lightweight Wordpress Development Theme  
Developed by Eddie Machado  
<http://themble.com/bones>  
<eddie@themble.com>  

Bones is designed to make the life of developers easier. It's built
using HTML5 & has a strong semantic foundation. It was updated recently
using some of the HTML5 Boilerplate's recommended markup and setup.

This is the responsive version and uses a lot of the latest "best practices" 
for designing responsive sites. It uses the "Mobile First" approach 
so that the smallest screens always get the lightest load. 

## Stylesheets

LESS, you say, but isn't that complicated? Nope. It does take
a few minutes to wrap your head around, but it will all
be worth it. Need a quick intro? Here are a few quick reads:

<http://coding.smashingmagazine.com/2011/09/09/an-introduction-to-less-and-comparison-to-sass/>

I would HIGHLY RECOMMEND, if you are going to be working with
LESS, that you DO NOT parse it using the javascript file. It can
be a HUGE performance hit and it kind of defeats the whole reasoning
behind using LESS.

That being said, here are a few MUST HAVE TOOLS for working with the
new Responsive version of Bones: (You really only need one of them)

- [CodeKit](http://incident57.com/codekit/)
- [LESS App](http://incident57.com/less/)
- [LESS Coda Plugin](http://incident57.com/coda/)
- [SimpLESS](http://wearekiss.com/simpless) (Windows Users)

These applications compile LESS into minified, valid CSS. This
way you can keep your production LESS file easy to read and your
CSS minified and speedy. Simply set the output to the
library/css folder and you are all set. It's a thing of beauty.