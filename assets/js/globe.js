async function createSvg(value, type) {
    const canvas = document.createElement('canvas');
    canvas.width = 200;
    canvas.height = 360;

    const image = new Image();
    image.src = 'data:image/svg+xml;base64,' + window.btoa(value);
    await new Promise((resolve, reject) => {
        image.onload = () => resolve(canvas);
    });

    if(type == 'perso') {
        canvas.getContext('2d').drawImage(image, 83, 140);
    }else{
        canvas.getContext('2d').drawImage(image, 0, -15);
    }

    return canvas;
}

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

import {
    Cartesian3,
    Color,
    defined,
    Ion,
    ScreenSpaceEventType,
    Viewer,
    HeightReference,
    Cartesian2,
    ArcGisMapServerImageryProvider,
    SceneMode
} from "cesium";

// Your access token can be found at: https://cesium.com/ion/tokens.
// This is the default access token
Ion.defaultAccessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJhYzNkMmMxNi04NDM3LTQyNzEtYjBlNy05NTAxNjVjMGVhMDEiLCJpZCI6OTA1MzQsImlhdCI6MTY1MDQzNTIyMn0.P6exO2RJvDDe-6SgILWjoj92WeEaeRkPdWEeP6YhfYw';

var ua = navigator.userAgent.toLowerCase();
var isAndroid = ua.indexOf("android") > -1; //&& ua.indexOf("mobile");
let sceneMode
if (false) {
    sceneMode = SceneMode.SCENE2D;
} else {
    sceneMode = SceneMode.SCENE3D;
}
// Initialize the Cesium Viewer in the HTML element with the `cesiumContainer` ID.
var viewer = new Viewer('cesiumContainer', {
    imageryProvider: new ArcGisMapServerImageryProvider({
        url:
            "https://services.arcgisonline.com/ArcGIS/rest/services/NatGeo_World_Map/MapServer/",
    }),
    resolutionScale: 1.0,
    sceneMode: sceneMode,
    allowTextureFilterAnisotropic: false,
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
    shouldAnimate: true,
    sun: false,
    skyBox: false,
    skyAtmosphere: false,
    requestRenderMode: true,
    contextOptions: {
        webgl: {
            alpha: true,
            antialias: false,
            depth: true,
            premultipliedAlpha: true,
            preserveDrawingBuffer: false,
            failIfMajorPerformanceCaveat: true,
            stencil: true,
        },
    }
});
const {scene, screenSpaceEventHandler, camera, entities} = viewer;

scene.backgroundColor = Color.clone(Color.TRANSPARENT).withAlpha(0.0);
viewer.highDynamicRange = false;
scene.screenSpaceCameraController.minimumZoomDistance = 6000;

if (isAndroid) {
    scene.screenSpaceCameraController.maximumZoomDistance = 4078137;
//HDR needs to be disable for transparent backgrounds
    camera.flyTo({
        destination: Cartesian3.fromDegrees(2.3491, 48.8579, 150000.0 * 30)
    });

} else {
    scene.screenSpaceCameraController.maximumZoomDistance = 6378137 * 2;
//HDR needs to be disable for transparent backgrounds
    camera.flyTo({
        destination: Cartesian3.fromDegrees(2.3491, 48.8579, 150000.0 * 60)
    });

}
// Ini
//Set the background of the scene to transparent


let datas = httpGet('/dream-data-map');
datas = JSON.parse(datas)
datas.dreams.forEach(dream => {

    entities.add({
        position: Cartesian3.fromDegrees(parseFloat(dream.lng), parseFloat(dream.lat)),
        billboard: {
            width: 200,
            height: 360,
        },
        show: true,
        dreams: dream.dreams,
        theme: dream.theme_short,
        dream_id: dream.id,
        category: dream.category,
    })

    screenSpaceEventHandler.setInputAction(function (mouse) {
        var pickedObject = scene.pick(mouse.position);
        if (defined(pickedObject)) {
            var x = document.getElementById("dream-" + pickedObject.id._dream_id);
            if (x.style.display === "none") {
                x.style.display = "block";
                httpGetAsync('/dream-inc-view?id=' + pickedObject.id._dream_id);
            } else {
                x.style.display = "none";
            }

        }
    }, ScreenSpaceEventType.LEFT_CLICK);

})
refreshPins();

let currentCategory = null;
let currentTheme = null;

let nodesCategory = document.getElementById("category").children;
for (let i = 0; i < nodesCategory.length; i++) {

    nodesCategory[i].onclick = function () {
        resetSelectedFilterClass('.category');
        if (currentCategory !== nodesCategory[i].id) {
            currentCategory = nodesCategory[i].id;
            nodesCategory[i].classList.add("selectedFilter");
            nodesCategory[i].classList.remove("filter-box-none");
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
            nodesTheme[i].classList.remove("filter-box-none");
        } else {
            currentTheme = null;
        }
        refreshPins();
    };
}

function refreshPins() {
    for (let element of entities._entities._array) {
        let svg = '';
        let count = 0
        let theme_short = ''
        let owner = false;
        let themeToShow = element.theme.includes(currentTheme) || currentTheme == null;
        let categoryToShow = element.category.includes(currentCategory) || currentCategory == null;
        element.dreams.forEach(dream => {
            if ((dream.category.toLowerCase() == currentCategory && currentTheme == null) ||
                (dream.theme_short == currentTheme && null == currentCategory) ||
                (dream.category.toLowerCase() == currentCategory && dream.theme_short == currentTheme) ||
                (currentCategory == null && currentTheme == null)
            ) {
                document.getElementById("dream-" + dream.id).style.display = "block";
                count++;
                svg = dream.theme_pin_ico
                theme_short = dream.theme_short
                owner = dream.is_author
            } else {
                document.getElementById("dream-" + dream.id).style.display = "none";
            }
        })

        element.show = themeToShow && categoryToShow;
        if (count > 1) {
            svg = '<svg version="1.1" width="200px" height="360px" id="Calque_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 595.3 841.9" style="enable-background:new 0 0 595.3 841.9;" xml:space="preserve"> <style type="text/css"> .st0{fill:#1C1F3C;} .st1{fill:none;stroke:#FFFFFF;stroke-width:4;stroke-miterlimit:10;} .st2{fill:#FFFFFF;} </style> <path class="st0" d="M343.6,407c0,40-46.1,73.1-46.1,73.1s-46.1-33-46.1-73.1c0-25.5,20.6-46.1,46.1-46.1S343.6,381.5,343.6,407z"/> <circle class="st1" cx="297.5" cy="407" r="34.4"/> <g><text x="284" y="427" font-family="Verdana" font-size="55" fill="white">' + count + ' </text> </g> </svg> ';
            theme_short = null;
        }
        console.log(owner)
        if (owner) {
            svg = svg.replace('none', '#0abff3');
            svg = svg.replace('white', '#1C1F3CFF');
        }
        element.billboard.image = createSvg(svg, theme_short);
    }
}

function resetSelectedFilterClass(typeClass) {
    let elems = document.querySelectorAll(".selectedFilter" + typeClass);

    [].forEach.call(elems, function (el) {
        el.classList.remove("selectedFilter");
        el.classList.add("filter-box-none");
    });
}


refreshPins();
