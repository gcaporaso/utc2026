//document.addEventListener("DOMContentLoaded", function() {

		var map = new L.map('mapid', {
			center: [ 41.1305,14.645538],
			zoom: 17,
			layers: [googleSat,catasto],
			contextmenu: true,
			contextmenuWidth: 140,
			contextmenuItems: [{
			text: 'Info Urbanistiche',
			callback: showInfo
			},{
				text: 'Scheda Urbanistica',
			callback: showInfoCatasto
				}, {
			text: 'Info Particella',
			callback: showInfo2
			},{
				text: 'Coordinate Qui',
			callback: showCoordinates
			}, {
			text: 'Centra Qui',
			callback: centerMap
			}, '-', {
			text: 'Zoom -',
			icon: 'img/zoom-in.png',
			callback: zoomIn
			}, {
			text: 'Zoom +',
			icon: 'img/zoom-out.png',
			callback: zoomOut
			}]
			});




map.createPane('pane_PIANOREGOLATOREGENERALE_0');
map.getPane('pane_PIANOREGOLATOREGENERALE_0').style.zIndex = 400;
map.getPane('pane_PIANOREGOLATOREGENERALE_0').style['mix-blend-mode'] = 'normal';
//});