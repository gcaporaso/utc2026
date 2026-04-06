var Bbox_width= 18.99-5.93;
var startResolution = Bbox_width/1024;
var grid_resolution = new Array(30);
for (var i = 0; i < 30; ++i) {
	grid_resolution[i] = startResolution / Math.pow(2, i);
}

var crs_6706 = new L.Proj.CRS('EPSG:6706',
   '+proj=longlat +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +no_defs',
   {
     origin: [0, 0],
     bounds: L.bounds([5.93, 34.76], [18.99, 47.1]),
     resolutions: grid_resolution
   });

var gcrs6706 = new L.Proj.CRS('EPSG:6706',
    '+proj=tmerc +lat_0=0 +lon_0=0 +k=1 +x_0=0 +y_0=0 +ellps=intl +units=m +no_defs',
    {
        resolutions: grid_resolution,
        origin: [0,0]
    }
);

var crs7792 = new L.Proj.CRS("EPSG:7792",
  "+proj=utm +zone=33 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs +type=crs",
  {
        resolutions: [8192, 4096, 2048, 1024, 512, 256, 128, 64, 32, 16, 8, 4, 2, 1],
        origin: [0,0]
    });

var B2box_width= 800000-200000;
var sRes = B2box_width/1024;
var grdres = new Array(30);
for (var i = 0; i < 30; ++i) {
	grdres[i] = sRes / Math.pow(2, i);
}    
var crs_25833 = new L.Proj.CRS('EPSG:25833',
  '+proj=utm +zone=33 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs +type=crs',
  {
        //resolutions: [8192, 4096, 2048, 1024, 512, 256, 128, 64, 32, 16, 8, 4, 2, 1],
        bounds: L.bounds([200000, 4300000], [800000, 4900000]),
        resolutions: grdres,
        origin: [0,0]
    });
//proj4.defs("EPSG:25833","+proj=utm +zone=33 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs +type=crs");
var S7792 = 'PROJCS["RDN2008_UTM_zone_33N",GEOGCS["GCS_RDN2008",DATUM["D_RDN2008",SPHEROID["GRS_1980",6378137.0,298.257222101]],PRIMEM["Greenwich",0.0],UNIT["Degree",0.0174532925199433]],PROJECTION["Transverse_Mercator"],PARAMETER["False_Easting",500000.0],PARAMETER["False_Northing",0.0],PARAMETER["Central_Meridian",15.0],PARAMETER["Scale_Factor",0.9996],PARAMETER["Latitude_Of_Origin",0.0],UNIT["Meter",1.0],AUTHORITY["EPSG","7792"]]';
var S6706 = 'GEOGCS["RDN2008",DATUM["Rete_Dinamica_Nazionale_2008",SPHEROID["GRS 1980",6378137,298.257222101,AUTHORITY["EPSG","7019"]],AUTHORITY["EPSG","1132"]],PRIMEM["Greenwich",0,AUTHORITY["EPSG","8901"]],UNIT["degree",0.0174532925199433,AUTHORITY["EPSG","9122"]],AUTHORITY["EPSG","6706"]]';
var S6708 = 'PROJCS["RDN2008_UTM_zone_33N_N-E",GEOGCS["GCS_RDN2008",DATUM["D_RDN2008",SPHEROID["GRS_1980",6378137.0,298.257222101]],PRIMEM["Greenwich",0.0],UNIT["Degree",0.0174532925199433]],PROJECTION["Transverse_Mercator"],PARAMETER["False_Easting",500000.0],PARAMETER["False_Northing",0.0],PARAMETER["Central_Meridian",15.0],PARAMETER["Scale_Factor",0.9996],PARAMETER["Latitude_Of_Origin",0.0],UNIT["Meter",1.0],AUTHORITY["EPSG","6708"]]';
var S25833= 'PROJCS["ETRS89 / UTM zone 33N",GEOGCS["ETRS89",DATUM["European_Terrestrial_Reference_System_1989",SPHEROID["GRS 1980",6378137,298.257222101,AUTHORITY["EPSG","7019"]],TOWGS84[0,0,0,0,0,0,0],AUTHORITY["EPSG","6258"]],PRIMEM["Greenwich",0,AUTHORITY["EPSG","8901"]],UNIT["degree",0.0174532925199433,AUTHORITY["EPSG","9122"]],AUTHORITY["EPSG","4258"]],PROJECTION["Transverse_Mercator"],PARAMETER["latitude_of_origin",0],PARAMETER["central_meridian",15],PARAMETER["scale_factor",0.9996],PARAMETER["false_easting",500000],PARAMETER["false_northing",0],UNIT["metre",1,AUTHORITY["EPSG","9001"]],AXIS["Easting",EAST],AXIS["Northing",NORTH],AUTHORITY["EPSG","25833"]]';

// var crs25833 = new proj4.defs("EPSG:25833",
//   "+proj=utm +zone=33 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs +type=crs");  