/**
 * Nextcloud - keeweb
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Holger Hees <holger.hees@gmail.com>
 * @author Jonne Haß <me@jhass.eu>
 * @copyright Jonne Haß 2016
 */

(function ($, OC) {
  // cleanup and prevent duplicates from localStorage / history
  if (localStorage.fileInfo) {
    var fileInfos = JSON.parse(localStorage.fileInfo),
        paths = [],
        open, path;

    // Get any open parameter
    open = window.location.search.match(/open=([^&]+)/);
    if (open) {
      open = decodeURIComponent(open[1]);
    }

    fileInfos = fileInfos.filter(function(fileInfo) {
      // Only webdav entries that we added
      if (fileInfo.path && fileInfo.path.match(/remote\.php\/webdav\//)) {
        // extract actual file path
        path = fileInfo.path.match(/remote\.php\/webdav(.+)\?requesttoken/)[1];

        // Is the same as the open parameter, drop as it will create a new one
        if (path === open) {
          return false;
        }

        // Duplicate entry, we've seen it before
        if ($.inArray(path, paths) !== -1) {
          return false;
        }

        paths.push(path);
      }

      return true;
    });

    fileInfos = fileInfos.map(function(fileInfo) {
      fileInfo.path = fileInfo.path.replace(/requesttoken=[^&]+/,"requesttoken=" + encodeURIComponent(OC.requestToken));
      return fileInfo;
    });

    localStorage.fileInfo = JSON.stringify(fileInfos);
  }
})(jQuery, OC);
