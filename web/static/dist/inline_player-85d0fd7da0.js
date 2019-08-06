"use strict";function _typeof(e){return(_typeof="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}var InlinePlayer=function(n){var r={};function o(e){if(r[e])return r[e].exports;var t=r[e]={i:e,l:!1,exports:{}};return n[e].call(t.exports,t,t.exports,o),t.l=!0,t.exports}return o.m=n,o.c=r,o.d=function(e,t,n){o.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},o.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.t=function(t,e){if(1&e&&(t=o(t)),8&e)return t;if(4&e&&"object"==_typeof(t)&&t&&t.__esModule)return t;var n=Object.create(null);if(o.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)o.d(n,r,function(e){return t[e]}.bind(null,r));return n},o.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(t,"a",t),t},o.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},o.p="/static/dist",o(o.s=5)}([function(n,r,e){(function(d){var e,t;void 0===(t="function"==typeof(e=function(){var r,i={},e="undefined"!=typeof window?window:d,t=e.document,o="localStorage";if(i.disabled=!1,i.version="1.3.20",i.set=function(e,t){},i.get=function(e,t){},i.has=function(e){return void 0!==i.get(e)},i.remove=function(e){},i.clear=function(){},i.transact=function(e,t,n){null==n&&(n=t,t=null),null==t&&(t={});var r=i.get(e,t);n(r),i.set(e,r)},i.getAll=function(){},i.forEach=function(){},i.serialize=function(e){return JSON.stringify(e)},i.deserialize=function(t){if("string"==typeof t)try{return JSON.parse(t)}catch(e){return t||void 0}},function(){try{return o in e&&e[o]}catch(e){return!1}}())r=e[o],i.set=function(e,t){return void 0===t?i.remove(e):(r.setItem(e,i.serialize(t)),t)},i.get=function(e,t){var n=i.deserialize(r.getItem(e));return void 0===n?t:n},i.remove=function(e){r.removeItem(e)},i.clear=function(){r.clear()},i.getAll=function(){var n={};return i.forEach(function(e,t){n[e]=t}),n},i.forEach=function(e){for(var t=0;t<r.length;t++){var n=r.key(t);e(n,i.get(n))}};else if(t&&t.documentElement.addBehavior){var a,n;try{(n=new ActiveXObject("htmlfile")).open(),n.write('<script>document.w=window<\/script><iframe src="/favicon.ico"></iframe>'),n.close(),a=n.w.frames[0].document,r=a.createElement("div")}catch(i){r=t.createElement("div"),a=t.body}var u=function(n){return function(){var e=Array.prototype.slice.call(arguments,0);e.unshift(r),a.appendChild(r),r.addBehavior("#default#userData"),r.load(o);var t=n.apply(i,e);return a.removeChild(r),t}},s=new RegExp("[!\"#$%&'()*+,/\\\\:;<=>?@[\\]^`{|}~]","g"),l=function(e){return e.replace(/^d/,"___$&").replace(s,"___")};i.set=u(function(e,t,n){return t=l(t),void 0===n?i.remove(t):(e.setAttribute(t,i.serialize(n)),e.save(o),n)}),i.get=u(function(e,t,n){t=l(t);var r=i.deserialize(e.getAttribute(t));return void 0===r?n:r}),i.remove=u(function(e,t){t=l(t),e.removeAttribute(t),e.save(o)}),i.clear=u(function(e){var t=e.XMLDocument.documentElement.attributes;e.load(o);for(var n=t.length-1;0<=n;n--)e.removeAttribute(t[n].name);e.save(o)}),i.getAll=function(e){var n={};return i.forEach(function(e,t){n[e]=t}),n},i.forEach=u(function(e,t){for(var n,r=e.XMLDocument.documentElement.attributes,o=0;n=r[o];++o)t(n.name,i.deserialize(e.getAttribute(n.name)))})}try{var c="__storejs__";i.set(c,c),i.get(c)!=c&&(i.disabled=!0),i.remove(c)}catch(r){i.disabled=!0}return i.enabled=!i.disabled,i})?e.apply(r,[]):e)||(n.exports=t)}).call(this,e(4))},function(e,t,n){var r=n(2);"string"==typeof r&&(r=[[e.i,r,""]]),r.locals&&(e.exports=r.locals),(0,n(6).default)("7d88b2a8",r,!1,{})},function(e,t,n){(e.exports=n(3)(!1)).push([e.i,"\n.player-inline .player-volume-range {\n  width: 100px;\n}\n",""])},function(e,t,n){e.exports=function(n){var a=[];return a.toString=function(){return this.map(function(e){var t=function(e,t){var n,r=e[1]||"",o=e[3];if(!o)return r;if(t&&"function"==typeof btoa){var i=(n=o,"/*# sourceMappingURL=data:application/json;charset=utf-8;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(n))))+" */"),a=o.sources.map(function(e){return"/*# sourceURL="+o.sourceRoot+e+" */"});return[r].concat(a).concat([i]).join("\n")}return[r].join("\n")}(e,n);return e[2]?"@media "+e[2]+"{"+t+"}":t}).join("")},a.i=function(e,t){"string"==typeof e&&(e=[[null,e,""]]);for(var n={},r=0;r<this.length;r++){var o=this[r][0];null!=o&&(n[o]=!0)}for(r=0;r<e.length;r++){var i=e[r];null!=i[0]&&n[i[0]]||(t&&!i[2]?i[2]=t:t&&(i[2]="("+i[2]+") and ("+t+")"),a.push(i))}},a}},function(e,t){var n;n=function(){return this}();try{n=n||new Function("return this")()}catch(e){"object"==("undefined"==typeof window?"undefined":_typeof(window))&&(n=window)}e.exports=n},function(e,t,n){n.r(t);function r(){var t=this,e=t.$createElement,n=t._self._c||e;return t.is_playing?n("div",{staticClass:"dropdown ml-3 player-inline"},[t._m(0),t._v(" "),n("ul",{staticClass:"dropdown-menu dropdown-menu-right"},[n("li",[n("a",{staticClass:"dropdown-item jp-pause",attrs:{href:"#"},on:{click:function(e){return e.preventDefault(),t.stop()}}},[n("i",{staticClass:"material-icons",attrs:{"aria-hidden":"true"}},[t._v("pause")]),t._v("\n                "+t._s(t.$t("pause"))+"\n            ")])]),t._v(" "),n("li",[n("span",{staticClass:"dropdown-item dropdown-item-text"},[n("i",{staticClass:"material-icons",attrs:{"aria-hidden":"true"}},[t._v("volume_up")]),t._v(" "),n("input",{directives:[{name:"model",rawName:"v-model",value:t.volume,expression:"volume"}],staticClass:"player-volume-range custom-range",attrs:{type:"range",title:t.$t("volume"),min:"0",max:"100",step:"1"},domProps:{value:t.volume},on:{__r:function(e){t.volume=e.target.value}}})])])])]):t._e()}var o=n(0),i=n.n(o),a={data:function(){return{is_playing:!1,volume:55,audio:null}},created:function(){var t=this;this.audio=document.createElement("audio"),this.audio.onended=function(){t.stop()},"mediaSession"in navigator&&navigator.mediaSession.setActionHandler("pause",function(){t.stop()}),i.a.enabled&&void 0!==i.a.get("player_volume")&&(this.volume=i.a.get("player_volume",this.volume)),this.$eventHub.$on("player_toggle",function(e){t.is_playing&&t.audio.src===e?t.stop():t.play(e)})},watch:{volume:function(e){this.audio.volume=Math.min((Math.exp(e/100)-1)/(Math.E-1),1),i.a.enabled&&i.a.set("player_volume",e)}},methods:{play:function(e){this.audio.src=e,this.audio.load(),this.audio.play(),this.is_playing=!0,this.$eventHub.$emit("player_playing",e)},stop:function(){var e=this;this.is_playing=!1,this.$eventHub.$emit("player_stopped",this.audio.src),this.audio.pause(),this.audio.src="",setTimeout(function(){e.audio.load()})}}},u=(r._withStripped=!0,function(e,t,n,r,o,i,a,u){var s=_typeof((e=e||{}).default);"object"!==s&&"function"!==s||(e=e.default);var l,c="function"==typeof e?e.options:e;if(c.render=t,c.staticRenderFns=[function(){var e=this.$createElement,t=this._self._c||e;return t("button",{staticClass:"navbar-toggler",attrs:{"aria-expanded":"false","aria-haspopup":"true","data-toggle":"dropdown",type:"button"}},[t("i",{staticClass:"material-icons",attrs:{"aria-hidden":"true"}},[this._v("radio")])])}],c._compiled=!0,l=o)if(c.functional){c._injectStyles=l;var d=c.render;c.render=function(e,t){return l.call(t),d(e,t)}}else{var f=c.beforeCreate;c.beforeCreate=f?[].concat(f,l):[l]}return{exports:e,options:c}}(a,r,0,0,function(e){n(1)}));u.options.__file="vue/inline_player.vue",t.default=u.exports},function(e,t,n){function s(e,t){for(var n=[],r={},o=0;o<t.length;o++){var i=t[o],a=i[0],u={id:e+":"+o,css:i[1],media:i[2],sourceMap:i[3]};r[a]?r[a].parts.push(u):n.push(r[a]={id:a,parts:[u]})}return n}n.r(t),n.d(t,"default",function(){return v});var r="undefined"!=typeof document;if("undefined"!=typeof DEBUG&&DEBUG&&!r)throw new Error("vue-style-loader cannot be used in a non-browser environment. Use { target: 'node' } in your Webpack config to indicate a server-rendering environment.");var l={},o=r&&(document.head||document.getElementsByTagName("head")[0]),i=null,a=0,c=!1,u=function(){},d=null,f="data-vue-ssr-id",p="undefined"!=typeof navigator&&/msie [6-9]\b/.test(navigator.userAgent.toLowerCase());function v(a,e,t,n){c=t,d=n||{};var u=s(a,e);return m(u),function(e){for(var t=[],n=0;n<u.length;n++){var r=u[n];(o=l[r.id]).refs--,t.push(o)}for(e?m(u=s(a,e)):u=[],n=0;n<t.length;n++){var o;if(0===(o=t[n]).refs){for(var i=0;i<o.parts.length;i++)o.parts[i]();delete l[o.id]}}}}function m(e){for(var t=0;t<e.length;t++){var n=e[t],r=l[n.id];if(r){r.refs++;for(var o=0;o<r.parts.length;o++)r.parts[o](n.parts[o]);for(;o<n.parts.length;o++)r.parts.push(y(n.parts[o]));r.parts.length>n.parts.length&&(r.parts.length=n.parts.length)}else{var i=[];for(o=0;o<n.parts.length;o++)i.push(y(n.parts[o]));l[n.id]={id:n.id,refs:1,parts:i}}}}function h(){var e=document.createElement("style");return e.type="text/css",o.appendChild(e),e}function y(t){var n,r,e=document.querySelector("style["+f+'~="'+t.id+'"]');if(e){if(c)return u;e.parentNode.removeChild(e)}if(p){var o=a++;e=i||(i=h()),n=_.bind(null,e,o,!1),r=_.bind(null,e,o,!0)}else e=h(),n=function(e,t){var n=t.css,r=t.media,o=t.sourceMap;if(r&&e.setAttribute("media",r),d.ssrId&&e.setAttribute(f,t.id),o&&(n+="\n/*# sourceURL="+o.sources[0]+" */",n+="\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(o))))+" */"),e.styleSheet)e.styleSheet.cssText=n;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(n))}}.bind(null,e),r=function(){e.parentNode.removeChild(e)};return n(t),function(e){if(e){if(e.css===t.css&&e.media===t.media&&e.sourceMap===t.sourceMap)return;n(t=e)}else r()}}var g,b=(g=[],function(e,t){return g[e]=t,g.filter(Boolean).join("\n")});function _(e,t,n,r){var o=n?"":r.css;if(e.styleSheet)e.styleSheet.cssText=b(t,o);else{var i=document.createTextNode(o),a=e.childNodes;a[t]&&e.removeChild(a[t]),a.length?e.insertBefore(i,a[t]):e.appendChild(i)}}}]);