<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Customer Satisfaction</title>
    <link href="{{ asset('assets') }}/bootstrap.min.css" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('survey.style_single')
</head>

<body>
    <div class="feedback-container" style="background-image: url('{{ asset($survey->template->bg_image_path) }}')">
        <!-- Header -->
        @include('survey._header')

        <!-- Question content -->
        <div class="question-section">
            <div class="question-box">
                <div class="question" id="questionText">{{ $survey->details[0]->question->question ?? '-' }}</div>
                <div class="feedback-options" id="feedbackOptions"></div>
            </div>
        </div>

        <!-- Footer running text -->
       @include('survey._footer')
    </div>

    <!-- Modals -->
    <div class="modal fade" id="reasonModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">We’re sorry to hear that</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <textarea id="reasonText" class="form-control" rows="3" placeholder="Type your reason here..."></textarea>
                </div>
                <div class="modal-footer">
                    <button id="confirmReasonBtn" class="btn btn-success text-white">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="thankyouModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center">
                <div class="modal-header">
                    <h5 class="modal-title w-100">Thank You!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Your feedback has been submitted. We appreciate your time!</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button class="btn btn-success" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets') }}/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('theme') }}/js/jquery-3.7.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            const surveyId = "{{ $survey->id }}";
            const questionId = "{{ $survey->details[0]->question_id }}";
            const locationId = "{{ $survey->location_id }}";
            const icons = ['😡', '😞', '😐', '🙂', '😄'];
            const $container = $("#feedbackOptions");
            const tooltips = ['Very Bad', 'Bad', 'Neutral', 'Good', 'Very Good'];

            // Render emoji buttons
            icons.forEach((emoji, i) => {
                const idx = i + 1;
                const html = `
                    <input type="radio" name="rating" value="${idx}" id="rating-${idx}">
                    <label for="rating-${idx}" class="emoji-label emoji-${idx}"
                        data-bs-toggle="tooltip" title="${tooltips[i]}">
                        ${emoji}
                    </label>
                `;
                $container.append(html);
            });

            // Init Bootstrap tooltip
            $('[data-bs-toggle="tooltip"]').each(function() {
                new bootstrap.Tooltip(this);
            });

            // Inisialisasi modal
            const reasonModal = new bootstrap.Modal('#reasonModal');
            const thankyouModal = new bootstrap.Modal('#thankyouModal');
            const $reasonText = $("#reasonText");

            let selectedRating = null;
            let modalTimeout = null;

            // Ketika user pilih emoji
            $container.on("change", "input[name='rating']", function() {
                selectedRating = $(this).val();
                if (selectedRating <= 2) {
                    reasonModal.show();
                } else {
                    showThankyouModal();
                    sendFeedback(selectedRating, null);
                }
            });

            // Tombol konfirmasi alasan
            $("#confirmReasonBtn").on("click", function() {
                const reason = $reasonText.val().trim();
                reasonModal.hide();
                sendFeedback(selectedRating, reason);

                setTimeout(() => {
                    showThankyouModal();
                    $reasonText.val('');
                }, 300);
            });

            // Fungsi menampilkan modal thank you dengan timeout auto-close
            function showThankyouModal() {
                thankyouModal.show();

                // Tutup otomatis setelah 3 detik
                modalTimeout = setTimeout(() => {
                    thankyouModal.hide();
                    resetEmojiSelection();
                }, 3000);
            }

            // Reset emoji setelah modal ditutup
            function resetEmojiSelection() {
                $("input[name='rating']").prop("checked", false);
            }

            // Fungsi AJAX kirim feedback
            function sendFeedback(rating, reason = null) {
                $.ajax({
                    url: "{{ url('response/feedback/store') }}", // ubah ke route kamu
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    data: {
                        survey_id: surveyId,
                        question_id: questionId,
                        location_id: locationId,
                        response_value: rating,
                        reason: reason || ''
                    },
                    success: function(res) {
                        console.log("Feedback sent:", res);
                    },
                    error: function(xhr) {
                        console.error("Error sending feedback:", xhr.responseText);
                    }
                });
            }

            // Update tanggal dan waktu live
            setInterval(() => {
                const now = new Date();
                const day = String(now.getDate()).padStart(2, '0');
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const year = now.getFullYear();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                $("#dateTime").text(`${day}/${month}/${year} ${hours}:${minutes}:${seconds}`);
            }, 1000);

        });
    </script>
</body>

</html>
