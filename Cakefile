muffin = require 'muffin'
fs = require 'fs'
glob = require 'glob'
path = require 'path'

option '-w', '--watch', 'continue to watch the files and rebuild them when they change'
option '-c', '--commit', 'operate on the git index instead of the working tree'
option '-m', '--compare', 'compare across git refs, stats task only.'
option '-p', '--production', 'minify the results for production use.'

minifyHtml = (source, options = {}) ->
  muffin.readFile(source,  options).then (original) ->
    {minify} = require 'html-minifier'
    muffin.writeFile source, minify original, removeComments: true, removeCommentsFromCDATA: true, collapseWhitespace: true, collapseBooleanAttributes: true, removeAttributeQuotes: true, removeRedundantAttributes: true, useShortDoctype: true, removeEmptyAttributes: true, removeEmptyElements: false, removeOptionalTags: true, removeScriptTypeAttributes: true, removeStyleLinkTypeAttributes: true, lint: false
    muffin.notify source, "Minified #{source} in place"

task 'build', 'compile to public dir', (options) ->
  muffin.run
    files: glob.sync './pages/**/*.html'
    options: options
    map:
      'pages/(.+)': (matches) ->
        muffin.copyFile(matches[0], "public/#{matches[1]}", options).then ->
          if options.production
            minifyHtml "public/#{matches[1]}", options

task 'clean', 'clean the public dir', (options) ->
  muffin.run
    files: './public/**/*'
    options: options
    map:
      'public/.+': (matches) ->
        fs.unlinkSync matches[0]
        muffin.notify matches[0], "Removed #{matches[0]}"
    after: ->
      if path.existsSync 'public'
        fs.rmdirSync 'public'
        muffin.notify 'public', 'Removed public'
