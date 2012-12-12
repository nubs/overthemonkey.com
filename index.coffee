express = require 'express'
app = express()

app.use express.logger 'dev'
app.use express.static "#{__dirname}/public"

port = process.env.PORT ? 8000
app.listen port, ->
  console.log "Listening on #{port}"
