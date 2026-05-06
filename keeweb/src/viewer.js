import { registerFileAction, Permission, DefaultType } from '@nextcloud/files'
import { generateUrl } from '@nextcloud/router'

registerFileAction({
	id: 'keeweb',
	order: 1,
	default: DefaultType.DEFAULT,
	iconSvgInline: () => '',
	displayName: () => t('keeweb', 'Open With Keeweb'),
	enabled: ({ nodes }) =>
		nodes.length > 0 &&
		nodes.every(
			(node) =>
				(node.permissions & Permission.READ) !== 0 &&
				node.mime === 'application/x-kdbx',
		),
	async exec({ nodes }) {
		const node = nodes[0]
		window.location.href = generateUrl('/apps/keeweb/?open={path}', { path: node.path })
		return null
	},
})
