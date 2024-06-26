# PinPinPin

A simple application wirtten in PHP and JavaScript that uses [Leaflet](https://leafletjs.com) to show geotagged photos on the OpenStreetMap map.

# Prerequisites

- Web server (tested with Apache)
- PHP 7.x or higher
- PHP-GD and PHP-EXIF modules

# Installation

1. Fetch the latest code using Git with the `https://github.com/dmpop/pinpinpin.git` command. Alternatively, download the code as a ZIP archive from the project's [GitHub repository](https://github.com/dmpop/pinpinpin).
2. In the resulting _pinpinpin_ directory, open the _index.php_ file for editing. Specify the correct path to the directory containing photos (the `$photo_dir` variable) and the desired file extensions (the `$ext` variable). Save the changes. This step is optional.
3. Move the resulting _pinpinpin_ directory to the document root of the web server.
4. Put photos into the specified photo directory.

## Limitations

PinPinPin doesn't support subdirectories.

# Using PinPinPin

- The map automatically centers on the most recent photo.
- Click on a marker to see a photo preview in a popup.
- Click on the preview image to open the full-resolution original in a new browser window.
- Use **+** and **-** buttons or the mouse to zoom in and out.

The [Linux Photography](https://gumroad.com/l/linux-photography) book provides detailed instructions on installing and using PinPinPin. Get your copy at [Google Play Store](https://play.google.com/store/books/details/Dmitri_Popov_Linux_Photography?id=cO70CwAAQBAJ) or [Gumroad](https://gumroad.com/l/linux-photography).

<img src="https://cameracode.coffee/uploads/linux-photography.png" title="Linux Photography" width="300"/>

## Problems?

Please report bugs and issues in the [Issues](https://github.com/dmpop/pinpinpin/issues) section.

## Contribute

If you've found a bug or have a suggestion for improvement, open an issue in the [Issues](https://github.com/dmpop/pinpinpin/issues) section.

To add a new feature or fix issues yourself, follow the following steps.

1. Fork the project's repository.
2. Create a feature branch using the `git checkout -b new-feature` command.
3. Add your new feature or fix bugs and run the `git commit -am 'Add a new feature'` command to commit changes.
4. Push changes using the `git push origin new-feature` command.
5. Submit a pull request.

## Author

Dmitri Popov [dmpop@cameracode.coffee](mailto:dmpop@cameracode.coffee)

## License

The [GNU General Public License version 3](http://www.gnu.org/licenses/gpl-3.0.en.html)

