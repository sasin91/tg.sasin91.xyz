<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Live Stream Details
    </div>
    <div class="card-body">
        <?php
        echo form_open($form_location);
        echo form_label('Title');
        echo form_input('title', $title, array("placeholder" => "Enter Title"));
        echo form_label('Description');
        echo form_textarea('description', $description, array("placeholder" => "Enter Description"));
        echo form_label('Summary <span>(optional)</span>');
        echo form_input('summary', $summary, array("placeholder" => "Enter Summary"));
        echo form_label('Start Date And Time');
        $default_start_time = !empty($start_date_and_time) ? $start_date_and_time : date('m/d/Y \a\t g:i A');
        $attr = array("class"=>"datetime-picker", "autocomplete"=>"off", "placeholder"=>"Select Start Date And Time");
        echo form_input('start_date_and_time', $default_start_time, $attr);

        if (!empty($mux_stream_key)) {
            echo '<div class="mux-info">';
            echo '<h3>Mux Streaming Details</h3>';
            echo form_label('Mux Stream ID');
            echo form_input('mux_stream_id', $mux_stream_id ?? '', array("readonly" => "readonly"));
            echo form_label('Stream Key');
            echo form_input('mux_stream_key', $mux_stream_key ?? '', array("readonly" => "readonly"));
            echo form_label('Playback ID');
            echo form_input('mux_playback_id', $mux_playback_id ?? '', array("readonly" => "readonly"));
            echo '</div>';
        }
        echo form_submit('submit', 'Submit');
        echo anchor($cancel_url, 'Cancel', array('class' => 'button alt'));
        echo form_close();
        ?>
    </div>
</div>