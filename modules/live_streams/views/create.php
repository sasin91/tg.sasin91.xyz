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
        echo '<div>';
        echo 'Live ';
        echo form_checkbox('live', 1, $checked=$live);
        echo '</div>';
        echo form_label('Start Date And Time');
        $attr = array("class"=>"datetime-picker", "autocomplete"=>"off", "placeholder"=>"Select Start Date And Time");
        echo form_input('start_date_and_time', $start_date_and_time, $attr);
        echo form_label('Ingest <span>(optional)</span>');
        echo form_input('ingest', $ingest, array("placeholder" => "Enter Ingest"));
        echo form_label('Playlist <span>(optional)</span>');
        echo form_input('playlist', $playlist, array("placeholder" => "Enter Playlist"));
        echo form_submit('submit', 'Submit');
        echo anchor($cancel_url, 'Cancel', array('class' => 'button alt'));
        echo form_close();
        ?>
    </div>
</div>