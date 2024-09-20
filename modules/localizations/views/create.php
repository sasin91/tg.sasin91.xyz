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
        echo form_input('module', $module, array("placeholder" => "Enter Module"));
        echo form_label('Locale');
        echo form_input('locale', $locale, array("placeholder" => "Enter Locale"));
        echo form_label('Key');
        echo form_input('key', $key, array("placeholder" => "Enter Key"));
        echo form_label('Value');
        echo form_input('value', $value, array("placeholder" => "Enter Value"));
        echo form_label('Created');
        $attr = array("class"=>"datetime-picker", "autocomplete"=>"off", "placeholder"=>"Select Created");
        echo form_input('created', $created, $attr);
        echo form_label('Updated');
        $attr = array("class"=>"datetime-picker", "autocomplete"=>"off", "placeholder"=>"Select Updated");
        echo form_input('updated', $updated, $attr);
        echo form_submit('submit', 'Submit');
        echo anchor($cancel_url, 'Cancel', array('class' => 'button alt'));
        echo form_close();
        ?>
    </div>
</div>