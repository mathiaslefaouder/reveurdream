define(["./defaultValue-81eec7ed","./Matrix2-7fbd2afb","./RuntimeError-8952249c","./EllipsoidGeometry-195bae20","./VertexFormat-a0b706b0","./ComponentDatatype-be80d12c","./WebGLConstants-508b9636","./GeometryOffsetAttribute-8c5e10db","./Transforms-969e35b7","./_commonjsHelpers-3aae1032-26891ab7","./combine-3c023bda","./GeometryAttribute-6e58c1bc","./GeometryAttributes-32b29525","./IndexDatatype-a852edb7"],(function(e,t,i,r,o,a,n,s,c,d,l,m,u,p){"use strict";function y(i){const o=e.defaultValue(i.radius,1),a={radii:new t.Cartesian3(o,o,o),stackPartitions:i.stackPartitions,slicePartitions:i.slicePartitions,vertexFormat:i.vertexFormat};this._ellipsoidGeometry=new r.EllipsoidGeometry(a),this._workerName="createSphereGeometry"}y.packedLength=r.EllipsoidGeometry.packedLength,y.pack=function(e,t,i){return r.EllipsoidGeometry.pack(e._ellipsoidGeometry,t,i)};const G=new r.EllipsoidGeometry,b={radius:void 0,radii:new t.Cartesian3,vertexFormat:new o.VertexFormat,stackPartitions:void 0,slicePartitions:void 0};return y.unpack=function(i,a,n){const s=r.EllipsoidGeometry.unpack(i,a,G);return b.vertexFormat=o.VertexFormat.clone(s._vertexFormat,b.vertexFormat),b.stackPartitions=s._stackPartitions,b.slicePartitions=s._slicePartitions,e.defined(n)?(t.Cartesian3.clone(s._radii,b.radii),n._ellipsoidGeometry=new r.EllipsoidGeometry(b),n):(b.radius=s._radii.x,new y(b))},y.createGeometry=function(e){return r.EllipsoidGeometry.createGeometry(e._ellipsoidGeometry)},function(t,i){return e.defined(i)&&(t=y.unpack(t,i)),y.createGeometry(t)}}));