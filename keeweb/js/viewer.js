/**
 * Nextcloud - keeweb
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Jonne Haß <me@jhass.eu>
 * @copyright Jonne Haß 2016
 */

// Inspired by documents app

(function ($, OC) {
  $(document).ready(function() {
    if ( typeof OCA !== 'undefined'
      && typeof OCA.Files !== 'undefined'
      && typeof OCA.Files.fileActions !== 'undefined'
    ) {
      OCA.Files.fileActions.register(
        'x-application/kdbx',
        'Open',
        OC.PERMISSION_UPDATE,
        OC.imagePath('core', 'actions/edit'),
        function (fileName, context) {
          var dir = context.dir;
          if (dir === '/') {
            dir = '';
          }

          window.location = '/index.php/apps/keeweb/?open=' + dir + '/' + fileName;
        },
        t('keeweb', 'Open')
      );

      OCA.Files.fileActions.setDefault('x-application/kdbx', 'Open');
    }
  });
})(jQuery, OC);
