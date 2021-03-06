<div id="{{ $field_id }}" class="form-group {{ $errors->has($field_id)?'has-error':'' }}">
    <div>
        <label class="control-label large">{{ $form['#label'] }}</label>
    </div>
    <div class="form-control-add-file text-center {{ $errors->has($field_id)?'has-error':'' }}">

        <input type="hidden" value="{{ $content_id }}" class="content-id">
        <input type="hidden" value="{{ $field_id }}" class="content-key">
        <input type="hidden" value="@if (isset($form['#content']['#id'])) {{ $form['#content']['#id'] }} @endif" name="{{ $field_id }}" class="model-id">

        <div class="model-add" @if (isset($form['#content']['#value'])) style="display:none" @endif>
            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#assets" data-opentab="#models-tab">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> {{ trans('template_asset_library_models.add_model') }}
            </button>
        </div>

        <div class="model-edit" @if (!isset($form['#content']['#value'])) style="display:none" @endif>
            <div class="model-placeholder" style="margin-bottom:10px"><img src="@if (isset($form['#content']['#value'])) {{ $form['#content']['#value'] }} @endif" class="img-responsive center-block"></div>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#assets" data-opentab="#models-tab">
                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> {{ trans('template_asset_library_models.edit_model_btn') }}
            </button>
            <button type="button" class="btn btn-primary remove-model-btn">
                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> {{ trans('template_asset_library_models.remove_model_btn') }}
            </button>
        </div>

    </div>
    <span class="info-block">{{ $form['#help'] }} @foreach ($form['#file-extension'] as $file_ext) <span class="label label-warning">{{ $file_ext }}</span>@endforeach @if ($form['#required'] == true) <span class="label label-danger">{{ trans('template_fields.required') }}</span>@endif</span>
    {!! $errors->has($field_id)?$errors->first($field_id, '<span class="help-block">:message</span>'):'' !!}
</div>

