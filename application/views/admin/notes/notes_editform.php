<form action="<?php echo admin_url('conference/add_notes/'.$id); ?>" method="post" id="NotesFrm" onsubmit="return form_submit('NotesFrm')">
    <div class="form-group">
        <label>Meeting Id</label>
        <input type="hidden" name="meeting_id" id="meeting_id" value="<?php echo $meeting_id; ?>">
        <p class="lead"><?php echo $meeting_id; ?></p>
    </div>
    <div class="form-group">
        <label>Notes</label>
        <textarea name="content" id="notescontent" class="form-control" ><?php echo $notes; ?></textarea>
    </div>
    <div class="form-action float-right">
        <input type="hidden" name="submitted" value="1">
        <button class="btn btn-success" type="submit">Save</button>
        <button class="btn btn-secondary" type="button" data-dismiss="modal" aria-label="Close">Close</button>
    </div>
</form>