## General

The Moodle plugin **mod_unilabel** enables you to include some nice formated text on the course- or frontpage.
There are 5 different content types included (extendable sub plugins):
- Simple text
- Carousel
- Collapsed text
- Course teaser
- Topic teaser

## Installation

Copy all files into the folder **mod/unilabel** inside your moodle installation.
Run the installation process in moodle.
You can find more detailes to the installation process here: https://docs.moodle.org/35/en/Installing_plugins#Installing_a_plugin

## Usage

The configuration consists of two steps (except the **Simple text** type).
1. The creation of a new instance by using the activity chooser.
1. The configuration of the content depending on the content type you chose in the first step.

## Description of the content types

### Simple text

This content type just show the label as you already know

### Carousel

In this content type you can define a series of images.
Each image is shown in a slide show.
You can also define a caption to each image that is show inside the slide item.
For each image you can define a url what makes the image to a clickable button.
The carousel is by default responsive to different screen sizes.
To optimize the responsivity to each of the images you can assigne a mobile optimized image.
This mobile image is shown on small devices smaller than 768 px.

### Grid

In this content type you can define a series of images and contents/urls.
The grid is by default responsive to different screen sizes.
You can set a column count for the grid to define how many tiles are shown in one row.
On smaller screens the column count is reduced by a half of the defined count.
On mobile devices like cellphones only one column is shown.
To optimize the responsivity to each of the images you can assign a mobile optimized image.
This mobile image is shown on small devices smaller than 768 px.
If you have defined a content for a slide the content is shown as a modal dialog if you click the slide.
If you have defined a url for a slide on click the url is loaded.

### Collapsed text

This content type offers you two options:
1. a folded content
1. a modal dialog containing the content.
Both types can be used with or without animation

### Course teaser

Mainly intended to show on the frontpage it shows the titles and images of selected courses.
The presentation can be a carousel or a grid.
Each Item is a clickable button that brings the user to the related course.

### Topic teaser

Mainly intended to show on the frontpage it shows the description of topics of a selected course.
The topics will be shown as carousel or as grid.
If you click on such a shown topic a modal dialog shows the topic content.
