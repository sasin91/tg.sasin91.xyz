<h1><?= out($headline) ?></h1>
<?php
flashdata();
echo '<p>'.anchor('localizations/create', 'Create New Localization Record', array("class" => "button"));
if(strtolower(ENV) === 'dev') {
    echo anchor('api/explorer/localizations', 'API Explorer', array("class" => "button alt"));
}
echo '</p>';
echo Pagination::display($pagination_data);
if (count($rows)>0) { ?>
    <table id="results-tbl">
        <thead>
            <tr>
                <th colspan="7">
                    <div>
                        <div><?php
                        echo form_open('localizations/manage/1/', array("method" => "get"));
                        echo form_search('searchphrase', '', array("placeholder" => "Search records..."));
                        echo form_submit('submit', 'Search', array("class" => "alt"));
                        echo form_close();
                        ?></div>
                        <div>Records Per Page: <?php
                        $dropdown_attr['onchange'] = 'setPerPage()';
                        echo form_dropdown('per_page', $per_page_options, $selected_per_page, $dropdown_attr); 
                        ?></div>

                    </div>                    
                </th>
            </tr>
            <tr>
                <th>Module</th>
                <th>Locale</th>
                <th>Key</th>
                <th>Value</th>
                <th>Created</th>
                <th>Updated</th>
                <th style="width: 20px;">Action</th>            
            </tr>
        </thead>
        <tbody>
            <?php 
            $attr['class'] = 'button alt';
            foreach($rows as $row) { ?>
            <tr>
                <td><?= out($row->module) ?></td>
                <td><?= out($row->locale) ?></td>
                <td><?= out($row->key) ?></td>
                <td><?= ($row->value) ?></td>
                <td><?= date('l jS F Y \a\t H:i',  strtotime($row->created)) ?></td>
                <td><?= date('l jS F Y \a\t H:i',  strtotime($row->updated)) ?></td>
                <td><?= anchor('localizations/show/'.$row->id, 'View', $attr) ?></td>        
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
<?php 
    if(count($rows)>9) {
        unset($pagination_data['include_showing_statement']);
        echo Pagination::display($pagination_data);
    }
}
?>