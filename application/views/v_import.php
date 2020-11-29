
					  <div class="col-md-6">
						<?php echo $this->session->flashdata('notif') ?>
						<form method="POST" action="<?php echo base_url() ?>import/upload" enctype="multipart/form-data">
						  <div class="form-group">
							<input type="file" name="file" class="form-control">
						  </div>
						  <br>
						  <button type="submit" class="btn btn-success">UPLOAD</button>
						</form>
					  </div>