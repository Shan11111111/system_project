import * as THREE from 'three';

// 創建場景
const scene = new THREE.Scene();

// 創建攝影機
const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
camera.position.z = 5;

// 創建渲染器
const renderer = new THREE.WebGLRenderer();
renderer.setSize(window.innerWidth, window.innerHeight);
document.body.appendChild(renderer.domElement);

// 添加幾何形狀
const geometry = new THREE.BoxGeometry();
const material = new THREE.MeshBasicMaterial({ color: 0x00ff00 });
const cube = new THREE.Mesh(geometry, material);
scene.add(cube);

// 加載動畫
let angle = 0;
const animate = function () {
    requestAnimationFrame(animate);

    // 在Y軸上旋轉
    cube.rotation.y += 0.05;

    // 模擬加載進度（例如一個旋轉圈）
    angle += 0.05;
    if (angle > Math.PI * 2) angle = 0;

    renderer.render(scene, camera);
};

animate();

// 確保視窗大小變化時更新渲染器
window.addEventListener('resize', () => {
    renderer.setSize(window.innerWidth, window.innerHeight);
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
});
