// Generated by CoffeeScript 1.10.0
(function() {
  var agentURL, agentsArgs, app, express, morgan, nib, parser, path, propArgs, propUrl, shuffle, soap, stylus;

  express = require('express');

  path = require('path');

  stylus = require('stylus');

  nib = require('nib');

  morgan = require('morgan');

  soap = require('soap');

  parser = require('xml2json');

  shuffle = require('knuth-shuffle');

  agentURL = 'https://secure.idxre.com/ihws/AgentWebService.cfc?wsdl';

  propUrl = 'https://secure.idxre.com/ihws/FeaturedPropWebService.cfc?wsdl';

  agentsArgs = {
    uid: 51244,
    sid: process.argv[2]
  };

  propArgs = {
    uid: 51244,
    sid: process.argv[2],
    propType: 'SFR',
    getActiveListings: true,
    getPendingSoldListings: false
  };

  app = express();

  app.set('port', process.env.PORT || 10000);

  app.set('views', path.resolve(__dirname, '..', 'views'));

  app.set('view engine', "jade");

  app.use(morgan('dev'));

  app.use(express["static"]("./public", {
    maxAge: 7200000
  }));

  app.get('/api/feature/:propType', function(req, res) {
    propArgs.propType = req.params.propType;
    return soap.createClient(propUrl, function(err, client) {
      return client.getProps(propArgs, function(err, result) {
        result = JSON.parse(parser.toJson(result.getPropsReturn['$value']));
        if (result.Request) {
          return res.json([]);
        } else {
          return res.json(result.featured_props.featured_prop.slice(0, 11));
        }
      });
    });
  });

  app.get('/api/prop/:propType/:listingNumber/:MLSID', function(req, res) {
    var propArg;
    propArg = agentsArgs;
    propArg.ListingNumber = req.params.listingNumber;
    propArg.MLSID = req.params.MLSID;
    propArg.PropType = req.params.propType;
    return soap.createClient(propUrl, function(err, client) {
      return client.getPropDetail(propArg, function(err, result) {
        result = JSON.parse(parser.toJson(result.getPropDetailReturn['$value']));
        return res.json(result.featured_prop);
      });
    });
  });

  app.get('/api/agents', function(req, res) {
    return soap.createClient(agentURL, function(err, client) {
      return client.getAllAgents(agentsArgs, function(err, result) {
        var agentsArray, resultJson, resultXML;
        resultXML = result.getAllAgentsReturn['$value'];
        resultJson = JSON.parse(parser.toJson(resultXML));
        agentsArray = resultJson.agentlist.agent;
        return res.json(shuffle.knuthShuffle(agentsArray));
      });
    });
  });

  app.get('/', function(req, res) {
    return res.render('index');
  });

  app.get('/story', function(req, res) {
    return res.render('story');
  });

  app.get('/management', function(req, res) {
    return res.render('management');
  });

  app.get('/services', function(req, res) {
    return res.render('services');
  });

  app.get('/locations', function(req, res) {
    return res.render('locations');
  });

  app.get('/christies', function(req, res) {
    return res.render('christies');
  });

  app.get('/agents', function(req, res) {
    return res.render('agents');
  });

  app.get('/careers', function(req, res) {
    return res.render('careers');
  });

  app.get('/properties/:propType', function(req, res) {
    var propType;
    propType = req.params.propType;
    return res.render('properties', {
      propType: propType
    });
  });

  app.get('/index.php', function(req, res) {
    return res.redirect(301, '/');
  });

  app.get('/story.php', function(req, res) {
    return res.redirect(301, '/story');
  });

  app.get('/executive-management.php', function(req, res) {
    return res.redirect(301, '/management');
  });

  app.get('/services.php', function(req, res) {
    return res.redirect(301, '/services');
  });

  app.get('/locations.php', function(req, res) {
    return res.redirect(301, '/locations');
  });

  app.get('/christies.php', function(req, res) {
    return res.redirect(301, '/christies');
  });

  app.get('/agents.php', function(req, res) {
    return res.redirect(301, '/agents');
  });

  app.get('/search-basic.php', function(req, res) {
    return res.redirect(301, 'http://idx.hklane.com/homesearch/51244');
  });

  app.get('/search-advanced.php', function(req, res) {
    return res.redirect(301, 'http://idx.hklane.com/homesearch/51244');
  });

  app.get('/search-address.php', function(req, res) {
    return res.redirect(301, 'http://idx.hklane.com/homesearch/51244');
  });

  app.get('/search-number.php', function(req, res) {
    return res.redirect(301, 'http://idx.hklane.com/homesearch/51244');
  });

  app.listen(app.get('port'), function() {
    return console.log("HKLane started on port: " + (app.get('port')));
  });

}).call(this);
