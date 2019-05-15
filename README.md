# Keeweb for Nextcloud

[Nextcloud](https://nextcloud.com/) is an open source, self-hosted file sync and share and communication app platform.
[Keeweb](https://keeweb.info/) is a web application for working with databases of the Keepass password manager.

This integrates the two with each other. Just click on a \*.kdbx file in Your Nextcloud to open it.

![demo](https://cloud.aeshna.de/u/mrzyx/keeweb.gif)

## Install

1. Go to the releases page, download the latest version.
1. Extract it to `your_nextcloud/apps`, for example with `tar xvf keeweb-0.5.1.tar.gz -C /path/to/your/nextcloud/apps`.
1. Go to "Apps" and then "Not enabled", scroll down to "Experimental" and enable it.

To update to a new version, simply repeat these steps.

## Mimetype detection

Unfortunately, apps can't declare new mimetypes on the fly. To make
Keeweb work properly, you need to add a new mimetype in the
`mimetypemapping.json` file (also see the Nextcloud manual at
https://docs.nextcloud.com/server/14/admin_manual/configuration_mimetypes/index.html).

To proceed, create the file `/config/mimetypemapping.json` (in the `config/` folder at
Nextcloud’s root directory; the file should be stored next to the `config.php`
file) or modify the existing one. Make sure, it contains at least the following
lines:

```
{
  "kdbx": ["application/x-kdbx"]
}
```

After that, run the following command in the root directory of Nextcloud on the server
(if needed, replace `www-data` with the actual user which is used by the webserver):

    sudo -u www-data php occ files:scan --all

## Development setup

```
git clone https://github.com/jhass/nextcloud-keeweb.git
cd nextcloud-keeweb

# You can skip this if you only want to build the appstore or source packages
git clone -b stable15 https://github.com/nextcloud/server.git nextcloud
ln -sf "$(pwd)/keeweb" nextcloud/apps
cd nextcloud && git submodule update --init && cd -

git clone https://github.com/keeweb/keeweb.git keeweb-source

# Install npm -- this might require a more recent npm than your distro's, see https://github.com/nodesource/distributions on how to deploy it
# Once npm is installed, install grunt and bower; instead of relying on your distro's, you can do
sudo npm install -g grunt

# Build nextcloud-keeweb
bin/build keeweb_branch # Requires 1.4 or later; use "bin/build develop" for the keeweb development branch

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
