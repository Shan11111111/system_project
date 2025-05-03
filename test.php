<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    .highlight-advice {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        background-color: #fff6da;
        border-left: 5px solid #d9a679;
        padding: 1rem;
        border-radius: 12px;
        margin: 1.5rem auto;
        max-width: 95%;
        box-shadow: 2px 2px 0 #e0caa3;
        color: #4b3f2f;
        gap: 0.4rem;
    }

    .highlight_content {
        font-size: 1.1rem;
        font-weight: bold;
    }

    .highlight-status,
    .highlight-deadline {
        font-size: 0.95rem;
        margin: 0;
    }

    .fire-emoji {
        display: inline-block;
        margin-right: 6px;
        animation: fire-dance 0.8s infinite ease-in-out;
        filter: drop-shadow(0 0 5px orange);
    }

    @keyframes fire-dance {
        0% {
            transform: scale(1) rotate(0deg);
            opacity: 1;
        }

        25% {
            transform: scale(1.15) rotate(-5deg);
            opacity: 0.9;
        }

        50% {
            transform: scale(1.25) rotate(5deg);
            opacity: 1;
        }

        75% {
            transform: scale(1.1) rotate(-3deg);
            opacity: 0.95;
        }

        100% {
            transform: scale(1) rotate(0deg);
            opacity: 1;
        }
    }

    .highlight_btn {
        background-color: #d9a679;
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        font-size: 1rem;
        transition: transform 0.2s ease-in-out;
        margin-top: 0.4rem;
    }

    .highlight_btn:hover {
        transform: scale(1.05);
        background-color: #c8925c;
    }

    .pulse {
        animation: pulse-btn 1.5s infinite;
    }

    @keyframes pulse-btn {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.08);
        }

        100% {
            transform: scale(1);
        }
    }
    .highlight_title {
  font-size: 1.1rem;
  font-weight: bold;
  margin-bottom: 0.25rem;
}

.highlight_subtext {
  font-size: 0.95rem;
  color: #5a4e3d;
}

</style>

<body>
    <div class="highlight_textblock">
        <div class="highlight_title">
            <span class="fire-emoji">🔥</span> 快要達標的建言：<strong>社團博覽會</strong>
        </div>
        <div class="highlight_subtext">還差 <strong>6 人</strong> 即可達成</div>
    </div>
</body>

</html>