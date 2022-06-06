"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[304],{74836:(e,t,n)=>{n(35666),n(82772),n(89554),n(41539),n(54747),n(54678),n(88674),n(78783),n(66992),n(33948),n(26699),n(32023),n(47042),n(68309),n(91038),n(74916),n(82526),n(41817),n(32165),n(79753);var r,a=n(36027);function o(e,t){var n="undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(!n){if(Array.isArray(e)||(n=function(e,t){if(!e)return;if("string"==typeof e)return i(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return i(e,t)}(e))||t&&e&&"number"==typeof e.length){n&&(e=n);var r=0,a=function(){};return{s:a,n:function(){return r>=e.length?{done:!0}:{done:!1,value:e[r++]}},e:function(e){throw e},f:a}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var o,s=!0,l=!1;return{s:function(){n=n.call(e)},n:function(){var e=n.next();return s=e.done,e},e:function(e){l=!0,o=e},f:function(){try{s||null==n.return||n.return()}finally{if(l)throw o}}}}function i(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}function s(e,t,n,r,a,o,i){try{var s=e[o](i),l=s.value}catch(e){return void n(e)}s.done?t(l):Promise.resolve(l).then(r,a)}function l(e){return function(){var t=this,n=arguments;return new Promise((function(r,a){var o=e.apply(t,n);function i(e){s(o,r,a,i,l,"next",e)}function l(e){s(o,r,a,i,l,"throw",e)}i(void 0)}))}}function c(e,t){return u.apply(this,arguments)}function u(){return(u=l(regeneratorRuntime.mark((function e(t,n){var r,a;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return(r=document.createElement("canvas")).width=200,r.height=360,(a=new Image).src="data:image/svg+xml;base64,"+window.btoa(t),e.next=7,new Promise((function(e,t){a.onload=function(){return e(r)}}));case 7:return console.log(n),"animaux"==n||"perso"==n?r.getContext("2d").drawImage(a,83,140):r.getContext("2d").drawImage(a,0,-15),e.abrupt("return",r);case 10:case"end":return e.stop()}}),e)})))).apply(this,arguments)}function d(){return(d=l(regeneratorRuntime.mark((function e(t){var n;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return(n=new XMLHttpRequest).open("GET",t,!0),n.send(null),e.abrupt("return",n.responseText);case 4:case"end":return e.stop()}}),e)})))).apply(this,arguments)}a.f3v.defaultAccessToken="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJhYzNkMmMxNi04NDM3LTQyNzEtYjBlNy05NTAxNjVjMGVhMDEiLCJpZCI6OTA1MzQsImlhdCI6MTY1MDQzNTIyMn0.P6exO2RJvDDe-6SgILWjoj92WeEaeRkPdWEeP6YhfYw",r=navigator.userAgent.toLowerCase().indexOf("android")>-1?a.B$V.SCENE2D:a.B$V.SCENE3D;var m=new a.AEx("cesiumContainer",{imageryProvider:new a.rpp({url:"https://services.arcgisonline.com/ArcGIS/rest/services/NatGeo_World_Map/MapServer/"}),resolutionScale:1,sceneMode:r,allowTextureFilterAnisotropic:!1,animation:!1,baseLayerPicker:!1,fullscreenButton:!1,geocoder:!1,homeButton:!1,sceneModePicker:!1,camera:!1,selectionIndicator:!1,timeline:!1,useDefaultRenderLoop:!0,navigationHelpButton:!1,shouldAnimate:!0,sun:!1,skyBox:!1,skyAtmosphere:!1,requestRenderMode:!0,contextOptions:{webgl:{alpha:!0,antialias:!1,depth:!0,premultipliedAlpha:!0,preserveDrawingBuffer:!1,failIfMajorPerformanceCaveat:!0,stencil:!0}}}),p=m.scene,f=m.screenSpaceEventHandler,h=m.camera,y=m.entities;p.backgroundColor=a.Ilk.clone(a.Ilk.TRANSPARENT).withAlpha(0),p.screenSpaceCameraController.minimumZoomDistance=6e3,p.screenSpaceCameraController.maximumZoomDistance=12756274,m.highDynamicRange=!1,h.flyTo({destination:a.b91.fromDegrees(2.3491,48.8579,9e6)});var g,v,w=(g="/dream-data-map",(v=new XMLHttpRequest).open("GET",g,!1),v.send(null),v.responseText);(w=JSON.parse(w)).dreams.forEach((function(e){y.add({position:a.b91.fromDegrees(parseFloat(e.lng),parseFloat(e.lat)),billboard:{width:200,height:360},show:!0,dreams:e.dreams,theme:e.theme_short,dream_id:e.id,category:e.category}),f.setInputAction((function(e){var t=p.pick(e.position);if((0,a.rif)(t)){var n=document.getElementById("dream-"+t.id._dream_id);"none"===n.style.display?(n.style.display="block",function(e){d.apply(this,arguments)}("/dream-inc-view?id="+t.id._dream_id)):n.style.display="none"}}),a.Wvr.LEFT_CLICK)})),M();for(var x=null,k=null,b=document.getElementById("category").children,I=function(e){b[e].onclick=function(){S(".category"),x!==b[e].id?(x=b[e].id,b[e].classList.add("selectedFilter")):x=null,M()}},C=0;C<b.length;C++)I(C);for(var E=document.getElementById("theme").children,A=function(e){E[e].onclick=function(){S(".theme"),k!==E[e].id?(k=E[e].id,E[e].classList.add("selectedFilter")):k=null,M()}},F=0;F<E.length;F++)A(F);function M(){return R.apply(this,arguments)}function R(){return(R=l(regeneratorRuntime.mark((function e(){var t,n,r;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:t=o(y._entities._array),e.prev=1,r=regeneratorRuntime.mark((function e(){var t,r,a,o,i,s;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t=n.value,r="",a=0,o="",i=t.theme.includes(k)||null==k,s=t.category.includes(x)||null==x,t.dreams.forEach((function(e){e.category.toLowerCase()==x&&null==k||e.theme_short==k&&null==x||e.category.toLowerCase()==x&&e.theme_short==k||null==x&&null==k?(document.getElementById("dream-"+e.id).style.display="block",a++,r=e.theme_pin_ico,o=e.theme_short):document.getElementById("dream-"+e.id).style.display="none"})),t.show=i&&s,a>1&&(r='<svg version="1.1" width="200px" height="360px" id="Calque_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 595.3 841.9" style="enable-background:new 0 0 595.3 841.9;" xml:space="preserve"> <style type="text/css"> .st0{fill:#1C1F3C;} .st1{fill:none;stroke:#FFFFFF;stroke-width:4;stroke-miterlimit:10;} .st2{fill:#FFFFFF;} </style> <path class="st0" d="M343.6,407c0,40-46.1,73.1-46.1,73.1s-46.1-33-46.1-73.1c0-25.5,20.6-46.1,46.1-46.1S343.6,381.5,343.6,407z"/> <circle class="st1" cx="297.5" cy="407" r="34.4"/> <g><text x="284" y="427" font-family="Verdana" font-size="55" fill="white">'+a+" </text> </g> </svg> ",o=null),e.next=11,c(r,o);case 11:t.billboard.image=e.sent;case 12:case"end":return e.stop()}}),e)})),t.s();case 4:if((n=t.n()).done){e.next=8;break}return e.delegateYield(r(),"t0",6);case 6:e.next=4;break;case 8:e.next=13;break;case 10:e.prev=10,e.t1=e.catch(1),t.e(e.t1);case 13:return e.prev=13,t.f(),e.finish(13);case 16:case"end":return e.stop()}}),e,null,[[1,10,13,16]])})))).apply(this,arguments)}function S(e){var t=document.querySelectorAll(".selectedFilter"+e);[].forEach.call(t,(function(e){e.classList.remove("selectedFilter")}))}M()}},e=>{e.O(0,[190],(()=>{return t=74836,e(e.s=t);var t}));e.O()}]);