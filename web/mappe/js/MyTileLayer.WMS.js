(function(L) {
    if (typeof L === 'undefined') {
        throw new Error('Leaflet must be included first');
    }

    L.MyTileLayer = {};

    L.MyTileLayer.WMS = L.TileLayer.WMS.extend({

        defaultWmsParams: {
            service: 'WMS',
            request: 'GetMap',
            layers: '',
            styles: '',
            format: 'image/jpeg',
            transparent: false,
            version: '1.3.0'
        },

        initialize: function(url, options) {
            this._url = url;

            var wmsParams = L.Util.extend({}, this.defaultWmsParams);

            // parametri extra rispetto alle opzioni standard
            for (var i in options) {
                if (!(i in this.options)) {
                    wmsParams[i] = options[i];
                }
            }

            L.setOptions(this, options);

            var realRetina = (this.options.detectRetina && L.Browser.retina) ? 2 : 1;
            var tileSize = this.getTileSize();

            wmsParams.width = tileSize.x * realRetina;
            wmsParams.height = tileSize.y * realRetina;

            this.wmsParams = wmsParams;
        },

        onAdd: function(map) {
            this._crs = this.options.crs || map.options.crs;
            this._wmsVersion = parseFloat(this.wmsParams.version);

            var projectionKey = this._wmsVersion >= 1.3 ? 'crs' : 'srs';
            this.wmsParams[projectionKey] = this._crs.code;

            L.TileLayer.WMS.prototype.onAdd.call(this, map);
        },

        getTileUrl: function(coords) {
            var tileBounds = this._tileCoordsToNwSe(coords),
                crs = this._crs,
                bounds = new L.Bounds(crs.project(tileBounds[0]), crs.project(tileBounds[1])),
                min = bounds.min,
                max = bounds.max,
                bbox = (this._wmsVersion >= 1.3 ?
                    [min.y, min.x, max.y, max.x] :
                    [min.x, min.y, max.x, max.y]).join(',');

            //var url = new URL(L.TileLayer.WMS.prototype.getTileUrl.call(this, coords));
             var url = new URL(this._url);
            for (const [k, v] of Object.entries({...this.wmsParams, bbox})) {
                //url.searchParams.append(this.options.uppercase ? k.toUpperCase() : k, v);
                url.searchParams.set(this.options.uppercase ? k.toUpperCase() : k, v);
            }
            return url.toString();
        },

        setParams: function(params, noRedraw) {
            L.Util.extend(this.wmsParams, params);
            if (!noRedraw) {
                this.redraw();
            }
            return this;
        }
    });

   // Factory function → disponibile come L.myTileLayer.wms(...)
    L.myTileLayer = {
        wms: function (url, options) {
            return new L.MyTileLayer.WMS(url, options);
        }
    };

})(L);

