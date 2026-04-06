"""
Converte tutti gli shapefile CTR2020 (EPSG:25833) in GeoJSON (WGS84/EPSG:4326).
I file GeoJSON risultanti vengono salvati nella stessa cartella dello shapefile.

Eseguire UNA VOLTA come root o con permessi adeguati:
    sudo python3 /var/www/utcbim/scripts/convert_ctr2020.py

Richiede: pyshp, pyproj
    pip3 install pyshp pyproj --break-system-packages
"""

import os
import json
import shapefile
from pyproj import Transformer

BASE_DIR = os.path.normpath(os.path.join(
    os.path.dirname(os.path.abspath(__file__)),
    '..', 'web', 'mappe', 'b542', 'ctr2020'
))
# I GeoJSON vengono salvati in web/ctr2020geojson/ (scrivibile senza root)
OUT_DIR = os.path.normpath(os.path.join(
    os.path.dirname(os.path.abspath(__file__)),
    '..', 'web', 'ctr2020geojson'
))
os.makedirs(OUT_DIR, exist_ok=True)
SHAPETYPES = ['LIN', 'POI', 'POL']

# EPSG:25833 (ETRS89 UTM Zone 33N) → EPSG:4326 (WGS84 lon/lat)
transformer = Transformer.from_crs("EPSG:25833", "EPSG:4326", always_xy=True)


def convert_point(x, y):
    """Coordinate UTM33N → [lon, lat] WGS84 con 7 decimali (~1 cm)."""
    lon, lat = transformer.transform(x, y)
    return [round(lon, 7), round(lat, 7)]


def shape_to_geojson_geometry(shape):
    """Converte un oggetto pyshp in geometria GeoJSON con coordinate WGS84."""
    st = shape.shapeType

    if st in (1, 11, 21):   # Point
        return {"type": "Point", "coordinates": convert_point(*shape.points[0])}

    if st in (8, 18, 28):   # MultiPoint
        return {"type": "MultiPoint",
                "coordinates": [convert_point(*p) for p in shape.points]}

    if st in (3, 13, 23):   # Polyline / Arc
        parts = list(shape.parts) + [len(shape.points)]
        rings = [
            [convert_point(*p) for p in shape.points[parts[i]:parts[i + 1]]]
            for i in range(len(parts) - 1)
        ]
        if len(rings) == 1:
            return {"type": "LineString", "coordinates": rings[0]}
        return {"type": "MultiLineString", "coordinates": rings}

    if st in (5, 15, 25):   # Polygon
        parts = list(shape.parts) + [len(shape.points)]
        rings = [
            [convert_point(*p) for p in shape.points[parts[i]:parts[i + 1]]]
            for i in range(len(parts) - 1)
        ]
        if len(rings) == 1:
            return {"type": "Polygon", "coordinates": rings}
        return {"type": "MultiPolygon", "coordinates": [[r] for r in rings]}

    return None


def convert_shapefile(shp_path, geojson_path):
    features = []
    with shapefile.Reader(shp_path) as sf:
        fields = [f[0] for f in sf.fields[1:]]
        for sr in sf.iterShapeRecords():
            geom = shape_to_geojson_geometry(sr.shape)
            if geom is None:
                continue
            props = {
                k: (v.strip() if isinstance(v, str) else v)
                for k, v in zip(fields, sr.record)
            }
            features.append({"type": "Feature", "geometry": geom, "properties": props})

    with open(geojson_path, 'w', encoding='utf-8') as f:
        json.dump({"type": "FeatureCollection", "features": features},
                  f, ensure_ascii=False, separators=(',', ':'))

    size_kb = os.path.getsize(geojson_path) / 1024
    # Permessi leggibili dal web server
    os.chmod(geojson_path, 0o644)
    print(f"  OK  {os.path.relpath(geojson_path, BASE_DIR):20s} — {len(features):5d} feature, {size_kb:7.0f} KB")


def main():
    print(f"Directory CTR2020: {BASE_DIR}\n")
    folders = sorted(
        d for d in os.listdir(BASE_DIR)
        if os.path.isdir(os.path.join(BASE_DIR, d)) and d.isdigit()
    )
    if not folders:
        print("Nessuna cartella trovata.")
        return

    for folder in folders:
        folder_path = os.path.join(BASE_DIR, folder)
        out_folder = os.path.join(OUT_DIR, folder)
        os.makedirs(out_folder, exist_ok=True)
        print(f"[{folder}]")
        for stype in SHAPETYPES:
            shp = os.path.join(folder_path, stype + '.shp')
            gjson = os.path.join(out_folder, stype + '.geojson')
            if not os.path.exists(shp):
                print(f"  SKIP {stype}.shp — non trovato")
                continue
            if (os.path.exists(gjson) and
                    os.path.getmtime(gjson) >= os.path.getmtime(shp)):
                size_kb = os.path.getsize(gjson) / 1024
                print(f"  GIA' AGGIORNATO: {stype}.geojson ({size_kb:.0f} KB)")
                continue
            convert_shapefile(shp, gjson)

    print("\nConversione completata.")


if __name__ == '__main__':
    main()
