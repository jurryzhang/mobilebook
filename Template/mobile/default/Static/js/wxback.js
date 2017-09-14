// ;!function(pkg, undefined){
//     var STATE = 'wx-back';
//     var element;
//
//     var onPopState = function(event){
//         event.state === STATE && fire();
//     }
//
//     var record = function(state){
//         history.pushState(state, null, location.href);
//     }
//
//     var fire = function(){
//         var event = document.createEvent('Events');
//         event.initEvent(STATE, false, false);
//         element.dispatchEvent(event);
//     }
//
//     var listen = function(listener){
//             element.addEventListener(STATE, listener, false);
//         }
//
//         ;!function(){
//         element = document.createElement('span');
//         window.addEventListener('popstate', onPopState);
//         this.listen = listen;
//         if(history.length<=2) {
//             record(STATE);
//         }
//     }.call(window[pkg] = window[pkg] || {});
//
// }('WXBack');


// var STATE = 'wx-back';
// var record = function(state){
//     history.pushState(state, null, "#");
// }
// window.onload = function() {
//     if(history.length<=2) {
//         record(STATE);
//     }
//     setTimeout(function() {
//         if (history.pushState) {
//             window.addEventListener('popstate', function (e) {
//                 alert("location: " + document.location + ", state: " + JSON.stringify(event.state));
//                 //                        var currentState = history.state;
//                 //                        alert(currentState);
//                 if (!!({$isweixin}&&event.state=="wx-back")) {
//                     WeixinJSBridge.call('closeWindow');
//                 }
//             });
//         }
//         else
//         {
//             alert("不支持")
//         }
//     }, 10);
// };