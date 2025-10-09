 <div class="running-text" style="background-color: {{ $survey->template->bg_running_text }}">
     <span id="runningText"
         style="color: {{ $survey->template->running_text_color }};
            animation: moveText {{ $survey->template->running_text_speed }}s linear infinite; ">
         {{ $survey->template->running_text }}
     </span>
 </div>
