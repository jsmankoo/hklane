express = require 'express'
stylus = require 'stylus'
nib = require 'nib'
morgan = require 'morgan'
soap = require 'soap'
parser = require 'xml2json'
shuffle = require 'knuth-shuffle'

#propType SFR (Single Family Residences)
#             CND (Condos), FRM (Farms),
#             RI (Residential Income), MH (Mobile Homes),
#             LL (Lots and Land), RNT (Rentals), COM (Commercial)
agentURL = 'https://secure.idxre.com/ihws/AgentWebService.cfc?wsdl'
propUrl = 'https://secure.idxre.com/ihws/FeaturedPropWebService.cfc?wsdl'
agentsArgs =
    uid: 51244
    sid: 'psprings244'
propArgs =
    uid: 51244
    sid: 'psprings244'
    propType: 'SFR'
    getActiveListings: true
    getPendingSoldListings: false


#init app
app = express()

app.set 'port', process.env.PORT||10000
app.set 'views', "./views"
app.set 'view engine', "jade"

app.use morgan 'dev'
app.use express.static "./public", {
  maxAge: 7200000
}


##API
app.get '/api/feature/:propType', (req, res)->
    propArgs.propType = req.params.propType
    soap.createClient propUrl, (err, client)->
        client.getProps propArgs, (err, result)->
            result = JSON.parse parser.toJson result.getPropsReturn['$value']
            if result.Request
                res.json []
            else
                res.json result.featured_props.featured_prop

app.get '/api/prop/:propType/:listingNumber/:MLSID', (req, res)->
    propArg = agentsArgs
    propArg.ListingNumber = req.params.listingNumber
    propArg.MLSID = req.params.MLSID
    propArg.PropType = req.params.propType
    soap.createClient propUrl, (err, client)->
        client.getPropDetail propArg, (err, result)->
            result = JSON.parse parser.toJson result.getPropDetailReturn['$value']
            res.json result.featured_prop


app.get '/api/agents', (req, res)->
    soap.createClient agentURL, (err, client)->
        client.getAllAgents agentsArgs, (err, result)->
            resultXML = result.getAllAgentsReturn['$value']
            resultJson = JSON.parse parser.toJson resultXML
            agentsArray = resultJson.agentlist.agent
            res.json shuffle.knuthShuffle agentsArray

app.get '/', (req, res)->
    soap.createClient propUrl, (err, client)->
        client.getProps propArgs, (err, result)->
            result = JSON.parse parser.toJson result.getPropsReturn['$value']
            resultArray = result.featured_props.featured_prop
            sortedArray = resultArray.sort (a,b)->
                    parseInt b.list_price - parseInt a.list_price

            res.render 'index', {
                featureProp: sortedArray[0..10]
            }

app.get '/story', (req, res)->
    res.render 'story'

app.get '/management', (req, res)->
    res.render 'management'

app.get '/services', (req, res)->
    res.render 'services'

app.get '/locations', (req, res)->
    res.render 'locations'

app.get '/christies', (req, res)->
    res.render 'christies'

app.get '/agents', (req, res)->
    res.render 'agents'

app.get '/careers', (req, res)->
    res.render 'careers'

app.get '/properties/:propType', (req, res)->
    propType = req.params.propType
    res.render 'properties', {
        propType : propType
    }

##301 redirects
app.get '/index.php', (req, res) -> res.redirect 301, '/'
app.get '/story.php', (req, res) -> res.redirect 301, '/story'
app.get '/executive-management.php', (req, res) -> res.redirect 301, '/management'
app.get '/services.php', (req, res) -> res.redirect 301, '/services'
app.get '/locations.php', (req, res) -> res.redirect 301, '/locations'
app.get '/christies.php', (req, res) -> res.redirect 301, '/christies'
app.get '/agents.php', (req, res) -> res.redirect 301, '/agents'
app.get '/search-basic.php', (req, res) -> res.redirect 301, 'http://idx.hklane.com/homesearch/51244'
app.get '/search-advanced.php', (req, res) -> res.redirect 301, 'http://idx.hklane.com/homesearch/51244'
app.get '/search-address.php', (req, res) -> res.redirect 301, 'http://idx.hklane.com/homesearch/51244'
app.get '/search-number.php', (req, res) -> res.redirect 301, 'http://idx.hklane.com/homesearch/51244'
app.get '/search-number.php', (req, res) -> res.redirect 301, 'http://idx.hklane.com/homesearch/51244'


app.listen app.get('port'), ->
    console.log "HKLane started on port: #{app.get 'port'}"
