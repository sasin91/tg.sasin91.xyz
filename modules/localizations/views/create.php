<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Localization Details
    </div>
    <div class="card-body">
        <?php
        echo form_open($form_location);
        echo form_label('Module');
        echo form_dropdown('module', $modules, $module);
        echo form_label('Locale');
        echo form_dropdown('locale', $locales, $locale);
        echo form_label('Key');
        echo form_input('key', $key, array("placeholder" => "Enter Key"));
        echo form_label('Value');
        echo form_input('value', $value, array("placeholder" => "Enter Value"));
        echo form_submit('submit', 'Submit');
        echo anchor($cancel_url, 'Cancel', array('class' => 'button alt'));
        echo form_close();
        ?>
    </div>
</div>