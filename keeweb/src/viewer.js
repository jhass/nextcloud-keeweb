import {registerFileAction, Permission, DefaultType} from '@nextcloud/files'
import {generateUrl} from '@nextcloud/router'

const fileAction = {
    id: 'keeweb',
    order: 1,
    default: DefaultType.DEFAULT,
    iconSvgInline(){
        return ""
    },
    displayName: () => t('keeweb', 'Open With Keeweb'),
    enabled: ({ nodes }) => {
        return nodes.filter((node) => (node.permissions & Permission.READ !== 0 && node.mime === "application/x-kdbx")).length > 0
    },
    exec: async ({ nodes }) => {
        // Handle the first selected node with .kdbx extension
        const node = nodes[0]
        if (node && node.mime === "application/x-kdbx") {
            // Build the URL with proper encoding
            const encodedPath = encodeURIComponent(node.path)
            const url = generateUrl('/apps/keeweb/?open=' + encodedPath)
            // Use window.location.href for NC33+
            window.location.href = url
        }
    },
}

registerFileAction(fileAction)
