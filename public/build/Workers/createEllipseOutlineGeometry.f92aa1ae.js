define(["./Matrix2-7fbd2afb","./defaultValue-81eec7ed","./EllipseOutlineGeometry-acd7d0c0","./RuntimeError-8952249c","./ComponentDatatype-be80d12c","./WebGLConstants-508b9636","./GeometryOffsetAttribute-8c5e10db","./Transforms-969e35b7","./_commonjsHelpers-3aae1032-26891ab7","./combine-3c023bda","./EllipseGeometryLibrary-97a827f4","./GeometryAttribute-6e58c1bc","./GeometryAttributes-32b29525","./IndexDatatype-a852edb7"],(function(e,t,r,i,n,o,a,l,c,s,b,d,u,m){"use strict";return function(i,n){return t.defined(n)&&(i=r.EllipseOutlineGeometry.unpack(i,n)),i._center=e.Cartesian3.clone(i._center),i._ellipsoid=e.Ellipsoid.clone(i._ellipsoid),r.EllipseOutlineGeometry.createGeometry(i)}}));