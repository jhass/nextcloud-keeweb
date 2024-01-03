import {registerFileAction, FileAction, Permission, DefaultType} from '@nextcloud/files'

const fileAction = new FileAction({
    id: 'keeweb',
    order: 1,
    default: DefaultType.DEFAULT,
    iconSvgInline(){
        return ""
    },
    displayName() {
        return t('keeweb', 'Open With Keeweb')
    },
    enabled(nodes) {
        return nodes.filter((node) => (node.permissions & Permission.READ !== 0 && node.mime ==="application/x-kdbx")).length > 0
    },
    async exec(node, view, dir) {
        let a = node.source.split("/")
        OC.redirect(`/apps/keeweb/?open=${dir === "/" ? "/" : dir + "/"}${a[a.length-1]}`)
    },
})

registerFileAction(fileAction)