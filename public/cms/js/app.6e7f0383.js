(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[1],{0:function(e,t,n){e.exports=n("2f39")},"2f39":function(e,t,n){"use strict";n.r(t);var a=n("967e"),s=n.n(a),r=(n("a481"),n("96cf"),n("fa84")),o=n.n(r),i=(n("7d6e"),n("e54f"),n("573e"),n("985d"),n("31cd"),n("6ba9"),n("2b0e")),c=n("1f91"),u=n("42d2"),l=n("b05d"),d=n("cb32"),f=n("9c40"),b=n("ead5"),p=n("079eb"),m=n("f20b"),g=n("e7a9"),j=n("f09f"),h=n("a370"),v=n("4b7e"),k=n("58ea"),y=n("8f8e"),w=n("b047"),Q=n("52ee"),x=n("24e8"),_=n("9404"),C=n("8572"),O=n("7d53"),P=n("0378"),z=n("e359"),S=n("0016"),E=n("66e5"),L=n("0170"),T=n("4074"),I=n("068f"),D=n("27f9"),U=n("74f7"),B=n("4d5a"),$=n("1c1c"),A=n("6b1d"),R=n("09e3"),M=n("7cbe"),F=n("3786"),q=n("0e51"),V=n("ddd8"),W=n("eb85"),H=n("293e"),N=n("0d59"),G=n("ca78"),J=n("429b"),K=n("7460"),X=n("823b"),Y=n("adad"),Z=n("65c6"),ee=n("05c0"),te=n("714f"),ne=n("7f67"),ae=n("18d6"),se=n("2a19"),re=n("436b");i["a"].use(l["a"],{config:{},lang:c["a"],iconSet:u["a"],components:{QAvatar:d["a"],QBtn:f["a"],QBreadcrumbs:b["a"],QBreadcrumbsEl:p["a"],QBtnDropdown:m["a"],QBtnGroup:g["a"],QCard:j["a"],QCardSection:h["a"],QCardActions:v["a"],QCircularProgress:k["a"],QCheckbox:y["a"],QChip:w["a"],QDate:Q["a"],QDialog:x["a"],QDrawer:_["a"],QField:C["a"],QFile:O["a"],QForm:P["a"],QHeader:z["a"],QIcon:S["a"],QItem:E["a"],QItemLabel:L["a"],QItemSection:T["a"],QImg:I["a"],QInput:D["a"],QInnerLoading:U["a"],QLayout:B["a"],QList:$["a"],QLinearProgress:A["a"],QPageContainer:R["a"],QPopupProxy:M["a"],QRadio:F["a"],QResponsive:q["a"],QSelect:V["a"],QSeparator:W["a"],QSkeleton:H["a"],QSpinner:N["a"],QTime:G["a"],QTabs:J["a"],QTab:K["a"],QTabPanel:X["a"],QTabPanels:Y["a"],QToolbar:Z["a"],QTooltip:ee["a"]},directives:{Ripple:te["a"],ClosePopup:ne["a"]},plugins:{LocalStorage:ae["a"],Notify:se["a"],Dialog:re["a"]}});var oe=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("div",{attrs:{id:"q-app"}},[e.prepared?e._e():n("div",{staticClass:"q-ma-xl flex justify-center"},[n("q-spinner",{attrs:{size:"xl"}})],1),e.prepared?n("router-view"):e._e()],1)},ie=[],ce=(n("7f7f"),{name:"Kusikusi",data:function(){return{prepared:!1}},created:function(){var e=this;return o()(s.a.mark((function t(){var n,a;return s.a.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return e.$api.baseURL="/api",t.next=3,e.$api.get("/cms/config");case 3:return n=t.sent,n.success&&e.$store.commit("setConfig",n.data),t.next=7,e.$store.dispatch("getLocalSession");case 7:if(!e.$store.getters.hasAuthtoken){t.next=14;break}return t.next=10,e.$api.get("/user/me");case 10:a=t.sent,a.status>=400&&"login"!==e.$route.name?(e.prepared=!0,e.$router.push({name:"login"})):(e.$store.commit("setUser",a.data),e.prepared=!0),t.next=16;break;case 14:e.prepared=!0,"login"!==e.$route.name&&e.$router.push({name:"login"});case 16:case"end":return t.stop()}}),t)})))()}}),ue=ce,le=n("2877"),de=Object(le["a"])(ue,oe,ie,!1,null,null,null),fe=de.exports,be=n("4bde"),pe=n("2f62"),me=(n("8e6e"),n("8a81"),n("ac6a"),n("cadf"),n("06db"),n("456d"),n("28a5"),n("c47a")),ge=n.n(me),je=(n("f559"),n("2ef0")),he=n.n(je);function ve(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);t&&(a=a.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,a)}return n}function ke(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?ve(Object(n),!0).forEach((function(t){ge()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):ve(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var ye={config:{},lang:"en",uiLang:null,currentTitle:"",loading:!1,toolbar:{editButton:!1,saveButton:!1},menuItems:{dashboard:{label:"dashboard.title",icon:"dashboard",name:"dashboard"},content:{label:"contents.home",icon:"home",name:"content",params:{entity_id:"home"}},website:{label:"contents.website",icon:"web",name:"content",params:{entity_id:"website"}},media:{label:"media.title",icon:"photo",name:"media"},users:{label:"users.title",icon:"supervised_user_circle",name:"users"},configuration:{label:"settings.title",icon:"settings_applications",name:"settings"},logout:{label:"login.logout",icon:"exit_to_app",name:"login"}}},we={media_url:function(){return""},langs:function(e){return he.a.get(e,"config.langs",[])},defaultLang:function(e){return e.config&&e.config.langs?e.config.langs[0]:""},menu:function(e,t,n){var a=he.a.clone(he.a.get(e,"config.menu.".concat(n.session.user.profile)));return a||(a="admin"===n.session.user.profile?[e.menuItems.content,e.menuItems.website]:[e.menuItems.content]),a.push(e.menuItems.logout),a},iconOf:function(e){return function(t){return he.a.get(e,"config.models[".concat(t,"].icon"),"blur_on")}},nameOf:function(e){return function(t){return he.a.get(e,"config.models[".concat(t,"].name"),t)}}},Qe={getCmsConfig:function(e){return o()(s.a.mark((function t(){var n,a,r;return s.a.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return n=e.commit,t.next=3,i["a"].prototype.$api.get("/config/cms");case 3:a=t.sent,n("setCms",a.result),r=ae["a"].getItem("uiLang"),r&&""!==r||(r=a.result.langs[0]||"en"),n("setUiLang",r);case 8:case"end":return t.stop()}}),t)})))()}},xe={setConfig:function(e,t){for(var n in he.a.set(t,"langs",he.a.get(t,"langs",["en"])),e.lang=t.langs[0],he.a.get(t,"models",[]))for(var a in he.a.get(t,"models[".concat(n,"].form"),[])){var s=[];for(var r in he.a.get(t,"models[".concat(n,"].form[").concat(a,"].components"),[])){var o=he.a.get(t,"models[".concat(n,"].form[").concat(a,"].components[").concat(r,"]"));if(he.a.startsWith(o.value,"contents."))for(var i in t.langs){var c=he.a.cloneDeep(o);c.value+=".".concat(t.langs[i]),c.isMultiLang=!0,c.props=ke({},c.props,{lang:t.langs[i],field:c.value.split(".")[1]}),s.push(c)}else o.isMultiLang=!1,s.push(o)}he.a.set(t,"models[".concat(n,"].form[").concat(a,"].components"),s)}e.config=t},setTitle:function(e,t){e.currentTitle=t},setLang:function(e,t){ae["a"].set("lang",t),e.lang=t},setUiLang:function(e,t){ae["a"].set("uiLang",t),e.uiLang=t},setEditButton:function(e,t){e.toolbar.editButton=t},setSaveButton:function(e,t){e.toolbar.saveButton=t}},_e={namespaced:!1,state:ye,getters:we,actions:Qe,mutations:xe},Ce={user:{profile:""},authtoken:""},Oe={hasAuthtoken:function(e){return""!==e.authtoken},entitiesWithWritePermissions:function(e){for(var t=[],n=0;n<he.a.get(e,"user.permissions.length",0);n++)if("none"!==e.user.permissions[n].write&&"none"!==e.user.permissions[n].read){var a=e.user.permissions[n].entity;a.write=e.user.permissions[n].write,a.read=e.user.permissions[n].read,t.push(a)}return t},entitiesWithPermissions:function(e){for(var t=[],n=0;n<he.a.get(e,"user.permissions.length",0);n++)if("none"!==e.user.permissions[n].read){var a=e.user.permissions[n].entity;a.write=e.user.permissions[n].write,a.read=e.user.permissions[n].read,t.push(a)}return t}},Pe={getLocalSession:function(e){var t=e.dispatch,n=e.commit,a=ae["a"].getItem("kusikusi_session");return a&&a!=={}?(n("setAuthtoken",a.authtoken),n("setUser",a.user)):t("resetUserData"),a},resetUserData:function(e){var t=e.commit;t("setAuthtoken",""),t("setUser",{})}},ze={setAuthtoken:function(e,t){e.authtoken=t,i["a"].prototype.$api.setAuthorization(t);var n=ae["a"].getItem("kusikusi_session")||{};n.authtoken=t,ae["a"].set("kusikusi_session",n)},setUser:function(e,t){e.user=t}},Se={namespaced:!1,state:Ce,getters:Oe,actions:Pe,mutations:ze},Ee={blankEntity:{id:"",model:"",view:"",contents:[],entity_relations:[],parent_entity_id:"",is_active:!0,properties:{},published_at:null,unpublished_at:null,version:0,version_full:0,version_relations:0,version_tree:0,created_at:null,updated_at:null,updated_by:null}},Le={namespaced:!1,state:Ee},Te=Object(be["store"])((function({Vue:e}){e.use(pe["a"]);const t=new pe["a"].Store({modules:{ui:_e,session:Se,content:Le},strict:!1});return t})),Ie=n("8c4f");const De=[{path:"/",component:()=>n.e(7).then(n.bind(null,"fd28")),redirect:{name:"login"},children:[{path:"/login",component:()=>n.e(4).then(n.bind(null,"013f")),name:"login"}]},{path:"/panel",component:()=>n.e(5).then(n.bind(null,"8071")),children:[{path:"/content/:entity_id?/:model?/:conector?/:parent_entity_id?",component:()=>Promise.all([n.e(0),n.e(3)]).then(n.bind(null,"cf8e")),name:"content"}]}];De.push({path:"*",component:()=>n.e(6).then(n.bind(null,"e51e"))});var Ue=De,Be=Object(be["route"])((function({Vue:e}){e.use(Ie["a"]);const t=new Ie["a"]({scrollBehavior:()=>({x:0,y:0}),routes:Ue,mode:"history",base:"/cms/"});return t})),$e=function(){return Ae.apply(this,arguments)};function Ae(){return Ae=o()(s.a.mark((function e(){var t,n,a;return s.a.wrap((function(e){while(1)switch(e.prev=e.next){case 0:if("function"!==typeof Te){e.next=6;break}return e.next=3,Te({Vue:i["a"]});case 3:e.t0=e.sent,e.next=7;break;case 6:e.t0=Te;case 7:if(t=e.t0,"function"!==typeof Be){e.next=14;break}return e.next=11,Be({Vue:i["a"],store:t});case 11:e.t1=e.sent,e.next=15;break;case 14:e.t1=Be;case 15:return n=e.t1,t.$router=n,a={el:"#q-app",router:n,store:t,render:function(e){return e(fe)}},e.abrupt("return",{app:a,store:t,router:n});case 19:case"end":return e.stop()}}),e)}))),Ae.apply(this,arguments)}var Re={en:"Inglés",es:"Español",en_US:"Inglés EU",es_MX:"Español México",es_ES:"Español España",all:"Todos",general:{title:"Kusikusi",subtitle:"By Cuatromedios",pullToRefresh:"Pull to refresh",releaseToRefresh:"release to refresh",refreshing:"Refreshing",serverError:"There was an error reading infromation from the server",back:"Back",edit:"Edit",save:"Save",cancel:"Cancel",confirm:"Confirm",ok:"Ok",sure:"Are you sure?",delete:"Delete",add:"Add",saveError:"There was an error trying to send information"},login:{title:"Login",logout:"Logout",email:"Email",password:"Password",button:"Login",loginInvalid:"Invalid credentials",loginError:"Can't connect to the server",invalidEmail:"Please enter a valid email",invalidPassword:"Please enter a password"},contents:{home:"Home",website:"Website",icon:"ballot",view:"Vista",contents:"Contents",publication:"Publication",active:"Published",delete:"Do you really want to delete this entity?",title:"Title",description:"Description",slug:"Friendly url",footer:"Footer",publishedAt:"Publish at",unpublishedAt:"Stop publishing at",children:"Children",media:"Media",reorder:"Reorder"},media:{title:"Media",singular:"medium",uploader:"Media uploader",upload:"Upload",library:"Library",select:"Select or drop files...",replace:"Replace file",unlink:"Unlink this media",edit:"Edit in new window",unlinkConfirm:"Are you sure you want to unlink the media from this entity?",tags:"Tags",tag:"tag",details:"Details",filename:"Original file name",format:"Format",mimetype:"Mime Type",size:"Size",url:"External URL",lang:"Medium lang",status:{title:"Status",idle:"Idle",failed:"Failed",uploading:"Uploading",uploaded:"Uploaded"}},models:{home:"Home",section:"Section",page:"Page",media:"Media container",medium:"Medium"},qr:{title:"QR Code",print:"Print"},users:{title:"Users"},settings:{title:"Settings"},security:{any:"any",own:"own",none:"none"}},Me={general:{title:"",subtitle:"By Cuatromedios",pullToRefresh:"Desliza para recargar",releaseToRefresh:"Suelta para recargar",refreshing:"Recargando",serverError:"Hubo un error inesperado en el servidor",back:"Regresar"},login:{title:"Ingresar",welcome:"¡Bienvenido!",email:"Correo Electrónico",password:"Contraseña",button:"Ingresar",invalid:"El correo electrónico o la contraseña son incorrectas"},layout:{dashboard:"Tablero",content:"Contenido",media:"Medios",users:"Usuarios",configuration:"Configuración",logout:"Cerrar sesión"},formComponents:{name:"Nombre",title:"Título",description:"Descripción",subtitle:"Subtítiulo",uploadFiles:"Subir archivos"},content:{update:"Actualizar",saveChild:"Crear hijo",delete:"Eliminar",children:"Hijos",saveEntitySuccess:"actualizada exitosamenta",media:"Agregar"}},Fe={"en-us":Re,"es-mx":Me},qe=n("a925");i["a"].use(qe["a"]);const Ve=new qe["a"]({locale:"en-us",fallbackLocale:"en-us",messages:Fe,silentTranslationWarn:!0});var We=Object(be["boot"])(({app:e})=>{e.i18n=Ve}),He=n("bc3a"),Ne=n.n(He),Ge=Object(be["boot"])(({Vue:e})=>{e.prototype.$axios=Ne.a}),Je=n("64c8"),Ke=n("4cf5"),Xe=n("c583"),Ye=n("1335");function Ze(){return et.apply(this,arguments)}function et(){return et=o()(s.a.mark((function e(){var t,n,a,r,o,c,u,l,d;return s.a.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,$e();case 2:t=e.sent,n=t.app,a=t.store,r=t.router,o=!0,c=function(e){o=!1,window.location.href=e},u=window.location.href.replace(window.location.origin,""),l=[We,Ge,Je["a"],Ke["a"],Xe["default"],Ye["a"]],d=0;case 11:if(!(!0===o&&d<l.length)){e.next=29;break}if("function"===typeof l[d]){e.next=14;break}return e.abrupt("continue",26);case 14:return e.prev=14,e.next=17,l[d]({app:n,router:r,store:a,Vue:i["a"],ssrContext:null,redirect:c,urlPath:u});case 17:e.next=26;break;case 19:if(e.prev=19,e.t0=e["catch"](14),!e.t0||!e.t0.url){e.next=24;break}return window.location.href=e.t0.url,e.abrupt("return");case 24:return console.error("[Quasar] boot error:",e.t0),e.abrupt("return");case 26:d++,e.next=11;break;case 29:if(!1!==o){e.next=31;break}return e.abrupt("return");case 31:new i["a"](n);case 32:case"end":return e.stop()}}),e,null,[[14,19]])}))),et.apply(this,arguments)}Ze()},"31cd":function(e,t,n){},4678:function(e,t,n){var a={"./af":"2bfb","./af.js":"2bfb","./ar":"8e73","./ar-dz":"a356","./ar-dz.js":"a356","./ar-kw":"423e","./ar-kw.js":"423e","./ar-ly":"1cfd","./ar-ly.js":"1cfd","./ar-ma":"0a84","./ar-ma.js":"0a84","./ar-sa":"8230","./ar-sa.js":"8230","./ar-tn":"6d83","./ar-tn.js":"6d83","./ar.js":"8e73","./az":"485c","./az.js":"485c","./be":"1fc1","./be.js":"1fc1","./bg":"84aa","./bg.js":"84aa","./bm":"a7fa","./bm.js":"a7fa","./bn":"9043","./bn.js":"9043","./bo":"d26a","./bo.js":"d26a","./br":"6887","./br.js":"6887","./bs":"2554","./bs.js":"2554","./ca":"d716","./ca.js":"d716","./cs":"3c0d","./cs.js":"3c0d","./cv":"03ec","./cv.js":"03ec","./cy":"9797","./cy.js":"9797","./da":"0f14","./da.js":"0f14","./de":"b469","./de-at":"b3eb","./de-at.js":"b3eb","./de-ch":"bb71","./de-ch.js":"bb71","./de.js":"b469","./dv":"598a","./dv.js":"598a","./el":"8d47","./el.js":"8d47","./en-SG":"cdab","./en-SG.js":"cdab","./en-au":"0e6b","./en-au.js":"0e6b","./en-ca":"3886","./en-ca.js":"3886","./en-gb":"39a6","./en-gb.js":"39a6","./en-ie":"e1d3","./en-ie.js":"e1d3","./en-il":"7333","./en-il.js":"7333","./en-nz":"6f50","./en-nz.js":"6f50","./eo":"65db","./eo.js":"65db","./es":"898b","./es-do":"0a3c","./es-do.js":"0a3c","./es-us":"55c9","./es-us.js":"55c9","./es.js":"898b","./et":"ec18","./et.js":"ec18","./eu":"0ff2","./eu.js":"0ff2","./fa":"8df4","./fa.js":"8df4","./fi":"81e9","./fi.js":"81e9","./fo":"0721","./fo.js":"0721","./fr":"9f26","./fr-ca":"d9f8","./fr-ca.js":"d9f8","./fr-ch":"0e49","./fr-ch.js":"0e49","./fr.js":"9f26","./fy":"7118","./fy.js":"7118","./ga":"5120","./ga.js":"5120","./gd":"f6b4","./gd.js":"f6b4","./gl":"8840","./gl.js":"8840","./gom-latn":"0caa","./gom-latn.js":"0caa","./gu":"e0c5","./gu.js":"e0c5","./he":"c7aa","./he.js":"c7aa","./hi":"dc4d","./hi.js":"dc4d","./hr":"4ba9","./hr.js":"4ba9","./hu":"5b14","./hu.js":"5b14","./hy-am":"d6b6","./hy-am.js":"d6b6","./id":"5038","./id.js":"5038","./is":"0558","./is.js":"0558","./it":"6e98","./it-ch":"6f12","./it-ch.js":"6f12","./it.js":"6e98","./ja":"079e","./ja.js":"079e","./jv":"b540","./jv.js":"b540","./ka":"201b","./ka.js":"201b","./kk":"6d79","./kk.js":"6d79","./km":"e81d","./km.js":"e81d","./kn":"3e92","./kn.js":"3e92","./ko":"22f8","./ko.js":"22f8","./ku":"2421","./ku.js":"2421","./ky":"9609","./ky.js":"9609","./lb":"440c","./lb.js":"440c","./lo":"b29d","./lo.js":"b29d","./lt":"26f9","./lt.js":"26f9","./lv":"b97c","./lv.js":"b97c","./me":"293c","./me.js":"293c","./mi":"688b","./mi.js":"688b","./mk":"6909","./mk.js":"6909","./ml":"02fb","./ml.js":"02fb","./mn":"958b","./mn.js":"958b","./mr":"39bd","./mr.js":"39bd","./ms":"ebe4","./ms-my":"6403","./ms-my.js":"6403","./ms.js":"ebe4","./mt":"1b45","./mt.js":"1b45","./my":"8689","./my.js":"8689","./nb":"6ce3","./nb.js":"6ce3","./ne":"3a39","./ne.js":"3a39","./nl":"facd","./nl-be":"db29","./nl-be.js":"db29","./nl.js":"facd","./nn":"b84c","./nn.js":"b84c","./pa-in":"f3ff","./pa-in.js":"f3ff","./pl":"8d57","./pl.js":"8d57","./pt":"f260","./pt-br":"d2d4","./pt-br.js":"d2d4","./pt.js":"f260","./ro":"972c","./ro.js":"972c","./ru":"957c","./ru.js":"957c","./sd":"6784","./sd.js":"6784","./se":"ffff","./se.js":"ffff","./si":"eda5","./si.js":"eda5","./sk":"7be6","./sk.js":"7be6","./sl":"8155","./sl.js":"8155","./sq":"c8f3","./sq.js":"c8f3","./sr":"cf1e","./sr-cyrl":"13e9","./sr-cyrl.js":"13e9","./sr.js":"cf1e","./ss":"52bd","./ss.js":"52bd","./sv":"5fbd","./sv.js":"5fbd","./sw":"74dc","./sw.js":"74dc","./ta":"3de5","./ta.js":"3de5","./te":"5cbb","./te.js":"5cbb","./tet":"576c","./tet.js":"576c","./tg":"3b1b","./tg.js":"3b1b","./th":"10e8","./th.js":"10e8","./tl-ph":"0f38","./tl-ph.js":"0f38","./tlh":"cf75","./tlh.js":"cf75","./tr":"0e81","./tr.js":"0e81","./tzl":"cf51","./tzl.js":"cf51","./tzm":"c109","./tzm-latn":"b53d","./tzm-latn.js":"b53d","./tzm.js":"c109","./ug-cn":"6117","./ug-cn.js":"6117","./uk":"ada2","./uk.js":"ada2","./ur":"5294","./ur.js":"5294","./uz":"2e8c","./uz-latn":"010e","./uz-latn.js":"010e","./uz.js":"2e8c","./vi":"2921","./vi.js":"2921","./x-pseudo":"fd7e","./x-pseudo.js":"fd7e","./yo":"7f33","./yo.js":"7f33","./zh-cn":"5c3a","./zh-cn.js":"5c3a","./zh-hk":"49ab","./zh-hk.js":"49ab","./zh-tw":"90ea","./zh-tw.js":"90ea"};function s(e){var t=r(e);return n(t)}function r(e){if(!n.o(a,e)){var t=new Error("Cannot find module '"+e+"'");throw t.code="MODULE_NOT_FOUND",t}return a[e]}s.keys=function(){return Object.keys(a)},s.resolve=r,e.exports=s,s.id="4678"}},[[0,2,0]]]);