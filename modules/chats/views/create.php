<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Chat Details
    </div>
    <div class="card-body">
        <?php
        echo form_open($form_location);
        echo form_label('Author');
        echo form_input('author', $author, array("placeholder" => "Enter Author"));
        echo form_label('Email <span>(optional)</span>');
        echo form_email('email', $email, array("placeholder" => "Enter Email"));
        echo form_label('Channel');
        echo form_input('channel', $channel, array("placeholder" => "Enter Channel"));
        echo form_label('Message');
        echo form_textarea('message', $message, array("placeholder" => "Enter Message"));
        echo form_label('Associated Live Stream');
        echo form_dropdown('live_streams_id', $live_streams_options, $live_streams_id);
        echo form_submit('submit', 'Submit');
        echo anchor($cancel_url, 'Cancel', array('class' => 'button alt'));
        echo form_close();
        ?>
    </div>
</div>