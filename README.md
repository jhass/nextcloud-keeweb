# Keeweb for Nextcloud

[Nextcloud](https://nextcloud.com/) is an open source, self-hosted file sync and share and communication app platform.
[Keeweb](https://keeweb.info/) is a web application for working with databases of the Keepass password manager.

This integrates the two with each other.

![demo](https://cloud.aeshna.de/u/mrzyx/keeweb.gif)

## Install

1. Go to the releases page, download the latest version.
1. Extract it to `your_nextcloud/apps`, for example with `tar xvf keeweb-0.2.2.tar.gz -C /path/to/your/nextcloud/apps`.
1. Go to "Apps" and then "Not enabled", scroll down to "Experimental" and enable it.

To update to a new version, simply repeat these steps.

## Development setup

```
git clone https://github.com/jhass/nextcloud-keeweb.git
cd nextcloud-keewb

git clone -b stable9 https://github.com/nextcloud/server.git nextcloud
ln -sf "$(pwd)/keeweb" nextcloud/apps

git clone https://github.com/keeweb/keeweb.git keeweb-source
git clone https://github.com/nextcloud/ncdev.git

# Install grunt, npm, bower

bin/build keeweb_version # Requires 1.3 or later
bin/server
```

## Contributing

1. Fork the repository and clone your fork.
1. Create a new branch.
1. Commit your patch.
1. Push the branch and open a pull request.
