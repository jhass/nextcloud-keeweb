const path = require('path')
const webpackConfig = require('@nextcloud/webpack-vue-config')
const webpackRules = require('@nextcloud/webpack-vue-config/rules')

const BabelLoaderExcludeNodeModulesExcept = require('babel-loader-exclude-node-modules-except')

webpackConfig.entry = {
    viewer: path.join(__dirname, 'src', 'viewer.js'),
}

webpackConfig.output = {
    path:  path.join(__dirname, 'js'),
    filename:  'viewer.js'
}

webpackRules.RULE_JS.test = /\.m?js$/
webpackRules.RULE_JS.exclude = BabelLoaderExcludeNodeModulesExcept([
    '@nextcloud/dialogs',
    '@nextcloud/event-bus'
])

// Replaces rules array
webpackConfig.module.rules = Object.values(webpackRules)

// Export final config
module.exports = webpackConfig