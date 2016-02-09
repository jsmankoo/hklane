// Generated by CoffeeScript 1.10.0
(function() {
  var agentURL, agentsArgs, app, express, morgan, nib, parser, propArgs, propUrl, shuffle, soap, stylus;

  express = require('express');

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
    sid: 'psprings244'
  };

  propArgs = {
    uid: 51244,
    sid: 'psprings244',
    propType: 'SFR',
    getActiveListings: true,
    getPendingSoldListings: false
  };

  app = express();

  app.set('port', process.env.PORT || 8000);

  app.set('views', "./views");

  app.set('view engine', "jade");

  app.use(morgan('dev'));

  app.use(express["static"]("./public"));

  app.get('/api/feature/:propType', function(req, res) {
    propArgs.propType = req.params.propType;
    return soap.createClient(propUrl, function(err, client) {
      return client.getProps(propArgs, function(err, result) {
        result = JSON.parse(parser.toJson(result.getPropsReturn['$value']));
        if (result.Request) {
          return res.json([]);
        } else {
          return res.json(result.featured_props.featured_prop);
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
    return soap.createClient(propUrl, function(err, client) {
      return client.getProps(propArgs, function(err, result) {
        var resultArray, sortedArray;
        result = JSON.parse(parser.toJson(result.getPropsReturn['$value']));
        resultArray = result.featured_props.featured_prop;
        sortedArray = resultArray.sort(function(a, b) {
          return parseInt(b.list_price - parseInt(a.list_price));
        });
        return res.render('index', {
          featureProp: sortedArray.slice(0, 11)
        });
      });
    });
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

  app.listen(app.get('port'), function() {
    return console.log("HKLane started on port: " + (app.get('port')));
  });

}).call(this);
