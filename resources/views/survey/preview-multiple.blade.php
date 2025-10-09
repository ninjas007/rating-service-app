<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Customer Satisfaction</title>
    <link href="{{ asset('assets/bootstrap.min.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('survey.style_multiple')
</head>

<body>
    <div class="feedback-container" style="background-image: url('{{ asset($survey->template->bg_image_path) }}')">
        @include('survey._header')

        <div class="feedback-section">
            <div id="feedbackRows">
                @php
                    $icons = ['😡', '😞', '😐', '🙂', '😄'];
                    $labels = ['Very Bad', 'Bad', 'Neutral', 'Good', 'Very Good'];
                @endphp

                @foreach ($survey->details as $detail)
                    <div class="feedback-row" data-question-id="{{ $detail->id }}"
                        data-question-text="{{ $detail->question->question }}">
                        <div class="feedback-icon">
                            <small>{{ $detail->question->question }}</small>
                        </div>
                        <div class="feedback-options">
                            @for ($i = 1; $i <= 5; $i++)
                                <input type="radio" name="question_{{ $detail->id }}" value="{{ $i }}"
                                    id="question_{{ $detail->id }}_{{ $i }}">
                                <label for="question_{{ $detail->id }}_{{ $i }}"
                                    class="emoji-label emoji-{{ $i }}" data-value="{{ $i }}"
                                    title="{{ $labels[$i - 1] }}">
                                    {{ $icons[$i - 1] }}
                                </label>
                            @endfor
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @include('survey._footer')
    </div>

    <!-- Reason Modal -->
    <div class="modal fade" id="reasonModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">We’re sorry to hear that</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <textarea id="reasonText" class="form-control" rows="3" placeholder="Please tell us what went wrong..."></textarea>
                </div>
                <div class="modal-footer">
                    <button id="confirmReasonBtn" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Thank You Modal -->
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

    <script src="{{ asset('theme/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap.bundle.min.js') }}"></script>
    <script>
        $(function() {
            const reasonModal = new bootstrap.Modal('#reasonModal');
            const thankyouModal = new bootstrap.Modal('#thankyouModal');
            const surveyId = "{{ $survey->id }}";
            const locationId = "{{ $survey->location_id }}";
            let currentQuestionId = null;
            let currentValue = null;

            // Klik emoji
            $('.emoji-label').on('click', function() {
                currentValue = $(this).data('value');
                currentQuestionId = $(this).closest('.feedback-row').data('question-id');
                const questionText = $(this).closest('.feedback-row').data('question-text');

                if (currentValue <= 2) {
                    $('#reasonModal .modal-title').text(`We’re sorry about "${questionText}"`);
                    reasonModal.show();
                } else {
                    submitFeedback(currentQuestionId, currentValue, '');
                }
            });

            // Konfirmasi alasan
            $('#confirmReasonBtn').on('click', function() {
                const reason = $('#reasonText').val().trim();
                reasonModal.hide();
                submitFeedback(currentQuestionId, currentValue, reason);
                $('#reasonText').val('');
            });

            // Fungsi submit feedback
            function submitFeedback(questionId, rating, reason) {
                $.ajax({
                    url: "{{ url('response/feedback/store') }}",
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    data: {
                        survey_id: surveyId,
                        question_id: questionId,
                        response_value: rating,
                        location_id: locationId,
                        reason: reason,
                        reason: reason || ''
                    },
                    success: function(res) {
                        thankyouModal.show();

                        // Reset radio setelah submit
                        const groupName = `question_${questionId}`;
                        $(`input[name="${groupName}"]`).prop('checked', false);

                        // Hilangkan focus dari label (agar tidak tersorot)
                        $(`input[name="${groupName}"]`).blur();
                    },
                    error: function(err) {
                        alert("Failed to send feedback");
                        console.error(err);
                    }
                });
            }
        });

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
    </script>
</body>

</html>
