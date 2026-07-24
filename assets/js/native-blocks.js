( function ( wp ) {
	'use strict';

	if ( ! wp || ! wp.blocks || ! window.elNakaaNativeBlocks ) {
		return;
	}

	const el = wp.element.createElement;
	const Fragment = wp.element.Fragment;
	const useEffect = wp.element.useEffect;
	const useState = wp.element.useState;
	const InspectorControls = wp.blockEditor.InspectorControls;
	const MediaUpload = wp.blockEditor.MediaUpload;
	const MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
	const ServerSideRender = wp.serverSideRender;
	const {
		Button,
		PanelBody,
		SelectControl,
		TextControl,
		TextareaControl,
		Notice,
	} = wp.components;

	const withoutTabs = ( fields ) => fields.filter( ( field ) => field.name && field.type !== 'tab' );

	function ImageControl( { field, value, onChange } ) {
		const image = value && typeof value === 'object' ? value : {};

		return el(
			'div',
			{ className: 'el-nakaa-native-image-control' },
			el( 'p', {}, field.label ),
			image.url ? el( 'img', { src: image.url, alt: image.alt || '', style: { maxWidth: '180px', height: 'auto' } } ) : null,
			el(
				MediaUploadCheck,
				{},
				el( MediaUpload, {
					allowedTypes: [ 'image' ],
					value: image.id || 0,
					onSelect: ( media ) => onChange( { id: media.id, url: media.url, alt: media.alt || '' } ),
					render: ( { open } ) => el( Button, { variant: 'secondary', onClick: open }, image.id ? 'تغيير الصورة' : 'اختيار صورة' ),
				} )
			),
			image.id ? el( Button, { isDestructive: true, onClick: () => onChange( {} ) }, 'إزالة الصورة' ) : null
		);
	}

	function TaxonomyControl( { field, value, onChange } ) {
		const [ choices, setChoices ] = useState( [ { label: '— اختر —', value: 0 } ] );

		useEffect( () => {
			wp.apiFetch( { path: '/wp/v2/product_cat?per_page=100&orderby=name&order=asc' } )
				.then( ( terms ) => setChoices( [ { label: '— اختر —', value: 0 } ].concat(
					terms.map( ( term ) => ( { label: term.name, value: term.id } ) )
				) ) )
				.catch( () => {} );
		}, [] );

		return el( SelectControl, {
			label: field.label,
			value: Number( value || 0 ),
			options: choices,
			onChange: ( next ) => onChange( Number( next ) ),
		} );
	}

	function FieldControl( { field, value, onChange } ) {
		if ( field.type === 'image' ) {
			return el( ImageControl, { field, value, onChange } );
		}
		if ( field.type === 'taxonomy' ) {
			return el( TaxonomyControl, { field, value, onChange } );
		}
		if ( field.type === 'select' ) {
			const choices = Object.keys( field.choices || {} ).map( ( key ) => ( { label: field.choices[ key ], value: key } ) );
			return el( SelectControl, { label: field.label, value: value || field.default_value || '', options: choices, onChange } );
		}
		if ( field.type === 'number' ) {
			return el( TextControl, { label: field.label, type: 'number', value: value || '', onChange: ( next ) => onChange( Number( next ) ) } );
		}
		if ( [ 'textarea', 'wysiwyg' ].includes( field.type ) ) {
			return el( TextareaControl, { label: field.label, help: field.instructions || '', value: value || '', rows: field.rows || 5, onChange } );
		}
		return el( TextControl, { label: field.label, help: field.instructions || '', type: field.type === 'url' ? 'url' : field.type === 'email' ? 'email' : 'text', value: value || '', onChange } );
	}

	function RepeaterControl( { field, value, onChange } ) {
		const rows = Array.isArray( value ) ? value : [];
		const subFields = withoutTabs( field.sub_fields || [] );
		const updateRow = ( index, key, nextValue ) => {
			const nextRows = rows.map( ( row, rowIndex ) => rowIndex === index ? { ...row, [ key ]: nextValue } : row );
			onChange( nextRows );
		};

		return el(
			'div',
			{ className: 'el-nakaa-native-repeater' },
			el( 'h3', {}, field.label ),
			rows.map( ( row, index ) =>
				el(
					PanelBody,
					{ key: index, title: `${ field.label } #${ index + 1 }`, initialOpen: false },
					subFields.map( ( subField ) =>
						subField.type === 'repeater'
							? el( RepeaterControl, { key: subField.name, field: subField, value: row[ subField.name ], onChange: ( next ) => updateRow( index, subField.name, next ) } )
							: el( FieldControl, { key: subField.name, field: subField, value: row[ subField.name ], onChange: ( next ) => updateRow( index, subField.name, next ) } )
					),
					el( Button, { isDestructive: true, onClick: () => onChange( rows.filter( ( item, rowIndex ) => rowIndex !== index ) ) }, 'حذف العنصر' )
				)
			),
			el( Button, { variant: 'secondary', onClick: () => onChange( rows.concat( [ {} ] ) ) }, field.button_label || 'إضافة عنصر' )
		);
	}

	function EditBlock( props, definition ) {
		const fields = withoutTabs( definition.fields || [] );
		return el(
			Fragment,
			{},
			el(
				InspectorControls,
				{},
				el(
					PanelBody,
					{ title: 'إعدادات البلوك', initialOpen: true },
					fields.map( ( field ) =>
						field.type === 'repeater'
							? el( RepeaterControl, { key: field.name, field, value: props.attributes[ field.name ], onChange: ( value ) => props.setAttributes( { [ field.name ]: value } ) } )
							: el( FieldControl, { key: field.name, field, value: props.attributes[ field.name ], onChange: ( value ) => props.setAttributes( { [ field.name ]: value } ) } )
					)
				)
			),
			el( ServerSideRender, { block: definition.name, attributes: props.attributes } ),
			fields.length ? null : el( Notice, { status: 'warning', isDismissible: false }, 'تعذر تحميل تعريفات حقول هذا البلوك.' )
		);
	}

	window.elNakaaNativeBlocks.forEach( ( definition ) => {
		wp.blocks.registerBlockType( definition.name, {
			apiVersion: 3,
			title: definition.title,
			icon: definition.icon,
			category: 'formatting',
			attributes: definition.attributes,
			supports: { align: false, anchor: true, html: false },
			edit: ( props ) => EditBlock( props, definition ),
			save: () => null,
		} );
	} );
} )( window.wp );
