express = require 'express'
app = express()
app.set 'view engine', 'hbs'
app.set 'view options', layout: false

app.use express.logger 'dev'
app.use express.static "#{__dirname}/public"

app.get '/scripts/mixpanel.js', (req, res) ->
  res.setHeader 'Content-Type', 'application/javascript'
  res.render 'scripts/mixpanel.js.hbs', mixpanelKey: process.env.MIXPANEL_KEY

port = process.env.PORT ? 8000
app.listen port, ->
  console.log "Listening on #{port}"
