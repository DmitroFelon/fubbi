<div class="ibox">
	<div class="ibox-title">
		<h5>{{_i('Messages')}}</h5>
		<div class="ibox-tools">
			<a class="collapse-link">
				<i class="fa fa-chevron-up"></i>
			</a>
		</div>
	</div>
	<div class="ibox-content">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="row">
					<div class="col-md-6">
					</div>
					<div class="col-md-6">
						<div class="small text-right">
							<h5>{{_i('Stats')}}:</h5>
							<div>
								<i class="fa fa-comments-o"> </i> {{$comments->count()}} {{_i('total')}}
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<h2>{{_i('Messages')}}:</h2>
						@each('entity.comment.row', $comments, 'comment', 'entity.comment.row-empty')
					</div>
				</div>
			</div>
		</div>
	</div>
</div>