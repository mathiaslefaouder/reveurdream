function httpGet(url) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", url, false); // false for synchronous request
    xmlHttp.send(null);
    return xmlHttp.responseText;
}

async function httpGetAsync(url) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", url, true); // false for synchronous request
    xmlHttp.send(null);
    return xmlHttp.responseText;
}

import {Cartesian3, Color, defined, Ion, ScreenSpaceEventType, Viewer, Cartesian2, ArcGisMapServerImageryProvider} from "cesium";

// Your access token can be found at: https://cesium.com/ion/tokens.
// This is the default access token
Ion.defaultAccessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJhYzNkMmMxNi04NDM3LTQyNzEtYjBlNy05NTAxNjVjMGVhMDEiLCJpZCI6OTA1MzQsImlhdCI6MTY1MDQzNTIyMn0.P6exO2RJvDDe-6SgILWjoj92WeEaeRkPdWEeP6YhfYw';

// Initialize the Cesium Viewer in the HTML element with the `cesiumContainer` ID.
var viewer = new Viewer('cesiumContainer', {
    imageryProvider: new ArcGisMapServerImageryProvider({
        url:
            "https://services.arcgisonline.com/ArcGIS/rest/services/NatGeo_World_Map/MapServer/",
    }),
    resolutionScale: 2.0,
    maximumRenderTimeChange: Infinity,
    animation: false,
    baseLayerPicker: false,
    fullscreenButton: false,
    geocoder: false,
    homeButton: false,
    sceneModePicker: false,
    camera: false,
    selectionIndicator: false,
    timeline: false,
    useDefaultRenderLoop: true,
    navigationHelpButton: false,
    scene3DOnly: true,
    shouldAnimate: true,
    sun: false,
    skyBox: false,
    skyAtmosphere: false,
    requestRenderMode: true,
    contextOptions: {
        webgl: {
            alpha: true,
        },
    }
});
const {scene, screenSpaceEventHandler, camera, entities} = viewer;

//Set the background of the scene to transparent
scene.backgroundColor = Color.clone(Color.TRANSPARENT).withAlpha(0.0);
scene.screenSpaceCameraController.minimumZoomDistance = 6000;
scene.screenSpaceCameraController.maximumZoomDistance = 6378137 * 2;
scene.globe.enableLighting = true;
scene.globe.dynamicAtmosphereLighting = true;
//HDR needs to be disable for transparent backgrounds
viewer.highDynamicRange = false;
camera.flyTo({
    destination: Cartesian3.fromDegrees(2.3491, 48.8579, 150000.0 * 60)
});


let datas = httpGet('/dream-data-map');
datas = JSON.parse(datas)
console.log(datas)
datas.dreams.forEach(dream => {
    console.log(dream)
    entities.add({
        position: Cartesian3.fromDegrees(parseFloat(dream.lng), parseFloat(dream.lat)),
        billboard: {
            image: dream.pin,
            width: 250,
            height: 400,
        },
        show: true,
        theme: dream.theme_short,
        dream_id: dream.id,
        category: dream.category,
    })


    screenSpaceEventHandler.setInputAction(function (mouse) {
        var pickedObject = scene.pick(mouse.position);
        if (defined(pickedObject)) {
            var x = document.getElementById("dream-"+pickedObject.id._dream_id);
            if (x.style.display === "none") {
                x.style.display = "block";
                httpGetAsync('/dream-inc-view?id='+pickedObject.id._dream_id);
            } else {
                x.style.display = "none";
            }

        }
    }, ScreenSpaceEventType.LEFT_CLICK);

})


scene.globe.tileLoadProgressEvent.addEventListener(function () {

    if (scene.globe.tilesLoaded) {
        document.getElementById("loading-overlay").style.display = "none";
    }
});

let currentCategory = null;
let currentTheme = null;

let nodesCategory = document.getElementById("category").children;

for (let i = 0; i < nodesCategory.length; i++) {

    nodesCategory[i].onclick = function () {
        resetSelectedFilterClass('.category');
        if (currentCategory !== nodesCategory[i].id) {
            currentCategory = nodesCategory[i].id;
            nodesCategory[i].classList.add("selectedFilter");
        } else {
            currentCategory = null;
        }
        refreshPins();
    };
}

let nodesTheme = document.getElementById("theme").children;

for (let i = 0; i < nodesTheme.length; i++) {
    nodesTheme[i].onclick = function () {
        resetSelectedFilterClass('.theme');
        if (currentTheme !== nodesTheme[i].id) {
            currentTheme = nodesTheme[i].id;
            nodesTheme[i].classList.add("selectedFilter");
        } else {
            currentTheme = null;
        }
        refreshPins();
    };
}

function refreshPins() {
    entities._entities._array.forEach(
        element => {

            let themeToShow = element.theme === currentTheme || currentTheme == null;
            let categoryToShow = element.category === currentCategory || currentCategory == null;

            element.show = themeToShow && categoryToShow;
        }
    )
}

function resetSelectedFilterClass(typeClass) {
    let elems = document.querySelectorAll(".selectedFilter" + typeClass);

    [].forEach.call(elems, function(el) {
        el.classList.remove("selectedFilter");
    });
}
