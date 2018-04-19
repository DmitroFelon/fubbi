<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="ibox">
        <div class="ibox-title">
            <h5>
                {{_i('Status')}} : {{ ucfirst(str_replace('_',' ',$project->state)) }}
            </h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
            </div>
        </div>
        <div class="ibox-content">
            @include('entity.project.partials.show.head-progress')
        </div>
    </div>
</div>