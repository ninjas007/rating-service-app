<style>
    html,
    body {
        height: 100%;
        margin: 0;
        padding: 0;
        overflow: hidden;
        background: #f9f9f9;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Full container layout */
    .feedback-container {
        display: flex;
        flex-direction: column;
        height: 100vh;
        background: #fff;
        border: 0.2vmin solid #000;
        max-width: 100vw;
        background-size: cover;
        background-position: center;
        object-fit: contain;
        background-attachment: fixed;
        background-repeat: no-repeat;
    }

    /* Header (fixed top) */
    .header {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        /* border-bottom: 0.1px solid #a3a3a3; */
        padding: 1.5vmin;
        height: 10vh;
        min-height: 70px;
        /* background: rgba(255, 255, 255, 0.4); */
        backdrop-filter: blur(5px);
    }

    .logo {
        width: 8vmin;
        height: 8vmin;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .logo img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .area-info {
        font-size: 2.3vmin;
        flex: 1;
        text-align: left;
        margin-left: 2%;
    }

    .datetime {
        white-space: nowrap;
        font-weight: 600;
        font-size: 1.8vmin;
    }

    /* Center section (fullscreen, centered vertically) */
    .question-section {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .question-box {
        background: rgba(255, 255, 255, 0.25);
        /* translucent white */
        backdrop-filter: blur(10px);
        /* frosted glass effect */
        border-radius: 2vmin;
        padding: 5vmin 8vmin;
        box-shadow: 0 0 2vmin rgba(0, 0, 0, 0.15);
        border: 0.3vmin solid rgba(255, 255, 255, 0.4);
        text-align: center;
        display: inline-block;
        max-width: 80%;
    }

    .question-box .question {
        font-size: 4vmin;
        font-weight: 800;
        color: #222;
        margin-bottom: 5vmin;
    }

    /* .question {
  font-size: 4vmin;
  font-weight: 800;
  margin-bottom: 10vmin;
  color: #222;
  text-wrap: balance;
  max-width: 80%;
} */

    /* Emoji feedback row */
    .feedback-options {
        display: flex;
        justify-content: center;
        gap: 8vmin;
        flex-wrap: wrap;
    }

    /* Emoji buttons (scale with screen) */
    .emoji-label {
        cursor: pointer;
        font-size: 10vmin;
        border-radius: 50%;
        padding: 2vmin;
        transition: transform .2s, filter .2s;
        border: 0.4vmin solid transparent;
    }

    .emoji-label:hover {
        transform: scale(1.15);
        filter: brightness(1.15);
    }

    input[type="radio"] {
        display: none;
    }

    input[type="radio"]:checked+.emoji-label {
        transform: scale(1.25);
        filter: brightness(1.3);
        box-shadow: 0 0 2vmin rgba(0, 0, 0, 0.4);
        border: 0.4vmin solid rgba(0, 0, 0, 0.3);
    }

    /* Vivid emoji gradients */
    .emoji-1 {
        background: radial-gradient(circle, #8b0000, #b71c1c);
        color: white;
    }

    .emoji-2 {
        background: radial-gradient(circle, #d32f2f, #ef5350);
        color: white;
    }

    .emoji-3 {
        background: radial-gradient(circle, #fbc02d, #ffeb3b);
    }

    .emoji-4 {
        background: radial-gradient(circle, #66bb6a, #43a047);
        color: white;
    }

    .emoji-5 {
        background: radial-gradient(circle, #2e7d32, #00c853);
        color: white;
    }

    /* Footer (fixed bottom) */
    .running-text {
        flex-shrink: 0;
        /* border-top: 0.2px solid #6c6c6c; */
        padding: 1vmin;
        text-align: center;
        font-weight: 700;
        /* background: rgba(227, 50, 50, 0.4); */
        /* ✅ semi-transparent */
        white-space: nowrap;
        overflow: hidden;
        height: 7vh;
        min-height: 50px;
        font-size: 2vmin;
        backdrop-filter: blur(5px);
    }

    .running-text span {
        display: inline-block;
        vertical-align: middle;
        font-size: 3vmin;
    }

    @keyframes moveText {
        0% {
            transform: translateX(100%);
        }

        100% {
            transform: translateX(-100%);
        }
    }

    /* Responsive tweaks */
    @media (max-width: 1024px) {
        .question {
            font-size: 5vmin;
        }

        .emoji-label {
            font-size: 12vmin;
        }
    }

    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            height: auto;
        }

        .area-info {
            margin-left: 0;
            text-align: center;
            font-size: 2.8vmin;
        }

        .feedback-options {
            gap: 5vmin;
        }

        .emoji-label {
            font-size: 13vmin;
        }
    }

    @media (max-width: 480px) {
        .emoji-label {
            font-size: 15vmin;
        }

        .question {
            font-size: 6vmin;
        }
    }
</style>
