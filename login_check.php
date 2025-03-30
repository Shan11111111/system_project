<?php session_start();?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>加載中Loading...</title>
    <style>
        body {
            margin: 0;
            overflow: hidden;
        }
        #loading-screen {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #ffdb7e; /* 黃色背景 */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: white;
            font-family: Arial, sans-serif;
            z-index: 10;
        }
        #progress-bar {
            width: 80%;
            height: 20px;
            background-color: #ffa73c; /* 白色進度條背景 */
            border-radius: 10px;
            overflow: hidden;
            margin-top: 10px;
        }
        #progress-bar-inner {
            height: 100%;
            width: 0%;
            background-color: #ffffff; /* 黑色進度條 */
            transition: width 0.1s;
        }
        #loading-message {
            margin-top: 15px;
            font-size: 16px;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
        }
    </style>
</head>
<body>
<?php
$user_id = $_POST['user_id'];
$password = $_POST['password'];
$method = $_POST['method'];


//Step 1
$link = mysqli_connect('localhost', 'root', '', 'system_project');
//Step 3
$sql = "select * from users where user_id= '$user_id' and password='$password'";
$result = mysqli_query($link, $sql);
// Step 4
if ($record = mysqli_fetch_assoc($result)) {
    // Check if the user is an admin
    $_SESSION['user_id'] = $record['user_id'];
    $_SESSION['name'] = $record['name'];
    $_SESSION['level'] = $record['level'];
    $_SESSION['department'] = $record['department'];
    $_SESSION['email'] = $record['email'];
    $_SESSION['name'] = $record['name'];
}
if (isset($_SESSION['level'])) { 
    ?>
    <div id="loading-screen">
        <div id="progress-text">1%</div>
        <div id="progress-bar">
            <div id="progress-bar-inner"></div>
        </div>
        <div id="loading-message">即將進入孵仁...</div> <!-- 新增的文字 -->
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
        // 初始化 Three.js 場景
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer();
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.body.appendChild(renderer.domElement);

        // 模擬加載進度
        let progress = 1;
        const progressText = document.getElementById('progress-text');
        const progressBarInner = document.getElementById('progress-bar-inner');
        const loadingScreen = document.getElementById('loading-screen');

        function simulateLoading() {
            if (progress <= 100) {
                progressText.textContent = `${progress}%`;
                progressBarInner.style.width = `${progress}%`;
                progress++;
                setTimeout(simulateLoading, 30); // 模擬加載速度
            } else {
                // 加載完成後跳轉到 homepage.php
                window.location.href = 'homepage.php';
            }
        }

        // 開始模擬加載
        simulateLoading();
    </script>
    </body>
    <?php
}else{
    echo "<script>alert('登入失敗，請檢查學號或密碼');</script>";
    echo "<script>window.location.href='login.php';</script>";
}
?>
</html>