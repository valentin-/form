<?php

return array(
	'form' => array(
		'widgets'            => array
		(
			'button'      => array
			(
				'form-control' => false,
				'modal-footer' => true,
			),

			'captcha'     => array
			(
				'input-group' => true,
			),

			'checkbox'    => array
			(
				'form-control' => false,
				'label'        => false,
			),

			'explanation' => array(
				'form-control' => false,
			),

			'headline'    => array(
				'form-control' => false,
			),

			'radio'       => array
			(
				'form-control' => false,
				'label'        => false
			),

			'submit'      => array
			(
				'form-control' => false,
				'modal-footer' => true,
			),

			'select'      => array
			(
				'styled-select' => true,
			),

			'text'        => array
			(
				'input-group' => true,
			),

			'email'       => array
			(
				'input-group' => true,
			),

			'digit'       => array
			(
				'input-group' => true,
			),

			'tel'         => array
			(
				'input-group' => true,
			),

			'url'         => array
			(
				'input-group' => true,
			),


			'textarea'    => array
			(
				'input-group' => true,
			),

			'password'    => array
			(
				'input-group' => true,
			),

		),

		// which columns shall be used for the form in table mode
		'horizontal'         => array
		(
			'label'   => 'col-lg-3',
			'control' => 'col-lg-9',
			'offset'  => 'col-lg-offset-3',
		),

		// how to display forms like comments form by default
		'default-horizontal' => true,

		// add style select to select list, set to false to disable
		'styled-select'      => array
		(
			'enabled'    => true,
			'class'      => 'selectpicker',
			'style'      => 'btn-default',
			'javascript' => array(
				'system/modules/bootstrap-form/assets/bootstrap-select/bootstrap-select.min.js',
				'system/modules/bootstrap-form/assets/bootstrap-select.js'
			),
			'stylesheet' => 'system/modules/bootstrap-form/assets/bootstrap-select/bootstrap-select.min.css',
		),

		// style the upload button
		'styled-upload'      => array
		(
			'enabled'  => true,
			'class'    => 'btn btn-primary',
			'position' => 'right',
			'onchange' => 'document.getElementById(\'%s_value\').value=this.value.replace(/C:\\\\fakepath\\\\/i, "");return false;',
			'label'    => &$GLOBALS['TL_LANG']['MSC']['bootstrapUploadButton']
		),

		// provides data attributes for custom select
		'data-attributes'    => array('target', 'toggle', 'dismiss', 'remote'),
	)
);