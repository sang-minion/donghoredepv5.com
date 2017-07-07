<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'Trường :attribute phải được chấp nhận.',
    'active_url'           => 'Trường :attribute không phải là một URL hợp lệ.',
    'after'                => 'Trường :attribute phải là 1 ngày sau :date.',
    'after_or_equal'       => 'Trường :attribute phải là ngày sau hoặc bằng :date.',
    'alpha'                => 'Trường :attribute chỉ có thể chứa chữ cái.',
    'alpha_dash'           => 'Trường :attribute có thể chứa chữ cái, số và dấu gạch ngang.',
    'alpha_num'            => 'Trường :attribute có thể chứa chữ cái và số.',
    'array'                => 'Trường :attribute phải là một mảng.',
    'before'               => 'Trường :attribute phải là một ngày trước ngày :date.',
    'before_or_equal'      => 'Trường :attribute phải là ngày trước hoặc bằng ngày :date.',
    'between'              => [
        'numeric' => 'Trường :attribute nội dung phải nằm trong khoảng :min đến :max.',
        'file'    => 'Trường :attribute dung lượng cho phép trong khoảng :min đến :max kilobytes.',
        'string'  => 'Trường :attribute độ dài chuổi trong khoảng :min đến :max ký tự.',
        'array'   => 'Trường :attribute nội dung phải nằm trong khoảng :min đến :max items.',
    ],
    'boolean'              => 'Trường :attribute phải lựa chọn đúng hoặc sai.',
    'confirmed'            => 'Trường :attribute nội dung không khớp',
    'date'                 => 'Trường :attribute không đúng định dạng ngày',
    'date_format'          => 'Trường :attribute khống khớp với định dạng :format.',
    'different'            => 'Trường :attribute và :other nội dung phải khác nhau.',
    'digits'               => 'Trường :attribute nội dung phải là số.',
    'digits_between'       => 'Trường :attribute số phải nằm trong khoảng :min đến :max.',
    'dimensions'           => 'Trường :attribute kích thước hình ảnh không hợp lệ.',
    'distinct'             => 'Trường :attribute nội dung này đã tồn tại.',
    'email'                => 'Trường :attribute phải la một địa chỉ email hợp lệ.',
    'exists'               => 'Trường selected :attribute nội dung không hợp lệ',
    'file'                 => 'Trường :attribute nội dung phải là 1 file.',
    'filled'               => 'Trường :attribute nội dung phải có giá trị.',
    'image'                => 'Trường :attribute nội dung phải là 1 file hình ảnh.',
    'in'                   => 'Trường selected :attribute nội dung vừa chọn không hợp lệ.',
    'in_array'             => 'Trường :attribute nội dung không tồn tại trong :other.',
    'integer'              => 'Trường :attribute nội dung phải là 1 số.',
    'ip'                   => 'Trường :attribute nội dung phải là 1 địa chỉ IP.',
    'ipv4'                 => 'Trường :attribute nội dung phải là 1 địa chỉ IPv4.',
    'ipv6'                 => 'Trường :attribute nội dung phải là 1 địa chỉ IPv6.',
    'json'                 => 'Trường :attribute nội dung phải đúng với định dạng chuỗi json.',
    'max'                  => [
        'numeric' => 'Trường :attribute nội dung không được lớn hơn :max.',
        'file'    => 'Trường :attribute nội dung không được lớn hơn :max kilobytes.',
        'string'  => 'Trường :attribute nội dung không được lớn hơn :max ký tự.',
        'array'   => 'Trường :attribute nội dung mảng không được nhiều hơn :max items.',
    ],
    'mimes'                => 'Trường :attribute nội dung phải là 1 tập tin loại type: :values.',
    'mimetypes'            => 'Trường :attribute nội dung phải la 1 tập tin loại type: :values.',
    'min'                  => [
        'numeric' => 'Trường :attribute bạn chọn :min item hợp lệ',
        'file'    => 'Trường :attribute nội dung ít nhất phải :min kilobytes.',
        'string'  => 'Trường :attribute chuỗi ít nhất phải :min ký tự.',
        'array'   => 'Trường :attribute nội dung mảng phải có ít nhất :min items.',
    ],
    'not_in'               => 'Trường lựa chọn :attribute không đúng.',
    'numeric'              => 'Trường :attribute phải là là 1 số.',
    'present'              => 'Trường :attribute field must be present.',
    'regex'                => 'Trường :attribute không đúng định dạng.',
    'required'             => 'Trường :attribute không được để trống.',
    'required_if'          => 'Trường :attribute field is required when :other is :value.',
    'required_unless'      => 'Trường :attribute field is required unless :other is in :values.',
    'required_with'        => 'Trường :attribute field is required when :values is present.',
    'required_with_all'    => 'Trường :attribute field is required when :values is present.',
    'required_without'     => 'Trường :attribute field is required when :values is not present.',
    'required_without_all' => 'Trường :attribute field is required when none of :values are present.',
    'same'                 => 'Trường :attribute and :other must match.',
    'size'                 => [
        'numeric' => 'Trường :attribute nội dung phải đúng :size.',
        'file'    => 'Trường :attribute nội dung phải đúng :size kilobytes.',
        'string'  => 'Trường :attribute nội dung phải đúng :size ký tự.',
        'array'   => 'Trường :attribute must contain :size items.',
    ],
    'string'               => 'The :attribute nội dung phải là 1 chuỗi.',
    'timezone'             => 'The :attribute nội dung phải là 1 múi giờ.',
    'unique'               => 'The :attribute nội dung đã tồn tại.',
    'uploaded'             => 'The :attribute upload thất bại.',
    'url'                  => 'The :attribute url không đúng định dạng.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
