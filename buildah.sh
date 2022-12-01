#!/usr/bin/env bash

container=$(buildah from opensuse/leap)
buildah run $container zypper update
buildah run $container zypper -n install php php-gd php-exif
buildah copy $container . /usr/src/pinpinpin/
buildah config --workingdir /usr/src/pinpinpin $container
buildah config --port 8000 $container
buildah config --cmd "php -S 0.0.0.0:8000" $container
buildah config --label description="PinPinPin container image" $container
buildah config --label maintainer="dmpop@tokyoma.de" $container
buildah config --label version="0.1" $container
buildah commit --squash $container pinpinpin
buildah rm $container