(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[5],{2651:function(t,e,a){"use strict";var n=a("eda5e"),i=a.n(n);i.a},8071:function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("q-layout",{staticClass:"bg-grey-3",attrs:{view:"hHh lpR lFf"}},[a("q-header",{staticClass:"bg-primary text-white"},[a("q-toolbar",[a("q-btn",{staticClass:"lt-md",attrs:{dense:"",icon:"menu",flat:""},on:{click:function(e){t.left=!t.left}}}),t._v("\n      "+t._s(t.$store.getters.title())+"\n    ")],1)],1),a("q-drawer",{staticClass:"kk-drawer",attrs:{side:"left",bordered:"","show-if-above":"",mini:t.miniState},on:{mouseover:function(e){t.miniState=!1},mouseout:function(e){t.miniState=!0}},model:{value:t.left,callback:function(e){t.left=e},expression:"left"}},[a("q-list",{staticClass:"rounded-borders text-info q-mt-lg"},t._l(t.$store.getters["menu"],(function(e){return a("q-item",{directives:[{name:"ripple",rawName:"v-ripple"}],key:e.label,attrs:{clickable:"",active:!1,"active-class":"bg-info text-white",to:{name:e.name,params:e.params}}},[a("q-item-section",{attrs:{avatar:""}},[a("q-icon",{attrs:{name:e.icon}})],1),a("q-item-section",[t._v(t._s(t.$t(e.label)))])],1)})),1)],1),a("q-page-container",[a("router-view")],1)],1)},i=[],s={name:"InternalLayout",data:function(){return{left:!0,miniState:!0}}},r=s,l=(a("2651"),a("2877")),o=Object(l["a"])(r,n,i,!1,null,null,null);e["default"]=o.exports},eda5e:function(t,e,a){}}]);