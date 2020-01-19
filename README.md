# Keeweb for Nextcloud

[Nextcloud](https://nextcloud.com/) is an open source, self-hosted file sync and share and communication app platform.
[Keeweb](https://keeweb.info/) is a web application for working with databases of the Keepass password manager.

This integrates the two with each other. Just click on a \*.kdbx file in Your Nextcloud to open it.

![demo](https://cloud.aeshna.de/u/mrzyx/keeweb.gif)

## Install

1. Go to the releases page, download the latest version.
1. Extract it to `your_nextcloud/apps`, for example with `tar xvf keeweb-0.6.0.tar.gz -C /path/to/your/nextcloud/apps`.
1. Go to "Apps" and then "Not enabled", scroll down to "Experimental" and enable it.

To update to a new version, simply repeat these steps.

## Development setup

```
git clone https://github.com/jhass/nextcloud-keeweb.git
cd nextcloud-keeweb

# You can skip this if you only want to build the appstore or source packages
git clone -b stable18 https://github.com/nextcloud/server.git nextcloud
ln -sf "$(pwd)/keeweb" nextcloud/apps
cd nextcloud && git submodule update --init && cd -

git clone https://github.com/keeweb/keeweb.git keeweb-source

# Install npm -- this might require a more recent npm than your distro's, see https://github.com/nodesource/distributions on how to deploy it
# Once npm is installed, install grunt and bower; instead of relying on your distro's, you can do
sudo npm install -g grunt

# Build nextcloud-keeweb
bin/build keeweb_version # use "bin/build develop" for the keeweb development branch

# Finally, run the nextcloud server
bin/server
# Alternatively, you can build the app package to test on a running nextcloud by doing
cd keeweb
make dist
# The package that can be installed in the nextcloud app folder is here: ./build/artifacts/appstore/keeweb.tar.gz
```

## Contributing

1. Fork the repository and clone your fork.
1. Create a new branch.
1. Commit your patch.
1. Push the branch and open a pull request.
