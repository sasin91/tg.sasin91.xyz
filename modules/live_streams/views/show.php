<h1><?= out($headline) ?> <span class="smaller hide-sm">(Record ID: <?= out($update_id) ?>)</span></h1>
<?= flashdata() ?>
<div class="card">
    <div class="card-heading">
        Options
    </div>
    <div class="card-body">
        <?= anchor('live_streams/manage', 'View All Live Streams', array("class" => "button alt")) ?>
        <?= anchor('live_streams/create/'.$update_id, 'Update Details', array("class" => "button")) ?>
        <span class="go-right">
            <?php if($live): ?>
                <?= anchor('live_streams/stop/'.$update_id, 'Stop Live Stream', array("class" => "button")) ?>
            <?php else: ?>
                <?= anchor('live_streams/start/'.$update_id, 'Start Live Stream', array("class" => "button danger")) ?>
            <?php endif; ?>
            <?php
            $attr_delete = array(
                "class" => "danger go-right",
                "id" => "btn-delete-modal",
                "onclick" => "openModal('delete-modal')"
            );
            echo form_button('delete', 'Delete', $attr_delete);
            ?>
        </span>
    </div>
</div>
<div class="three-col">
    <div class="card">
        <div class="card-heading">
            Live Stream Details
        </div>
        <div class="card-body">
            <div class="record-details">
                <div class="row">
                    <div>Title</div>
                    <div><?= out($title) ?></div>
                </div>
                <div class="row">
                    <div class="full-width">
                        <div><b>Description</b></div>
                        <div><?= nl2br(out($description)) ?></div>
                    </div>
                </div>
                <div class="row">
                    <div>Summary</div>
                    <div><?= out($summary) ?></div>
                </div>
                <div class="row">
                    <div>Live</div>
                    <div><?= out($live) ?></div>
                </div>
                <div class="row">
                    <div>Start Date And Time</div>
                    <div><?= date('l jS F Y \a\t H:i',  strtotime($start_date_and_time)) ?></div>
                </div>
                <div class="row">
                    <div>Ingest</div>
                    <div><?= out($ingest) ?></div>
                </div>
                <div class="row">
                    <div>Playlist</div>
                    <div><?= out($playlist) ?></div>
                </div>
            </div>
        </div>
    </div>
    <?= Modules::run('trongate_filezone/_draw_summary_panel', $update_id, $filezone_settings); ?>
    
    <?= Modules::run('module_relations/_draw_summary_panel', 'chats', $token) ?>

    <div class="card">
        <div class="card-heading">
            Comments
        </div>
        <div class="card-body">
            <div class="text-center">
                <p><button class="alt" onclick="openModal('comment-modal')">Add New Comment</button></p>
                <div id="comments-block"><table></table></div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="comment-modal" style="display: none;">
    <div class="modal-heading"><i class="fa fa-commenting-o"></i> Add New Comment</div>
    <div class="modal-body">
        <p><textarea placeholder="Enter comment here..."></textarea></p>
        <p><?php
            $attr_close = array( 
                "class" => "alt",
                "onclick" => "closeModal()"
            );
            echo form_button('close', 'Cancel', $attr_close);
            echo form_button('submit', 'Submit Comment', array("onclick" => "submitComment()"));
            ?>
        </p>
    </div>
</div>
<div class="modal" id="delete-modal" style="display: none;">
    <div class="modal-heading danger"><i class="fa fa-trash"></i> Delete Record</div>
    <div class="modal-body">
        <?= form_open('live_streams/submit_delete/'.$update_id) ?>
        <p>Are you sure?</p>
        <p>You are about to delete a Live stream record.  This cannot be undone.  Do you really want to do this?</p> 
        <?php 
        echo '<p>'.form_button('close', 'Cancel', $attr_close);
        echo form_submit('submit', 'Yes - Delete Now', array("class" => 'danger')).'</p>';
        echo form_close();
        ?>
    </div>
</div>
<script>
const token = '<?= $token ?>';
const baseUrl = '<?= BASE_URL ?>';
const segment1 = '<?= segment(1) ?>';
const updateId = '<?= $update_id ?>';
const drawComments = true;
</script>