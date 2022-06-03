define(["./AttributeCompression-27507afe","./Matrix2-37e55508","./Color-1ca27bfa","./defaultValue-81eec7ed","./IndexDatatype-f1dcdf35","./ComponentDatatype-a15c9a19","./OrientedBoundingBox-86a6888d","./createTaskProcessorWorker","./RuntimeError-8952249c","./Transforms-dca21951","./_commonjsHelpers-3aae1032-26891ab7","./combine-3c023bda","./WebGLConstants-508b9636","./EllipsoidTangentPlane-06e319ef","./AxisAlignedBoundingBox-0ddf9b79","./IntersectionTests-ee135b8e","./Plane-6ee42cab"],(function(e,t,n,a,r,o,s,i,c,f,d,l,u,h,g,p,b){"use strict";const m=new t.Cartesian3,y=new t.Ellipsoid,C=new t.Rectangle,I={min:void 0,max:void 0,indexBytesPerElement:void 0};const x=new t.Cartesian3,w=new t.Cartesian3,A=new t.Cartesian3,E=new t.Cartesian3,N=new t.Cartesian3,T=new t.Cartographic,B=new t.Rectangle;return i((function(i,c){let f;!function(e){const n=new Float64Array(e);let a=0;I.indexBytesPerElement=n[a++],I.min=n[a++],I.max=n[a++],t.Cartesian3.unpack(n,a,m),a+=t.Cartesian3.packedLength,t.Ellipsoid.unpack(n,a,y),a+=t.Ellipsoid.packedLength,t.Rectangle.unpack(n,a,C)}(i.packedBuffer),f=2===I.indexBytesPerElement?new Uint16Array(i.indices):new Uint32Array(i.indices);const d=new Uint16Array(i.positions),l=new Uint32Array(i.counts),u=new Uint32Array(i.indexCounts),h=new Uint32Array(i.batchIds),g=new Uint32Array(i.batchTableColors),p=new Array(l.length),b=m,k=y;let L=C;const O=I.min,U=I.max;let P,F,S,D=i.minimumHeights,R=i.maximumHeights;a.defined(D)&&a.defined(R)&&(D=new Float32Array(D),R=new Float32Array(R));const _=d.length/2,M=d.subarray(0,_),G=d.subarray(_,2*_);e.AttributeCompression.zigZagDeltaDecode(M,G);const V=new Float64Array(3*_);for(P=0;P<_;++P){const e=M[P],n=G[P],a=o.CesiumMath.lerp(L.west,L.east,e/32767),r=o.CesiumMath.lerp(L.south,L.north,n/32767),s=t.Cartographic.fromRadians(a,r,0,T),i=k.cartographicToCartesian(s,x);t.Cartesian3.pack(i,V,3*P)}const Y=l.length,H=new Array(Y),v=new Array(Y);let W=0,j=0;for(P=0;P<Y;++P)H[P]=W,v[P]=j,W+=l[P],j+=u[P];const z=new Float32Array(3*_*2),Z=new Uint16Array(2*_),q=new Uint32Array(v.length),J=new Uint32Array(u.length);let K=[];const Q={};for(P=0;P<Y;++P)S=g[P],a.defined(Q[S])?(Q[S].positionLength+=l[P],Q[S].indexLength+=u[P],Q[S].batchIds.push(P)):Q[S]={positionLength:l[P],indexLength:u[P],offset:0,indexOffset:0,batchIds:[P]};let X,$=0,ee=0;for(S in Q)if(Q.hasOwnProperty(S)){X=Q[S],X.offset=$,X.indexOffset=ee;const e=2*X.positionLength,t=2*X.indexLength+6*X.positionLength;$+=e,ee+=t,X.indexLength=t}const te=[];for(S in Q)Q.hasOwnProperty(S)&&(X=Q[S],te.push({color:n.Color.fromRgba(parseInt(S)),offset:X.indexOffset,count:X.indexLength,batchIds:X.batchIds}));for(P=0;P<Y;++P){S=g[P],X=Q[S];const e=X.offset;let n=3*e,r=e;const o=H[P],i=l[P],c=h[P];let d=O,m=U;a.defined(D)&&a.defined(R)&&(d=D[P],m=R[P]);let y=Number.POSITIVE_INFINITY,C=Number.NEGATIVE_INFINITY,I=Number.POSITIVE_INFINITY,_=Number.NEGATIVE_INFINITY;for(F=0;F<i;++F){const e=t.Cartesian3.unpack(V,3*o+3*F,x);k.scaleToGeodeticSurface(e,e);const a=k.cartesianToCartographic(e,T),s=a.latitude,i=a.longitude;y=Math.min(s,y),C=Math.max(s,C),I=Math.min(i,I),_=Math.max(i,_);const f=k.geodeticSurfaceNormal(e,w);let l=t.Cartesian3.multiplyByScalar(f,d,A);const u=t.Cartesian3.add(e,l,E);l=t.Cartesian3.multiplyByScalar(f,m,l);const h=t.Cartesian3.add(e,l,N);t.Cartesian3.subtract(h,b,h),t.Cartesian3.subtract(u,b,u),t.Cartesian3.pack(h,z,n),t.Cartesian3.pack(u,z,n+3),Z[r]=c,Z[r+1]=c,n+=6,r+=2}L=B,L.west=I,L.east=_,L.south=y,L.north=C,p[P]=s.OrientedBoundingBox.fromRectangle(L,O,U,k);let M=X.indexOffset;const G=v[P],Y=u[P];for(q[P]=M,F=0;F<Y;F+=3){const t=f[G+F]-o,n=f[G+F+1]-o,a=f[G+F+2]-o;K[M++]=2*t+e,K[M++]=2*n+e,K[M++]=2*a+e,K[M++]=2*a+1+e,K[M++]=2*n+1+e,K[M++]=2*t+1+e}for(F=0;F<i;++F){const t=F,n=(F+1)%i;K[M++]=2*t+1+e,K[M++]=2*n+e,K[M++]=2*t+e,K[M++]=2*t+1+e,K[M++]=2*n+1+e,K[M++]=2*n+e}X.offset+=2*i,X.indexOffset=M,J[P]=M-q[P]}K=r.IndexDatatype.createTypedArray(z.length/3,K);const ne=te.length;for(let e=0;e<ne;++e){const t=te[e].batchIds;let n=0;const a=t.length;for(let e=0;e<a;++e)n+=J[t[e]];te[e].count=n}const ae=function(e,t,a){const r=t.length,o=2+r*s.OrientedBoundingBox.packedLength+1+function(e){const t=e.length;let a=0;for(let r=0;r<t;++r)a+=n.Color.packedLength+3+e[r].batchIds.length;return a}(a),i=new Float64Array(o);let c=0;i[c++]=e,i[c++]=r;for(let e=0;e<r;++e)s.OrientedBoundingBox.pack(t[e],i,c),c+=s.OrientedBoundingBox.packedLength;const f=a.length;i[c++]=f;for(let e=0;e<f;++e){const t=a[e];n.Color.pack(t.color,i,c),c+=n.Color.packedLength,i[c++]=t.offset,i[c++]=t.count;const r=t.batchIds,o=r.length;i[c++]=o;for(let e=0;e<o;++e)i[c++]=r[e]}return i}(2===K.BYTES_PER_ELEMENT?r.IndexDatatype.UNSIGNED_SHORT:r.IndexDatatype.UNSIGNED_INT,p,te);return c.push(z.buffer,K.buffer,q.buffer,J.buffer,Z.buffer,ae.buffer),{positions:z.buffer,indices:K.buffer,indexOffsets:q.buffer,indexCounts:J.buffer,batchIds:Z.buffer,packedBuffer:ae.buffer}}))}));