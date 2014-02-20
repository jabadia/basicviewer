{
  "values": {
    "displaybasemaps": "true",
    "displaylegend": "true",
    "displayshare": "true",
    "displaymeasure": "true",
    "displayelevation": "true",
    "showelevationdifference": "true",
    "theme": "red",
    "displaydetails": "true",
    "displayeditor": "true",
    "displaylayerlist": "true",
    "displayoverviewmap": "true",
    "displaytimeslider": "true",
    "displayprint": "true",
    "displaysearch": "true",
    "idescat": "false"
  },
  "configurationSettings": [{
    "category": "<b>Configuración General</b>",
    "fields": [{
      "label": "Combinación de Colores:",
      "fieldName": "theme",
      "type": "string",
      "options": [{
        "value": "red",
        "label": "Rojo"
      },{
        "value": "blue",
        "label": "Azul"
      },{
        "value": "gray",
        "label": "Gris"
      },{
        "value": "green",
        "label": "Verde"
      },{
        "value": "orange",
        "label": "Naranja"
      },{
        "value": "purple",
        "label": "Morado"
      }],
      "tooltip": "Combinación de colores para el visor"
    },{
      "label": "Mostrar el título",
      "fieldName": "displaytitle",
      "type": "boolean",
      "tooltip": "Mostrar el título"
    },{
      "label": "IDESCAT",
      "fieldName": "idescat",
      "type": "boolean",
      "tooltip": "Conexión con IDESCAT para obtener información estadística de los municipios."
    },{
      "placeHolder": "por defecto, el nombre del mapa",
      "label": "Título:",
      "fieldName": "title",
      "type": "string",
      "tooltip": ""
    },{
      "placeHolder": "url del logo",
      "label": "Mostrar el logo:",
      "fieldName": "customlogoimage",
      "type": "string",
      "tooltip": "URL de la imagen"
    },{
      "label": "Mostrar Mapa de Situación",
      "fieldName": "displayoverviewmap",
      "type": "boolean",
      "tooltip": ""
    }]
  },{
    "category": "<b>Opciones del Menú</b>",
    "fields": [{
      "label": "Leyenda *",
      "fieldName": "displaylegend",
      "type": "boolean",
      "tooltip": ""
    },{
      "label": "Detalles *",
      "fieldName": "displaydetails",
      "type": "boolean",
      "tooltip": ""
    },{
      "label": "Editor *",
      "fieldName": "displayeditor",
      "type": "boolean",
      "tooltip": "Mostrar opciones de edición si el mapa tiene capas editables (Feature Service)"
    },{
      "label": "Escala Temporal *",
      "fieldName": "displaytimeslider",
      "type": "boolean",
      "tooltip": "Mostrar la escala de tiempo si el mapa tiene información temporal"
    },{
      "label": "Imprimir",
      "fieldName": "displayprint",
      "type": "boolean",
      "tooltip": ""
    },{
      "label": "Lista de Capas *",
      "fieldName": "displaylayerlist",
      "type": "boolean",
      "tooltip": ""
    },{
      "label": "Mapas Base",
      "fieldName": "displaybasemaps",
      "type": "boolean",
      "tooltip": ""
    },{
      "label": "Marcadores",
      "fieldName": "displaybookmarks",
      "type": "boolean",
      "tooltip": "Mostrar los marcadores incluidos en el webmap"
    },{
      "label": "Medir",
      "fieldName": "displaymeasure",
      "type": "boolean",
      "tooltip": ""
    },{
      "label": "Elevación",
      "fieldName": "displayelevation",
      "type": "boolean",
      "tooltip": "Muestra un perfil de elevación. Es necesario activar las herramientas de medida."
    },{
     "label": "Mostrar diferencia de alturas",
     "fieldName": "showelevationdifference",
     "type":"boolean",
     "tooltip": "Muestra la diferencia de altura entre dos puntos"
    },{
      "label": "Compartir",
      "fieldName": "displayshare",
      "type": "boolean",
      "tooltip": ""
    },{
      "label": "Buscar",
      "fieldName": "displaysearch",
      "type": "boolean",
      "tooltip": ""
    },{
      "value": "* Solo aparecen si el webmap contiene capas con esta funcionalidad.",
      "type": "paragraph"
    }]
  }]
}