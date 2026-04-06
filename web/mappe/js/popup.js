
// Piano Regolatore Generale
function onEachFeature(feature, layer) {
    // does this feature have a property named popupContent?
    if (feature.properties && feature.properties.Z) {
        layer.bindPopup(feature.properties.Z);
    }
    if (feature.properties && feature.properties.zonizzazio) {
        layer.bindPopup(feature.properties.zonizzazio);
    }
    if (feature.style) {
    var style = feature.style;
        layer.setStyle(style);
    }
    if (feature.properties && feature.properties.rischio) {
        layer.bindPopup(feature.properties.rischio);
    }
    
}


//definizione dell'oggetto popup
var popup = L.popup({maxWidth: 500});
