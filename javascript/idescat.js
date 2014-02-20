dojo.provide("myModules.idescat");
dojo.require("esri.layers.FeatureLayer");

dojo.declare("myModules.idescat", null, 
{
  enabled: false,
  municipios: null,

  constructor: function(args)
  {
    console.log("idescat");

    var self = this;

    var map = args.map;

    if( args.button )
    {
      dojo.connect(args.button, "onClick", function(evt)
      {
        self.enabled = !self.enabled;
      });
    }

    this.municipios = new esri.layers.FeatureLayer("http://services.arcgis.com/qkUvCrgGj4CxYfQu/arcgis/rest/services/Municipios/FeatureServer/0",
    {
    //mode: "MODE_SELECTION",
      editable: false,
      autoGeneralize: true,
      outFields: ["Codigo"],
      mode: esri.layers.FeatureLayer.MODE_SELECTION
      //visible: false
    });

        var outline = new esri.symbol.SimpleLineSymbol()
          .setColor(dojo.colorFromHex('#fff'));
        
        var sym = new esri.symbol.SimpleFillSymbol()
          .setColor(new dojo.Color([52, 67, 83, 0.4]))
          .setOutline(outline);
        
        this.municipios.setRenderer(new esri.renderer.SimpleRenderer(sym));
        map.addLayer(this.municipios);    

    var selectQuery = new esri.tasks.Query();
    dojo.connect(map, 'onClick', function(evt)
    {
      if( !self.enabled )
      {
        return false;
      }

      map.infoWindow.hide();
      //this.showLoading();

      var point = evt.mapPoint;
      selectQuery.geometry = point;
      var query = self.municipios.selectFeatures(selectQuery, esri.layers.FeatureLayer.SELECTION_NEW);

      query.then(function()
      {
        if( self.municipios.graphics.length == 0 )
        {
          console.log("no hay municipio seleccionado");
          map.infoWindow.hide();
          //this.hideLoading();
        }
        else
        {
          var codigoMunicipio = self.municipios.graphics[0].attributes["Codigo"];
          console.log( codigoMunicipio );

          // http://www.idescat.cat/api/emex/?lang=es
          var getDataRequest = esri.request({
            url: 'http://api.idescat.cat/emex/v1/dades.json',
            content: {
              id: codigoMunicipio,
              i: "f171,f36,f42,f262,f3,f91,f92,f95",
              tipus: "mun"
            },
            handleAs: "json"
          });

            getDataRequest.then(function(response)
            {
              var content = self.generateTable(response);
              //https://www.google.com/fusiontables/exporttable?query=select%20*%20from%201zfdLwKFtojeEKlHIi-1vU1Wt5uCRxi8NvKcN-EY
              //http://www20.gencat.cat/portal/site/dadesobertes/menuitem.db4d3cf2bccf921baacf3010b0c0e1a0/?vgnextoid=49b19ee9acb42310VgnVCM1000000b0c1e0aRCRD&vgnextchannel=49b19ee9acb42310VgnVCM1000000b0c1e0aRCRD&vgnextfmt=detall2&id=5&newLang=es_ES
              console.log(content);
              map.infoWindow.setTitle("<img style='margin-bottom: -2px;' src='http://www.idescat.cat/images/idescat.png'>.cat");
              map.infoWindow.setContent(content);
              map.infoWindow.show(point);
              map.infoWindow.resize(400,140);
              //this.hideLoading();
            },
            function(error)
            {
              map.infoWindow.setTitle("idescat.cat - ERROR");
              map.infoWindow.setContent(error.responseText);
              map.infoWindow.show(point);
              //this.hideLoading(error);  
            });
        }
      });
    });        
  },

  enable: function(enable)
  {
    this.enabled = enable;
    console.log(this.enabled);
  },

  disable: function()
  {
    this.enable(false);
  },

  showLoading: function()
  {
    console.log("showLoading");
  },

  hideLoading: function()
  {
    console.log("hideLoading");
  },

  generateTable: function(data)
  {
    var table = "";
    var columns = data.fitxes.cols.col;
    var indicadors = data.fitxes.indicadors.i;

    table += "<table class='dataTable'><tr><th></th><th>"
    table += dojo.map( columns, function(c) { return c.content; }).join("</th><th>");
    table += "</th></tr>";

    dojo.forEach( indicadors, function(i)
    {
      if( i.v )
      {
      table += "<tr>";
      table += "<th>" + i.c + (i.u? "<br />(" + i.u + ")" : "") + ":</th>";
      table += "<td>" + dojo.map(i.v.split(','), function(v) { return dojo.number.format(v); }).join('</td><td>') + "</td>";
      table += "</tr>";
      }
      else
      {
        console.log(i);
      }
    });
    table += "</table>";

    return table;
  }
});

