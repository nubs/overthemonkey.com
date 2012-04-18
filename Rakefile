require 'rake/clean'

CLOBBER.include('public/*.html', 'public/*.js', 'public/*.css')

PAGES = FileList['pages/*']
JAVASCRIPT = FileList['js/*']
CSS = FileList['css/*']

task :default => [*PAGES.pathmap('public/%f'), *JAVASCRIPT.pathmap('public/%f'), 'public/style.css']

directory 'public'

PAGES.each do |source|
  pageout = source.pathmap 'public/%f'

  if ENV['compress']
    file pageout => ['util/minifier.js', source, 'public'] do
      sh "node util/minifier.js < #{source} > #{pageout}"
    end
  else
    file pageout => [source, 'public'] do
      cp source, pageout
    end
  end
end

JAVASCRIPT.each do |source|
  out = source.pathmap 'public/%f'

  file out => [source, 'public'] do
    if ENV['compress']
      sh "yuicompressor #{source} > #{out}"
    else
      cp source, out
    end
  end
end

file 'public/style.css' => CSS do
  unless CSS.empty?
    if ENV['compress']
      sh "cat #{CSS.join(' ')} | yuicompressor --type css > public/style.css"
    else
      sh "cat #{CSS.join(' ')} > public/style.css"
    end
  end
end
