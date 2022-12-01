FROM opensuse/leap
LABEL maintainer="dmpop@tokyoma.de"
LABEL version="0.1"
LABEL description="PinPinPin container image"
RUN zypper update
RUN zypper -n install php php-gd php-exif
COPY . /usr/src/pinpinpin
WORKDIR /usr/src/pinpinpin
EXPOSE 8000
CMD [ "php", "-S", "0.0.0.0:8000" ]