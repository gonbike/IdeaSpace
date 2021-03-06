<?php

namespace App\Content; 

use App\Field;
use App\Content\ContentType;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FieldTypePosition {

    use FieldTypeTrait;

    CONST NONE = 'none';

    public $subjectTypeTemplates;

    private $template_add = 'admin.space.content.field_position_add';
    private $template_edit = 'admin.space.content.field_position_edit';
    private $template_add_script = 'public/assets/admin/space/content/js/field_position_add.js';
    private $template_edit_script = 'public/assets/admin/space/content/js/field_position_edit.js';


    /**
     * Create a new field instance.
     *
     * @return void
     */
    public function __construct() {
        $this->subjectTypeTemplates[FieldTypePosition::NONE] = 'admin.space.content.field_position.positions_blank_partial';
        $this->subjectTypeTemplates[ContentType::FIELD_TYPE_VIDEO] = 'admin.space.content.field_position.positions_video_partial';
        $this->subjectTypeTemplates[ContentType::FIELD_TYPE_VIDEOSPHERE] = 'admin.space.content.field_position.positions_videosphere_partial';
        $this->subjectTypeTemplates[ContentType::FIELD_TYPE_PHOTOSPHERE] = 'admin.space.content.field_position.positions_photosphere_partial';
        $this->subjectTypeTemplates[ContentType::FIELD_TYPE_IMAGE] = 'admin.space.content.field_position.positions_image_partial';
        $this->subjectTypeTemplates[ContentType::FIELD_TYPE_MODEL3D . '__obj_mtl'] = 'admin.space.content.field_position.positions_model3d_obj_mtl_partial';
        $this->subjectTypeTemplates[ContentType::FIELD_TYPE_MODEL3D . '__dae'] = 'admin.space.content.field_position.positions_model3d_dae_partial';
        $this->subjectTypeTemplates[ContentType::FIELD_TYPE_MODEL3D . '__ply'] = 'admin.space.content.field_position.positions_model3d_ply_partial';
    }


    /**
     * Prepare template.
     *
     * @param String $field_key
     * @param Array $field_properties
     * @param Array $all_fields 
     *
     * @return Array
     */
    public function prepare($field_key, $field_properties, $all_fields) {

        $field = [];
        $field = $field_properties;
        $field['#template'] = $this->template_add;
        $field['#template_script'] = $this->template_add_script;

        if (array_key_exists('#field', $field) && array_key_exists($field['#field'], $all_fields)) {

            $subject = $all_fields[$field['#field']];
            
            $field['#field-type'] = $subject['#type'];
            $field['#field-name'] = $field['#field'];

        } else {

            /* blank room */
            $field['#field-type'] = '';
            $field['#field-name'] = '';
        }

        return $field;
    }


    /**
     * Load content.
     *
     * @param integer $content_id
     * @param String $field_key
     * @param Array $properties
     * @param Array $all_fields
     *
     * @return Array
     */
    public function load($content_id, $field_key, $properties, $all_fields) {

        $field_arr = [];

        $field_arr = $this->prepare($field_key, $properties, $all_fields);
        $field_arr['#template'] = $this->template_edit;
        $field_arr['#template_script'] = $this->template_edit_script;

        try {
            $field = Field::where('content_id', $content_id)->where('key', $field_key)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return $field_arr;
        }

        $field_arr['#content'] = array('#value' => $field->data);

        return $field_arr;
    }


    /**
     * Get validation rules and messages.
     *
     * @param Request $request
     * @param Array $validation_rules_messages
     * @param String $field_key
     * @param Array $properties
     *
     * @return Array
     */
    public function get_validation_rules_messages($request, $validation_rules_messages, $field_key, $properties) {

        return $validation_rules_messages;
    }


    /**
     * Save entry.
     *
     * @param int $space_id
     * @param int $content_id
     * @param String $field_key
     * @param String $type
     * @param Array $request_all
     *
     * @return True
     */
    public function save($space_id, $content_id, $field_key, $type, $request_all) {

        try {
            /* there is only one field key per content (id) */
            $field = Field::where('content_id', $content_id)->where('key', $field_key)->firstOrFail();
            $field->data = $request_all[$field_key];
            $field->save();

        } catch (ModelNotFoundException $e) {

            $field = new Field;
            $field->content_id = $content_id;
            $field->key = $field_key;
            $field->type = $type;
            $field->data = $request_all[$field_key];
            $field->save();
        }

        return true;
    }


    /**
     * Delete content.
     *
     * @param integer $content_id
     * @param String $field_key
     * @param Array $properties
     *
     * @return Array
     */
    public function delete($content_id, $field_key, $properties) {

        try {
            $field = Field::where('content_id', $content_id)->where('key', $field_key)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return;
        }
        $field->delete();
    }


    /**
     * Validate theme config field.
     *
     * @param Array $field
     *
     * @return True if valid, false otherwise.
     */
    public function validateThemeFieldType($field) {

        $mandatoryKeys = [
            '#label' => 'string',
            '#description' => 'string',
            '#required' => 'boolean',
            '#content' => 'string',
            /* '#field' => 'string', field is optional */
            '#maxnumber' => 'number'];

        return $this->validateFieldType($mandatoryKeys, $field);
    }


    /**
     * Load content for theme.
     *
     * @param Field $field
     *
     * @return Array
     */
    public function loadContent($field) {

        $content_arr = [];

        $content_arr['#type'] = $field->type;
        $content_arr['#value'] = $field->data;

        return $content_arr;
    }


}
