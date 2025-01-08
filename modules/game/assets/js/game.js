// credits: https://github.com/mrdoob/three.js/blob/dev/examples/games_fps.html

import * as THREE from 'three';
import Stats from 'addons/stats.module.js';
import {GLTFLoader} from 'addons/GLTFLoader.js';
import {Octree} from 'addons/Octree.js';
import {OctreeHelper} from 'addons/OctreeHelper.js';
import {Capsule} from 'addons/Capsule.js';
import {GUI} from 'addons/lil-gui.module.min.js';
import {CSS2DObject} from "addons/CSS2DRenderer.js";

document.addEventListener('DOMContentLoaded', function () {
    const trongateToken = document.querySelector('meta[name="trongate-token"]').getAttribute('content');

    let players = [];
    const playersRequest = new XMLHttpRequest();
    playersRequest.open('GET', '/game/players', true);
    playersRequest.setRequestHeader('Content-Type', 'application/json');
    playersRequest.setRequestHeader('Accept', 'application/json');
    playersRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    playersRequest.setRequestHeader('TrongateToken', trongateToken);
    playersRequest.addEventListener('load', (data) => {
        players = JSON.parse(playersRequest.responseText);
    })
    playersRequest.send();

    const container = document.querySelector('#container');

    const player = JSON.parse(container.dataset.player);
    const server = container.dataset.server;

    const fileLoader = new THREE.FileLoader();
    fileLoader.setPath('game_module/');

    const gltfLoader = new GLTFLoader();
    gltfLoader.setPath('game_module/');

    const clock = new THREE.Clock();

    const scene = new THREE.Scene();
    scene.background = new THREE.Color(0x88ccee);
    scene.fog = new THREE.Fog(0x88ccee, 0, 50);

    // camera
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.01, 1000);
    camera.position.set(player.x, player.y, player.z);

    const cameraOrigin = new THREE.Vector3(0, 1.5, 0);
    camera.lookAt(cameraOrigin);
    camera.layers.enableAll();

    const cameraDiv = document.createElement('div');
    cameraDiv.className = 'label';
    cameraDiv.textContent = 'Player';
    cameraDiv.style.backgroundColor = 'transparent';

    const cameraLabel = new CSS2DObject(cameraDiv);
    cameraLabel.position.set(1.5, 0, 0);
    cameraLabel.center.set(0, 1);
    camera.add(cameraLabel);
    cameraLabel.layers.set(0);

    const fillLight1 = new THREE.HemisphereLight(0x8dc1de, 0x00668d, 1.5);
    fillLight1.position.set(2, 1, 1);
    scene.add(fillLight1);

    const directionalLight = new THREE.DirectionalLight(0xffffff, 2.5);
    directionalLight.position.set(-5, 25, -1);
    directionalLight.castShadow = true;
    directionalLight.shadow.camera.near = 0.01;
    directionalLight.shadow.camera.far = 500;
    directionalLight.shadow.camera.right = 30;
    directionalLight.shadow.camera.left = -30;
    directionalLight.shadow.camera.top = 30;
    directionalLight.shadow.camera.bottom = -30;
    directionalLight.shadow.mapSize.width = 1024;
    directionalLight.shadow.mapSize.height = 1024;
    directionalLight.shadow.radius = 4;
    directionalLight.shadow.bias = -0.00006;
    scene.add(directionalLight);

    const renderer = new THREE.WebGLRenderer({
        antialias: true
    });
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.shadowMap.enabled = true;
    renderer.shadowMap.type = THREE.VSMShadowMap;
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    container.appendChild(renderer.domElement);

    const stats = new Stats();
    stats.domElement.style.position = 'absolute';
    stats.domElement.style.top = '0px';
    container.appendChild(stats.domElement);

    socket.onMessage(`game:${server}`, ({event, player, payload}) => {
        switch (event) {
            case 'player:online':
                console.log(player, 'online');

                players.push(player);

                player.group = new THREE.Group();
                player.group.copy(playerGroup);

                scene.add(player.group);
                break;
            case 'player:offline':
                console.log(player, 'offline');

                const playerIndex = players.findIndex((p) => p === player);

                const _player = players[playerIndex];
                scene.remove(_player.group);

                delete players[playerIndex];
                break;
            default:
                console.log({
                    event,
                    player,
                    payload
                });
                break;
        }
    });

    const GRAVITY = 30;

    const NUM_SPHERES = 100;
    const SPHERE_RADIUS = 0.2;

    const STEPS_PER_FRAME = 5;

    const sphereGeometry = new THREE.IcosahedronGeometry(SPHERE_RADIUS, 5);
    const sphereMaterial = new THREE.MeshLambertMaterial({
        color: 0xdede8d
    });

    const spheres = [];
    let sphereIdx = 0;

    for (let i = 0; i < NUM_SPHERES; i++) {

        const sphere = new THREE.Mesh(sphereGeometry, sphereMaterial);
        sphere.castShadow = true;
        sphere.receiveShadow = true;

        scene.add(sphere);

        spheres.push({
            mesh: sphere,
            collider: new THREE.Sphere(new THREE.Vector3(0, -100, 0), SPHERE_RADIUS),
            velocity: new THREE.Vector3()
        });

    }

    const worldOctree = new Octree();

    const playerCollider = new Capsule(
        new THREE.Vector3(0, 0.35, 0),
        new THREE.Vector3(0, 1, 0),
        0.35
    );

    const playerVelocity = new THREE.Vector3();
    const playerDirection = new THREE.Vector3();

    let playerOnFloor = false;
    let mouseTime = 0;

    gltfLoader.load('maps/collision-world.glb', (gltf) => {

        scene.add(gltf.scene);

        worldOctree.fromGraphNode(gltf.scene);

        gltf.scene.traverse(child => {

            if (child.isMesh) {

                child.castShadow = true;
                child.receiveShadow = true;

                if (child.material.map) {

                    child.material.map.anisotropy = 4;

                }

            }

        });

        const helper = new OctreeHelper(worldOctree);
        helper.visible = false;
        scene.add(helper);

        const gui = new GUI({
            width: 200
        });
        gui.add({
            debug: false
        }, 'debug')
            .onChange(function (value) {

                helper.visible = value;

            });

        animate();
    });

    const keyStates = {};
    let playerModel, playerSkeleton, playerMixer;
    const allActions = [];
    const baseActions = {
        idle: {
            weight: 1
        },
        walk: {
            weight: 0
        },
        run: {
            weight: 0
        }
    };
    const additiveActions = {
        sneak_pose: {
            weight: 0
        },
        sad_pose: {
            weight: 0
        },
        agree: {
            weight: 0
        },
        headShake: {
            weight: 0
        }
    };
    let numAnimations;
    const playerGroup = new THREE.Group();
    const xAxis = new THREE.Vector3(1, 0, 0);
    const tempCameraVector = new THREE.Vector3();
    const tempModelVector = new THREE.Vector3();

    function setWeight(action, weight) {
        action.enabled = true;
        action.setEffectiveTimeScale(1);
        action.setEffectiveWeight(weight);
    }

    function activateAction(action) {
        if (!action) {
            console.trace();
            return;
        }

        const clip = action.getClip();
        const settings = baseActions[clip.name] || additiveActions[clip.name];
        setWeight(action, settings.weight);
        action.play();
    }

    gltfLoader.load('models/Xbot.glb', function (gltf) {

        playerModel = gltf.scene;
        playerGroup.add(playerModel);

        playerModel.traverse(function (object) {

            if (object.isMesh) object.castShadow = true;

        });

        playerSkeleton = new THREE.SkeletonHelper(playerModel);
        playerSkeleton.visible = false;
        playerGroup.add(playerSkeleton);

        const animations = gltf.animations;
        playerMixer = new THREE.AnimationMixer(playerModel);

        numAnimations = animations.length;

        for (let i = 0; i !== numAnimations; ++i) {

            let clip = animations[i];
            const name = clip.name;

            if (baseActions[name]) {

                const action = playerMixer.clipAction(clip);
                activateAction(action);
                baseActions[name].action = action;
                allActions.push(action);

            } else if (additiveActions[name]) {

                // Make the clip additive and remove the reference frame

                THREE.AnimationUtils.makeClipAdditive(clip);

                if (clip.name.endsWith('_pose')) {

                    clip = THREE.AnimationUtils.subclip(clip, clip.name, 2, 3, 30);

                }

                const action = playerMixer.clipAction(clip);
                activateAction(action);
                additiveActions[name].action = action;
                allActions.push(action);

            }
        }

        for (const p of players) {
            // TODO: figure out how to use the Xbot model instead of a boxDoodad
            // p.group = new THREE.Group();
            // p.group.copy(playerGroup, false);
            // p.group.position.set(p.x, p.y, p.z);

            // scene.add(p.group);

            const boxDoodad = new THREE.Mesh(
                new THREE.BoxGeometry(1, 1, 1),
                new THREE.MeshNormalMaterial()
            );

            boxDoodad.position.set(p.x, p.y, p.z);
            scene.add(boxDoodad);
        }
    });

    const loadBackgroundNightSky = async () => {
        const skyVertexShader = await fileLoader.loadAsync('shaders/sky.vert');
        const skyFragmentShader = await fileLoader.loadAsync('shaders/sky.frag');

        const loader = new THREE.CubeTextureLoader();
        loader.setPath('game_module/');

        const background = loader.load([
            'sky/Cold_Sunset__Cam_2_Left+X.png',
            'sky/Cold_Sunset__Cam_3_Right-X.png',
            'sky/Cold_Sunset__Cam_4_Up+Y.png',
            'sky/Cold_Sunset__Cam_5_Down-Y.png',
            'sky/Cold_Sunset__Cam_0_Front+Z.png',
            'sky/Cold_Sunset__Cam_1_Back-Z.png',
        ]);

        const stars = loader.load([
            'sky/space-posx.jpg',
            'sky/space-negx.jpg',
            'sky/space-posy.jpg',
            'sky/space-negy.jpg',
            'sky/space-posz.jpg',
            'sky/space-negz.jpg',
        ]);

        const skyGeo = new THREE.SphereGeometry(1000, 32, 15);
        const skyMat = new THREE.ShaderMaterial({
            uniforms: {
                background: {
                    value: background
                },
                stars: {
                    value: stars
                },
            },
            vertexShader: skyVertexShader,
            fragmentShader: skyFragmentShader,
            side: THREE.BackSide
        });

        return new THREE.Mesh(skyGeo, skyMat);
    };

    loadBackgroundNightSky().then((sky) => scene.add(sky));

    const vector1 = new THREE.Vector3();
    const vector2 = new THREE.Vector3();
    const vector3 = new THREE.Vector3();

    document.addEventListener('keydown', (event) => {

        keyStates[event.code] = true;

    });

    document.addEventListener('keyup', (event) => {

        keyStates[event.code] = false;

    });

    document.addEventListener('mousedown', () => {

        document.body.requestPointerLock();

        mouseTime = performance.now();

    });

    document.addEventListener('mouseup', () => {
        if (document.pointerLockElement !== null) cast();

    });

    document.body.addEventListener('mousemove', (event) => {
        if (document.pointerLockElement === document.body) {

            camera.rotation.y -= event.movementX / 500;
            camera.rotation.x -= event.movementY / 500;

        }

    });

    window.addEventListener('resize', onWindowResize);

    function onWindowResize() {

        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();

        renderer.setSize(window.innerWidth, window.innerHeight);

    }

    function cast() {

        const sphere = spheres[sphereIdx];

        camera.getWorldDirection(playerDirection);

        sphere.collider.center.copy(playerCollider.end).addScaledVector(playerDirection, playerCollider.radius * 1.5);

        // throw the ball with more force if we hold the button longer, and if we move forward

        const impulse = 15 + 30 * (1 - Math.exp((mouseTime - performance.now()) * 0.001));

        socket.send(JSON.stringify({
            channel: 'game',
            server,
            event: 'player:cast',
            payload: {
                impulse,
                sphereIdx
            }
        }));

        sphere.velocity.copy(playerDirection).multiplyScalar(impulse);
        sphere.velocity.addScaledVector(playerVelocity, 2);

        sphereIdx = (sphereIdx + 1) % spheres.length;

    }

    function playerCollisions() {

        const result = worldOctree.capsuleIntersect(playerCollider);

        playerOnFloor = false;

        if (result) {
            playerOnFloor = result.normal.y > 0;

            if (!playerOnFloor) {
                playerVelocity.addScaledVector(result.normal, -result.normal.dot(playerVelocity));
            }

            const value = result.normal.multiplyScalar(result.depth);

            playerCollider.translate(value);
        }

    }

    function updatePlayer(deltaTime) {

        let damping = Math.exp(-4 * deltaTime) - 1;

        if (!playerOnFloor) {

            playerVelocity.y -= GRAVITY * deltaTime;

            // small air resistance
            damping *= 0.1;

        }

        playerVelocity.addScaledVector(playerVelocity, damping);

        const deltaPosition = playerVelocity.clone().multiplyScalar(deltaTime);
        playerCollider.translate(deltaPosition);

        playerCollisions();

        camera.position.copy(playerCollider.end);

    }

    function playerSphereCollision(sphere) {

        const center = vector1.addVectors(playerCollider.start, playerCollider.end).multiplyScalar(0.5);

        const sphere_center = sphere.collider.center;

        const r = playerCollider.radius + sphere.collider.radius;
        const r2 = r * r;

        // approximation: player = 3 spheres

        for (const point of [playerCollider.start, playerCollider.end, center]) {

            const d2 = point.distanceToSquared(sphere_center);

            if (d2 < r2) {

                const normal = vector1.subVectors(point, sphere_center).normalize();
                const v1 = vector2.copy(normal).multiplyScalar(normal.dot(playerVelocity));
                const v2 = vector3.copy(normal).multiplyScalar(normal.dot(sphere.velocity));

                playerVelocity.add(v2).sub(v1);
                sphere.velocity.add(v1).sub(v2);

                const d = (r - Math.sqrt(d2)) / 2;
                sphere_center.addScaledVector(normal, -d);

            }

        }

    }

    function spheresCollisions() {

        for (let i = 0, length = spheres.length; i < length; i++) {

            const s1 = spheres[i];

            for (let j = i + 1; j < length; j++) {

                const s2 = spheres[j];

                const d2 = s1.collider.center.distanceToSquared(s2.collider.center);
                const r = s1.collider.radius + s2.collider.radius;
                const r2 = r * r;

                if (d2 < r2) {

                    const normal = vector1.subVectors(s1.collider.center, s2.collider.center).normalize();
                    const v1 = vector2.copy(normal).multiplyScalar(normal.dot(s1.velocity));
                    const v2 = vector3.copy(normal).multiplyScalar(normal.dot(s2.velocity));

                    s1.velocity.add(v2).sub(v1);
                    s2.velocity.add(v1).sub(v2);

                    const d = (r - Math.sqrt(d2)) / 2;

                    s1.collider.center.addScaledVector(normal, d);
                    s2.collider.center.addScaledVector(normal, -d);

                }

            }

        }

    }

    function updateSpheres(deltaTime) {

        spheres.forEach(sphere => {

            sphere.collider.center.addScaledVector(sphere.velocity, deltaTime);

            const result = worldOctree.sphereIntersect(sphere.collider);

            if (result) {

                sphere.velocity.addScaledVector(result.normal, -result.normal.dot(sphere.velocity) * 1.5);
                sphere.collider.center.add(result.normal.multiplyScalar(result.depth));

            } else {

                sphere.velocity.y -= GRAVITY * deltaTime;

            }

            const damping = Math.exp(-1.5 * deltaTime) - 1;
            sphere.velocity.addScaledVector(sphere.velocity, damping);

            playerSphereCollision(sphere);

        });

        spheresCollisions();

        for (const sphere of spheres) {

            sphere.mesh.position.copy(sphere.collider.center);

        }

    }

    function getForwardVector() {

        camera.getWorldDirection(playerDirection);
        playerDirection.y = 0;
        playerDirection.normalize();

        return playerDirection;

    }

    function getSideVector() {

        camera.getWorldDirection(playerDirection);
        playerDirection.y = 0;
        playerDirection.normalize();
        playerDirection.cross(camera.up);

        return playerDirection;

    }

    let moving = false;

    function handleKeyStates(deltaTime) {
        const anyActivated = Object.values(keyStates).filter(Boolean).length > 0;

        if (!anyActivated) {
            return;
        }

        // gives a bit of air control
        const speedDelta = deltaTime * (playerOnFloor ? 25 : 8);


        if (keyStates['KeyW']) {
            playerVelocity.add(getForwardVector().multiplyScalar(speedDelta));
            moving = true;
        }

        if (keyStates['KeyS']) {
            playerVelocity.add(getForwardVector().multiplyScalar(-speedDelta));
            moving = true;
        }

        if (keyStates['KeyA']) {
            playerVelocity.add(getSideVector().multiplyScalar(-speedDelta));
            moving = true;
        }

        if (keyStates['KeyD']) {
            playerVelocity.add(getSideVector().multiplyScalar(speedDelta));
            moving = true;
        }

        if (moving) {
            baseActions.idle.weight = 0;
            baseActions.run.weight = 5;
            activateAction(baseActions.run.action);
            activateAction(baseActions.idle.action);
        } else {
            baseActions.idle.weight = 1;
            baseActions.run.weight = 0;
            activateAction(baseActions.run.action);
            activateAction(baseActions.idle.action);
        }

        if (playerOnFloor) {
            if (keyStates['Space']) {
                playerVelocity.y = 15;
            }
        }
    }

    setInterval(() => {
        if (moving) {
            const vector = camera.position.clone();

            vector.applyMatrix4(camera.matrixWorld);

            socket.send(JSON.stringify({
                channel: 'game',
                server,
                event: 'player:move',
                payload: {
                    x: vector.x,
                    y: vector.y,
                    z: vector.z
                }
            }));

            moving = false;
        }
    }, 2_500);

    function teleportPlayerIfOob() {

        if (camera.position.y <= -25) {

            playerCollider.start.set(0, 0.35, 0);
            playerCollider.end.set(0, 1, 0);
            playerCollider.radius = 0.35;
            camera.position.copy(playerCollider.end);
            camera.rotation.set(0, 0, 0);

        }

    }


    function animate() {

        const deltaTime = Math.min(0.05, clock.getDelta()) / STEPS_PER_FRAME;

        // we look for collisions in substeps to mitigate the risk of
        // an object traversing another too quickly for detection.

        for (let i = 0; i < STEPS_PER_FRAME; i++) {

            handleKeyStates(deltaTime);

            updatePlayer(deltaTime);

            updateSpheres(deltaTime);

            teleportPlayerIfOob();

        }

        while (allActions.length > 0) {
            const action = allActions.shift();

            const clip = action.getClip();

            if (baseActions[clip.name]) {
                const settings = baseActions[clip.name];
                settings.weight = action.getEffectiveWeight();
            }
        }

        if (playerMixer) {
            playerMixer.update(deltaTime);
        }
        if (playerModel) {

            // Get the X-Z plane in which camera is looking to move the player
            camera.getWorldDirection(tempCameraVector);
            const cameraDirection = tempCameraVector.setY(0).normalize();

            // Get the X-Z plane in which player is looking to compare with camera
            playerModel.getWorldDirection(tempModelVector);
            const direction = tempModelVector.setY(0).normalize();

            // Get the angle to x-axis. z component is used to compare if the angle is clockwise or anticlockwise since angleTo returns a positive value
            const cameraAngle = cameraDirection.angleTo(xAxis) * (cameraDirection.z > 0 ? 1 : -1);
            const playerAngle = direction.angleTo(xAxis) * (direction.z > 0 ? 1 : -1);

            // Get the angle to rotate the player to face the camera. Clockwise positive
            const angleToRotate = playerAngle - cameraAngle;

            // Get the shortest angle from clockwise angle to ensure the player always rotates the shortest angle
            let sanitisedAngle = angleToRotate;
            if (angleToRotate > Math.PI) {
                sanitisedAngle = angleToRotate - 2 * Math.PI
            }
            if (angleToRotate < -Math.PI) {
                sanitisedAngle = angleToRotate + 2 * Math.PI
            }

            // Rotate the model by a tiny value towards the camera direction
            playerModel.rotateY(
                Math.max(-0.05, Math.min(sanitisedAngle, 0.05))
            );
            playerGroup.position.add(cameraDirection.multiplyScalar(0.05));
            //camera.lookAt(playerGroup.position.clone().add(cameraOrigin));
        }

        renderer.render(scene, camera);

        stats.update();

        camera.updateMatrixWorld();

        requestAnimationFrame(animate);

    }

    function ping() {
        const pingRequest = new XMLHttpRequest();
        pingRequest.open('POST', '/game/ping', true);
        pingRequest.setRequestHeader('Content-Type', 'application/json');
        pingRequest.setRequestHeader('Accept', 'application/json');
        pingRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        pingRequest.setRequestHeader('TrongateToken', trongateToken);
        pingRequest.send(JSON.stringify({
            time: Date.now(),
            player_id: player.id
        }));
    }

    setInterval(() => {
        ping();
    }, 25_000);
});