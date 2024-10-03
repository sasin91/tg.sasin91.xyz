<h1><?= out($headline) ?></h1>
<?php
flashdata();
echo '<p>'.anchor('live_streams/create', 'Create New Live Stream Record', array("class" => "button"));
if(strtolower(ENV) === 'dev') {
    echo anchor('api/explorer/live_streams', 'API Explorer', array("class" => "button alt"));
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
                        echo form_open('live_streams/manage/1/', array("method" => "get"));
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
                <th>Title</th>
                <th>Summary</th>
                <th>Live</th>
                <th>Start Date And Time</th>
                <th>Ingest</th>
                <th>Playlist</th>
                <th style="width: 20px;">Action</th>            
            </tr>
        </thead>
        <tbody>
            <?php 
            $attr['class'] = 'button alt';
            foreach($rows as $row) { ?>
            <tr>
                <td><?= out($row->title) ?></td>
                <td><?= out($row->summary) ?></td>
                <td><?= out($row->live) ?></td>
                <td><?= date('l jS F Y \a\t H:i',  strtotime($row->start_date_and_time)) ?></td>
                <td><?= out($row->ingest) ?></td>
                <td><?= out($row->playlist) ?></td>
                <td><?= anchor('live_streams/show/'.$row->id, 'View', $attr) ?></td>        
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