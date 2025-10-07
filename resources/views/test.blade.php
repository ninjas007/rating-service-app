<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Notifikasi</title>
</head>

<body>
    <h3>Test Notifikasi Order</h3>
    <audio id="notify-sound" src="{{ asset('theme/sound/bell.mp3') }}" preload="auto"></audio>

    <!-- Pusher.js langsung dari CDN -->
    <script src="{{ asset('theme/js') }}/pusher.min.js"></script>
    <script>
        // Aktifkan debug
        Pusher.logToConsole = true;

        // Sambungkan ke Websockets lokal
        var pusher = new Pusher("local", { // harus sama dengan PUSHER_APP_KEY di .env
            wsHost: window.location.hostname,
            wsPort: 6001,
            forceTLS: false,
            disableStats: true,
            enabledTransports: ['ws', 'wss'] // biar ga fallback ke pusher.com
        });

        // Subscribe channel
        var channel = pusher.subscribe('lab-orders');

        // Bind event
        channel.bind('new-order', function(data) {
            document.getElementById('notify-sound').play();
        });
    </script>
</body>

</html>
