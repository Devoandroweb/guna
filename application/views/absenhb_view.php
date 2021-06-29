    <br /><br /><br /><br />
    <div class="container">
        <div class="row clearfix">
            <div class="col-md-12 column">
                <div id='calendar'></div>
            </div>
        </div>
    </div>
    <div class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="error"></div>
                        <form class="form-horizontal" id="crud-form">
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="title">Keterangan</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" id="title" name="title"></textarea>
                                </div>
                            </div>
                            <input id="time" name="time" type="hidden" class="form-control input-md" />
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    <br /><br />
  <?php include "footer.php" ?>
</body>
</html>