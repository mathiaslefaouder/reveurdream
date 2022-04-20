function httpGet(theUrl) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", theUrl, false); // false for synchronous request
    xmlHttp.send(null);
    return xmlHttp.responseText;
}

import {Ion, Viewer, Color, PinBuilder, Cartesian3, VerticalOrigin} from "cesium";

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
const {scene, entities} = viewer;

//Set the background of the scene to transparent
scene.backgroundColor = Color.clone(Color.TRANSPARENT).withAlpha(0.0);


//HDR needs to be disable for transparent backgrounds
viewer.highDynamicRange = false;

const pinBuilder = new PinBuilder();


let data = httpGet('/test');
data = JSON.parse(data)
console.log(data)


function init() {
    const bluePin = entities.add({
        name: data.name,
        position: Cartesian3.fromDegrees(-75.170726, 39.9208667),
        billboard: {
            image: pinBuilder.fromColor(Color.ROYALBLUE, 48).toDataURL(),
            verticalOrigin: VerticalOrigin.BOTTOM,
        },
    });
}

export {init}