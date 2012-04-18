process.stdin.resume();
process.stdin.setEncoding('utf8');

data= ''
process.stdin.on('data', function(chunk) {
	data = data + chunk;
});

process.stdin.on('end', function() {
	var minifier = require('./html-minifier/htmlminifier.js')
	options = {
		removeComments:         true,
	removeCommentsFromCDATA:        true,
	removeCDATASectionsFromCDATA:   true,
	collapseWhitespace:             true,
	collapseBooleanAttributes:      true,
	removeAttributeQuotes:          true,  
	removeRedundantAttributes:      true,
	useShortDoctype:                true,
	removeEmptyAttributes:          true,
	removeEmptyElements:            false,
	removeOptionalTags:             true,
	removeScriptTypeAttributes:     true,
	removeStyleLinkTypeAttributes:  true,
	lint:                           false
	};

	var output = minifier.minify(data, options);
	process.stdout.write(output);
});
