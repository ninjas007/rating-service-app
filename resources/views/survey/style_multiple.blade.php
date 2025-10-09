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

    /* Container layout */
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
        font-size: 2vmin;
        flex: 1;
        text-align: left;
        margin-left: 2%;
    }

    .datetime {
        white-space: nowrap;
        font-weight: 600;
        font-size: 1.8vmin;
    }

    /* Scrollable middle section */
    .feedback-section {
        flex: 1;
        overflow-y: auto;
        padding: 20px 30px;
        background: transparent;
        /* ✅ allow background from container to show through */
        backdrop-filter: none;
        /* ✅ no blur here — handled by child boxes */
    }

    /* Feedback row styling (auto scale) */
    .feedback-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border: 0.25vmin solid #000;
        margin-bottom: 2vmin;
        padding: 2vmin 3vmin;
        background: rgba(255, 255, 255, 0.2);
        /* ✅ translucent white */
        border-radius: 1vmin;
        transition: transform .2s ease, box-shadow .2s ease;
        backdrop-filter: blur(6px);
        /* adds glassy effect */
    }

    .feedback-row:hover {
        transform: scale(1.01);
        box-shadow: 0 0.8vmin 2vmin rgba(0, 0, 0, 0.1);
    }

    /* Icon column */
    .feedback-icon {
        width: 13vmin;
        text-align: center;
    }

    .feedback-icon img {
        width: 8vmin;
        height: 8vmin;
        object-fit: contain;
        margin-bottom: 0.8vmin;
    }

    .feedback-icon small {
        font-weight: 700;
        font-size: 1.8vmin;
        display: block;
    }

    /* Emoji row */
    .feedback-options {
        flex: 1;
        display: flex;
        justify-content: center;
        gap: 4vmin;
        flex-wrap: wrap;
        /* prevents horizontal overflow */
    }

    /* Emoji buttons (responsive size) */
    .emoji-label {
        cursor: pointer;
        font-size: 6vmin;
        border-radius: 50%;
        padding: 1.5vmin;
        transition: transform .2s, filter .2s, box-shadow .2s;
        border: 0.4vmin solid transparent;
        flex-shrink: 0;
        box-shadow: 0 0.8vmin 1.5vmin rgba(0, 0, 0, 0.2);

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
        border: 0.4vmin solid rgba(0, 0, 0, 0.2);
    }

    /* Emoji gradient colors (more vivid) */
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

    /* Footer running text (fixed bottom) */
    .running-text {
        flex-shrink: 0;
        border-top: 0.25vmin solid #000;
        padding: 1vmin;
        text-align: center;
        font-weight: 700;
        background: rgba(255, 255, 255, 0.4);
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
        animation: moveText 15s linear infinite;
    }

    @keyframes moveText {
        0% {
            transform: translateX(100%);
        }

        100% {
            transform: translateX(-100%);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .emoji-label {
            font-size: 8vmin;
        }

        .feedback-icon img {
            width: 10vmin;
            height: 10vmin;
        }

        .feedback-icon small {
            font-size: 2vmin;
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
            font-size: 2.5vmin;
        }

        .feedback-options {
            gap: 3vmin;
        }

        .emoji-label {
            font-size: 9vmin;
        }
    }

    @media (max-width: 480px) {
        .emoji-label {
            font-size: 10vmin;
        }

        .feedback-icon {
            width: 20vmin;
        }

        .feedback-icon img {
            width: 12vmin;
            height: 12vmin;
        }
    }
</style>
