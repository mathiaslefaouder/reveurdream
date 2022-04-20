function httpGet(theUrl) {
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", theUrl, false); // false for synchronous request
    xmlHttp.send(null);
    return xmlHttp.responseText;
}

import {Ion, Viewer, Color, PinBuilder, Cartesian3, VerticalOrigin, BillboardCollection, ScreenSpaceEventHandler, ScreenSpaceEventType, defined } from "cesium";

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
    destination : Cartesian3.fromDegrees(2.3491, 48.8579, 150000.0 *60)
});



let data = httpGet('/test');
data = JSON.parse(data)

let svg =  '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 383.95 383.95" height="50px"><path d="M0,204V180c.83-6.4,1.41-12.83,2.52-19.17C10.1,117.34,29.78,80.33,62.34,50.47a187.49,187.49,0,0,1,97-47.56C166.16,1.74,173.08,1,180,0h24c6.54.84,13.12,1.45,19.61,2.56,39.46,6.79,73.71,24,102.36,52,29.41,28.75,47.85,63.44,55,104,1.25,7.09,2,14.27,3,21.41v24c-.22,1.09-.5,2.17-.67,3.26-1.55,9.84-2.43,19.83-4.71,29.49A191.48,191.48,0,0,1,225,381c-7,1.19-14,2-21,2.94H180a18.11,18.11,0,0,0-2.86-.64A170.91,170.91,0,0,1,125.5,372Q24.25,331.66,2.92,224.65C1.58,217.83,1,210.87,0,204ZM300.11,308.54c0-.5-.05-1-.13-1.49-.11-.74-.25-1.48-.41-2.21-5-23.26-18-41.12-37.27-54.56-17.5-12.2-37.13-18.56-58.29-20.3-30.21-2.48-58.4,3.46-83.46,21.12-18.88,13.31-31.38,31.14-36.23,54-1.43,6.77,1.38,11.28,8.22,12.49a13,13,0,0,0,6.23-.46c24.55-8.21,49.79-12.38,75.59-13.7,27.82-1.41,55.42.17,82.66,6.11,9.84,2.15,19.46,5.28,29.2,7.89C293.31,319.32,300.16,314.89,300.11,308.54Zm-62.4-160.79A24.59,24.59,0,1,0,262.41,123,24.69,24.69,0,0,0,237.71,147.75Zm-91.45-.15a24.55,24.55,0,1,0-24.42,24.68A24.6,24.6,0,0,0,146.26,147.6Z"/></svg>'

function init() {
    var dream = entities.add({
        position: Cartesian3.fromDegrees(-75.59777, 40.03883),
        billboard: {
            image : "data:image/svg+xml," + svg
        },
    });
    screenSpaceEventHandler.setInputAction(function(mouse) {
        var pickedObject = scene.pick(mouse.position);
        if (defined(pickedObject)) {
            var x = document.getElementById("dream-1");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }

        }
    }, ScreenSpaceEventType.LEFT_CLICK);
}

export {init}