function httpGet(url) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", url, false); // false for synchronous request
    xmlHttp.send(null);
    return xmlHttp.responseText;
}

import {Cartesian3, Color, defined, Ion, ScreenSpaceEventType, Viewer} from "cesium";

// Your access token can be found at: https://cesium.com/ion/tokens.
// This is the default access token
Ion.defaultAccessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJhYzNkMmMxNi04NDM3LTQyNzEtYjBlNy05NTAxNjVjMGVhMDEiLCJpZCI6OTA1MzQsImlhdCI6MTY1MDQzNTIyMn0.P6exO2RJvDDe-6SgILWjoj92WeEaeRkPdWEeP6YhfYw';

// Initialize the Cesium Viewer in the HTML element with the `cesiumContainer` ID.
var viewer = new Viewer('cesiumContainer', {
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
scene.screenSpaceCameraController.minimumZoomDistance = 120000;
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
console.log(datas.dreams)
datas.dreams.forEach(dream => {
    let svg = "data:image/svg+xml," + '<svg id="a" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 92.19 119.19"><defs><style>.b{fill:#fff;}.c{fill:#1c1f3c;}.d{fill:none;stroke:#fff;stroke-miterlimit:10;stroke-width:4px;}</style></defs><path class="c" d="M92.19,46.1c0,40.05-46.1,73.1-46.1,73.1,0,0-46.1-33.05-46.1-73.1C0,20.64,20.64,0,46.1,0s46.1,20.64,46.1,46.1Z"/><circle class="d" cx="46.1" cy="46.12" r="34.42"/><g><path class="b" d="M38.24,66.91c-.56-.13-1.13-.21-1.67-.4-1.94-.65-2.96-2.08-3.31-4.05-.29-1.64-.08-3.25,.28-4.85,.92-4.12,2.45-7.99,4.85-11.49,1.06-1.54,2.27-2.94,3.87-3.95,2.66-1.68,5.48-1.58,8.04,.26,1.39,1,2.51,2.26,3.46,3.66,2.57,3.76,4.18,7.93,5.05,12.39,.27,1.41,.37,2.84,.05,4.27-.53,2.36-2.19,3.83-4.6,4.07-.11,.01-.21,.05-.32,.08h-1.86c-1.35-.18-2.53-.84-3.78-1.32-1.66-.64-3.35-.55-4.98,.22-.61,.29-1.22,.56-1.86,.78-.44,.16-.92,.22-1.38,.32h-1.86Z"/><path class="b" d="M39.43,23.68c1.05,.24,2.02,.64,2.85,1.36,1.54,1.34,2.4,3.05,2.77,5.03,.44,2.34,.17,4.57-1.14,6.6-1.12,1.74-2.7,2.73-4.84,2.53-1.62-.15-2.87-1-3.87-2.23-1.53-1.88-2.13-4.08-2.03-6.48,.07-1.7,.54-3.26,1.57-4.63,.83-1.11,1.9-1.86,3.29-2.11,.05,0,.1-.05,.15-.07h1.27Z"/><path class="b" d="M54.03,23.68c.63,.25,1.32,.42,1.89,.77,1.6,.98,2.48,2.51,2.86,4.3,.7,3.26,.07,6.23-2.24,8.73-1.55,1.67-3.78,2.21-5.73,1.4-1.5-.62-2.46-1.79-3.1-3.23-1.48-3.33-.76-7.53,1.74-10.16,.86-.91,1.9-1.52,3.16-1.72,.05,0,.1-.05,.15-.07h1.27Z"/><path class="b" d="M67.71,43.27c-.23,.8-.37,1.65-.71,2.4-.78,1.76-2.04,3.07-3.91,3.71-2.15,.73-4.41-.29-5.34-2.58-1.31-3.23,.57-7.64,3.81-8.92,2.69-1.06,5.39,.41,5.98,3.27,.05,.26,.11,.52,.17,.77v1.35Z"/><path class="b" d="M24.48,41.92c.14-.54,.23-1.1,.44-1.61,1.07-2.61,3.74-3.52,6.27-2.16,2.82,1.51,4.33,5.3,3.35,8.33-.93,2.88-3.96,4.1-6.77,2.27-1.86-1.21-2.86-2.99-3.22-5.16-.02-.11-.05-.21-.08-.32,0-.45,0-.9,0-1.35Z"/></g></svg>'
        console.log(svg)
    entities.add({
        position: Cartesian3.fromDegrees(parseFloat(dream.gps.log), parseFloat(dream.gps.lat)),
        billboard: {
            image: '/img/epingle-'+dream.theme_short+'.png',
            width: 250,
            height: 400
        },
        show: true,
        theme: dream.theme_short,
        category: dream.category.toLowerCase(),
    })


    screenSpaceEventHandler.setInputAction(function (mouse) {
        var pickedObject = scene.pick(mouse.position);
        if (defined(pickedObject)) {
            var x = document.getElementById("dream-"+dream.id);
            if (x.style.display === "none") {
                x.style.display = "block";
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

    console.log(nodesCategory[i]);
    nodesCategory[i].onclick = function () {
        resetSelectedFilterClass('.category');
        console.log(currentCategory);
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
        console.log(currentTheme);
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
