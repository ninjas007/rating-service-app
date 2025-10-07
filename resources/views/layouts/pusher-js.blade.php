<!-- Pusher.js langsung dari CDN -->
{{-- <script src="{{ asset('theme/js') }}/pusher.min.js"></script> --}}
{{-- <script type="text/javascript">
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
</script> --}}


<script type="text/javascript">
    let audio = document.getElementById('notify-sound');

    // Unlock audio saat page load
    audio.volume = 0;
    let lastId = 0;

    // Polling setiap 3 detik dengan AJAX
    setInterval(() => {
        $.ajax({
            url: `{{ url('notif-simrs') }}`, // route Laravel
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.status == 'success') {
                    audio.play().finally(() => {
                        audio.volume = 1; // kembali normal
                    });
                }
            },
            error: function(err) {
                console.error("Polling error:", err);
            }
        });
    }, 3000);
</script>
