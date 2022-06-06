define(["exports","./AxisAlignedBoundingBox-842a104c","./Matrix2-7fbd2afb","./RuntimeError-8952249c","./defaultValue-81eec7ed","./IntersectionTests-325bf999","./Plane-85eed013","./Transforms-969e35b7"],(function(t,n,e,i,o,r,s,a){"use strict";const l=new e.Cartesian4;function c(t,n){t=(n=o.defaultValue(n,e.Ellipsoid.WGS84)).scaleToGeodeticSurface(t);const i=a.Transforms.eastNorthUpToFixedFrame(t,n);this._ellipsoid=n,this._origin=t,this._xAxis=e.Cartesian3.fromCartesian4(e.Matrix4.getColumn(i,0,l)),this._yAxis=e.Cartesian3.fromCartesian4(e.Matrix4.getColumn(i,1,l));const r=e.Cartesian3.fromCartesian4(e.Matrix4.getColumn(i,2,l));this._plane=s.Plane.fromPointNormal(t,r)}Object.defineProperties(c.prototype,{ellipsoid:{get:function(){return this._ellipsoid}},origin:{get:function(){return this._origin}},plane:{get:function(){return this._plane}},xAxis:{get:function(){return this._xAxis}},yAxis:{get:function(){return this._yAxis}},zAxis:{get:function(){return this._plane.normal}}});const d=new n.AxisAlignedBoundingBox;c.fromPoints=function(t,e){return new c(n.AxisAlignedBoundingBox.fromPoints(t,d).center,e)};const f=new r.Ray,p=new e.Cartesian3;c.prototype.projectPointOntoPlane=function(t,n){const i=f;i.origin=t,e.Cartesian3.normalize(t,i.direction);let s=r.IntersectionTests.rayPlane(i,this._plane,p);if(o.defined(s)||(e.Cartesian3.negate(i.direction,i.direction),s=r.IntersectionTests.rayPlane(i,this._plane,p)),o.defined(s)){const t=e.Cartesian3.subtract(s,this._origin,s),i=e.Cartesian3.dot(this._xAxis,t),r=e.Cartesian3.dot(this._yAxis,t);return o.defined(n)?(n.x=i,n.y=r,n):new e.Cartesian2(i,r)}},c.prototype.projectPointsOntoPlane=function(t,n){o.defined(n)||(n=[]);let e=0;const i=t.length;for(let r=0;r<i;r++){const i=this.projectPointOntoPlane(t[r],n[e]);o.defined(i)&&(n[e]=i,e++)}return n.length=e,n},c.prototype.projectPointToNearestOnPlane=function(t,n){o.defined(n)||(n=new e.Cartesian2);const i=f;i.origin=t,e.Cartesian3.clone(this._plane.normal,i.direction);let s=r.IntersectionTests.rayPlane(i,this._plane,p);o.defined(s)||(e.Cartesian3.negate(i.direction,i.direction),s=r.IntersectionTests.rayPlane(i,this._plane,p));const a=e.Cartesian3.subtract(s,this._origin,s),l=e.Cartesian3.dot(this._xAxis,a),c=e.Cartesian3.dot(this._yAxis,a);return n.x=l,n.y=c,n},c.prototype.projectPointsToNearestOnPlane=function(t,n){o.defined(n)||(n=[]);const e=t.length;n.length=e;for(let i=0;i<e;i++)n[i]=this.projectPointToNearestOnPlane(t[i],n[i]);return n};const u=new e.Cartesian3;c.prototype.projectPointOntoEllipsoid=function(t,n){o.defined(n)||(n=new e.Cartesian3);const i=this._ellipsoid,r=this._origin,s=this._xAxis,a=this._yAxis,l=u;return e.Cartesian3.multiplyByScalar(s,t.x,l),n=e.Cartesian3.add(r,l,n),e.Cartesian3.multiplyByScalar(a,t.y,l),e.Cartesian3.add(n,l,n),i.scaleToGeocentricSurface(n,n),n},c.prototype.projectPointsOntoEllipsoid=function(t,n){const e=t.length;o.defined(n)?n.length=e:n=new Array(e);for(let i=0;i<e;++i)n[i]=this.projectPointOntoEllipsoid(t[i],n[i]);return n},t.EllipsoidTangentPlane=c}));