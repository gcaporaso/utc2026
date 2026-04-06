#!/bin/bash

# attivo il debug così vedo cosa avviene nella shell
set -x

# per ogni file shp contenuto nella cartella dello script, esegue
# l'import nel file spatialite denominato nome_che_vuoi.sqlite
for i in *.shp; 
  do ogr2ogr -append -f SQLite -dsco SPATIALITE=YES catasto_cart_4326.sqlite ./"$i"; 
done
# view raw

