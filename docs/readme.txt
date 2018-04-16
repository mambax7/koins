README FIRST
-----------------------

Koins module can create module icons as you select parts.
Module developers without special image editing software can also easily create icons.
By adding material images to become parts, it is also possible to increase variations of icons that can be created.

How to add material parts

Koins can freely add more visual assets.

Storage location of visual parts

plates	/modules/koins/assets/images/plates
icons	/modules/koins/assets/images/icons
font	/modules/koins/assets/images/letters

Logo plate:

Add png image of 128x28. Please make it an image that makes it easy for marks to be synthesized on the left 28 x 28 area.

Icons:

Add a png image of 28x28. The color of the figure is basically white. Please make the background color transparent.

Font

Add an image with a height of 7px. Font image maps correspondence relation to setupLettersImg method of Koins\IconGenerator class.
