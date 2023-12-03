		<?php
		if(!empty($txtMessage))
		{
		?>
			<div class="alert alert-success alert-dismissible" role="alert">
				<?php	echo $txtMessage;?>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		<?php
		}
		?>