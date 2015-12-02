<!DOCTYPE html>
<html>
    <head>
        <title>VRSeen | 免费高清360度全景视频 | {{$post->title}}</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
        <style>
            body {
                margin: 0;
                overflow: hidden;
                background-color: #000;
            }
            a{
                color: #528CE0;
            }
            #demo{
                /* comment for fixed dimentsions */
                position: relative;
                margin: 0 auto;
                overflow: hidden;
                cursor: move; /* fallback if grab cursor is unsupported */
                cursor: grab;
                cursor: -moz-grab;
                cursor: -webkit-grab;
            }
            #demo:active { 
                cursor: grabbing;
                cursor: -moz-grabbing;
                cursor: -webkit-grabbing;
            }
            #log{
                position: absolute;
                background: #fff;
                padding: 20px;
                bottom: 20px;
                left: 20px;
                width: 150px;
                font: normal 12px/18px Monospace, Arial, Helvetical, sans-serif;
                text-align: left;
                border: 3px double #ddd;
            }
            #description{
                display: inline-block;
                width: 100%;
                max-width: 600px;
                text-align: left;
            }
        </style>
    </head>
    <body>
        <div id="demo">
            <div id="log"></div>
        </div>        

        <script src="three.min.js"></script>        
        <script>
            "use strict";
var camera,
        scene,
        element = document.getElementById('demo'), // Inject scene into this
        renderer,
        onPointerDownPointerX,
        onPointerDownPointerY,
        onPointerDownLon,
        onPointerDownLat,
        fov = 70, // Field of View
        isUserInteracting = false,
        lon = 0,
        lat = 0,
        phi = 0,
        theta = 0,
        onMouseDownMouseX = 0,
        onMouseDownMouseY = 0,
        onMouseDownLon = 0,
        onMouseDownLat = 0,
        width = window.innerWidth, // int || window.innerWidth
        height = window.innerHeight, // int || window.innerHeight
        ratio = width / height;
var texture = THREE.ImageUtils.loadTexture('{{env('UPLOAD_PATH').$post->file}}', new THREE.UVMapping(), function() {
    init();
    animate();
});
function init() {
    camera = new THREE.PerspectiveCamera(fov, ratio, 1, 1000);
    scene = new THREE.Scene();
    var mesh = new THREE.Mesh(new THREE.SphereGeometry(500, 60, 40), new THREE.MeshBasicMaterial({map: texture}));
    mesh.scale.x = -1;
    scene.add(mesh);
    renderer = new THREE.WebGLRenderer({antialias: true});
    renderer.setSize(width, height);
    element.appendChild(renderer.domElement);
    element.addEventListener('mousedown', onDocumentMouseDown, false);
    element.addEventListener('mousewheel', onDocumentMouseWheel, false);
    element.addEventListener('DOMMouseScroll', onDocumentMouseWheel, false);
    window.addEventListener('resize', onWindowResized, false);
    onWindowResized(null);
}
function onWindowResized(event) {
//    renderer.setSize(window.innerWidth, window.innerHeight);
//    camera.projectionMatrix.makePerspective(fov, window.innerWidth / window.innerHeight, 1, 1100);
    renderer.setSize(width, height);
    camera.projectionMatrix.makePerspective(fov, ratio, 1, 1100);
}
function onDocumentMouseDown(event) {
    event.preventDefault();
    onPointerDownPointerX = event.clientX;
    onPointerDownPointerY = event.clientY;
    onPointerDownLon = lon;
    onPointerDownLat = lat;
    isUserInteracting = true;
    element.addEventListener('mousemove', onDocumentMouseMove, false);
    element.addEventListener('mouseup', onDocumentMouseUp, false);
}
function onDocumentMouseMove(event) {
    lon = (event.clientX - onPointerDownPointerX) * -0.175 + onPointerDownLon;
    lat = (event.clientY - onPointerDownPointerY) * -0.175 + onPointerDownLat;
}
function onDocumentMouseUp(event) {
    isUserInteracting = false;
    element.removeEventListener('mousemove', onDocumentMouseMove, false);
    element.removeEventListener('mouseup', onDocumentMouseUp, false);
}
function onDocumentMouseWheel(event) {
    // WebKit
    if (event.wheelDeltaY) {
        fov -= event.wheelDeltaY * 0.05;
        // Opera / Explorer 9
    } else if (event.wheelDelta) {
        fov -= event.wheelDelta * 0.05;
        // Firefox
    } else if (event.detail) {
        fov += event.detail * 1.0;
    }
    if (fov < 45 || fov > 90) {
        fov = (fov < 45) ? 45 : 90;
    }
    camera.projectionMatrix.makePerspective(fov, ratio, 1, 1100);
}
function animate() {
    requestAnimationFrame(animate);
    render();
}
function render() {
    if (isUserInteracting === false) {
        lon += .05;
    }
    lat = Math.max(-85, Math.min(85, lat));
    phi = THREE.Math.degToRad(90 - lat);
    theta = THREE.Math.degToRad(lon);
    camera.position.x = 100 * Math.sin(phi) * Math.cos(theta);
    camera.position.y = 100 * Math.cos(phi);
    camera.position.z = 100 * Math.sin(phi) * Math.sin(theta);
    var log = ("x: " + camera.position.x);
    log = log + ("<br/>y: " + camera.position.y);
    log = log + ("<br/>z: " + camera.position.z);
    log = log + ("<br/>fov: " + fov);
    document.getElementById('log').innerHTML = log;
    camera.lookAt(scene.position);
    renderer.render(scene, camera);
}
</script>       
    </body>
</html>
